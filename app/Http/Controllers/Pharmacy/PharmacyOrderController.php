<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Mail\PharmacyOrderInvoiceMail;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\PharmacyOrder;
use App\Models\PrescriptionOrderItem;
use App\Models\User;
use App\Services\PharmacyNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class PharmacyOrderController extends Controller
{
    // ═══════════════════════════════════════════════════════════
    // INDEX
    // ═══════════════════════════════════════════════════════════
    public function index(Request $request)
    {
        $pharmacy = Auth::user()->pharmacy;

        $query = PharmacyOrder::where('pharmacy_id', $pharmacy->id)
            ->with(['patient.user', 'items.medication']);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('order_type')) {
            match ($request->order_type) {
                'has_items'  => $query->whereHas('items'),
                'presc_only' => $query->whereDoesntHave('items'),
                'cart_otc'   => $query->whereHas('items')
                    ->whereDoesntHave('items', fn($q) =>
                        $q->whereHas('medication', fn($m) =>
                            $m->where('requires_prescription', true)
                        )
                    ),
                'cart_rx' => $query->whereHas('items', fn($q) =>
                    $q->whereHas('medication', fn($m) =>
                        $m->where('requires_prescription', true)
                    )
                ),
                default => null,
            };
        }

        if ($request->filled('search')) {
            $s = '%' . $request->search . '%';
            $query->where(fn($q) =>
                $q->where('order_number', 'like', $s)
                  ->orWhereHas('patient', fn($p) =>
                      $p->where('first_name', 'like', $s)
                        ->orWhere('last_name', 'like', $s)
                  )
            );
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(20)->withQueryString();

        $counts = [];
        foreach (['pending','verified','processing','ready','dispatched','delivered','cancelled'] as $st) {
            $counts[$st] = PharmacyOrder::where('pharmacy_id', $pharmacy->id)
                ->where('status', $st)->count();
        }

        return view('pharmacy.orders.index', compact('orders', 'counts'));
    }

    // ═══════════════════════════════════════════════════════════
    // SHOW
    // ═══════════════════════════════════════════════════════════
    public function show(PharmacyOrder $order)
    {
        $this->authOrder($order);

        $pharmacy = Auth::user()->pharmacy;
        $order->load(['patient.user', 'items.medication', 'payment']);

        $medicines = Medicine::where('pharmacy_id', $pharmacy->id)
            ->where('is_active', true)
            ->where('stock_status', '!=', 'out_of_stock')
            ->orderBy('name')
            ->get();

        return view('pharmacy.orders.show', compact('order', 'medicines'));
    }

    // ═══════════════════════════════════════════════════════════
    // CREATE
    // ═══════════════════════════════════════════════════════════
    public function create()
    {
        $pharmacy  = Auth::user()->pharmacy;
        $patients  = Patient::with('user')->get();
        $medicines = Medicine::where('pharmacy_id', $pharmacy->id)
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->orderBy('name')
            ->get();

        return view('pharmacy.orders.create', compact('patients', 'medicines'));
    }

    // ═══════════════════════════════════════════════════════════
    // STORE
    // ═══════════════════════════════════════════════════════════
    public function store(Request $request)
    {
        $pharmacy = Auth::user()->pharmacy;

        $request->validate([
            'patient_id'            => 'required|exists:patients,id',
            'prescription_file'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'delivery_address'      => 'required|string|max:500',
            'delivery_method'       => 'nullable|in:uber,pickme,own_delivery',
            'delivery_fee'          => 'nullable|numeric|min:0',
            'payment_method'        => 'required|in:cash_on_delivery,online',
            'items'                 => 'required|array|min:1',
            'items.*.medication_id' => 'required|exists:medications,id',
            'items.*.quantity'      => 'required|integer|min:1',
            'items.*.price'         => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $prescPath = $request->hasFile('prescription_file')
                ? $request->file('prescription_file')->store('prescriptions', 'public')
                : '';

            $totalAmount = collect($request->items)
                ->sum(fn($i) => $i['quantity'] * $i['price']);

            $order = PharmacyOrder::create([
                'patient_id'        => $request->patient_id,
                'pharmacy_id'       => $pharmacy->id,
                'prescription_file' => $prescPath,
                'delivery_address'  => $request->delivery_address,
                'delivery_method'   => $request->delivery_method,
                'delivery_fee'      => $request->delivery_fee ?? 0,
                'payment_method'    => $request->payment_method,
                'total_amount'      => $totalAmount,
                'status'            => 'verified',
                'payment_status'    => 'unpaid',
            ]);

            foreach ($request->items as $item) {
                $med = Medicine::findOrFail($item['medication_id']);
                PrescriptionOrderItem::create([
                    'order_id'        => $order->id,
                    'medication_id'   => $med->id,
                    'medication_name' => $med->name,
                    'quantity'        => $item['quantity'],
                    'price'           => $item['price'],
                    'subtotal'        => $item['quantity'] * $item['price'],
                ]);
                $med->decreaseStock((int) $item['quantity']);
            }

            $order->load(['patient.user', 'pharmacy', 'items.medication']);

            PharmacyNotificationService::newOrderSubmitted($order);
            PharmacyNotificationService::statusChanged($order, 'verified');

            $this->notifyPatient(
                $order,
                'order_placed',
                'New Order Created',
                $pharmacy->name . ' has created an order for you. Order #' .
                $order->order_number . ' — LKR ' . number_format($totalAmount, 2) . '.'
            );

            $this->sendInvoiceEmail($order, 'verified');

            DB::commit();

            return redirect()->route('pharmacy.orders.show', $order->id)
                ->withSuccess('Order #' . $order->order_number . ' created. Patient notified & invoice emailed.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Store order error: ' . $e->getMessage());
            return back()->withError('Failed to create order: ' . $e->getMessage())->withInput();
        }
    }

    // ═══════════════════════════════════════════════════════════
    // SET AMOUNT
    // ═══════════════════════════════════════════════════════════
    public function setAmount(Request $request, PharmacyOrder $order)
    {
        $this->authOrder($order);

        $request->validate([
            'total_amount' => 'required|numeric|min:0',
            'delivery_fee' => 'nullable|numeric|min:0',
        ]);

        $order->update([
            'total_amount' => $request->total_amount,
            'delivery_fee' => $request->delivery_fee ?? 0,
        ]);

        $order->load(['patient.user', 'pharmacy']);

        $grand = (float)$request->total_amount + (float)($request->delivery_fee ?? 0);

        PharmacyNotificationService::statusChanged($order, $order->status);

        $this->notifyPatient(
            $order,
            'amount_confirmed',
            'Order Amount Confirmed',
            'Your order #' . $order->order_number . ' amount has been confirmed: LKR ' .
            number_format($grand, 2) . '. ' .
            ($order->payment_method === 'online' ? 'You can now pay online.' : 'Please pay on delivery.')
        );

        return back()->withSuccess(
            'Amount set for Order #' . $order->order_number .
            ' — LKR ' . number_format($request->total_amount, 2)
        );
    }

    // ═══════════════════════════════════════════════════════════
    // VERIFY
    // ═══════════════════════════════════════════════════════════
    public function verify(Request $request, PharmacyOrder $order)
    {
        $this->authOrder($order);

        if ($order->status !== 'pending') {
            return back()->withError('Only pending orders can be verified.');
        }

        $request->validate([
            'items'                 => 'required|array|min:1',
            'items.*.type'          => 'required|in:medicine,other',
            'items.*.medication_id' => 'nullable|exists:medications,id',
            'items.*.medicine_name' => 'required|string|max:255',
            'items.*.quantity'      => 'required|integer|min:1',
            'items.*.price'         => 'required|numeric|min:0.01',
            'delivery_fee'          => 'nullable|numeric|min:0',
            'pharmacist_notes'      => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $totalAmount = collect($request->items)
                ->sum(fn($i) => (int)$i['quantity'] * (float)$i['price']);
            $deliveryFee = (float)($request->delivery_fee ?? 0);

            $order->update([
                'status'           => 'verified',
                'total_amount'     => $totalAmount,
                'delivery_fee'     => $deliveryFee,
                'pharmacist_notes' => $request->pharmacist_notes,
            ]);

            $order->items()->delete();

            foreach ($request->items as $item) {
                $isDb     = $item['type'] === 'medicine' && !empty($item['medication_id']);
                $medicine = $isDb ? Medicine::find($item['medication_id']) : null;
                $medName  = $medicine ? $medicine->name : $item['medicine_name'];

                PrescriptionOrderItem::create([
                    'order_id'        => $order->id,
                    'medication_id'   => $isDb ? $item['medication_id'] : null,
                    'medication_name' => $medName,
                    'quantity'        => $item['quantity'],
                    'price'           => $item['price'],
                    'subtotal'        => (int)$item['quantity'] * (float)$item['price'],
                ]);

                if ($isDb && $medicine) {
                    $medicine->decreaseStock((int) $item['quantity']);
                }
            }

            $order->load(['patient.user', 'pharmacy', 'items.medication']);

            PharmacyNotificationService::statusChanged($order, 'verified');

            $this->notifyPatient(
                $order,
                'order_verified',
                'Prescription Verified',
                'Your prescription for Order #' . $order->order_number . ' has been verified by ' .
                $order->pharmacy->name . '. Total: LKR ' .
                number_format($totalAmount + $deliveryFee, 2) . '. ' .
                ($order->payment_method === 'online'
                    ? 'Please proceed to pay online.'
                    : 'Pay LKR ' . number_format($totalAmount + $deliveryFee, 2) . ' on delivery.')
            );

            $this->sendInvoiceEmail($order, 'verified');

            DB::commit();

            return back()->withSuccess(
                'Order #' . $order->order_number . ' verified! Patient notified & invoice emailed. Total: LKR ' .
                number_format($totalAmount + $deliveryFee, 2)
            );

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Verify order error: ' . $e->getMessage());
            return back()->withError('Failed to verify order: ' . $e->getMessage());
        }
    }

    // ═══════════════════════════════════════════════════════════
    // PROCESS
    // ═══════════════════════════════════════════════════════════
    public function process(PharmacyOrder $order)
    {
        $this->authOrder($order);
        $order->markAsProcessing();
        $order->load(['patient.user', 'pharmacy']);

        PharmacyNotificationService::statusChanged($order, 'processing');
        $this->notifyPatient(
            $order,
            'order_processing',
            'Order Being Prepared',
            'Your order #' . $order->order_number . ' is now being prepared by ' .
            $order->pharmacy->name . '. We\'ll notify you when it\'s ready.'
        );

        return back()->withSuccess('Order #' . $order->order_number . ' marked as processing.');
    }

    // ═══════════════════════════════════════════════════════════
    // READY
    // ═══════════════════════════════════════════════════════════
    public function ready(PharmacyOrder $order)
    {
        $this->authOrder($order);
        $order->markAsReady();
        $order->load(['patient.user', 'pharmacy']);

        $isPickup = $order->delivery_address === 'PICKUP';

        PharmacyNotificationService::statusChanged($order, 'ready');
        $this->notifyPatient(
            $order,
            'order_ready',
            'Order Ready!',
            'Your order #' . $order->order_number . ' is ready. ' .
            ($isPickup
                ? 'Please visit ' . $order->pharmacy->name . ' to collect your medicines.'
                : 'Your medicines will be dispatched shortly.')
        );

        return back()->withSuccess('Order #' . $order->order_number . ' is ready for delivery/pickup.');
    }

    // ═══════════════════════════════════════════════════════════
    // DISPATCH
    // ═══════════════════════════════════════════════════════════
    public function markDispatch(Request $request, PharmacyOrder $order)
    {
        $this->authOrder($order);

        $request->validate(['tracking_number' => 'required|string|max:100']);

        $order->markAsDispatched($request->tracking_number);
        $order->load(['patient.user', 'pharmacy']);

        PharmacyNotificationService::statusChanged($order, 'dispatched');
        $this->notifyPatient(
            $order,
            'order_dispatched',
            'Order Dispatched!',
            'Your order #' . $order->order_number . ' is on its way! Tracking: ' .
            $request->tracking_number .
            ($order->delivery_method
                ? '. Via ' . ucfirst(str_replace('_', ' ', $order->delivery_method)) . '.'
                : '.')
        );

        return back()->withSuccess(
            'Order #' . $order->order_number . ' dispatched. Tracking: ' . $request->tracking_number
        );
    }

    // ═══════════════════════════════════════════════════════════
    // DELIVER
    // ═══════════════════════════════════════════════════════════
    public function deliver(PharmacyOrder $order)
    {
        $this->authOrder($order);
        $order->markAsDelivered();

        if ($order->payment_method === 'cash_on_delivery') {
            $order->update(['payment_status' => 'paid']);
        }

        $order->load(['patient.user', 'pharmacy', 'items.medication']);

        PharmacyNotificationService::statusChanged($order, 'delivered');
        $this->notifyPatient(
            $order,
            'order_delivered',
            'Order Delivered!',
            'Your order #' . $order->order_number . ' has been delivered successfully. ' .
            ($order->payment_method === 'cash_on_delivery'
                ? 'Payment received. Thank you!'
                : ($order->payment_status === 'paid'
                    ? 'Payment confirmed. Thank you!'
                    : 'Please complete payment if not done.')) .
            ' Please leave a review for ' . $order->pharmacy->name . '.'
        );

        $this->sendInvoiceEmail($order, 'delivered');

        return back()->withSuccess(
            'Order #' . $order->order_number . ' delivered! Patient notified & receipt emailed.'
        );
    }

    // ═══════════════════════════════════════════════════════════
    // CANCEL
    // ═══════════════════════════════════════════════════════════
    public function cancel(Request $request, PharmacyOrder $order)
    {
        $this->authOrder($order);

        if (!$order->canBeCancelled()) {
            return back()->withError('This order cannot be cancelled at this stage.');
        }

        $request->validate(['cancelled_reason' => 'required|string|max:500']);

        DB::beginTransaction();
        try {
            $order->cancel($request->cancelled_reason);

            foreach ($order->items as $item) {
                if ($item->medication) {
                    $item->medication->increaseStock($item->quantity);
                }
            }

            $order->load(['patient.user', 'pharmacy']);

            PharmacyNotificationService::statusChanged($order, 'cancelled');
            $this->notifyPatient(
                $order,
                'order_cancelled',
                'Order Cancelled',
                'Your order #' . $order->order_number . ' has been cancelled by ' .
                $order->pharmacy->name . '. Reason: ' . $request->cancelled_reason
            );

            DB::commit();

            return back()->withSuccess(
                'Order #' . $order->order_number . ' cancelled. Patient notified. Stock restored.'
            );

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Cancel order error: ' . $e->getMessage());
            return back()->withError('Cancel failed: ' . $e->getMessage());
        }
    }

    // ═══════════════════════════════════════════════════════════
    // UPDATE STATUS — Generic
    // ═══════════════════════════════════════════════════════════
    public function updateStatus(Request $request, PharmacyOrder $order)
    {
        $this->authOrder($order);

        $request->validate([
            'status'           => 'required|in:pending,verified,processing,ready,dispatched,delivered,cancelled',
            'total_amount'     => 'nullable|numeric|min:0',
            'delivery_fee'     => 'nullable|numeric|min:0',
            'tracking_number'  => 'nullable|string|max:100',
            'pharmacist_notes' => 'nullable|string|max:1000',
        ]);

        $updateData = [
            'status'           => $request->status,
            'tracking_number'  => $request->tracking_number  ?? $order->tracking_number,
            'pharmacist_notes' => $request->pharmacist_notes ?? $order->pharmacist_notes,
        ];

        if ($request->filled('total_amount')) $updateData['total_amount'] = $request->total_amount;
        if ($request->filled('delivery_fee'))  $updateData['delivery_fee'] = $request->delivery_fee;

        if ($request->status === 'delivered' &&
            $order->payment_method === 'cash_on_delivery') {
            $updateData['payment_status'] = 'paid';
        }

        $order->update($updateData);
        $order->load(['patient.user', 'pharmacy', 'items.medication']);

        PharmacyNotificationService::statusChanged($order, $request->status);

        // ── Notification config per status ──────────────────────
        $notifMap = [
            'verified' => [
                'title'   => 'Prescription Verified',
                'message' => 'Your prescription for Order #' . $order->order_number .
                             ' has been verified. Total: LKR ' .
                             number_format(($order->total_amount ?? 0) + ($order->delivery_fee ?? 0), 2) . '.' .
                             ($order->payment_method === 'online' ? ' Please pay online.' : ''),
            ],
            'processing' => [
                'title'   => 'Order Being Prepared',
                'message' => 'Your order #' . $order->order_number .
                             ' is being prepared by ' . $order->pharmacy->name . '.',
            ],
            'ready' => [
                'title'   => 'Order Ready!',
                'message' => 'Your order #' . $order->order_number . ' is ready for ' .
                             ($order->delivery_address === 'PICKUP' ? 'pickup.' : 'dispatch.'),
            ],
            'dispatched' => [
                'title'   => 'Order Dispatched!',
                'message' => 'Your order #' . $order->order_number . ' is on the way!' .
                             ($order->tracking_number
                                 ? ' Tracking: ' . $order->tracking_number . '.'
                                 : ''),
            ],
            'delivered' => [
                'title'   => 'Order Delivered!',
                'message' => 'Your order #' . $order->order_number . ' has been delivered. Thank you!',
            ],
            'cancelled' => [
                'title'   => 'Order Cancelled',
                'message' => 'Your order #' . $order->order_number . ' has been cancelled.',
            ],
        ];

        if (isset($notifMap[$request->status])) {
            $this->notifyPatient(
                $order,
                'order_' . $request->status,
                $notifMap[$request->status]['title'],
                $notifMap[$request->status]['message']
            );
        }

        if (in_array($request->status, ['verified', 'delivered'])) {
            $this->sendInvoiceEmail($order, $request->status);
        }

        return back()->withSuccess(
            'Order #' . $order->order_number . ' updated to ' .
            ucfirst($request->status) . '. Patient notified.'
        );
    }

    // ═══════════════════════════════════════════════════════════
    // DOWNLOAD PRESCRIPTION
    // ═══════════════════════════════════════════════════════════
    public function downloadPrescription(PharmacyOrder $order)
    {
        $this->authOrder($order);

        if (!$order->prescription_file) {
            return back()->withError('No prescription file available.');
        }

        return Storage::disk('public')->download($order->prescription_file);
    }

    // ═══════════════════════════════════════════════════════════
    // PRINT INVOICE
    // ═══════════════════════════════════════════════════════════
    public function printInvoice(PharmacyOrder $order)
    {
        $this->authOrder($order);
        $order->load(['patient.user', 'items.medication', 'pharmacy']);
        return view('pharmacy.orders.invoice', compact('order'));
    }

    // ═══════════════════════════════════════════════════════════
    // PRIVATE — Patient DB Notification
    // Table columns: notifiable_type, notifiable_id, type,
    //                title, message, related_type, related_id,
    //                is_read, read_at, created_at, updated_at
    // ═══════════════════════════════════════════════════════════
    private function notifyPatient(
        PharmacyOrder $order,
        string $type,
        string $title,
        string $message
    ): void {
        try {
            $user = $order->patient?->user;

            if (!$user) {
                Log::warning('Notification skipped — no user for Order #' . $order->order_number);
                return;
            }

            DB::table('notifications')->insert([
                'notifiable_type' => User::class,        // App\Models\User
                'notifiable_id'   => $user->id,
                'type'            => $type,
                'title'           => $title,
                'message'         => $message,
                'related_type'    => 'pharmacy_order',
                'related_id'      => $order->id,
                'is_read'         => false,
                'read_at'         => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            Log::info('Notification [' . $type . '] → user_id:' . $user->id .
                      ' | Order #' . $order->order_number);

        } catch (\Exception $e) {
            // Non-blocking
            Log::error('Notification failed [Order #' . $order->order_number . ']: ' .
                       $e->getMessage());
        }
    }

    // ═══════════════════════════════════════════════════════════
    // PRIVATE — Invoice Email (verified + delivered only)
    // ═══════════════════════════════════════════════════════════
    private function sendInvoiceEmail(PharmacyOrder $order, string $eventType): void
    {
        try {
            $email = $order->patient?->user?->email;

            if (!$email) {
                Log::warning('Invoice email skipped — no email for Order #' . $order->order_number);
                return;
            }

            Mail::to($email)->send(new PharmacyOrderInvoiceMail($order, $eventType));

            Log::info('Invoice email [' . $eventType . '] → ' . $email .
                      ' | Order #' . $order->order_number);

        } catch (\Exception $e) {
            Log::error('Invoice email failed [Order #' . $order->order_number .
                       '] [' . $eventType . ']: ' . $e->getMessage());
        }
    }

    // ═══════════════════════════════════════════════════════════
    // PRIVATE — Authorization
    // ═══════════════════════════════════════════════════════════
    private function authOrder(PharmacyOrder $order): void
    {
        if ($order->pharmacy_id !== Auth::user()->pharmacy->id) {
            abort(403, 'Unauthorized access to this order.');
        }
    }
}
