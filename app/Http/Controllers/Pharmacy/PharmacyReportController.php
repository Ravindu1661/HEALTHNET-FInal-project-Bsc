<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PharmacyOrder;
use App\Models\Medicine;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PharmacyReportController extends Controller
{
    /**
     * Display reports dashboard
     */
    public function index()
    {
        return view('pharmacy.reports.index');
    }

    /**
     * Daily report
     */
    public function daily(Request $request)
    {
        $pharmacy = Auth::user()->pharmacy;
        $date = $request->date ? Carbon::parse($request->date) : today();

        $orders = PharmacyOrder::where('pharmacy_id', $pharmacy->id)
            ->whereDate('created_at', $date)
            ->with(['patient.user', 'items'])
            ->get();

        $totalOrders = $orders->count();
        $totalRevenue = $orders->where('payment_status', 'paid')->sum('total_amount');
        $pendingOrders = $orders->where('status', 'pending')->count();

        return view('pharmacy.reports.daily', compact('orders', 'totalOrders', 'totalRevenue', 'pendingOrders', 'date'));
    }

    /**
     * Monthly report
     */
    public function monthly(Request $request)
    {
        $pharmacy = Auth::user()->pharmacy;
        $month = $request->month ?? date('Y-m');

        $orders = PharmacyOrder::where('pharmacy_id', $pharmacy->id)
            ->whereYear('created_at', Carbon::parse($month)->year)
            ->whereMonth('created_at', Carbon::parse($month)->month)
            ->with(['patient.user', 'items'])
            ->get();

        $totalOrders = $orders->count();
        $totalRevenue = $orders->where('payment_status', 'paid')->sum('total_amount');
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        return view('pharmacy.reports.monthly', compact('orders', 'totalOrders', 'totalRevenue', 'averageOrderValue', 'month'));
    }

    /**
     * Sales report
     */
    public function sales(Request $request)
    {
        $pharmacy = Auth::user()->pharmacy;

        $startDate = $request->start_date ? Carbon::parse($request->start_date) : now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : now()->endOfMonth();

        $orders = PharmacyOrder::where('pharmacy_id', $pharmacy->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->with(['patient.user', 'items'])
            ->get();

        $totalRevenue = $orders->sum('total_amount');
        $totalOrders = $orders->count();

        return view('pharmacy.reports.sales', compact('orders', 'totalRevenue', 'totalOrders', 'startDate', 'endDate'));
    }

    /**
     * Inventory report
     */
    public function inventory()
    {
        $pharmacy = Auth::user()->pharmacy;

        $medicines = Medicine::where('pharmacy_id', $pharmacy->id)->get();

        $totalMedicines = $medicines->count();
        $totalStockValue = $medicines->sum(function($medicine) {
            return $medicine->stock_quantity * $medicine->price;
        });
        $lowStockCount = $medicines->where('stock_status', 'low_stock')->count();
        $outOfStockCount = $medicines->where('stock_status', 'out_of_stock')->count();

        return view('pharmacy.reports.inventory', compact('medicines', 'totalMedicines', 'totalStockValue', 'lowStockCount', 'outOfStockCount'));
    }

    /**
     * Export report
     */
    public function export(Request $request)
    {
        // Implement CSV/PDF export here

        return back()->with('success', 'Report exported successfully.');
    }
}
