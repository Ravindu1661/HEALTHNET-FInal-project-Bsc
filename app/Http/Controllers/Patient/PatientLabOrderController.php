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
use Illuminate\Support\Facades\Storage;

class PatientLabOrderController extends Controller
{
    // ═══════════════════════════════════════════
    // INDEX
    // ═══════════════════════════════════════════
    public function index(Request $request)
    {
        $patient = Patient::where('user_id', Auth::id())->first();
        if (!$patient) {
            return redirect()->route('patient.dashboard')->with('error', 'Patient profile not found.');
        }

        $query = LabOrder::with(['laboratory', 'items'])
            ->where('patient_id', $patient->id);

        if ($request->filled('status'))  $query->where('status', $request->status);
        if ($request->filled('payment')) $query->where('payment_status', $request->payment);

        $orders = $query->orderBy('created_at', 'desc')->paginate(10);

        $base = LabOrder::where('patient_id', $patient->id);
        $statusCounts = [
            'pending'          => (clone $base)->where('status', 'pending')->count(),
            'sample_collected' => (clone $base)->where('status', 'sample_collected')->count(),
            'processing'       => (clone $base)->where('status', 'processing')->count(),
            'completed'        => (clone $base)->where('status', 'completed')->count(),
        ];

        return view('patient.lab-orders.index', compact('orders', 'statusCounts'));
    }

    // ═══════════════════════════════════════════
    // CREATE
    // ═══════════════════════════════════════════
    public function create(Request $request, $labId)
    {
        $laboratory = Laboratory::where('status', 'approved')->findOrFail($labId);

        $labTests = LabTest::where('laboratory_id', $labId)
            ->where('is_active', true)
            ->orderBy('test_category')
            ->orderBy('test_name')
            ->get();

        $labPackages = LabPackage::where('laboratory_id', $labId)
            ->where('is_active', true)
            ->with('tests')
            ->get();

        $referralNote = $request->get('referral', null);

        return view('patient.lab-orders.create',
            compact('laboratory', 'labTests', 'labPackages', 'referralNote'));
    }

    // ═══════════════════════════════════════════
    // STORE
    // ═══════════════════════════════════════════
    public function store(Request $request, $labId)
    {
        $request->validate([
            'collection_date'    => 'required|date|after_or_equal:today',
            'collection_type'    => 'required|in:walk_in,appointment,home',
            'collection_time'    => 'nullable|string',
            'collection_address' => 'required_if:collection_type,home|nullable|string|max:500',
            'notes'              => 'nullable|string|max:1000',
            'prescription_file'  => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'selected_items'     => 'nullable|array',
        ]);

        $patient    = Patient::where('user_id', Auth::id())->first();
        $laboratory = Laboratory::where('status', 'approved')->findOrFail($labId);

        if (!$patient) {
            return redirect()->back()->with('error', 'Patient profile not found.');
        }

        $orderId = null;

        DB::transaction(function () use ($request, $patient, $laboratory, $labId, &$orderId) {

            $totalAmount = 0;
            $items       = [];
            $isHome      = $request->collection_type === 'home';

            // Process selected items
            if ($request->filled('selected_items')) {
                foreach ($request->selected_items as $item) {
                    if (str_starts_with($item, 'test_')) {
                        $testId = substr($item, 5);
                        $test   = LabTest::where('laboratory_id', $labId)->find($testId);
                        if ($test) {
                            $totalAmount += $test->price ?? 0;
                            $items[] = [
                                'test_id'    => $test->id,
                                'package_id' => null,
                                'item_name'  => $test->test_name,
                                'price'      => $test->price ?? 0,
                            ];
                        }
                    } elseif (str_starts_with($item, 'package_')) {
                        $pkgId = substr($item, 8);
                        $pkg   = LabPackage::where('laboratory_id', $labId)->find($pkgId);
                        if ($pkg) {
                            // Calculate discounted price from discount_percentage
                            $price = $pkg->discount_percentage
                                ? round($pkg->price * (1 - $pkg->discount_percentage / 100), 2)
                                : $pkg->price;
                            $totalAmount += $price;
                            $items[] = [
                                'test_id'    => null,
                                'package_id' => $pkg->id,
                                'item_name'  => $pkg->package_name . ' (Package)',
                                'price'      => $price,
                            ];
                        }
                    }
                }
            }

            // If no items selected — general request (price 0)
            if (empty($items)) {
                $items[] = [
                    'test_id'    => null,
                    'package_id' => null,
                    'item_name'  => 'General Lab Test Request',
                    'price'      => 0,
                ];
            }

            // Prescription file
            $prescriptionPath = null;
            if ($request->hasFile('prescription_file')) {
                $prescriptionPath = $request->file('prescription_file')
                    ->store('lab-prescriptions/' . $patient->id, 'public');
            }

            // Generate unique numbers
            $orderNumber = 'LAB-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
            $refNumber   = 'REF-' . strtoupper(uniqid());

            // Create order — matching exact migration columns
            $order = LabOrder::create([
                'order_number'       => $orderNumber,
                'reference_number'   => $refNumber,
                'patient_id'         => $patient->id,
                'laboratory_id'      => $labId,
                'doctor_id'          => null,
                'prescription_file'  => $prescriptionPath,
                'status'             => 'pending',
                'total_amount'       => $totalAmount,
                'payment_status'     => 'unpaid',
                'payment_method'     => null,
                'home_collection'    => $isHome,
                'collection_address' => $request->collection_address,
                'collection_date'    => $request->collection_date,
                'collection_time'    => $request->collection_time,
            ]);

            // Create order items
            foreach ($items as $item) {
                LabOrderItem::create([
                    'order_id'   => $order->id,
                    'test_id'    => $item['test_id'],
                    'package_id' => $item['package_id'],
                    'item_name'  => $item['item_name'],
                    'price'      => $item['price'],
                ]);
            }

            // Notification to lab
            try {
                $lab = Laboratory::find($labId);
                if ($lab && $lab->user_id) {
                    DB::table('notifications')->insert([
                        'notifiable_type' => \App\Models\User::class,
                        'notifiable_id'   => $lab->user_id,
                        'type'            => 'lab_order',
                        'title'           => '🧪 New Lab Order — ' . $orderNumber,
                        'message'         => 'Patient ' . $patient->first_name . ' ' . $patient->last_name
                            . ' has submitted a lab order. Collection: '
                            . ($isHome ? 'Home Collection' : ucwords(str_replace('_', ' ', $request->collection_type)))
                            . ' on ' . \Carbon\Carbon::parse($request->collection_date)->format('d M Y') . '.',
                        'related_type'    => 'lab_order',
                        'related_id'      => $order->id,
                        'is_read'         => false,
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ]);
                }
            } catch (\Exception $e) {
                Log::warning('Lab order notification: ' . $e->getMessage());
            }

            $orderId = $order->id;
        });

        if ($orderId) {
            $order = LabOrder::find($orderId);
            if ($order && $order->total_amount > 0) {
                return redirect()->route('patient.lab-orders.payment', $orderId)
                    ->with('success', 'Lab order created! Please complete payment.');
            }
            return redirect()->route('patient.lab-orders.show', $orderId)
                ->with('success', '✅ Lab order submitted! The lab will contact you to confirm.');
        }

        return redirect()->route('patient.lab-orders.index')
            ->with('error', 'Order creation failed. Please try again.');
    }

    // ═══════════════════════════════════════════
    // SHOW
    // ═══════════════════════════════════════════
    public function show($id)
    {
        $patient = Patient::where('user_id', Auth::id())->first();
        if (!$patient) {
            return redirect()->route('patient.dashboard')->with('error', 'Patient profile not found.');
        }

        $order = LabOrder::with(['laboratory', 'items.test', 'items.package'])
            ->where('patient_id', $patient->id)
            ->findOrFail($id);

        return view('patient.lab-orders.show', compact('order'));
    }

    // ═══════════════════════════════════════════
    // PAYMENT PAGE
    // ═══════════════════════════════════════════
    public function payment($id)
    {
        $patient = Patient::where('user_id', Auth::id())->first();
        if (!$patient) {
            return redirect()->route('patient.dashboard')->with('error', 'Patient profile not found.');
        }

        $order = LabOrder::with(['laboratory', 'items'])
            ->where('patient_id', $patient->id)
            ->findOrFail($id);

        if ($order->payment_status === 'paid') {
            return redirect()->route('patient.lab-orders.show', $id)
                ->with('info', 'This order is already paid.');
        }

        $stripeKey = config('services.stripe.key');

        return view('patient.lab-orders.payment', compact('order', 'stripeKey'));
    }

    // ═══════════════════════════════════════════
    // PAY — Stripe
    // ═══════════════════════════════════════════
    public function pay(Request $request, $id)
    {
        $request->validate(['payment_method_id' => 'required|string']);

        $patient = Patient::where('user_id', Auth::id())->first();
        if (!$patient) {
            return redirect()->route('patient.lab-orders.index')->with('error', 'Patient profile not found.');
        }

        $order = LabOrder::with(['laboratory'])
            ->where('patient_id', $patient->id)
            ->findOrFail($id);

        if ($order->payment_status === 'paid') {
            return redirect()->route('patient.lab-orders.show', $id)->with('info', 'Already paid.');
        }

        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            $amount = (int) round(($order->total_amount ?? 0) * 100);

            if ($amount < 100) {
                return redirect()->route('patient.lab-orders.payment', $id)
                    ->with('error', 'Invalid amount. Minimum Rs. 1.00');
            }

            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount'              => $amount,
                'currency'            => 'lkr',
                'payment_method'      => $request->payment_method_id,
                'confirmation_method' => 'automatic',
                'confirm'             => true,
                'return_url'          => route('patient.lab-orders.payment.callback', $id),
                'description'         => 'HealthNet Lab Order #' . $order->order_number,
                'metadata'            => [
                    'order_id'   => $order->id,
                    'patient_id' => $patient->id,
                ],
            ]);

            if ($paymentIntent->status === 'succeeded') {
                $this->markOrderPaid($order, $paymentIntent->id, $patient, $request->cardholder_name ?? null);
                return redirect()->route('patient.lab-orders.show', $id)
                    ->with('success', '✅ Payment successful! Your lab order is confirmed.');
            }

            if ($paymentIntent->status === 'requires_action'
                && $paymentIntent->next_action?->type === 'redirect_to_url') {
                session(['pending_lab_payment_intent' => $paymentIntent->id]);
                session(['pending_lab_order_id'       => $id]);
                return redirect()->away($paymentIntent->next_action->redirect_to_url->url);
            }

            return redirect()->route('patient.lab-orders.payment', $id)
                ->with('error', 'Payment unsuccessful. Please try again.');

        } catch (\Stripe\Exception\CardException $e) {
            return redirect()->route('patient.lab-orders.payment', $id)
                ->with('error', '💳 ' . $e->getUserMessage());
        } catch (\Exception $e) {
            Log::error('Lab Payment Error: ' . $e->getMessage());
            return redirect()->route('patient.lab-orders.payment', $id)
                ->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }

    // ═══════════════════════════════════════════
    // PAYMENT CALLBACK — 3DS
    // ═══════════════════════════════════════════
    public function paymentCallback(Request $request, $id)
    {
        $intentId = session('pending_lab_payment_intent');

        if (!$intentId) {
            return redirect()->route('patient.lab-orders.index')
                ->with('error', 'Payment session expired. Please try again.');
        }

        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            $intent  = \Stripe\PaymentIntent::retrieve($intentId);
            $patient = Patient::where('user_id', Auth::id())->first();
            $order   = LabOrder::where('patient_id', $patient->id)->findOrFail($id);

            if ($intent->status === 'succeeded') {
                $this->markOrderPaid($order, $intentId, $patient, null);
                session()->forget(['pending_lab_payment_intent', 'pending_lab_order_id']);
                return redirect()->route('patient.lab-orders.show', $id)
                    ->with('success', '✅ Payment successful! Your lab order is confirmed.');
            }

            return redirect()->route('patient.lab-orders.payment', $id)
                ->with('error', 'Payment authentication failed. Please try again.');

        } catch (\Exception $e) {
            Log::error('Lab Callback Error: ' . $e->getMessage());
            return redirect()->route('patient.lab-orders.index')
                ->with('error', 'Payment verification failed.');
        }
    }

    // ═══════════════════════════════════════════
    // DOWNLOAD REPORT
    // ═══════════════════════════════════════════
    public function downloadReport($id)
    {
        $patient = Patient::where('user_id', Auth::id())->first();
        if (!$patient) {
            return redirect()->route('patient.dashboard')->with('error', 'Patient profile not found.');
        }

        $order = LabOrder::with(['laboratory'])
            ->where('patient_id', $patient->id)
            ->findOrFail($id);

        if ($order->payment_status !== 'paid') {
            return redirect()->route('patient.lab-orders.show', $id)
                ->with('error', 'Please complete payment to download your report.');
        }

        if ($order->status !== 'completed') {
            return redirect()->route('patient.lab-orders.show', $id)
                ->with('info', 'Your report is not ready yet. The lab is still processing.');
        }

        if (!$order->report_file) {
            return redirect()->route('patient.lab-orders.show', $id)
                ->with('info', 'Report not uploaded yet. Please contact the lab.');
        }

        if (Storage::disk('public')->exists($order->report_file)) {
            return Storage::disk('public')->download(
                $order->report_file,
                'HealthNet_Report_' . $order->order_number . '.pdf'
            );
        }

        return redirect()->route('patient.lab-orders.show', $id)
            ->with('error', 'Report file not found. Please contact the lab.');
    }

    // ═══════════════════════════════════════════
    // PRIVATE — Mark Order Paid
    // ═══════════════════════════════════════════
    private function markOrderPaid(LabOrder $order, string $transactionId, Patient $patient, ?string $cardholderName): void
    {
        DB::transaction(function () use ($order, $transactionId, $patient, $cardholderName) {

            // Update order — migration: payment_status enum('unpaid','paid')
            $order->update([
                'payment_status' => 'paid',
                'payment_method' => 'card',
            ]);

            // payments table — exact migration columns
            $paymentNumber = 'PAY-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));

            DB::table('payments')->insert([
                'payment_number'  => $paymentNumber,
                'payer_id'        => $patient->user_id,
                'payee_type'      => 'laboratory',
                'payee_id'        => $order->laboratory_id,
                'related_type'    => 'lab_order',  // migration: enum('appointment','lab_order','prescription_order')
                'related_id'      => $order->id,
                'amount'          => $order->total_amount ?? 0,
                'payment_method'  => 'card',
                'payment_status'  => 'completed',
                'transaction_id'  => $transactionId,
                'payment_date'    => now(),
                'notes'           => $cardholderName
                    ? 'Cardholder: ' . $cardholderName
                    : 'Lab order payment via Stripe',
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            // Patient notification
            try {
                DB::table('notifications')->insert([
                    'notifiable_type' => \App\Models\User::class,
                    'notifiable_id'   => $patient->user_id,
                    'type'            => 'payment',
                    'title'           => '✅ Lab Payment Confirmed — ' . $order->order_number,
                    'message'         => 'Your payment of Rs. ' . number_format($order->total_amount, 2)
                        . ' for Lab Order #' . $order->order_number
                        . ' at ' . ($order->laboratory->name ?? 'Lab')
                        . ' has been received. The lab will process your samples shortly.',
                    'related_type'    => 'lab_order',
                    'related_id'      => $order->id,
                    'is_read'         => false,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
            } catch (\Exception $e) {
                Log::warning('Patient notification: ' . $e->getMessage());
            }

            // Lab notification
            try {
                $lab = \App\Models\Laboratory::find($order->laboratory_id);
                if ($lab && $lab->user_id) {
                    DB::table('notifications')->insert([
                        'notifiable_type' => \App\Models\User::class,
                        'notifiable_id'   => $lab->user_id,
                        'type'            => 'payment',
                        'title'           => '💰 Payment Received — ' . $order->order_number,
                        'message'         => 'Patient ' . $patient->first_name . ' ' . $patient->last_name
                            . ' paid Rs. ' . number_format($order->total_amount, 2)
                            . ' for Order #' . $order->order_number . '. Please process the samples.',
                        'related_type'    => 'lab_order',
                        'related_id'      => $order->id,
                        'is_read'         => false,
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ]);
                }
            } catch (\Exception $e) {
                Log::warning('Lab notification: ' . $e->getMessage());
            }
        });
    }
}
