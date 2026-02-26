<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Laboratory;
use App\Models\LabOrder;
use App\Models\LabOrderItem;
use App\Models\LabTest;
use App\Models\LabPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PatientLabOrderController extends Controller
{
    // ─── Helper: get authenticated patient ───────────────────────────
    private function getPatient()
    {
        return Auth::user()->patient;
    }

    // ─── Helper: send notification to patient ────────────────────────
    private function notify(int $userId, string $title, string $message, int $orderId): void
    {
        try {
            DB::table('notifications')->insert([
                'notifiable_type' => \App\Models\User::class,
                'notifiable_id'   => $userId,
                'type'            => 'lab_order',
                'title'           => $title,
                'message'         => $message,
                'related_type'    => 'lab_order',
                'related_id'      => $orderId,
                'is_read'         => false,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        } catch (\Exception $e) {
            // Silently fail — notification is non-critical
        }
    }

    // ══════════════════════════════════════════════════════════════════
    //  GET /patient/lab-orders
    //  Route: patient.lab-orders.index
    //  View:  patient.lab-orders.index
    // ══════════════════════════════════════════════════════════════════
    public function index(Request $request)
    {
        $patient = $this->getPatient();

        if (!$patient) {
            return redirect()->route('patient.dashboard')
                ->with('error', 'Patient profile not found.');
        }

        $query = LabOrder::with(['laboratory', 'items'])
            ->where('patient_id', $patient->id);

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(10);

        // Status counts for filter badges
        $base   = LabOrder::where('patient_id', $patient->id);
        $counts = (object) [
            'total'            => (clone $base)->count(),
            'pending'          => (clone $base)->where('status', 'pending')->count(),
            'sample_collected' => (clone $base)->where('status', 'sample_collected')->count(),
            'processing'       => (clone $base)->where('status', 'processing')->count(),
            'completed'        => (clone $base)->where('status', 'completed')->count(),
            'cancelled'        => (clone $base)->where('status', 'cancelled')->count(),
        ];

        return view('patient.lab-orders.index', compact('orders', 'counts'));
    }

    // ══════════════════════════════════════════════════════════════════
    //  GET /patient/lab-orders/create/{labId}
    //  Route: patient.lab-orders.create
    //  View:  patient.lab-orders.create
    // ══════════════════════════════════════════════════════════════════
   public function create($labId)
{
    // ✅ status = 'approved'
    $laboratory = Laboratory::where('status', 'approved')->findOrFail($labId);

    $labTests = LabTest::where('laboratory_id', $labId)
        ->where('is_active', true)
        ->orderBy('test_category')
        ->orderBy('test_name')
        ->get();

    $labPackages = LabPackage::with('tests')
        ->where('laboratory_id', $labId)
        ->where('is_active', true)
        ->get();

    $referralNote = session('referral_note');

    return view('patient.lab-orders.create', compact(
        'laboratory',
        'labTests',
        'labPackages',
        'referralNote'
    ));
}


    // ══════════════════════════════════════════════════════════════════
    //  POST /patient/lab-orders/store/{labId}
    //  Route: patient.lab-orders.store
    // ══════════════════════════════════════════════════════════════════
    public function store(Request $request, $labId)
    {
        $request->validate([
            'collection_date'    => 'required|date|after_or_equal:today',
            'collection_time'    => 'nullable|date_format:H:i',
            'collection_type'    => 'required|in:walk_in,appointment,home',
            'collection_address' => 'required_if:collection_type,home|nullable|string|max:500',
            'prescription_file'  => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'notes'              => 'nullable|string|max:1000',
            'selected_items'     => 'nullable|array',
        ]);

        $patient    = $this->getPatient();
        $laboratory = Laboratory::findOrFail($labId);

        if (!$patient) {
            return back()->with('error', 'Patient profile not found.');
        }

        // ── Parse selected items & calculate total ────────────────────
        $selectedItems = $request->input('selected_items', []);
        $totalAmount   = 0;
        $parsedItems   = [];

        foreach ($selectedItems as $itemValue) {
            // Format: "test_{id}" | "package_{id}" | "service_{idx}_{slug}"
            if (Str::startsWith($itemValue, 'test_')) {
                $testId = (int) Str::after($itemValue, 'test_');
                $test   = LabTest::where('id', $testId)
                    ->where('laboratory_id', $labId)
                    ->where('is_active', true)
                    ->first();
                if ($test) {
                    $parsedItems[] = [
                        'type'      => 'test',
                        'id'        => $test->id,
                        'item_name' => $test->test_name,
                        'price'     => $test->price ?? 0,
                    ];
                    $totalAmount += $test->price ?? 0;
                }

            } elseif (Str::startsWith($itemValue, 'package_')) {
                $pkgId = (int) Str::after($itemValue, 'package_');
                $pkg   = LabPackage::where('id', $pkgId)
                    ->where('laboratory_id', $labId)
                    ->where('is_active', true)
                    ->first();
                if ($pkg) {
                    $finalPrice = $pkg->discount_percentage
                        ? round($pkg->price * (1 - $pkg->discount_percentage / 100), 2)
                        : $pkg->price;
                    $parsedItems[] = [
                        'type'      => 'package',
                        'id'        => $pkg->id,
                        'item_name' => $pkg->package_name . ' (Package)',
                        'price'     => $finalPrice,
                    ];
                    $totalAmount += $finalPrice;
                }

            } elseif (Str::startsWith($itemValue, 'service_')) {
                // e.g. "service_0_blood-test"
                $parts      = explode('_', $itemValue, 3);
                $svcSlug    = $parts[2] ?? $itemValue;
                $svcName    = ucwords(str_replace('-', ' ', $svcSlug));
                $parsedItems[] = [
                    'type'      => 'service',
                    'id'        => null,
                    'item_name' => $svcName,
                    'price'     => 0,
                ];
            }
        }

        // ── Upload prescription ───────────────────────────────────────
        $prescriptionPath = null;
        if ($request->hasFile('prescription_file')) {
            $prescriptionPath = $request->file('prescription_file')
                ->store('prescriptions/' . $patient->id, 'public');
        }

        // ── Generate unique reference number ─────────────────────────
        $reference = 'LO-' . strtoupper(Str::random(3)) . '-' . now()->format('ymd') . '-' . rand(100, 999);

        // ── Create LabOrder ───────────────────────────────────────────
        $order = LabOrder::create([
            'patient_id'         => $patient->id,
            'laboratory_id'      => $labId,
            'reference_number'   => $reference,
            'order_date'         => now(),
            'status'             => 'pending',
            'payment_status'     => 'unpaid',
            'total_amount'       => $totalAmount,
            'home_collection'    => $request->collection_type === 'home',
            'collection_date'    => $request->collection_date,
            'collection_time'    => $request->collection_time,
            'collection_address' => $request->collection_type === 'home'
                ? $request->collection_address
                : null,
            'prescription_file'  => $prescriptionPath,
            'notes'              => $request->notes,
        ]);

        // ── Create LabOrderItems ──────────────────────────────────────
        foreach ($parsedItems as $item) {
            LabOrderItem::create([
                'lab_order_id' => $order->id,
                'test_id'      => $item['type'] === 'test'    ? $item['id'] : null,
                'package_id'   => $item['type'] === 'package' ? $item['id'] : null,
                'item_name'    => $item['item_name'],
                'price'        => $item['price'],
            ]);
        }

        // ── Clear referral note session ───────────────────────────────
        session()->forget('referral_note');

        // ── Notify patient ────────────────────────────────────────────
        $this->notify(
            Auth::id(),
            'Lab Order Submitted',
            '✅ Your lab order #' . $reference . ' at ' . $laboratory->name . ' has been submitted.',
            $order->id
        );

        return redirect()
            ->route('patient.lab-orders.show', $order->id)
            ->with('success', 'Lab order submitted successfully! Reference: ' . $reference);
    }

    // ══════════════════════════════════════════════════════════════════
    //  GET /patient/lab-orders/{id}
    //  Route: patient.lab-orders.show
    //  View:  patient.lab-orders.show
    // ══════════════════════════════════════════════════════════════════
    public function show($id)
    {
        $patient = $this->getPatient();

        $order = LabOrder::with(['laboratory', 'items.test', 'items.package'])
            ->where('patient_id', $patient->id)
            ->findOrFail($id);

        // Status tracker steps
        $statusSteps = [
            'pending'          => ['label' => 'Submitted',        'icon' => 'fa-paper-plane'],
            'sample_collected' => ['label' => 'Sample Collected', 'icon' => 'fa-vial'],
            'processing'       => ['label' => 'Processing',       'icon' => 'fa-microscope'],
            'completed'        => ['label' => 'Report Ready',     'icon' => 'fa-check-circle'],
        ];

        $statusOrder  = array_keys($statusSteps);
        $currentIdx   = array_search($order->status, $statusOrder);
        $currentIdx   = $currentIdx === false ? 0 : $currentIdx;

        return view('patient.lab-orders.show', compact(
            'order',
            'statusSteps',
            'statusOrder',
            'currentIdx'
        ));
    }

    // ══════════════════════════════════════════════════════════════════
    //  GET /patient/lab-orders/{id}/payment
    //  Route: patient.lab-orders.payment
    //  View:  patient.lab-orders.payment
    // ══════════════════════════════════════════════════════════════════
    public function payment($id)
    {
        $patient = $this->getPatient();

        $order = LabOrder::with(['laboratory', 'items'])
            ->where('patient_id', $patient->id)
            ->findOrFail($id);

        // If cancelled, block payment
        if ($order->status === 'cancelled') {
            return redirect()
                ->route('patient.lab-orders.show', $id)
                ->with('error', 'Cancelled orders cannot be paid.');
        }

        return view('patient.lab-orders.payment', compact('order'));
    }

    // ══════════════════════════════════════════════════════════════════
    //  POST /patient/lab-orders/{id}/pay
    //  Route: patient.lab-orders.pay
    // ══════════════════════════════════════════════════════════════════
    public function pay(Request $request, $id)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,card,online',
            'transaction_id' => 'required_if:payment_method,online|nullable|string|max:100',
        ]);

        $patient = $this->getPatient();

        $order = LabOrder::where('patient_id', $patient->id)->findOrFail($id);

        if ($order->payment_status === 'paid') {
            return back()->with('error', 'This order is already paid.');
        }

        if ($order->status === 'cancelled') {
            return back()->with('error', 'Cannot pay for a cancelled order.');
        }

        $order->update([
            'payment_status' => 'paid',
            'payment_method' => $request->payment_method,
            'paid_at'        => now(),
        ]);

        // Notify patient
        $this->notify(
            Auth::id(),
            'Payment Confirmed',
            '💳 Payment confirmed for order #' . $order->reference_number . ' via ' . ucfirst($request->payment_method) . '.',
            $order->id
        );

        return redirect()
            ->route('patient.lab-orders.show', $id)
            ->with('success', 'Payment confirmed successfully!');
    }

    // ══════════════════════════════════════════════════════════════════
    //  GET /patient/lab-orders/{id}/report
    //  Route: patient.lab-orders.report
    // ══════════════════════════════════════════════════════════════════
    public function downloadReport($id)
    {
        $patient = $this->getPatient();

        $order = LabOrder::where('patient_id', $patient->id)
            ->where('status', 'completed')
            ->findOrFail($id);

        if (!$order->report_file) {
            return back()->with('error', 'Report is not available yet.');
        }

        $path = storage_path('app/public/' . $order->report_file);

        if (!file_exists($path)) {
            return back()->with('error', 'Report file not found. Please contact the laboratory.');
        }

        $filename = 'lab-report-' . $order->reference_number . '.pdf';

        return response()->download($path, $filename);
    }

    // ══════════════════════════════════════════════════════════════════
    //  GET /patient/lab-orders/{id}/payment/callback
    //  Route: patient.lab-orders.payment.callback
    //  (For online payment gateway callback)
    // ══════════════════════════════════════════════════════════════════
    public function paymentCallback(Request $request, $id)
    {
        $patient = $this->getPatient();

        $order = LabOrder::where('patient_id', $patient->id)->findOrFail($id);

        // Basic gateway callback handling
        // Adjust this based on your payment gateway (PayHere, Stripe, etc.)
        $status        = $request->input('status_code');       // PayHere: 2=Success
        $paymentId     = $request->input('payment_id');
        $merchantOrder = $request->input('order_id');

        if ($status == 2 || $request->input('status') === 'success') {
            $order->update([
                'payment_status' => 'paid',
                'payment_method' => 'online',
                'paid_at'        => now(),
            ]);

            $this->notify(
                Auth::id(),
                'Online Payment Confirmed',
                '✅ Online payment confirmed for Order #' . $order->reference_number,
                $order->id
            );

            return redirect()
                ->route('patient.lab-orders.show', $id)
                ->with('success', 'Payment successful!');
        }

        return redirect()
            ->route('patient.lab-orders.payment', $id)
            ->with('error', 'Payment was not completed. Please try again.');
    }

    // ══════════════════════════════════════════════════════════════════
    //  POST /patient/lab-orders/{id}/cancel
    //  Route: patient.lab-orders.cancel
    // ══════════════════════════════════════════════════════════════════
    public function cancel($id)
    {
        $patient = $this->getPatient();

        $order = LabOrder::where('patient_id', $patient->id)
            ->where('status', 'pending')   // Only pending orders can be cancelled
            ->findOrFail($id);

        $order->update(['status' => 'cancelled']);

        $this->notify(
            Auth::id(),
            'Order Cancelled',
            '❌ Your lab order #' . $order->reference_number . ' has been cancelled.',
            $order->id
        );

        return redirect()
            ->route('patient.lab-orders.index')
            ->with('success', 'Order cancelled successfully.');
    }
}
