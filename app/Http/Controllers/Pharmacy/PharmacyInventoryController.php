<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Medicine;

class PharmacyInventoryController extends Controller
{
    /**
     * Display inventory dashboard
     */
    public function index()
    {
        $pharmacy = Auth::user()->pharmacy;

        $totalMedicines = Medicine::where('pharmacy_id', $pharmacy->id)->count();

        $inStock = Medicine::where('pharmacy_id', $pharmacy->id)
            ->where('stock_status', 'in_stock')
            ->count();

        $lowStock = Medicine::where('pharmacy_id', $pharmacy->id)
            ->where('stock_status', 'low_stock')
            ->count();

        $outOfStock = Medicine::where('pharmacy_id', $pharmacy->id)
            ->where('stock_status', 'out_of_stock')
            ->count();

        $totalStockValue = Medicine::where('pharmacy_id', $pharmacy->id)
            ->selectRaw('SUM(stock_quantity * price) as total')
            ->value('total') ?? 0;

        $medicines = Medicine::where('pharmacy_id', $pharmacy->id)
            ->latest()
            ->paginate(20);

        return view('pharmacy.inventory.index', compact(
            'totalMedicines',
            'inStock',
            'lowStock',
            'outOfStock',
            'totalStockValue',
            'medicines'
        ));
    }

    /**
     * Low stock medicines
     */
    public function lowStock()
    {
        $pharmacy = Auth::user()->pharmacy;

        $medicines = Medicine::where('pharmacy_id', $pharmacy->id)
            ->where('stock_status', 'low_stock')
            ->orderBy('stock_quantity', 'asc')
            ->paginate(20);

        return view('pharmacy.inventory.low-stock', compact('medicines'));
    }

    /**
     * Out of stock medicines
     */
    public function outOfStock()
    {
        $pharmacy = Auth::user()->pharmacy;

        $medicines = Medicine::where('pharmacy_id', $pharmacy->id)
            ->where('stock_status', 'out_of_stock')
            ->paginate(20);

        return view('pharmacy.inventory.out-of-stock', compact('medicines'));
    }

    /**
     * Stock history
     */
    public function stockHistory(Medicine $medicine)
    {
        // Check authorization
        if ($medicine->pharmacy_id !== Auth::user()->pharmacy->id) {
            abort(403, 'Unauthorized action.');
        }

        // You can implement stock history tracking here

        return view('pharmacy.inventory.stock-history', compact('medicine'));
    }
}
