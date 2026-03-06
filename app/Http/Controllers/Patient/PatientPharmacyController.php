<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\Payment;
use App\Models\Pharmacy;
use App\Models\PharmacyOrder;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Services\PharmacyNotificationService;
class PatientPharmacyController extends Controller
{
    public function index(Request $request)
    {
        $query = Pharmacy::query()->where('status', 'approved');

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                  ->orWhere('city', 'like', $search)
                  ->orWhere('address', 'like', $search)
                  ->orWhere('pharmacist_name', 'like', $search);
            });
        }
        if ($request->filled('city'))     $query->where('city', $request->city);
        if ($request->filled('delivery')) $query->where('delivery_available', $request->delivery === 'yes' ? 1 : 0);

        $cities = Pharmacy::where('status', 'approved')
            ->whereNotNull('city')->where('city', '!=', '')
            ->distinct()->orderBy('city')->pluck('city');

        $pharmacies = $query->orderBy('rating', 'desc')->orderBy('name')->paginate(12);

        return view('patient.pharmacies', compact('pharmacies', 'cities'));
    }

    public function show($id)
    {
        $pharmacy = Pharmacy::with('user')->where('status', 'approved')->findOrFail($id);

        $ratings = Rating::where('ratable_type', 'pharmacy')
            ->where('ratable_id', $pharmacy->id)
            ->with('patient')
            ->latest()->paginate(5, ['*'], 'reviews_page');

        $ratingBreakdown = [];
        for ($i = 5; $i >= 1; $i--) {
            $count = Rating::where('ratable_type', 'pharmacy')
                ->where('ratable_id', $pharmacy->id)->where('rating', $i)->count();
            $ratingBreakdown[$i] = [
                'count'      => $count,
                'percentage' => $pharmacy->total_ratings > 0 ? round(($count / $pharmacy->total_ratings) * 100) : 0,
            ];
        }

        $patient       = Auth::user()->patient;
        $userRating    = null;
        $canReview     = false;
        $reviewableOrder = null;
        $previousOrders  = collect();

        if ($patient) {
            $userRating = Rating::where('patient_id', $patient->id)
                ->where('ratable_type', 'pharmacy')->where('ratable_id', $pharmacy->id)->latest()->first();

            $reviewableOrder = PharmacyOrder::where('pharmacy_id', $pharmacy->id)
                ->where('patient_id', $patient->id)->where('status', 'delivered')
                ->whereNotExists(function ($q) use ($patient) {
                    $q->select(DB::raw(1))->from('ratings')
                      ->whereColumn('ratings.related_id', 'prescription_orders.id')
                      ->where('ratings.related_type', 'prescriptionorder')
                      ->where('ratings.patient_id', $patient->id);
                })->latest()->first();

            $canReview      = $reviewableOrder !== null;
            $previousOrders = PharmacyOrder::where('pharmacy_id', $pharmacy->id)
                ->where('patient_id', $patient->id)
                ->with('items')->latest()->limit(3)->get();
        }

        return view('patient.pharmacy-profile', compact(
            'pharmacy', 'ratings', 'ratingBreakdown',
            'canReview', 'reviewableOrder', 'userRating', 'previousOrders'
        ));
    }

    public function medicines(Request $request, $id)
    {
        $pharmacy = Pharmacy::where('status', 'approved')->findOrFail($id);

        $query = Medicine::where('pharmacy_id', $pharmacy->id)
            ->where('is_active', true)
            ->where('stock_status', '!=', 'out_of_stock');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $s = '%' . $request->search . '%';
            $query->where(fn($q) =>
                $q->where('name', 'like', $s)
                ->orWhere('generic_name', 'like', $s)
            );
        }

        if ($request->filled('rx')) {
            $query->where('requires_prescription', $request->rx === 'rx' ? 1 : 0);
        }

        $medicines  = $query->orderBy('category')->orderBy('name')->paginate(18);
        $categories = Medicine::where('pharmacy_id', $pharmacy->id)
            ->where('is_active', true)
            ->where('stock_status', '!=', 'out_of_stock') // ✅ correct ENUM
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('patient.pharmacy-medicines', compact('pharmacy', 'medicines', 'categories'));
    }


    public function orderForm($id)
    {
        $pharmacy = Pharmacy::where('status', 'approved')->findOrFail($id);
        $patient  = Auth::user()->patient;
        if (!$patient) return redirect()->route('login');

        return view('patient.pharmacy-order', compact('pharmacy', 'patient'));
    }

 public function placeOrder(Request $request, $id)
{
    $pharmacy = Pharmacy::where('status', 'approved')->findOrFail($id);
    $patient  = Auth::user()->patient;
    if (!$patient) return back()->withError('Patient profile not found.');

    $request->validate([
        'prescription_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        'delivery_type'     => 'required|in:delivery,pickup',
        'delivery_address'  => 'required_if:delivery_type,delivery|nullable|string|max:500',
        'delivery_method'   => 'nullable|in:uber,pickme,own_delivery',   // ✅ own_delivery
        'payment_method'    => 'required|in:cash_on_delivery,online',    // ✅ cash_on_delivery
    ]);

    DB::beginTransaction();
    try {
        $path       = $request->file('prescription_file')->store('prescriptions', 'public');
        $isDelivery = $request->delivery_type === 'delivery';

        $order = PharmacyOrder::create([
            'patient_id'        => $patient->id,
            'pharmacy_id'       => $pharmacy->id,
            'prescription_file' => $path,
            'status'            => 'pending',
            'total_amount'      => 0.00,
            'delivery_fee'      => 0.00,
            'payment_method'    => $request->payment_method,  // → 'cash_on_delivery' or 'online'
            'payment_status'    => 'unpaid',
            'delivery_address'  => $isDelivery ? $request->delivery_address : 'PICKUP',
            'delivery_method'   => $isDelivery ? $request->delivery_method : null,
        ]);

        PharmacyNotificationService::newOrderSubmitted(
            $order->load(['patient.user', 'pharmacy'])
        );

        DB::commit();

        if ($request->payment_method === 'online') {
            return redirect()->route('patient.pharmacies.track', $pharmacy->id)
                ->withSuccess('Prescription submitted! Pay online after pharmacy confirms your order.');
        }

        return redirect()->route('patient.pharmacies.track', $pharmacy->id)
            ->withSuccess('Prescription submitted! The pharmacy will verify and contact you.');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Pharmacy order error: ' . $e->getMessage() . ' | Line: ' . $e->getLine());
        return back()->withError('Failed to place order. Please try again.')->withInput();
    }
}


    public function paymentPage($orderId)
    {
        $patient = Auth::user()->patient;
        if (!$patient) return redirect()->route('login');

        $order = PharmacyOrder::where('patient_id', $patient->id)
            ->with(['pharmacy', 'items.medication'])
            ->findOrFail($orderId);

        if ($order->payment_status === 'paid') {
            return redirect()->route('patient.pharmacies.track', $order->pharmacy_id)
                ->withInfo('This order is already paid.');
        }

        if (!in_array($order->status, ['verified', 'processing', 'ready'])) {
            return redirect()->route('patient.pharmacies.track', $order->pharmacy_id)
                ->withInfo('Payment is not available yet. Pharmacy must verify your order first.');
        }

        $stripeKey = config('services.stripe.key');
        return view('patient.pharmacy-payment', compact('order', 'stripeKey'));
    }

   public function processPayment(Request $request, $orderId)
{
    $patient = Auth::user()->patient;
    if (!$patient) return redirect()->route('login');

    $order = PharmacyOrder::where('patient_id', $patient->id)
        ->with('pharmacy')->findOrFail($orderId);

    $request->validate([
        'payment_method_id' => 'required|string',
        'cardholder_name'   => 'nullable|string|max:100',
    ]);

    if ($order->payment_status === 'paid')
        return back()->withError('This order is already paid.');

    if ($order->status === 'cancelled')
        return back()->withError('Cannot pay for a cancelled order.');

    DB::beginTransaction();
    try {
        Stripe::setApiKey(config('services.stripe.secret'));

        $total  = ($order->total_amount ?? 0) + ($order->delivery_fee ?? 0);
        $amount = (int) round($total * 100); // cents

        if ($amount < 100)
            return redirect()->route('patient.pharmacies.payment', $orderId)
                ->withError('Invalid amount. Minimum payment is Rs. 1.00');

        $paymentIntent = PaymentIntent::create([
            'amount'             => $amount,
            'currency'           => 'lkr',
            'payment_method'     => $request->payment_method_id,
            'confirmation_method'=> 'automatic',
            'confirm'            => true,
            'return_url'         => route('patient.pharmacies.track', $order->pharmacy_id),
            'description'        => 'Pharmacy Order #' . $order->order_number,
            'metadata'           => [
                'order_id'   => $order->id,
                'patient_id' => $patient->id,
            ],
        ]);

        // ✅ Payment succeeded immediately
        if ($paymentIntent->status === 'succeeded') {
            $this->markPharmacyOrderPaid($order, $paymentIntent->id, Auth::id(), $request->cardholder_name);
            DB::commit();
            return redirect()->route('patient.pharmacies.track', $order->pharmacy_id)
                ->withSuccess('Payment successful! Order #' . $order->order_number . ' confirmed.');
        }

        // ⚡ 3DS Authentication required
        if ($paymentIntent->status === 'requires_action' &&
            $paymentIntent->next_action?->type === 'redirect_to_url') {
            session(['pending_pharmacy_payment_intent' => $paymentIntent->id]);
            session(['pending_pharmacy_order_id' => $orderId]);
            DB::commit();
            return redirect()->away($paymentIntent->next_action->redirect_to_url->url);
        }

        // ❌ Card declined
        if ($paymentIntent->status === 'requires_payment_method') {
            DB::rollBack();
            return redirect()->route('patient.pharmacies.payment', $orderId)
                ->withError('Card was declined. Please try a different card.');
        }

        DB::rollBack();
        return redirect()->route('patient.pharmacies.payment', $orderId)
            ->withError('Payment unsuccessful. Status: ' . $paymentIntent->status);

    } catch (\Stripe\Exception\CardException $e) {
        DB::rollBack();
        return redirect()->route('patient.pharmacies.payment', $orderId)
            ->withError($e->getUserMessage());
    } catch (\Stripe\Exception\InvalidRequestException $e) {
        DB::rollBack();
        if (str_contains($e->getMessage(), 'currency'))
            return redirect()->route('patient.pharmacies.payment', $orderId)
                ->withError('LKR currency issue. Please contact support.');
        return redirect()->route('patient.pharmacies.payment', $orderId)
            ->withError('Invalid payment request: ' . $e->getMessage());
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Pharmacy payment error: ' . $e->getMessage());
        return redirect()->route('patient.pharmacies.payment', $orderId)
            ->withError('Payment failed: ' . $e->getMessage());
    }
}

// ✅ 3DS Callback Route
public function paymentCallback(Request $request, $orderId)
{
    $intentId = session('pending_pharmacy_payment_intent');
    $sessionOrderId = session('pending_pharmacy_order_id');

    if (!$intentId || !$sessionOrderId)
        return redirect()->route('patient.pharmacies.track', request('pharmacy_id', 0))
            ->withError('Payment session expired. Please try again.');

    try {
        Stripe::setApiKey(config('services.stripe.secret'));
        $paymentIntent = PaymentIntent::retrieve($intentId);
        $patient = Auth::user()->patient;
        $order = PharmacyOrder::where('patient_id', $patient->id)->findOrFail($sessionOrderId);

        if ($paymentIntent->status === 'succeeded') {
            $this->markPharmacyOrderPaid($order, $intentId, $patient->id, null);
            session()->forget(['pending_pharmacy_payment_intent', 'pending_pharmacy_order_id']);
            return redirect()->route('patient.pharmacies.track', $order->pharmacy_id)
                ->withSuccess('Payment successful! Order confirmed.');
        }

        return redirect()->route('patient.pharmacies.payment', $orderId)
            ->withError('Payment authentication failed. Please try again.');

    } catch (\Exception $e) {
        Log::error('Pharmacy payment callback error: ' . $e->getMessage());
        return redirect()->route('patient.pharmacies.track', 0)
            ->withError('Payment verification failed.');
    }
}

// ✅ Helper: Mark order as paid + create Payment record
private function markPharmacyOrderPaid(
    PharmacyOrder $order,
    string $transactionId,
    int $payerId,
    ?string $cardholderName
): void {
    DB::transaction(function () use ($order, $transactionId, $payerId, $cardholderName) {

        // ✅ prescription_orders.payment_method ENUM = 'cashondelivery','online'
        // Stripe online payment → 'online' use කරන්න, 'card' නොවේ!
        $order->update([
            'payment_method' => 'online',   // ← 'card' නොවේ! ENUM value නිවැරදි
            'payment_status' => 'paid',
        ]);

        // payments table payment_method ENUM = 'cash','card','online','banktransfer'
        // payments table හිදී 'card' use කළ හැකි ✅
        $paymentNumber = 'PAY-PH-' . strtoupper(\Illuminate\Support\Str::random(8));

        DB::table('payments')->insert([
            'payment_number'  => $paymentNumber,
            'payer_id'        => $payerId,
            'payee_type'      => 'pharmacy',
            'payee_id'        => $order->pharmacy_id,
            'related_type'    => 'prescriptionorder',
            'related_id'      => $order->id,
            'amount'          => ($order->total_amount ?? 0) + ($order->delivery_fee ?? 0),
            'payment_method'  => 'card',    // ← payments table 'card' OK ✅
            'payment_status'  => 'completed',
            'transaction_id'  => $transactionId,
            'payment_date'    => now()->toDateString(),
            'notes'           => $cardholderName
                                    ? 'Cardholder: ' . $cardholderName . '. Online payment via Stripe'
                                    : 'Online payment via Stripe',
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // ✅ Notifications
        PharmacyNotificationService::paymentReceived(
            $order->load(['patient.user', 'pharmacy'])
        );
    });
}



    public function trackOrder(Request $request, $id)
    {
        $pharmacy = Pharmacy::where('status', 'approved')->findOrFail($id);
        $patient  = Auth::user()->patient;
        if (!$patient) return redirect()->route('login');

        $statusFilter = $request->status;
        $query = PharmacyOrder::where('pharmacy_id', $pharmacy->id)
            ->where('patient_id', $patient->id)
            ->with('items.medication');

        if ($statusFilter) $query->where('status', $statusFilter);

        $orders    = $query->latest()->paginate(10);
        $orderView = $request->order
            ? PharmacyOrder::where('pharmacy_id', $pharmacy->id)
                ->where('patient_id', $patient->id)
                ->with('items.medication')
                ->findOrFail($request->order)
            : null;

        return view('patient.pharmacy-track', compact('pharmacy', 'orders', 'orderView', 'statusFilter'));
    }

    public function cancelOrder(Request $request, $id, $orderId)
{
    $pharmacy = Pharmacy::where('status', 'approved')->findOrFail($id);
    $patient  = Auth::user()->patient;
    if (!$patient) return redirect()->route('login');

    $order = PharmacyOrder::where('pharmacy_id', $pharmacy->id)
        ->where('patient_id', $patient->id)
        ->whereIn('status', ['pending']) // ✅ pending පමණි cancel කළ හැකි
        ->findOrFail($orderId);

    $request->validate([
        'cancelled_reason' => 'nullable|string|max:500',
    ]);

    DB::beginTransaction();
    try {
        $order->update([
            'status'           => 'cancelled',
            'cancelled_reason' => $request->cancelled_reason ?? 'Cancelled by patient',
        ]);

        // ✅ Both parties notify
        PharmacyNotificationService::statusChanged(
            $order->load(['patient.user', 'pharmacy']),
            'cancelled'
        );

        DB::commit();
        return redirect()->route('patient.pharmacies.track', $pharmacy->id)
            ->withSuccess('Order #' . $order->order_number . ' has been cancelled.');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Pharmacy cancel error: ' . $e->getMessage());
        return back()->withError('Failed to cancel order. Please try again.');
    }
}

    public function storeReview(Request $request, $id)
    {
        $pharmacy = Pharmacy::where('status', 'approved')->findOrFail($id);
        $patient  = Auth::user()->patient;
        if (!$patient) return back()->withError('Patient profile not found.');

        $request->validate([
            'rating'           => 'required|integer|min:1|max:5',
            'review'           => 'nullable|string|max:1000',
            'related_order_id' => 'nullable|exists:prescription_orders,id',
        ]);

        $hasDelivered = PharmacyOrder::where('pharmacy_id', $pharmacy->id)
            ->where('patient_id', $patient->id)->where('status', 'delivered')->exists();

        if (!$hasDelivered) {
            return back()->withError('You can only review after receiving your order.');
        }

        $exists = Rating::where('patient_id', $patient->id)
            ->where('ratable_type', 'pharmacy')->where('ratable_id', $pharmacy->id)
            ->when($request->related_order_id, fn($q) =>
                $q->where('related_id', $request->related_order_id)->where('related_type', 'prescriptionorder')
            )->exists();

        if ($exists) return back()->withError('You have already reviewed this pharmacy.');

        DB::beginTransaction();
        try {
            Rating::create([
                'patient_id'   => $patient->id,
                'ratable_type' => 'pharmacy',
                'ratable_id'   => $pharmacy->id,
                'rating'       => $request->rating,
                'review'       => $request->review,
                'related_type' => $request->related_order_id ? 'prescriptionorder' : null,
                'related_id'   => $request->related_order_id,
            ]);

            $avg   = Rating::where('ratable_type', 'pharmacy')->where('ratable_id', $pharmacy->id)->avg('rating');
            $total = Rating::where('ratable_type', 'pharmacy')->where('ratable_id', $pharmacy->id)->count();
            $pharmacy->update(['rating' => round($avg, 2), 'total_ratings' => $total]);

            DB::commit();
            return back()->withSuccess('Thank you for your review!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withError('Failed to submit review.');
        }
    }
/**
 * Redirect to latest pharmacy order track page
 */
    public function myOrdersRedirect()
    {
        $patient = Auth::user()->patient;

        if (!$patient) {
            return redirect()->route('patient.dashboard')
                ->with('error', 'Patient profile not found.');
        }

        // Get latest order with pharmacy
        $latestOrder = PharmacyOrder::with('pharmacy')
            ->where('patient_id', $patient->id)
            ->latest()
            ->first();

        if (!$latestOrder) {
            // No orders yet → go to find pharmacies page
            return redirect()->route('patient.pharmacies')
                ->with('info', 'You have no pharmacy orders yet. Browse pharmacies to place an order.');
        }

        // Redirect to track page with pharmacy id
        return redirect()->route('patient.pharmacies.track', $latestOrder->pharmacy_id);
    }

}
