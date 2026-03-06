<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\PharmacyOrder;
use App\Models\Medicine;
use Carbon\Carbon;

class PharmacyReportController extends Controller
{
    /* ─────────────────────────────────────────
     |  HELPER
     ─────────────────────────────────────────*/
    private function pharmacy()
    {
        return Auth::user()->pharmacy;
    }

    /* ─────────────────────────────────────────
     |  INDEX — Reports Dashboard
     ─────────────────────────────────────────*/
    public function index()
    {
        $pharmacy = $this->pharmacy();
        $pid      = $pharmacy->id;

        // ── All-time KPIs ──
        $totalOrders   = PharmacyOrder::where('pharmacy_id', $pid)->count();
        $totalRevenue  = PharmacyOrder::where('pharmacy_id', $pid)
            ->where('payment_status', 'paid')->sum('total_amount');
        $totalPatients = PharmacyOrder::where('pharmacy_id', $pid)
            ->distinct('patient_id')->count('patient_id');
        $totalMedicines = Medicine::where('pharmacy_id', $pid)->count();

        // ── This Month ──
        $thisMonthOrders = PharmacyOrder::where('pharmacy_id', $pid)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();
        $thisMonthRevenue = PharmacyOrder::where('pharmacy_id', $pid)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        // ── Last Month ──
        $lastMonthRevenue = PharmacyOrder::where('pharmacy_id', $pid)
            ->whereYear('created_at',  now()->subMonth()->year)
            ->whereMonth('created_at', now()->subMonth()->month)
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        // ── Monthly Revenue Chart (current year) ──
        $monthlySalesRaw = PharmacyOrder::where('pharmacy_id', $pid)
            ->where('payment_status', 'paid')
            ->whereYear('created_at', now()->year)
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month');

        $monthlySales = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlySales[$i] = $monthlySalesRaw->get($i, 0);
        }

        // ── Order Status Breakdown ──
        $ordersByStatus = PharmacyOrder::where('pharmacy_id', $pid)
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        // ── Payment Method Breakdown ──
        $ordersByPayment = PharmacyOrder::where('pharmacy_id', $pid)
            ->select('payment_method', DB::raw('COUNT(*) as count'))
            ->groupBy('payment_method')
            ->pluck('count', 'payment_method');

        // ── Top 5 Medicines (all time) ──
        $topMedicines = DB::table('prescription_order_items as oi')
            ->join('prescription_orders as po', 'po.id', '=', 'oi.order_id')
            ->where('po.pharmacy_id', $pid)
            ->whereNotIn('po.status', ['cancelled'])
            ->select(
                'oi.medication_name',
                DB::raw('SUM(oi.quantity) as total_qty'),
                DB::raw('SUM(oi.subtotal) as total_revenue'),
                DB::raw('COUNT(DISTINCT oi.order_id) as order_count')
            )
            ->groupBy('oi.medication_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // ── Today's Stats ──
        $todayOrders  = PharmacyOrder::where('pharmacy_id', $pid)
            ->whereDate('created_at', today())->count();
        $todayRevenue = PharmacyOrder::where('pharmacy_id', $pid)
            ->whereDate('created_at', today())
            ->where('payment_status', 'paid')->sum('total_amount');

        return view('pharmacy.reports.index', compact(
            'totalOrders', 'totalRevenue', 'totalPatients', 'totalMedicines',
            'thisMonthOrders', 'thisMonthRevenue', 'lastMonthRevenue',
            'monthlySales', 'ordersByStatus', 'ordersByPayment',
            'topMedicines', 'todayOrders', 'todayRevenue'
        ));
    }

    /* ─────────────────────────────────────────
     |  DAILY REPORT
     ─────────────────────────────────────────*/
    public function daily(Request $request)
    {
        $pharmacy = $this->pharmacy();
        $pid      = $pharmacy->id;

        $date = $request->date
            ? Carbon::parse($request->date)
            : Carbon::today();

        $orders = PharmacyOrder::where('pharmacy_id', $pid)
            ->whereDate('created_at', $date)
            ->with('patient.user', 'items')
            ->latest()
            ->get();

        $totalOrders   = $orders->count();
        $totalRevenue  = $orders->where('payment_status', 'paid')->sum('total_amount');
        $pendingOrders = $orders->where('status', 'pending')->count();
        $deliveredOrders = $orders->where('status', 'delivered')->count();
        $cancelledOrders = $orders->where('status', 'cancelled')->count();
        $totalItems    = $orders->sum(fn($o) => $o->items->count());

        // Hourly breakdown
        $hourlyOrders = $orders->groupBy(fn($o) => Carbon::parse($o->created_at)->format('H'))
            ->map(fn($group) => [
                'count'   => $group->count(),
                'revenue' => $group->where('payment_status', 'paid')->sum('total_amount'),
            ]);

        // Status counts
        $statusBreakdown = $orders->groupBy('status')
            ->map(fn($group) => $group->count());

        // Navigate prev/next
        $prevDate = $date->copy()->subDay()->format('Y-m-d');
        $nextDate = $date->copy()->addDay()->format('Y-m-d');

        return view('pharmacy.reports.daily', compact(
            'orders', 'date', 'totalOrders', 'totalRevenue',
            'pendingOrders', 'deliveredOrders', 'cancelledOrders',
            'totalItems', 'hourlyOrders', 'statusBreakdown',
            'prevDate', 'nextDate'
        ));
    }

    /* ─────────────────────────────────────────
     |  MONTHLY REPORT
     ─────────────────────────────────────────*/
    public function monthly(Request $request)
    {
        $pharmacy = $this->pharmacy();
        $pid      = $pharmacy->id;

        $month = $request->month ?? now()->format('Y-m');
        $dt    = Carbon::parse($month);

        $orders = PharmacyOrder::where('pharmacy_id', $pid)
            ->whereYear('created_at',  $dt->year)
            ->whereMonth('created_at', $dt->month)
            ->with('patient.user', 'items')
            ->latest()
            ->get();

        $totalOrders      = $orders->count();
        $totalRevenue     = $orders->where('payment_status', 'paid')->sum('total_amount');
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        $paidOrders       = $orders->where('payment_status', 'paid')->count();
        $unpaidOrders     = $orders->where('payment_status', 'unpaid')->count();
        $cancelledOrders  = $orders->where('status', 'cancelled')->count();
        $deliveredOrders  = $orders->where('status', 'delivered')->count();

        // Daily breakdown for the month
        $dailyData = $orders->groupBy(fn($o) => Carbon::parse($o->created_at)->format('Y-m-d'))
            ->map(fn($group) => [
                'count'   => $group->count(),
                'revenue' => $group->where('payment_status', 'paid')->sum('total_amount'),
            ]);

        // Fill all days of month
        $daysInMonth = $dt->daysInMonth;
        $dailyChart  = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $key = $dt->format('Y-m-') . str_pad($d, 2, '0', STR_PAD_LEFT);
            $dailyChart[$d] = $dailyData->get($key, ['count' => 0, 'revenue' => 0]);
        }

        // Status breakdown
        $statusBreakdown = $orders->groupBy('status')
            ->map(fn($group) => $group->count());

        // Top medicines this month
        $topMedicines = DB::table('prescription_order_items as oi')
            ->join('prescription_orders as po', 'po.id', '=', 'oi.order_id')
            ->where('po.pharmacy_id', $pid)
            ->whereYear('po.created_at',  $dt->year)
            ->whereMonth('po.created_at', $dt->month)
            ->whereNotIn('po.status', ['cancelled'])
            ->select(
                'oi.medication_name',
                DB::raw('SUM(oi.quantity) as total_qty'),
                DB::raw('SUM(oi.subtotal) as total_revenue')
            )
            ->groupBy('oi.medication_name')
            ->orderByDesc('total_qty')
            ->limit(8)
            ->get();

        // Compare with previous month
        $prevDt       = $dt->copy()->subMonth();
        $prevRevenue  = PharmacyOrder::where('pharmacy_id', $pid)
            ->whereYear('created_at',  $prevDt->year)
            ->whereMonth('created_at', $prevDt->month)
            ->where('payment_status', 'paid')
            ->sum('total_amount');
        $prevOrders   = PharmacyOrder::where('pharmacy_id', $pid)
            ->whereYear('created_at',  $prevDt->year)
            ->whereMonth('created_at', $prevDt->month)
            ->count();

        $prevMonth = $prevDt->format('Y-m');

        return view('pharmacy.reports.monthly', compact(
            'orders', 'month', 'dt', 'totalOrders', 'totalRevenue', 'averageOrderValue',
            'paidOrders', 'unpaidOrders', 'cancelledOrders', 'deliveredOrders',
            'dailyChart', 'statusBreakdown', 'topMedicines',
            'prevRevenue', 'prevOrders', 'prevMonth', 'daysInMonth'
        ));
    }

    /* ─────────────────────────────────────────
     |  SALES REPORT
     ─────────────────────────────────────────*/
    public function sales(Request $request)
    {
        $pharmacy = $this->pharmacy();
        $pid      = $pharmacy->id;

        $startDate = $request->start_date
            ? Carbon::parse($request->start_date)->startOfDay()
            : now()->startOfMonth();
        $endDate   = $request->end_date
            ? Carbon::parse($request->end_date)->endOfDay()
            : now()->endOfDay();

        $orders = PharmacyOrder::where('pharmacy_id', $pid)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with('patient.user', 'items')
            ->latest()
            ->get();

        $paidOrders     = $orders->where('payment_status', 'paid');
        $totalRevenue   = $paidOrders->sum('total_amount');
        $totalOrders    = $orders->count();
        $totalDeliveryFee = $orders->sum('delivery_fee');
        $avgOrderValue  = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Payment method breakdown
        $byPaymentMethod = $orders->groupBy('payment_method')
            ->map(fn($g) => [
                'count'   => $g->count(),
                'revenue' => $g->where('payment_status', 'paid')->sum('total_amount'),
            ]);

        // Delivery method breakdown
        $byDeliveryMethod = $orders->groupBy('delivery_method')
            ->map(fn($g) => $g->count());

        // Daily trend in the range
        $dailyTrend = $orders->groupBy(fn($o) => Carbon::parse($o->created_at)->format('Y-m-d'))
            ->map(fn($g) => [
                'count'   => $g->count(),
                'revenue' => $g->where('payment_status', 'paid')->sum('total_amount'),
            ]);

        // Top Selling Medicines in period
        $topMedicines = DB::table('prescription_order_items as oi')
            ->join('prescription_orders as po', 'po.id', '=', 'oi.order_id')
            ->where('po.pharmacy_id', $pid)
            ->whereBetween('po.created_at', [$startDate, $endDate])
            ->whereNotIn('po.status', ['cancelled'])
            ->select(
                'oi.medication_name',
                DB::raw('SUM(oi.quantity) as total_qty'),
                DB::raw('SUM(oi.subtotal) as total_revenue'),
                DB::raw('COUNT(DISTINCT oi.order_id) as order_count')
            )
            ->groupBy('oi.medication_name')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

        // Status summary
        $statusSummary = $orders->groupBy('status')
            ->map(fn($g) => ['count' => $g->count(), 'revenue' => $g->sum('total_amount')]);

        return view('pharmacy.reports.sales', compact(
            'orders', 'startDate', 'endDate', 'totalRevenue', 'totalOrders',
            'totalDeliveryFee', 'avgOrderValue', 'byPaymentMethod',
            'byDeliveryMethod', 'dailyTrend', 'topMedicines', 'statusSummary'
        ));
    }

    /* ─────────────────────────────────────────
     |  INVENTORY REPORT
     ─────────────────────────────────────────*/
    public function inventory(Request $request)
    {
        $pharmacy = $this->pharmacy();
        $pid      = $pharmacy->id;

        $query = Medicine::where('pharmacy_id', $pid);

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('stock_status')) {
            $query->where('stock_status', $request->stock_status);
        }

        $medicines       = $query->orderBy('stock_quantity', 'asc')->get();
        $totalMedicines  = $medicines->count();
        $totalStockValue = $medicines->sum(fn($m) => $m->stock_quantity * $m->price);
        $inStockCount    = $medicines->where('stock_status', 'in_stock')->count();
        $lowStockCount   = $medicines->where('stock_status', 'low_stock')->count();
        $outOfStockCount = $medicines->where('stock_status', 'out_of_stock')->count();
        $activeCount     = $medicines->where('is_active', true)->count();

        // Category Breakdown
        $categoryBreakdown = $medicines->groupBy('category')
            ->map(fn($group) => [
                'count'       => $group->count(),
                'total_stock' => $group->sum('stock_quantity'),
                'stock_value' => $group->sum(fn($m) => $m->stock_quantity * $m->price),
                'low_stock'   => $group->where('stock_status', 'low_stock')->count(),
                'out_stock'   => $group->where('stock_status', 'out_of_stock')->count(),
            ])
            ->sortByDesc(fn($g) => $g['count']);

        // Most Dispensed (from orders)
        $mostDispensed = DB::table('prescription_order_items as oi')
            ->join('prescription_orders as po', 'po.id', '=', 'oi.order_id')
            ->where('po.pharmacy_id', $pid)
            ->whereNotIn('po.status', ['cancelled'])
            ->select(
                'oi.medication_id',
                'oi.medication_name',
                DB::raw('SUM(oi.quantity) as total_dispensed'),
                DB::raw('SUM(oi.subtotal) as total_revenue')
            )
            ->groupBy('oi.medication_id', 'oi.medication_name')
            ->orderByDesc('total_dispensed')
            ->limit(10)
            ->get();

        $categories = Medicine::where('pharmacy_id', $pid)
            ->whereNotNull('category')
            ->distinct()->pluck('category')->sort()->values();

        return view('pharmacy.reports.inventory', compact(
            'medicines', 'totalMedicines', 'totalStockValue',
            'inStockCount', 'lowStockCount', 'outOfStockCount', 'activeCount',
            'categoryBreakdown', 'mostDispensed', 'categories'
        ));
    }

    /* ─────────────────────────────────────────
     |  GENERATE (POST)
     ─────────────────────────────────────────*/
    public function generate(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:daily,monthly,sales,inventory',
            'date'        => 'nullable|date',
            'month'       => 'nullable|string',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
        ]);

        return match ($request->report_type) {
            'daily'     => redirect()->route('pharmacy.reports.daily',
                                ['date' => $request->date ?? today()->toDateString()]),
            'monthly'   => redirect()->route('pharmacy.reports.monthly',
                                ['month' => $request->month ?? now()->format('Y-m')]),
            'sales'     => redirect()->route('pharmacy.reports.sales', [
                                'start_date' => $request->start_date ?? now()->startOfMonth()->toDateString(),
                                'end_date'   => $request->end_date   ?? now()->toDateString(),
                            ]),
            'inventory' => redirect()->route('pharmacy.reports.inventory'),
        };
    }

    /* ─────────────────────────────────────────
     |  EXPORT (CSV)
     ─────────────────────────────────────────*/
    public function export(Request $request)
    {
        $pharmacy = $this->pharmacy();
        $pid      = $pharmacy->id;

        $type      = $request->get('type', 'sales');
        $startDate = $request->start_date
            ? Carbon::parse($request->start_date)->startOfDay()
            : now()->startOfMonth();
        $endDate   = $request->end_date
            ? Carbon::parse($request->end_date)->endOfDay()
            : now()->endOfDay();

        $filename = "pharmacy_{$type}_report_" . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($type, $pid, $startDate, $endDate) {
            $file = fopen('php://output', 'w');

            if ($type === 'sales' || $type === 'daily' || $type === 'monthly') {
                // CSV Headers
                fputcsv($file, [
                    'Order #', 'Date', 'Patient', 'Status',
                    'Payment Method', 'Payment Status',
                    'Total Amount', 'Delivery Fee', 'Items Count',
                ]);

                PharmacyOrder::where('pharmacy_id', $pid)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->with('patient', 'items')
                    ->orderBy('created_at')
                    ->each(function ($order) use ($file) {
                        fputcsv($file, [
                            $order->order_number,
                            $order->created_at->format('Y-m-d H:i'),
                            optional($order->patient)->first_name . ' '
                                . optional($order->patient)->last_name,
                            $order->status,
                            $order->payment_method,
                            $order->payment_status,
                            $order->total_amount,
                            $order->delivery_fee,
                            $order->items->count(),
                        ]);
                    });
            } elseif ($type === 'inventory') {
                fputcsv($file, [
                    'Medicine Name', 'Generic Name', 'Category',
                    'Manufacturer', 'Price', 'Stock Qty',
                    'Stock Status', 'Stock Value', 'Requires Prescription', 'Active',
                ]);

                Medicine::where('pharmacy_id', $pid)
                    ->orderBy('name')
                    ->each(function ($med) use ($file) {
                        fputcsv($file, [
                            $med->name,
                            $med->generic_name,
                            $med->category,
                            $med->manufacturer,
                            $med->price,
                            $med->stock_quantity,
                            $med->stock_status,
                            $med->stock_quantity * $med->price,
                            $med->requires_prescription ? 'Yes' : 'No',
                            $med->is_active ? 'Yes' : 'No',
                        ]);
                    });
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
