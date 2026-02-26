<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Laboratory;
use App\Models\LabOrder;
use App\Models\LabOrderItem;
use App\Models\LabTest;
use App\Models\LabPackage;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\CardException;
use Stripe\Exception\InvalidRequestException;
use Stripe\Exception\AuthenticationException;

class PatientLabOrderController extends Controller
{
    // ─── Helper: get authenticated patient ───────────────────────────
    private function getPatient()
    {
        return Auth::user()->patient;
    }

    // ─── Helper: send in-app notification ────────────────────────────
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
    //  PRIVATE HELPER — Mark Lab Order Paid (Stripe)
    // ══════════════════════════════════════════════════════════════════
    private function markLabOrderPaid(LabOrder $order, string $transactionId, int $patientId, ?string $cardholderName): void
    {
        DB::transaction(function () use ($order, $transactionId, $patientId, $cardholderName) {

            // 1. Update order
            $order->update([
                'payment_status' => 'paid',
                'payment_method' => 'card',
                'paid_at'        => now(),
            ]);

            // 2. Payment record
            $paymentNumber = 'PAY-LAB-' . strtoupper(Str::random(8));
            DB::table('payments')->insert([
                'payment_number'  => $paymentNumber,
                'payer_id'        => $patientId,
                'payee_type'      => 'laboratory',
                'payee_id'        => $order->laboratory_id,
                'related_type'    => 'lab_order',
                'related_id'      => $order->id,
                'amount'          => $order->total_amount ?? 0,
                'payment_method'  => 'card',
                'payment_status'  => 'completed',
                'transaction_id'  => $transactionId,
                'payment_date'    => now()->toDateString(),
                'notes'           => $cardholderName
                                        ? 'Cardholder: ' . $cardholderName . ' — Online card payment via Stripe'
                                        : 'Online card payment via Stripe',
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            // 3. Patient notification
            try {
                $this->notify(
                    Auth::id(),
                    '💳 Payment Confirmed',
                    'Payment of Rs. ' . number_format($order->total_amount, 2) .
                    ' confirmed for Lab Order #' . $order->order_number .
                    ' at ' . ($order->laboratory->name ?? 'Laboratory') . '.',
                    $order->id
                );
            } catch (\Exception $e) {
                Log::warning('Lab Stripe payment — patient notification error: ' . $e->getMessage());
            }

            // 4. Laboratory notification
            try {
                if ($order->laboratory && $order->laboratory->user_id) {
                    $this->notify(
                        $order->laboratory->user_id,
                        '💰 Payment Received',
                        'Payment of Rs. ' . number_format($order->total_amount, 2) .
                        ' received via Stripe for Order #' . $order->order_number . '.',
                        $order->id
                    );
                }
            } catch (\Exception $e) {
                Log::warning('Lab Stripe payment — lab notification error: ' . $e->getMessage());
            }
        });
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
                // Format: "service_{idx}_{slug}"
                $parts   = explode('_', $itemValue, 3);
                $svcSlug = $parts[2] ?? $itemValue;
                $svcName = ucwords(str_replace('-', ' ', $svcSlug));
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

        // ── Create LabOrder ───────────────────────────────────────────
        $order = LabOrder::create([
            'patient_id'         => $patient->id,
            'laboratory_id'      => $labId,
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
        ]);

        // ── Create LabOrderItems ──────────────────────────────────────
        foreach ($parsedItems as $item) {
            LabOrderItem::create([
                'order_id'   => $order->id,
                'test_id'    => $item['type'] === 'test'    ? $item['id'] : null,
                'package_id' => $item['type'] === 'package' ? $item['id'] : null,
                'item_name'  => $item['item_name'],
                'price'      => $item['price'],
            ]);
        }

        // ── Clear referral note session ───────────────────────────────
        session()->forget('referral_note');

        // ── Notify patient ────────────────────────────────────────────
        $this->notify(
            Auth::id(),
            '✅ Lab Order Submitted',
            'Your lab order #' . $order->order_number . ' at ' . $laboratory->name . ' has been submitted successfully.',
            $order->id
        );

        // ── Notify laboratory ─────────────────────────────────────────
        $this->notify(
            $laboratory->user_id,
            '🔬 New Lab Order Received',
            'A new lab order #' . $order->order_number . ' has been placed by ' .
                $patient->first_name . ' ' . $patient->last_name . '.',
            $order->id
        );

        return redirect()
            ->route('patient.lab-orders.show', $order->id)
            ->with('success', 'Lab order submitted successfully! Reference: ' . $order->reference_number);
    }

    // ══════════════════════════════════════════════════════════════════
    //  GET /patient/lab-orders/{id}
    //  Route: patient.lab-orders.show
    //  View:  patient.lab-orders.show
    // ══════════════════════════════════════════════════════════════════
    public function show($id)
    {
        $patient = $this->getPatient();

        if (!$patient) {
            return redirect()->route('patient.dashboard')
                ->with('error', 'Patient profile not found.');
        }

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

        $statusOrder = array_keys($statusSteps);
        $currentIdx  = array_search($order->status, $statusOrder);
        $currentIdx  = ($currentIdx === false) ? 0 : $currentIdx;

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

        if (!$patient) {
            return redirect()->route('patient.dashboard')
                ->with('error', 'Patient profile not found.');
        }

        $order = LabOrder::with(['laboratory', 'items'])
            ->where('patient_id', $patient->id)
            ->findOrFail($id);

        if ($order->payment_status === 'paid') {
            return redirect()->route('patient.lab-orders.show', $id)
                ->with('info', 'This order is already paid.');
        }

        if ($order->status === 'cancelled') {
            return redirect()->route('patient.lab-orders.show', $id)
                ->with('error', 'Cancelled orders cannot be paid.');
        }

        $stripeKey = config('services.stripe.key');

        return view('patient.lab-orders.payment', compact('order', 'stripeKey'));
    }

    // ══════════════════════════════════════════════════════════════════
    //  POST /patient/lab-orders/{id}/pay
    //  Route: patient.lab-orders.pay
    //  (Stripe PaymentIntent flow)
    // ══════════════════════════════════════════════════════════════════
    public function pay(Request $request, $id)
    {
        $request->validate([
            'payment_method_id' => 'required|string',
            'cardholder_name'   => 'nullable|string|max:100',
        ]);

        $patient = $this->getPatient();

        if (!$patient) {
            return redirect()->route('patient.lab-orders.index')
                ->with('error', 'Patient profile not found.');
        }

        $order = LabOrder::with('laboratory')
            ->where('patient_id', $patient->id)
            ->findOrFail($id);

        if ($order->payment_status === 'paid') {
            return back()->with('error', 'This order is already paid.');
        }

        if ($order->status === 'cancelled') {
            return back()->with('error', 'Cannot pay for a cancelled order.');
        }

        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $amount = (int) round(($order->total_amount ?? 0) * 100);

            if ($amount < 100) {
                return redirect()->route('patient.lab-orders.payment', $id)
                    ->with('error', 'Invalid amount. Minimum payment is Rs. 1.00');
            }

            $paymentIntent = PaymentIntent::create([
                'amount'              => $amount,
                'currency'            => 'lkr',
                'payment_method'      => $request->payment_method_id,
                'confirmation_method' => 'automatic',
                'confirm'             => true,
                'return_url'          => route('patient.lab-orders.payment.callback', $id),
                'description'         => 'HealthNet Lab Order #' . $order->reference_number,
                'metadata'            => [
                    'order_id'   => $order->id,
                    'patient_id' => $patient->id,
                    'reference'  => $order->reference_number,
                ],
            ]);

            // ── Payment succeeded immediately ─────────────────────────
            if ($paymentIntent->status === 'succeeded') {
                $this->markLabOrderPaid(
                    $order,
                    $paymentIntent->id,
                    $patient->id,
                    $request->cardholder_name ?? null
                );

                return redirect()->route('patient.lab-orders.show', $id)
                    ->with('success', 'Payment successful! Your lab order is confirmed. Reference: ' . $order->reference_number);
            }

            // ── 3DS / redirect required ───────────────────────────────
            if (
                $paymentIntent->status === 'requires_action' &&
                $paymentIntent->next_action &&
                $paymentIntent->next_action->type === 'redirect_to_url'
            ) {
                session(['pending_lab_payment_intent' => $paymentIntent->id]);
                session(['pending_lab_order_id'       => $id]);

                return redirect()->away(
                    $paymentIntent->next_action->redirect_to_url->url
                );
            }

            // ── Card declined ─────────────────────────────────────────
            if ($paymentIntent->status === 'requires_payment_method') {
                return redirect()->route('patient.lab-orders.payment', $id)
                    ->with('error', 'Card was declined. Please try a different card.');
            }

            return redirect()->route('patient.lab-orders.payment', $id)
                ->with('error', 'Payment unsuccessful. Status: ' . $paymentIntent->status);

        } catch (CardException $e) {
            Log::warning('Stripe Card Declined (Lab): ' . $e->getMessage());
            return redirect()->route('patient.lab-orders.payment', $id)
                ->with('error', $e->getUserMessage());

        } catch (InvalidRequestException $e) {
            Log::error('Stripe Invalid Request (Lab): ' . $e->getMessage());
            if (str_contains($e->getMessage(), 'currency')) {
                return redirect()->route('patient.lab-orders.payment', $id)
                    ->with('error', 'LKR currency issue. Please contact support.');
            }
            return redirect()->route('patient.lab-orders.payment', $id)
                ->with('error', 'Invalid payment request: ' . $e->getMessage());

        } catch (AuthenticationException $e) {
            Log::error('Stripe Auth Error (Lab): ' . $e->getMessage());
            return redirect()->route('patient.lab-orders.payment', $id)
                ->with('error', 'Payment service error. Please check API keys.');

        } catch (\Exception $e) {
            Log::error('Lab Payment Error: ' . $e->getMessage());
            return redirect()->route('patient.lab-orders.payment', $id)
                ->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }

    // ══════════════════════════════════════════════════════════════════
    //  GET /patient/lab-orders/{id}/payment/callback
    //  Route: patient.lab-orders.payment.callback
    //  (Stripe 3DS redirect return)
    // ══════════════════════════════════════════════════════════════════
    public function paymentCallback(Request $request, $id)
    {
        $orderId  = session('pending_lab_order_id');
        $intentId = session('pending_lab_payment_intent');

        if (!$orderId || !$intentId) {
            return redirect()->route('patient.lab-orders.index')
                ->with('error', 'Payment session expired. Please try again.');
        }

        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            $paymentIntent = PaymentIntent::retrieve($intentId);

            $patient = $this->getPatient();

            if (!$patient) {
                return redirect()->route('patient.dashboard')
                    ->with('error', 'Patient profile not found.');
            }

            $order = LabOrder::with('laboratory')
                ->where('patient_id', $patient->id)
                ->findOrFail($orderId);

            if ($paymentIntent->status === 'succeeded') {
                $this->markLabOrderPaid($order, $intentId, $patient->id, null);
                session()->forget(['pending_lab_payment_intent', 'pending_lab_order_id']);

                return redirect()->route('patient.lab-orders.show', $orderId)
                    ->with('success', 'Payment successful! Your lab order is confirmed.');
            }

            return redirect()->route('patient.lab-orders.payment', $orderId)
                ->with('error', 'Payment authentication failed. Please try again.');

        } catch (\Exception $e) {
            Log::error('Lab Payment Callback Error: ' . $e->getMessage());
            return redirect()->route('patient.lab-orders.index')
                ->with('error', 'Payment verification failed.');
        }
    }

    // ══════════════════════════════════════════════════════════════════
    //  GET /patient/lab-orders/{id}/report/download
    //  Route: patient.lab-orders.report
    // ══════════════════════════════════════════════════════════════════
    public function downloadReport($id)
    {
        $patient = $this->getPatient();

        if (!$patient) {
            return redirect()->route('patient.dashboard')
                ->with('error', 'Patient profile not found.');
        }

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
    //  POST /patient/lab-orders/{id}/cancel
    //  Route: patient.lab-orders.cancel
    // ══════════════════════════════════════════════════════════════════
    public function cancel($id)
    {
        $patient = $this->getPatient();

        if (!$patient) {
            return redirect()->route('patient.dashboard')
                ->with('error', 'Patient profile not found.');
        }

        $order = LabOrder::where('patient_id', $patient->id)
            ->where('status', 'pending')   // Only pending orders can be cancelled
            ->findOrFail($id);

        $order->update(['status' => 'cancelled']);

        // Notify patient
        $this->notify(
            Auth::id(),
            '❌ Order Cancelled',
            'Your lab order #' . $order->order_number . ' has been cancelled.',
            $order->id
        );

        // Notify laboratory
        $order->load('laboratory');
        $this->notify(
            $order->laboratory->user_id,
            '❌ Order Cancelled by Patient',
            'Lab order #' . $order->order_number . ' has been cancelled by the patient.',
            $order->id
        );

        return redirect()
            ->route('patient.lab-orders.index')
            ->with('success', 'Order cancelled successfully.');
    }

    // ══════════════════════════════════════════════════════════════════
    //  POST /patient/lab-orders/{id}/review
    //  Route: patient.lab-orders.review.store
    // ══════════════════════════════════════════════════════════════════
    public function storeReview(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        $patient = $this->getPatient();

        if (!$patient) {
            return back()->with('error', 'Patient profile not found.');
        }

        // Only completed orders can be reviewed
        $order = LabOrder::with('laboratory')
            ->where('patient_id', $patient->id)
            ->where('status', 'completed')
            ->findOrFail($id);

        // Duplicate review check
        $existing = DB::table('ratings')
            ->where('patient_id',   $patient->id)
            ->where('ratable_type', 'laboratory')
            ->where('ratable_id',   $order->laboratory_id)
            ->where('related_type', 'lab_order')
            ->where('related_id',   $order->id)
            ->exists();

        if ($existing) {
            return back()->with('error', 'You have already reviewed this order.');
        }

        // Insert rating
        DB::table('ratings')->insert([
            'patient_id'   => $patient->id,
            'ratable_type' => 'laboratory',
            'ratable_id'   => $order->laboratory_id,
            'rating'       => $request->rating,
            'review'       => $request->review ?? null,
            'related_type' => 'lab_order',
            'related_id'   => $order->id,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        // Recalculate laboratory rating
        $stats = DB::table('ratings')
            ->where('ratable_type', 'laboratory')
            ->where('ratable_id',   $order->laboratory_id)
            ->selectRaw('AVG(rating) as avg_rating, COUNT(*) as total')
            ->first();

        DB::table('laboratories')
            ->where('id', $order->laboratory_id)
            ->update([
                'rating'        => round($stats->avg_rating, 2),
                'total_ratings' => $stats->total,
            ]);

        // Notify patient
        $this->notify(
            Auth::id(),
            '⭐ Review Submitted',
            'Your review for ' . $order->laboratory->name . ' has been submitted. Thank you!',
            $order->id
        );

        return back()->with('success', 'Your review has been submitted successfully!');
    }
}
