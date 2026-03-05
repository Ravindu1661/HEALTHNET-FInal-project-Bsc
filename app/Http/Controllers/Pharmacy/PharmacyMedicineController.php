<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Medicine;

class PharmacyMedicineController extends Controller
{
    private function getPharmacy()
    {
        return Auth::user()->pharmacy;
    }

    private function resolveStockStatus(int $qty): string
    {
        if ($qty <= 0)  return 'out_of_stock';
        if ($qty <= 10) return 'low_stock';
        return 'in_stock';
    }

    private function authorise(Medicine $medicine): void
    {
        if ($medicine->pharmacy_id !== $this->getPharmacy()->id) {
            abort(403, 'Unauthorized action.');
        }
    }

    // ────────────────────────────────────────────
    // INDEX
    // ────────────────────────────────────────────
    public function index(Request $request)
    {
        $pharmacy = $this->getPharmacy();

        $query = Medicine::where('pharmacy_id', $pharmacy->id);

        // Search — name, generic_name, category, manufacturer
        if ($request->filled('search')) {
            $s = '%' . $request->search . '%';
            $query->where(function ($q) use ($s) {
                $q->where('name',         'like', $s)
                  ->orWhere('generic_name',  'like', $s)
                  ->orWhere('category',      'like', $s)
                  ->orWhere('manufacturer',  'like', $s);
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by stock_status (DB enum: in_stock | low_stock | out_of_stock)
        if ($request->filled('stock_status')) {
            $query->where('stock_status', $request->stock_status);
        }

        // Filter by is_active
        if ($request->has('is_active') && $request->is_active !== '') {
            $query->where('is_active', (bool) $request->is_active);
        }

        // Filter by requires_prescription
        if ($request->has('requires_prescription') && $request->requires_prescription !== '') {
            $query->where('requires_prescription', (bool) $request->requires_prescription);
        }

        $medicines = $query->latest()->paginate(20)->withQueryString();

        // Categories for filter dropdown
        $categories = Medicine::where('pharmacy_id', $pharmacy->id)
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        // Summary stats
        $stats = [
            'total'        => Medicine::where('pharmacy_id', $pharmacy->id)->count(),
            'active'       => Medicine::where('pharmacy_id', $pharmacy->id)->where('is_active', true)->count(),
            'in_stock'     => Medicine::where('pharmacy_id', $pharmacy->id)->where('stock_status', 'in_stock')->count(),
            'low_stock'    => Medicine::where('pharmacy_id', $pharmacy->id)->where('stock_status', 'low_stock')->count(),
            'out_of_stock' => Medicine::where('pharmacy_id', $pharmacy->id)->where('stock_status', 'out_of_stock')->count(),
        ];

        return view('pharmacy.medicines.index', compact('medicines', 'categories', 'stats'));
    }

    // ────────────────────────────────────────────
    // CREATE
    // ────────────────────────────────────────────
    public function create()
    {
        $pharmacy = $this->getPharmacy();

        $categories = Medicine::where('pharmacy_id', $pharmacy->id)
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('pharmacy.medicines.create', compact('categories'));
    }

    // ────────────────────────────────────────────
    // STORE
    // ────────────────────────────────────────────
    public function store(Request $request)
    {
        $pharmacy = $this->getPharmacy();

        $validated = $request->validate([
            'name'                 => 'required|string|max:255',
            'generic_name'         => 'nullable|string|max:255',
            'category'             => 'nullable|string|max:100',
            'manufacturer'         => 'nullable|string|max:255',
            'description'          => 'nullable|string',
            'dosage'               => 'nullable|string|max:100',
            'price'                => 'required|numeric|min:0',
            'stock_quantity'       => 'required|integer|min:0',
            'requires_prescription'=> 'boolean',
            'is_active'            => 'boolean',
        ]);

        $qty = (int) $validated['stock_quantity'];

        Medicine::create([
            'pharmacy_id'           => $pharmacy->id,
            'name'                  => $validated['name'],
            'generic_name'          => $validated['generic_name']   ?? null,
            'category'              => $validated['category']        ?? null,
            'manufacturer'          => $validated['manufacturer']    ?? null,
            'description'           => $validated['description']     ?? null,
            'dosage'                => $validated['dosage']          ?? null,
            'price'                 => $validated['price'],
            'stock_quantity'        => $qty,
            'stock_status'          => $this->resolveStockStatus($qty),
            'requires_prescription' => $request->boolean('requires_prescription', true),
            'is_active'             => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('pharmacy.medicines.index')
            ->with('success', 'Medicine added successfully.');
    }

    // ────────────────────────────────────────────
    // SHOW
    // ────────────────────────────────────────────
    public function show(Medicine $medicine)
    {
        $this->authorise($medicine);
        return view('pharmacy.medicines.show', compact('medicine'));
    }

    // ────────────────────────────────────────────
    // EDIT
    // ────────────────────────────────────────────
    public function edit(Medicine $medicine)
    {
        $this->authorise($medicine);

        $pharmacy   = $this->getPharmacy();
        $categories = Medicine::where('pharmacy_id', $pharmacy->id)
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('pharmacy.medicines.edit', compact('medicine', 'categories'));
    }

    // ────────────────────────────────────────────
    // UPDATE
    // ────────────────────────────────────────────
    public function update(Request $request, Medicine $medicine)
    {
        $this->authorise($medicine);

        $validated = $request->validate([
            'name'                 => 'required|string|max:255',
            'generic_name'         => 'nullable|string|max:255',
            'category'             => 'nullable|string|max:100',
            'manufacturer'         => 'nullable|string|max:255',
            'description'          => 'nullable|string',
            'dosage'               => 'nullable|string|max:100',
            'price'                => 'required|numeric|min:0',
            'stock_quantity'       => 'required|integer|min:0',
            'requires_prescription'=> 'boolean',
            'is_active'            => 'boolean',
        ]);

        $qty = (int) $validated['stock_quantity'];

        $medicine->update([
            'name'                  => $validated['name'],
            'generic_name'          => $validated['generic_name']   ?? null,
            'category'              => $validated['category']        ?? null,
            'manufacturer'          => $validated['manufacturer']    ?? null,
            'description'           => $validated['description']     ?? null,
            'dosage'                => $validated['dosage']          ?? null,
            'price'                 => $validated['price'],
            'stock_quantity'        => $qty,
            'stock_status'          => $this->resolveStockStatus($qty),
            'requires_prescription' => $request->boolean('requires_prescription', true),
            'is_active'             => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Medicine updated successfully.');
    }

    // ────────────────────────────────────────────
    // DESTROY
    // ────────────────────────────────────────────
    public function destroy(Medicine $medicine)
    {
        $this->authorise($medicine);

        // Safety — skip if has order items
        if ($medicine->prescriptionOrderItems()->exists()) {
            return back()->with('error', 'Cannot delete medicine with existing orders.');
        }

        $medicine->delete();

        return redirect()
            ->route('pharmacy.medicines.index')
            ->with('success', 'Medicine deleted successfully.');
    }

    // ────────────────────────────────────────────
    // UPDATE STOCK — Quick update
    // Uses Medicine model updateStock() — adds/sets quantity
    // ────────────────────────────────────────────
    public function updateStock(Request $request, Medicine $medicine)
    {
        $this->authorise($medicine);

        $request->validate([
            'stock_quantity' => 'required|integer|min:0',
        ]);

        // Directly set new quantity (not add — override)
        $qty = (int) $request->stock_quantity;
        $medicine->update([
            'stock_quantity' => $qty,
            'stock_status'   => $this->resolveStockStatus($qty),
        ]);

        return back()->with('success', 'Stock updated successfully.');
    }

    // ────────────────────────────────────────────
    // TOGGLE STATUS
    // ────────────────────────────────────────────
    public function toggleStatus(Medicine $medicine)
    {
        $this->authorise($medicine);

        $medicine->update(['is_active' => ! $medicine->is_active]);

        $status = $medicine->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Medicine {$status} successfully.");
    }

    // ────────────────────────────────────────────
    // BULK ACTION
    // ────────────────────────────────────────────
    public function bulkAction(Request $request)
    {
        $pharmacy = $this->getPharmacy();

        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'ids'    => 'required|array|min:1',
            'ids.*'  => 'integer',
        ]);

        $medicines = Medicine::where('pharmacy_id', $pharmacy->id)
            ->whereIn('id', $request->ids)
            ->get();

        switch ($request->action) {
            case 'activate':
                $medicines->each->update(['is_active' => true]);
                $msg = $medicines->count() . ' medicines activated.';
                break;

            case 'deactivate':
                $medicines->each->update(['is_active' => false]);
                $msg = $medicines->count() . ' medicines deactivated.';
                break;

            case 'delete':
                $deleted = 0;
                foreach ($medicines as $m) {
                    if (! $m->prescriptionOrderItems()->exists()) {
                        $m->delete();
                        $deleted++;
                    }
                }
                $msg = "{$deleted} medicines deleted (skipped those with orders).";
                break;

            default:
                return back()->with('error', 'Invalid action.');
        }

        return back()->with('success', $msg);
    }
}
