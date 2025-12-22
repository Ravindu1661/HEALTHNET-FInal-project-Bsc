<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\PharmacyOrder;
use App\Models\PrescriptionOrderItem;
use App\Models\Medicine;
use App\Models\Patient;

class PharmacyOrderController extends Controller
{
    /**
     * Display orders list
     */
    public function index(Request $request)
    {
        $pharmacy = Auth::user()->pharmacy;

        $query = PharmacyOrder::where('pharmacy_id', $pharmacy->id)
            ->with(['patient.user', 'items']);

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('patient', function($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->latest()->paginate(20);

        return view('pharmacy.orders.index', compact('orders'));
    }

    /**
     * Show order details
     */
    public function show(PharmacyOrder $order)
    {
        // Check authorization
        if ($order->pharmacy_id !== Auth::user()->pharmacy->id) {
            abort(403, 'Unauthorized action.');
        }

        $order->load(['patient.user', 'items.medication', 'payment']);

        return view('pharmacy.orders.show', compact('order'));
    }

    /**
     * Show create order form
     */
    public function create()
    {
        $patients = Patient::with('user')->get();
        $medicines = Medicine::where('pharmacy_id', Auth::user()->pharmacy->id)
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->get();

        return view('pharmacy.orders.create', compact('patients', 'medicines'));
    }

    /**
     * Store new order
     */
    public function store(Request $request)
    {
        $pharmacy = Auth::user()->pharmacy;

        $validatedData = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'prescription_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'delivery_address' => 'required|string',
            'delivery_method' => 'nullable|in:uber,pickme,own_delivery',
            'delivery_fee' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash_on_delivery,online',
            'items' => 'required|array|min:1',
            'items.*.medication_id' => 'required|exists:medications,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        // Handle prescription file upload
        if ($request->hasFile('prescription_file')) {
            $validatedData['prescription_file'] = $request->file('prescription_file')
                ->store('prescriptions', 'public');
        }

        // Calculate total amount
        $totalAmount = 0;
        foreach ($request->items as $item) {
            $totalAmount += $item['quantity'] * $item['price'];
        }

        // Create order
        $order = PharmacyOrder::create([
            'patient_id' => $validatedData['patient_id'],
            'pharmacy_id' => $pharmacy->id,
            'prescription_file' => $validatedData['prescription_file'] ?? null,
            'delivery_address' => $validatedData['delivery_address'],
            'delivery_method' => $validatedData['delivery_method'] ?? null,
            'delivery_fee' => $validatedData['delivery_fee'] ?? 0,
            'payment_method' => $validatedData['payment_method'],
            'total_amount' => $totalAmount,
            'status' => 'pending',
        ]);

        // Create order items
        foreach ($request->items as $item) {
            $medicine = Medicine::find($item['medication_id']);

            PrescriptionOrderItem::create([
                'order_id' => $order->id,
                'medication_id' => $item['medication_id'],
                'medication_name' => $medicine->name,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['quantity'] * $item['price'],
            ]);

            // Decrease stock
            $medicine->decreaseStock($item['quantity']);
        }

        return redirect()->route('pharmacy.orders.show', $order->id)
            ->with('success', 'Order created successfully.');
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, PharmacyOrder $order)
    {
        // Check authorization
        if ($order->pharmacy_id !== Auth::user()->pharmacy->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:pending,verified,processing,ready,dispatched,delivered,cancelled',
            'tracking_number' => 'nullable|string',
            'pharmacist_notes' => 'nullable|string',
        ]);

        $order->update([
            'status' => $request->status,
            'tracking_number' => $request->tracking_number,
            'pharmacist_notes' => $request->pharmacist_notes,
        ]);

        // If delivered, mark payment as paid for COD
        if ($request->status === 'delivered' && $order->payment_method === 'cash_on_delivery') {
            $order->update(['payment_status' => 'paid']);
        }

        return back()->with('success', 'Order status updated successfully.');
    }

    /**
     * Verify prescription
     */
    public function verify(PharmacyOrder $order)
    {
        // Check authorization
        if ($order->pharmacy_id !== Auth::user()->pharmacy->id) {
            abort(403, 'Unauthorized action.');
        }

        $order->markAsVerified();

        return back()->with('success', 'Prescription verified successfully.');
    }

    /**
     * Mark as processing
     */
    public function process(PharmacyOrder $order)
    {
        // Check authorization
        if ($order->pharmacy_id !== Auth::user()->pharmacy->id) {
            abort(403, 'Unauthorized action.');
        }

        $order->markAsProcessing();

        return back()->with('success', 'Order marked as processing.');
    }

    /**
     * Mark as ready
     */
    public function ready(PharmacyOrder $order)
    {
        // Check authorization
        if ($order->pharmacy_id !== Auth::user()->pharmacy->id) {
            abort(403, 'Unauthorized action.');
        }

        $order->markAsReady();

        return back()->with('success', 'Order is ready for delivery.');
    }

    /**
     * Mark as dispatched - RENAMED METHOD ✅
     */
    public function markDispatch(Request $request, PharmacyOrder $order)
    {
        // Check authorization
        if ($order->pharmacy_id !== Auth::user()->pharmacy->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'tracking_number' => 'required|string',
        ]);

        $order->markAsDispatched($request->tracking_number);

        return back()->with('success', 'Order dispatched successfully.');
    }

    /**
     * Mark as delivered
     */
    public function deliver(PharmacyOrder $order)
    {
        // Check authorization
        if ($order->pharmacy_id !== Auth::user()->pharmacy->id) {
            abort(403, 'Unauthorized action.');
        }

        $order->markAsDelivered();

        return back()->with('success', 'Order delivered successfully.');
    }

    /**
     * Cancel order
     */
    public function cancel(Request $request, PharmacyOrder $order)
    {
        // Check authorization
        if ($order->pharmacy_id !== Auth::user()->pharmacy->id) {
            abort(403, 'Unauthorized action.');
        }

        if (!$order->canBeCancelled()) {
            return back()->with('error', 'This order cannot be cancelled.');
        }

        $request->validate([
            'cancelled_reason' => 'required|string',
        ]);

        $order->cancel($request->cancelled_reason);

        // Restore stock
        foreach ($order->items as $item) {
            if ($item->medication) {
                $item->medication->increaseStock($item->quantity);
            }
        }

        return back()->with('success', 'Order cancelled successfully.');
    }

    /**
     * Download prescription
     */
    public function downloadPrescription(PharmacyOrder $order)
    {
        // Check authorization
        if ($order->pharmacy_id !== Auth::user()->pharmacy->id) {
            abort(403, 'Unauthorized action.');
        }

        if (!$order->prescription_file) {
            return back()->with('error', 'No prescription file available.');
        }

        return Storage::disk('public')->download($order->prescription_file);
    }

    /**
     * Print invoice
     */
    public function printInvoice(PharmacyOrder $order)
    {
        // Check authorization
        if ($order->pharmacy_id !== Auth::user()->pharmacy->id) {
            abort(403, 'Unauthorized action.');
        }

        $order->load(['patient.user', 'items.medication', 'pharmacy']);

        return view('pharmacy.orders.invoice', compact('order'));
    }
}
