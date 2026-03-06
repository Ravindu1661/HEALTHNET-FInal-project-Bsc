<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PharmacyMedicineController extends Controller
{
    // ═══════════════════════════════════════════════════════════
    // INDEX
    // ═══════════════════════════════════════════════════════════
    public function index(Request $request)
    {
        $pharmacy = Auth::user()->pharmacy;

        $query = Medicine::where('pharmacy_id', $pharmacy->id);

        if ($request->filled('search')) {
            $s = '%' . $request->search . '%';
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', $s)
                  ->orWhere('generic_name', 'like', $s)
                  ->orWhere('category', 'like', $s)
                  ->orWhere('manufacturer', 'like', $s);
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('stock_status')) {
            $query->where('stock_status', $request->stock_status);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        if ($request->filled('requires_prescription')) {
            $query->where('requires_prescription', $request->requires_prescription);
        }

        $medicines = $query->latest()->paginate(20)->withQueryString();

        // Stats
        $stats = [
            'total'       => Medicine::where('pharmacy_id', $pharmacy->id)->count(),
            'in_stock'    => Medicine::where('pharmacy_id', $pharmacy->id)->where('stock_status', 'in_stock')->count(),
            'low_stock'   => Medicine::where('pharmacy_id', $pharmacy->id)->where('stock_status', 'low_stock')->count(),
            'out_of_stock'=> Medicine::where('pharmacy_id', $pharmacy->id)->where('stock_status', 'out_of_stock')->count(),
            'inactive'    => Medicine::where('pharmacy_id', $pharmacy->id)->where('is_active', false)->count(),
        ];

        // Categories for filter
        $categories = Medicine::where('pharmacy_id', $pharmacy->id)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->sort()
            ->values();

        return view('pharmacy.medicines.index', compact('medicines', 'stats', 'categories'));
    }

    // ═══════════════════════════════════════════════════════════
    // SHOW
    // ═══════════════════════════════════════════════════════════
    public function show(Medicine $medicine)
    {
        $this->authMedicine($medicine);
        return view('pharmacy.medicines.show', compact('medicine'));
    }

    // ═══════════════════════════════════════════════════════════
    // CREATE
    // ═══════════════════════════════════════════════════════════
    public function create()
    {
        $pharmacy   = Auth::user()->pharmacy;
        $categories = Medicine::where('pharmacy_id', $pharmacy->id)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->sort()
            ->values();

        return view('pharmacy.medicines.create', compact('categories'));
    }

    // ═══════════════════════════════════════════════════════════
    // STORE
    // ═══════════════════════════════════════════════════════════
    public function store(Request $request)
    {
        $pharmacy = Auth::user()->pharmacy;

        $data = $request->validate([
            'name'                  => 'required|string|max:255',
            'generic_name'          => 'nullable|string|max:255',
            'category'              => 'required|string|max:100',
            'manufacturer'          => 'nullable|string|max:255',
            'description'           => 'nullable|string',
            'dosage'                => 'nullable|string|max:100',
            'price'                 => 'required|numeric|min:0',
            'stock_quantity'        => 'required|integer|min:0',
            'requires_prescription' => 'boolean',
            'is_active'             => 'boolean',
        ]);

        $data['pharmacy_id']   = $pharmacy->id;
        $data['stock_status']  = $this->resolveStockStatus($data['stock_quantity']);
        $data['requires_prescription'] = $request->boolean('requires_prescription');
        $data['is_active']     = $request->boolean('is_active', true);

        Medicine::create($data);

        return redirect()->route('pharmacy.medicines.index')
            ->withSuccess('Medicine "' . $data['name'] . '" added successfully.');
    }

    // ═══════════════════════════════════════════════════════════
    // EDIT
    // ═══════════════════════════════════════════════════════════
    public function edit(Medicine $medicine)
    {
        $this->authMedicine($medicine);

        $pharmacy   = Auth::user()->pharmacy;
        $categories = Medicine::where('pharmacy_id', $pharmacy->id)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->sort()
            ->values();

        return view('pharmacy.medicines.edit', compact('medicine', 'categories'));
    }

    // ═══════════════════════════════════════════════════════════
    // UPDATE
    // ═══════════════════════════════════════════════════════════
    public function update(Request $request, Medicine $medicine)
    {
        $this->authMedicine($medicine);

        $data = $request->validate([
            'name'                  => 'required|string|max:255',
            'generic_name'          => 'nullable|string|max:255',
            'category'              => 'required|string|max:100',
            'manufacturer'          => 'nullable|string|max:255',
            'description'           => 'nullable|string',
            'dosage'                => 'nullable|string|max:100',
            'price'                 => 'required|numeric|min:0',
            'stock_quantity'        => 'required|integer|min:0',
            'requires_prescription' => 'boolean',
            'is_active'             => 'boolean',
        ]);

        $data['stock_status']          = $this->resolveStockStatus($data['stock_quantity']);
        $data['requires_prescription'] = $request->boolean('requires_prescription');
        $data['is_active']             = $request->boolean('is_active');

        $medicine->update($data);

        return redirect()->route('pharmacy.medicines.show', $medicine->id)
            ->withSuccess('Medicine "' . $medicine->name . '" updated successfully.');
    }

    // ═══════════════════════════════════════════════════════════
    // DESTROY
    // ═══════════════════════════════════════════════════════════
    public function destroy(Medicine $medicine)
    {
        $this->authMedicine($medicine);
        $name = $medicine->name;
        $medicine->delete();

        return redirect()->route('pharmacy.medicines.index')
            ->withSuccess('"' . $name . '" deleted successfully.');
    }

    // ═══════════════════════════════════════════════════════════
    // UPDATE STOCK
    // ═══════════════════════════════════════════════════════════
    public function updateStock(Request $request, Medicine $medicine)
    {
        $this->authMedicine($medicine);

        $request->validate([
            'action'   => 'required|in:add,set,subtract',
            'quantity' => 'required|integer|min:0',
        ]);

        $qty = (int) $request->quantity;

        $newQty = match($request->action) {
            'add'      => $medicine->stock_quantity + $qty,
            'subtract' => max(0, $medicine->stock_quantity - $qty),
            'set'      => $qty,
        };

        $medicine->update([
            'stock_quantity' => $newQty,
            'stock_status'   => $this->resolveStockStatus($newQty),
        ]);

        return back()->withSuccess(
            'Stock updated for "' . $medicine->name . '". New quantity: ' . $newQty
        );
    }

    // ═══════════════════════════════════════════════════════════
    // TOGGLE STATUS
    // ═══════════════════════════════════════════════════════════
    public function toggleStatus(Medicine $medicine)
    {
        $this->authMedicine($medicine);

        $medicine->update(['is_active' => !$medicine->is_active]);

        $status = $medicine->is_active ? 'activated' : 'deactivated';

        return back()->withSuccess('"' . $medicine->name . '" ' . $status . ' successfully.');
    }

    // ═══════════════════════════════════════════════════════════
    // PRIVATE — Stock Status Resolver
    // ═══════════════════════════════════════════════════════════
    private function resolveStockStatus(int $qty): string
    {
        if ($qty <= 0)  return 'out_of_stock';
        if ($qty <= 10) return 'low_stock';
        return 'in_stock';
    }

    // ═══════════════════════════════════════════════════════════
    // PRIVATE — Authorization
    // ═══════════════════════════════════════════════════════════
    private function authMedicine(Medicine $medicine): void
    {
        if ($medicine->pharmacy_id !== Auth::user()->pharmacy->id) {
            abort(403, 'Unauthorized access.');
        }
    }
}
