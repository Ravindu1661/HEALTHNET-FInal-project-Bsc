<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PharmacyOrder;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\Rating;
use Illuminate\Support\Facades\DB;

class PharmacyDashboardController extends Controller
{
    /**
     * Display pharmacy dashboard
     */
    public function index()
    {
        $user = Auth::user();
        $pharmacy = $user->pharmacy;

        // If pharmacy profile doesn't exist, redirect to create profile
        if (!$pharmacy) {
            return redirect()->route('pharmacy.profile.create')
                ->with('error', 'Please complete your pharmacy profile first.');
        }

        // Check if pharmacy is approved
        if ($pharmacy->status === 'pending') {
            return view('pharmacy.dashboard-pending', compact('pharmacy'));
        }

        if ($pharmacy->status === 'rejected') {
            return view('pharmacy.dashboard-rejected', compact('pharmacy'));
        }

        // Statistics
        $totalMedicines = Medicine::where('pharmacy_id', $pharmacy->id)->count();

        $totalOrders = PharmacyOrder::where('pharmacy_id', $pharmacy->id)->count();

        $pendingOrders = PharmacyOrder::where('pharmacy_id', $pharmacy->id)
            ->where('status', 'pending')
            ->count();

        $totalRevenue = PharmacyOrder::where('pharmacy_id', $pharmacy->id)
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        $totalPatients = PharmacyOrder::where('pharmacy_id', $pharmacy->id)
            ->distinct('patient_id')
            ->count('patient_id');

        // Recent Orders
        $recentOrders = PharmacyOrder::where('pharmacy_id', $pharmacy->id)
            ->with(['patient.user', 'items'])
            ->latest()
            ->limit(10)
            ->get();

        // Low Stock Medicines
        $lowStockMedicines = Medicine::where('pharmacy_id', $pharmacy->id)
            ->where('stock_quantity', '<=', 10)
            ->where('stock_quantity', '>', 0)
            ->orderBy('stock_quantity', 'asc')
            ->limit(5)
            ->get();

        // Out of Stock Medicines
        $outOfStockMedicines = Medicine::where('pharmacy_id', $pharmacy->id)
            ->where('stock_quantity', '<=', 0)
            ->count();

        // Monthly Sales Data (for chart)
        $monthlySales = PharmacyOrder::where('pharmacy_id', $pharmacy->id)
            ->where('payment_status', 'paid')
            ->whereYear('created_at', date('Y'))
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month');

        // Fill missing months with 0
        $salesData = [];
        for ($i = 1; $i <= 12; $i++) {
            $salesData[] = $monthlySales->get($i, 0);
        }

        // Recent Ratings
        $recentRatings = Rating::where('ratable_type', 'pharmacy')
            ->where('ratable_id', $pharmacy->id)
            ->with('patient.user')
            ->latest()
            ->limit(5)
            ->get();

        return view('pharmacy.dashboard', compact(
            'pharmacy',
            'totalMedicines',
            'totalOrders',
            'pendingOrders',
            'totalRevenue',
            'totalPatients',
            'recentOrders',
            'lowStockMedicines',
            'outOfStockMedicines',
            'salesData',
            'recentRatings'
        ));
    }

    /**
     * Get all notifications
     */
    public function notifications()
    {
        $notifications = Auth::user()->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('pharmacy.notifications', compact('notifications'));
    }

    /**
     * Mark notification as read
     */
    public function markNotificationRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->update(['is_read' => true, 'read_at' => now()]);

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllNotificationsRead()
    {
        Auth::user()->notifications()->update([
            'is_read' => true,
            'read_at' => now()
        ]);

        return back()->with('success', 'All notifications marked as read.');
    }
}
