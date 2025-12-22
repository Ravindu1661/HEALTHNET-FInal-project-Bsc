<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Medicine;

class PharmacyMedicineController extends Controller
{
    /**
     * Display medicines list
     */
    public function index(Request $request)
    {
        $pharmacy = Auth::user()->pharmacy;

        $query = Medicine::where('pharmacy_id', $pharmacy->id);

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('generic_name', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        // Filter by stock status
        if ($request->has('stock_status') && $request->stock_status != '') {
            $query->where('stock_status', $request->stock_status);
        }

        // Filter by active status
        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $medicines = $query->latest()->paginate(20);

        // Get all categories
        $categories = Medicine::where('pharmacy_id', $pharmacy->id)
            ->distinct()
            ->pluck('category');

        return view('pharmacy.medicines.index', compact('medicines', 'categories'));
    }

    /**
     * Show create medicine form
     */
    public function create()
    {
        return view('pharmacy.medicines.create');
    }

    /**
     * Store new medicine
     */
    public function store(Request $request)
    {
        $pharmacy = Auth::user()->pharmacy;

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'category' => 'required|string|max:100',
            'manufacturer' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'dosage' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'requires_prescription' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Determine stock status
        $stockQuantity = $validatedData['stock_quantity'];
        if ($stockQuantity <= 0) {
            $stockStatus = 'out_of_stock';
        } elseif ($stockQuantity <= 10) {
            $stockStatus = 'low_stock';
        } else {
            $stockStatus = 'in_stock';
        }

        $validatedData['pharmacy_id'] = $pharmacy->id;
        $validatedData['stock_status'] = $stockStatus;

        Medicine::create($validatedData);

        return redirect()->route('pharmacy.medicines.index')
            ->with('success', 'Medicine added successfully.');
    }

    /**
     * Show medicine details
     */
    public function show(Medicine $medicine)
    {
        // Check authorization
        if ($medicine->pharmacy_id !== Auth::user()->pharmacy->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('pharmacy.medicines.show', compact('medicine'));
    }

    /**
     * Show edit medicine form
     */
    public function edit(Medicine $medicine)
    {
        // Check authorization
        if ($medicine->pharmacy_id !== Auth::user()->pharmacy->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('pharmacy.medicines.edit', compact('medicine'));
    }

    /**
     * Update medicine
     */
    public function update(Request $request, Medicine $medicine)
    {
        // Check authorization
        if ($medicine->pharmacy_id !== Auth::user()->pharmacy->id) {
            abort(403, 'Unauthorized action.');
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'category' => 'required|string|max:100',
            'manufacturer' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'dosage' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'requires_prescription' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Determine stock status
        $stockQuantity = $validatedData['stock_quantity'];
        if ($stockQuantity <= 0) {
            $stockStatus = 'out_of_stock';
        } elseif ($stockQuantity <= 10) {
            $stockStatus = 'low_stock';
        } else {
            $stockStatus = 'in_stock';
        }

        $validatedData['stock_status'] = $stockStatus;

        $medicine->update($validatedData);

        return back()->with('success', 'Medicine updated successfully.');
    }

    /**
     * Delete medicine
     */
    public function destroy(Medicine $medicine)
    {
        // Check authorization
        if ($medicine->pharmacy_id !== Auth::user()->pharmacy->id) {
            abort(403, 'Unauthorized action.');
        }

        $medicine->delete();

        return redirect()->route('pharmacy.medicines.index')
            ->with('success', 'Medicine deleted successfully.');
    }

    /**
     * Update stock quantity
     */
    public function updateStock(Request $request, Medicine $medicine)
    {
        // Check authorization
        if ($medicine->pharmacy_id !== Auth::user()->pharmacy->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'stock_quantity' => 'required|integer|min:0',
        ]);

        $medicine->updateStock($request->stock_quantity - $medicine->stock_quantity);

        return back()->with('success', 'Stock updated successfully.');
    }

    /**
     * Toggle medicine active status
     */
    public function toggleStatus(Medicine $medicine)
    {
        // Check authorization
        if ($medicine->pharmacy_id !== Auth::user()->pharmacy->id) {
            abort(403, 'Unauthorized action.');
        }

        $medicine->update(['is_active' => !$medicine->is_active]);

        $status = $medicine->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Medicine {$status} successfully.");
    }
}
