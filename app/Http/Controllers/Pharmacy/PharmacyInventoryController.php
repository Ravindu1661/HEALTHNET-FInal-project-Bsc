<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Medicine;

class PharmacyInventoryController extends Controller
{
    /* ─────────────────────────────────────────
     |  HELPER
     ─────────────────────────────────────────*/
    private function pharmacy()
    {
        return Auth::user()->pharmacy;
    }

    /* ─────────────────────────────────────────
     |  INDEX  – Inventory Dashboard
     ─────────────────────────────────────────*/
    public function index(Request $request)
    {
        $pharmacy = $this->pharmacy();

        // ── Summary Counts ──
        $totalMedicines = Medicine::where('pharmacy_id', $pharmacy->id)->count();

        $inStock    = Medicine::where('pharmacy_id', $pharmacy->id)
                        ->where('stock_status', 'in_stock')->count();
        $lowStock   = Medicine::where('pharmacy_id', $pharmacy->id)
                        ->where('stock_status', 'low_stock')->count();
        $outOfStock = Medicine::where('pharmacy_id', $pharmacy->id)
                        ->where('stock_status', 'out_of_stock')->count();

        $totalStockValue = Medicine::where('pharmacy_id', $pharmacy->id)
            ->selectRaw('COALESCE(SUM(stock_quantity * price), 0) as total')
            ->value('total');

        // ── Category Stats ──
        $categoryStats = Medicine::where('pharmacy_id', $pharmacy->id)
            ->select(
                'category',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(stock_quantity) as total_stock'),
                DB::raw('SUM(stock_quantity * price) as total_value')
            )
            ->whereNotNull('category')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        // ── Filters ──
        $query = Medicine::where('pharmacy_id', $pharmacy->id);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('generic_name', 'like', "%{$s}%")
                  ->orWhere('category', 'like', "%{$s}%")
                  ->orWhere('manufacturer', 'like', "%{$s}%");
            });
        }

        if ($request->filled('stock_status')) {
            $query->where('stock_status', $request->stock_status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('requires_prescription')) {
            $query->where('requires_prescription', $request->requires_prescription);
        }

        // Sort
        $sortBy  = $request->get('sort_by', 'created_at');
        $sortDir = $request->get('sort_dir', 'desc');
        $allowedSorts = ['name', 'stock_quantity', 'price', 'created_at', 'category'];
        if (!in_array($sortBy, $allowedSorts)) $sortBy = 'created_at';

        $medicines = $query->orderBy($sortBy, $sortDir)->paginate(20)->withQueryString();

        // Categories for filter dropdown
        $categories = Medicine::where('pharmacy_id', $pharmacy->id)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->sort()
            ->values();

        return view('pharmacy.inventory.index', compact(
            'totalMedicines', 'inStock', 'lowStock', 'outOfStock',
            'totalStockValue', 'categoryStats', 'medicines', 'categories'
        ));
    }

    /* ─────────────────────────────────────────
     |  LOW STOCK
     ─────────────────────────────────────────*/
    public function lowStock(Request $request)
    {
        $pharmacy = $this->pharmacy();

        $query = Medicine::where('pharmacy_id', $pharmacy->id)
            ->where('stock_status', 'low_stock');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('category', 'like', "%{$s}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $medicines = $query->orderBy('stock_quantity', 'asc')->paginate(20)->withQueryString();

        $categories = Medicine::where('pharmacy_id', $pharmacy->id)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->sort()
            ->values();

        $lowStockCount = Medicine::where('pharmacy_id', $pharmacy->id)
            ->where('stock_status', 'low_stock')->count();

        return view('pharmacy.inventory.low-stock', compact('medicines', 'categories', 'lowStockCount'));
    }

    /* ─────────────────────────────────────────
     |  OUT OF STOCK
     ─────────────────────────────────────────*/
    public function outOfStock(Request $request)
    {
        $pharmacy = $this->pharmacy();

        $query = Medicine::where('pharmacy_id', $pharmacy->id)
            ->where('stock_status', 'out_of_stock');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('category', 'like', "%{$s}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $medicines = $query->orderBy('name', 'asc')->paginate(20)->withQueryString();

        $categories = Medicine::where('pharmacy_id', $pharmacy->id)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->sort()
            ->values();

        $outOfStockCount = Medicine::where('pharmacy_id', $pharmacy->id)
            ->where('stock_status', 'out_of_stock')->count();

        return view('pharmacy.inventory.out-of-stock', compact('medicines', 'categories', 'outOfStockCount'));
    }

    /* ─────────────────────────────────────────
     |  STOCK HISTORY
     ─────────────────────────────────────────*/
    public function stockHistory(Medicine $medicine)
    {
        // Authorization — only this pharmacy's medicines
        if ($medicine->pharmacy_id !== $this->pharmacy()->id) {
            abort(403, 'Unauthorized action.');
        }

        // Prescription order items history (stock consumed)
        $orderHistory = DB::table('prescription_order_items as oi')
            ->join('prescription_orders as po', 'po.id', '=', 'oi.order_id')
            ->join('patients as pt', 'pt.id', '=', 'po.patient_id')
            ->where('oi.medication_id', $medicine->id)
            ->select(
                'po.order_number',
                'po.status as order_status',
                'po.created_at as order_date',
                DB::raw("CONCAT(pt.first_name,' ',pt.last_name) as patient_name"),
                'oi.quantity',
                'oi.price',
                'oi.subtotal',
                DB::raw("'dispensed' as movement_type")
            )
            ->orderByDesc('po.created_at')
            ->limit(50)
            ->get();

        // Stock totals per status
        $dispensedTotal  = DB::table('prescription_order_items as oi')
            ->join('prescription_orders as po', 'po.id', '=', 'oi.order_id')
            ->where('oi.medication_id', $medicine->id)
            ->whereNotIn('po.status', ['cancelled'])
            ->sum('oi.quantity');

        $cancelledRestored = DB::table('prescription_order_items as oi')
            ->join('prescription_orders as po', 'po.id', '=', 'oi.order_id')
            ->where('oi.medication_id', $medicine->id)
            ->where('po.status', 'cancelled')
            ->sum('oi.quantity');

        return view('pharmacy.inventory.stock-history', compact(
            'medicine', 'orderHistory', 'dispensedTotal', 'cancelledRestored'
        ));
    }
}
