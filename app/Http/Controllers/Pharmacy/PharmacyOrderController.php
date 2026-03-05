<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\PharmacyOrder;       // prescription_orders table
use App\Models\Medicine;             // medications table
use App\Models\Patient;
use Carbon\Carbon;

class PharmacyOrderController extends Controller
{
    /* ─────────────────────────────────────────
     |  HELPER: Auth pharmacy guard
     ─────────────────────────────────────────*/
    private function pharmacy()
    {
        return Auth::user()->pharmacy;
    }

    private function authorizeOrder(PharmacyOrder $order): void
    {
        if ($order->pharmacy_id !== $this->pharmacy()->id) {
            abort(403, 'Unauthorized action.');
        }
    }

    /* ─────────────────────────────────────────
     |  INDEX  – orders list with filters
     ─────────────────────────────────────────*/
    public function index(Request $request)
    {
        $pharmacy = $this->pharmacy();

        $query = DB::table('prescription_orders as po')
            ->join('patients as pt', 'pt.id', '=', 'po.patient_id')
            ->where('po.pharmacy_id', $pharmacy->id)
            ->select(
                'po.*',
                DB::raw("CONCAT(pt.first_name,' ',pt.last_name) as patient_name"),
                'pt.profile_image as patient_image',
                'pt.phone as patient_phone'
            );

        // Status filter
        if ($request->filled('status')) {
            $query->where('po.status', $request->status);
        }

        // Payment filter
        if ($request->filled('payment_status')) {
            $query->where('po.payment_status', $request->payment_status);
        }

        // Date filter
        if ($request->filled('date_from')) {
            $query->whereDate('po.order_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('po.order_date', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('po.order_number', 'like', "%{$s}%")
                  ->orWhere(DB::raw("CONCAT(pt.first_name,' ',pt.last_name)"), 'like', "%{$s}%")
                  ->orWhere('pt.phone', 'like', "%{$s}%");
            });
        }

        $orders = $query->orderByDesc('po.order_date')->paginate(20)->withQueryString();

        // Status counts for badges
        $counts = DB::table('prescription_orders')
            ->where('pharmacy_id', $pharmacy->id)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('pharmacy.orders.index', compact('orders', 'counts'));
    }

    /* ─────────────────────────────────────────
     |  SHOW  – order details
     ─────────────────────────────────────────*/
    public function show(PharmacyOrder $order)
    {
        $this->authorizeOrder($order);

        // Eager load items with medication details
        $items = DB::table('prescription_order_items as oi')
            ->leftJoin('medications as m', 'm.id', '=', 'oi.medication_id')
            ->where('oi.order_id', $order->id)
            ->select('oi.*', 'm.category', 'm.dosage', 'm.manufacturer', 'm.requires_prescription')
            ->get();

        $patient = DB::table('patients as pt')
            ->join('users as u', 'u.id', '=', 'pt.user_id')
            ->where('pt.id', $order->patient_id)
            ->select('pt.*', 'u.email', 'u.status as user_status')
            ->first();

        // Payment record if exists
        $payment = DB::table('payments')
            ->where('related_type', 'prescription_order')
            ->where('related_id', $order->id)
            ->first();

        return view('pharmacy.orders.show', compact('order', 'items', 'patient', 'payment'));
    }

    /* ─────────────────────────────────────────
     |  CREATE  – manual order form
     ─────────────────────────────────────────*/
    public function create()
    {
        $pharmacy = $this->pharmacy();

        $patients = DB::table('patients as pt')
            ->join('users as u', 'u.id', '=', 'pt.user_id')
            ->where('u.status', 'active')
            ->select('pt.id', 'pt.first_name', 'pt.last_name', 'pt.phone', 'u.email')
            ->orderBy('pt.first_name')
            ->get();

        $medicines = DB::table('medications')
            ->where('pharmacy_id', $pharmacy->id)
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->orderBy('name')
            ->get();

        return view('pharmacy.orders.create', compact('patients', 'medicines'));
    }

    /* ─────────────────────────────────────────
     |  STORE  – save new manual order
     ─────────────────────────────────────────*/
    public function store(Request $request)
    {
        $pharmacy = $this->pharmacy();

        $request->validate([
            'patient_id'        => 'required|exists:patients,id',
            'prescription_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'delivery_address'  => 'required|string|max:500',
            'delivery_method'   => 'nullable|in:uber,pickme,own_delivery',
            'delivery_fee'      => 'nullable|numeric|min:0',
            'payment_method'    => 'required|in:cash_on_delivery,online',
            'pharmacist_notes'  => 'nullable|string|max:1000',
            'items'             => 'required|array|min:1',
            'items.*.medication_id' => 'required|exists:medications,id',
            'items.*.quantity'      => 'required|integer|min:1',
            'items.*.price'         => 'required|numeric|min:0',
        ]);

        // Upload prescription
        $prescriptionPath = $request->file('prescription_file')
            ->store('prescriptions', 'public');

        // Calculate totals
        $subtotal    = 0;
        $deliveryFee = $request->delivery_fee ?? 0;

        foreach ($request->items as $item) {
            $subtotal += $item['quantity'] * $item['price'];
        }
        $totalAmount = $subtotal + $deliveryFee;

        // Generate order number
        $orderNumber = 'RX' . strtoupper(uniqid());

        DB::beginTransaction();
        try {
            // Insert prescription_orders
            $orderId = DB::table('prescription_orders')->insertGetId([
                'order_number'     => $orderNumber,
                'patient_id'       => $request->patient_id,
                'pharmacy_id'      => $pharmacy->id,
                'prescription_file'=> $prescriptionPath,
                'order_date'       => now(),
                'status'           => 'pending',
                'total_amount'     => $totalAmount,
                'delivery_fee'     => $deliveryFee,
                'payment_method'   => $request->payment_method,
                'payment_status'   => 'unpaid',
                'delivery_address' => $request->delivery_address,
                'delivery_method'  => $request->delivery_method,
                'pharmacist_notes' => $request->pharmacist_notes,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);

            // Insert items & decrease stock
            foreach ($request->items as $item) {
                $med = DB::table('medications')->find($item['medication_id']);

                DB::table('prescription_order_items')->insert([
                    'order_id'        => $orderId,
                    'medication_id'   => $item['medication_id'],
                    'medication_name' => $med->name,
                    'quantity'        => $item['quantity'],
                    'price'           => $item['price'],
                    'subtotal'        => $item['quantity'] * $item['price'],
                    'created_at'      => now(),
                ]);

                // Decrease stock in medications table
                $newQty = max(0, $med->stock_quantity - $item['quantity']);
                $newStatus = $newQty <= 0 ? 'out_of_stock' : ($newQty <= 10 ? 'low_stock' : 'in_stock');
                DB::table('medications')
                    ->where('id', $item['medication_id'])
                    ->update([
                        'stock_quantity' => $newQty,
                        'stock_status'   => $newStatus,
                        'updated_at'     => now(),
                    ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Order creation failed: ' . $e->getMessage());
        }

        return redirect()->route('pharmacy.orders.show', $orderId)
            ->with('success', 'Order #' . $orderNumber . ' created successfully.');
    }

    /* ─────────────────────────────────────────
     |  UPDATE STATUS
     ─────────────────────────────────────────*/
    public function updateStatus(Request $request, PharmacyOrder $order)
    {
        $this->authorizeOrder($order);

        $request->validate([
            'status'           => 'required|in:pending,verified,processing,ready,dispatched,delivered,cancelled',
            'tracking_number'  => 'nullable|string|max:100',
            'pharmacist_notes' => 'nullable|string|max:1000',
        ]);

        $updateData = [
            'status'           => $request->status,
            'pharmacist_notes' => $request->pharmacist_notes ?? $order->pharmacist_notes,
            'updated_at'       => now(),
        ];

        if ($request->filled('tracking_number')) {
            $updateData['tracking_number'] = $request->tracking_number;
        }

        // Auto-mark paid on delivery (COD)
        if ($request->status === 'delivered' && $order->payment_method === 'cash_on_delivery') {
            $updateData['payment_status'] = 'paid';
        }

        DB::table('prescription_orders')
            ->where('id', $order->id)
            ->update($updateData);

        return back()->with('success', 'Order status updated to "' . ucfirst($request->status) . '" successfully.');
    }

    /* ─────────────────────────────────────────
     |  QUICK STATUS ACTIONS
     ─────────────────────────────────────────*/
    public function verify(PharmacyOrder $order)
    {
        $this->authorizeOrder($order);
        DB::table('prescription_orders')->where('id', $order->id)
            ->update(['status' => 'verified', 'updated_at' => now()]);
        return back()->with('success', 'Prescription verified successfully.');
    }

    public function process(PharmacyOrder $order)
    {
        $this->authorizeOrder($order);
        DB::table('prescription_orders')->where('id', $order->id)
            ->update(['status' => 'processing', 'updated_at' => now()]);
        return back()->with('success', 'Order is now being processed.');
    }

    public function ready(PharmacyOrder $order)
    {
        $this->authorizeOrder($order);
        DB::table('prescription_orders')->where('id', $order->id)
            ->update(['status' => 'ready', 'updated_at' => now()]);
        return back()->with('success', 'Order is ready for pickup/dispatch.');
    }

    public function markDispatch(Request $request, PharmacyOrder $order)
    {
        $this->authorizeOrder($order);
        $request->validate(['tracking_number' => 'required|string|max:100']);
        DB::table('prescription_orders')->where('id', $order->id)
            ->update([
                'status'          => 'dispatched',
                'tracking_number' => $request->tracking_number,
                'updated_at'      => now(),
            ]);
        return back()->with('success', 'Order dispatched. Tracking: ' . $request->tracking_number);
    }

    public function deliver(PharmacyOrder $order)
    {
        $this->authorizeOrder($order);
        $updateData = ['status' => 'delivered', 'updated_at' => now()];
        if ($order->payment_method === 'cash_on_delivery') {
            $updateData['payment_status'] = 'paid';
        }
        DB::table('prescription_orders')->where('id', $order->id)->update($updateData);
        return back()->with('success', 'Order marked as delivered.');
    }

    public function cancel(Request $request, PharmacyOrder $order)
    {
        $this->authorizeOrder($order);

        if (in_array($order->status, ['delivered', 'cancelled'])) {
            return back()->with('error', 'This order cannot be cancelled.');
        }

        $request->validate(['cancelled_reason' => 'required|string|max:500']);

        DB::beginTransaction();
        try {
            DB::table('prescription_orders')->where('id', $order->id)->update([
                'status'           => 'cancelled',
                'cancelled_reason' => $request->cancelled_reason,
                'updated_at'       => now(),
            ]);

            // Restore stock
            $items = DB::table('prescription_order_items')
                ->where('order_id', $order->id)
                ->get();

            foreach ($items as $item) {
                if ($item->medication_id) {
                    $med = DB::table('medications')->find($item->medication_id);
                    if ($med) {
                        $newQty    = $med->stock_quantity + $item->quantity;
                        $newStatus = $newQty <= 0 ? 'out_of_stock' : ($newQty <= 10 ? 'low_stock' : 'in_stock');
                        DB::table('medications')->where('id', $item->medication_id)->update([
                            'stock_quantity' => $newQty,
                            'stock_status'   => $newStatus,
                            'updated_at'     => now(),
                        ]);
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Cancellation failed: ' . $e->getMessage());
        }

        return back()->with('success', 'Order cancelled successfully.');
    }

    /* ─────────────────────────────────────────
     |  DOWNLOAD PRESCRIPTION
     ─────────────────────────────────────────*/
    public function downloadPrescription(PharmacyOrder $order)
    {
        $this->authorizeOrder($order);

        if (!$order->prescription_file) {
            return back()->with('error', 'No prescription file available.');
        }

        return Storage::disk('public')->download($order->prescription_file);
    }

    /* ─────────────────────────────────────────
     |  PRINT INVOICE
     ─────────────────────────────────────────*/
    public function printInvoice(PharmacyOrder $order)
    {
        $this->authorizeOrder($order);

        $items = DB::table('prescription_order_items as oi')
            ->leftJoin('medications as m', 'm.id', '=', 'oi.medication_id')
            ->where('oi.order_id', $order->id)
            ->select('oi.*', 'm.dosage', 'm.category')
            ->get();

        $patient = DB::table('patients as pt')
            ->join('users as u', 'u.id', '=', 'pt.user_id')
            ->where('pt.id', $order->patient_id)
            ->select('pt.*', 'u.email')
            ->first();

        $pharmacy = $this->pharmacy();

        return view('pharmacy.orders.invoice', compact('order', 'items', 'patient', 'pharmacy'));
    }

    /* ─────────────────────────────────────────
     |  AJAX: Get medicine price
     ─────────────────────────────────────────*/
    public function getMedicinePrice(Request $request)
    {
        $med = DB::table('medications')
            ->where('id', $request->id)
            ->where('pharmacy_id', $this->pharmacy()->id)
            ->select('id', 'name', 'price', 'stock_quantity', 'dosage', 'requires_prescription')
            ->first();

        if (!$med) {
            return response()->json(['success' => false], 404);
        }

        return response()->json(['success' => true, 'medicine' => $med]);
    }
}
