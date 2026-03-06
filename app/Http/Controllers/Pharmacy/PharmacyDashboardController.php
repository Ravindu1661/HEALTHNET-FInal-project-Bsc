<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PharmacyDashboardController extends Controller
{
    public function index()
    {
        $user     = Auth::user();
        $pharmacy = $user->pharmacy;

        if (!$pharmacy) {
            return redirect()->route('pharmacy.profile.create')
                ->with('error', 'Please complete your pharmacy profile first.');
        }

        if ($pharmacy->status === 'pending') {
            return view('pharmacy.dashboard-pending', compact('pharmacy'));
        }

        if ($pharmacy->status === 'rejected') {
            return view('pharmacy.dashboard-rejected', compact('pharmacy'));
        }

        $pid = $pharmacy->id;

        // ── MEDICATIONS ──
        // stock_status enum: 'in_stock', 'low_stock', 'out_of_stock' ✅
        $totalMedicines = DB::table('medications')
            ->where('pharmacy_id', $pid)
            ->count();

        $outOfStockCount = DB::table('medications')
            ->where('pharmacy_id', $pid)
            ->where('stock_status', 'out_of_stock')
            ->count();

        $lowStockCount = DB::table('medications')
            ->where('pharmacy_id', $pid)
            ->where('stock_status', 'low_stock')
            ->count();

        $lowStockMedicines = DB::table('medications')
            ->where('pharmacy_id', $pid)
            ->where('stock_status', 'low_stock')
            ->orderBy('stock_quantity', 'asc')
            ->limit(5)
            ->get();

        // ── PRESCRIPTION ORDERS ──
        // payment_status enum: 'unpaid', 'paid' ✅
        $totalOrders = DB::table('prescription_orders')
            ->where('pharmacy_id', $pid)
            ->count();

        $pendingOrders = DB::table('prescription_orders')
            ->where('pharmacy_id', $pid)
            ->where('status', 'pending')
            ->count();

        $totalRevenue = (float) DB::table('prescription_orders')
            ->where('pharmacy_id', $pid)
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        $totalPatients = DB::table('prescription_orders')
            ->where('pharmacy_id', $pid)
            ->distinct()
            ->count('patient_id');

        $orderStats = [
            'total'      => $totalOrders,
            'pending'    => $pendingOrders,
            'verified'   => DB::table('prescription_orders')->where('pharmacy_id', $pid)->where('status', 'verified')->count(),
            'processing' => DB::table('prescription_orders')->where('pharmacy_id', $pid)->where('status', 'processing')->count(),
            'ready'      => DB::table('prescription_orders')->where('pharmacy_id', $pid)->where('status', 'ready')->count(),
            'dispatched' => DB::table('prescription_orders')->where('pharmacy_id', $pid)->where('status', 'dispatched')->count(),
            'delivered'  => DB::table('prescription_orders')->where('pharmacy_id', $pid)->where('status', 'delivered')->count(),
            'cancelled'  => DB::table('prescription_orders')->where('pharmacy_id', $pid)->where('status', 'cancelled')->count(),
        ];

        // Recent orders — patients JOIN (first_name, last_name from patients table)
        $recentOrders = DB::table('prescription_orders as po')
            ->leftJoin('patients as pt', 'pt.id', '=', 'po.patient_id')
            ->where('po.pharmacy_id', $pid)
            ->orderByDesc('po.created_at')
            ->limit(10)
            ->select(
                'po.*',
                DB::raw("CONCAT(pt.first_name, ' ', pt.last_name) as patient_name"),
                'pt.profile_image as patient_image'
            )
            ->get();

        // Today's orders — patients JOIN
        $todayOrders = DB::table('prescription_orders as po')
            ->leftJoin('patients as pt', 'pt.id', '=', 'po.patient_id')
            ->where('po.pharmacy_id', $pid)
            ->whereDate('po.order_date', today())
            ->orderByDesc('po.created_at')
            ->select(
                'po.*',
                DB::raw("CONCAT(pt.first_name, ' ', pt.last_name) as patient_name"),
                'pt.profile_image as patient_image'
            )
            ->get();

        // Monthly sales chart data (current year)
        $monthlySalesRaw = DB::table('prescription_orders')
            ->where('pharmacy_id', $pid)
            ->where('payment_status', 'paid')
            ->whereRaw('YEAR(created_at) = ?', [date('Y')])
            ->selectRaw('MONTH(created_at) as month, SUM(total_amount) as total, COUNT(*) as cnt')
            ->groupByRaw('MONTH(created_at)')
            ->get()
            ->keyBy('month');

        $salesData     = [];
        $monthlyOrders = [];
        for ($m = 1; $m <= 12; $m++) {
            $row             = $monthlySalesRaw->get($m);
            $salesData[]     = $row ? (float) $row->total : 0;
            $monthlyOrders[] = $row ? (int)   $row->cnt   : 0;
        }

        // ── RATINGS ──
        // ratable_type enum: 'doctor','hospital','laboratory','pharmacy','medical_centre' ✅
        $recentRatings = DB::table('ratings as r')
            ->leftJoin('patients as pt', 'pt.id', '=', 'r.patient_id')
            ->where('r.ratable_type', 'pharmacy')
            ->where('r.ratable_id', $pid)
            ->orderByDesc('r.created_at')
            ->limit(5)
            ->select(
                'r.*',
                DB::raw("CONCAT(pt.first_name, ' ', pt.last_name) as patient_name"),
                'pt.profile_image as patient_image'
            )
            ->get();

        $avgRating = DB::table('ratings')
            ->where('ratable_type', 'pharmacy')
            ->where('ratable_id', $pid)
            ->avg('rating') ?? 0;

        $totalRatings = DB::table('ratings')
            ->where('ratable_type', 'pharmacy')
            ->where('ratable_id', $pid)
            ->count();

        // ── NOTIFICATIONS ──
        // is_read: boolean (0/1), read_at: timestamp ✅
        $unreadNotifications = DB::table('notifications')
            ->where('notifiable_type', 'App\Models\User')
            ->where('notifiable_id', $user->id)
            ->where('is_read', 0)
            ->count();

        return view('pharmacy.dashboard', compact(
            'pharmacy',
            'totalMedicines',
            'totalOrders',
            'pendingOrders',
            'totalRevenue',
            'totalPatients',
            'outOfStockCount',
            'lowStockCount',
            'orderStats',
            'recentOrders',
            'todayOrders',
            'lowStockMedicines',
            'salesData',
            'monthlyOrders',
            'recentRatings',
            'avgRating',
            'totalRatings',
            'unreadNotifications'
        ));
    }

    // ── AJAX Stats ──
    public function getStats()
    {
        $pid = Auth::user()->pharmacy->id;

        return response()->json([
            'totalOrders'   => DB::table('prescription_orders')->where('pharmacy_id', $pid)->count(),
            'pendingOrders' => DB::table('prescription_orders')->where('pharmacy_id', $pid)->where('status', 'pending')->count(),
            'totalRevenue'  => (float) DB::table('prescription_orders')->where('pharmacy_id', $pid)->where('payment_status', 'paid')->sum('total_amount'),
            'lowStock'      => DB::table('medications')->where('pharmacy_id', $pid)->where('stock_status', 'low_stock')->count(),
        ]);
    }

    // ── Notifications Page ──
   public function notifications()
{
    $user = Auth::user();

    $notifications = $user->notifications()
        ->paginate(20);

    $unreadCount = $user->notifications()
        ->where('is_read', false)
        ->count();

    $typeStats = $user->notifications()
        ->selectRaw('type, count(*) as count')
        ->groupBy('type')
        ->pluck('count', 'type');

    return view('pharmacy.notifications', compact(
        'notifications',
        'unreadCount',
        'typeStats'
    ));
}


    // ── Mark Single Notification Read ──
    public function markNotificationRead($notification)
    {
        DB::table('notifications')
            ->where('id', $notification)
            ->where('notifiable_id', Auth::id())
            ->update([
                'is_read' => 1,
                'read_at' => now(),
            ]);

        return response()->json(['success' => true]);
    }

    // ── Mark All Notifications Read ──
    public function markAllNotificationsRead()
    {
        DB::table('notifications')
            ->where('notifiable_type', 'App\Models\User')
            ->where('notifiable_id', Auth::id())
            ->where('is_read', 0)
            ->update([
                'is_read' => 1,
                'read_at' => now(),
            ]);

        return back()->with('success', 'All notifications marked as read.');
    }
}
