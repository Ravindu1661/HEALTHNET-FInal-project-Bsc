<?php
namespace App\Http\Controllers\Laboratory;

use App\Http\Controllers\Controller;
use App\Models\Laboratory;
use App\Models\LabOrder;
use App\Models\LabTest;
use App\Models\LabPackage;
use App\Models\Payment;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaboratoryDashboardController extends Controller
{
    private function getLab(): Laboratory
    {
        return Laboratory::where('user_id', Auth::id())->firstOrFail();
    }

    public function index()
    {
        $lab = $this->getLab();
        $labId = $lab->id;

        /* ── Stats ── */
        $stats = [
            'today_orders'    => LabOrder::where('laboratory_id', $labId)->whereDate('created_at', today())->count(),
            'pending'         => LabOrder::where('laboratory_id', $labId)->where('status', 'pending')->count(),
            'processing'      => LabOrder::where('laboratory_id', $labId)->where('status', 'processing')->count(),
            'completed'       => LabOrder::where('laboratory_id', $labId)->where('status', 'completed')->count(),
            'home_collection' => LabOrder::where('laboratory_id', $labId)->where('home_collection', true)->whereIn('status', ['pending','sample_collected'])->count(),
            'monthly_revenue' => Payment::where('payee_type', 'laboratory')
                ->where('payee_id', $labId)
                ->where('payment_status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->sum('amount'),
            'total_revenue'   => Payment::where('payee_type', 'laboratory')
                ->where('payee_id', $labId)
                ->where('payment_status', 'completed')
                ->sum('amount'),
            'active_tests'    => LabTest::where('laboratory_id', $labId)->where('is_active', true)->count(),
            'total_tests'     => LabTest::where('laboratory_id', $labId)->count(),
            'total_packages'  => LabPackage::where('laboratory_id', $labId)->count(),
            'avg_rating'      => Rating::where('ratable_type', 'laboratory')->where('ratable_id', $labId)->avg('rating') ?? 0,
            'total_ratings'   => Rating::where('ratable_type', 'laboratory')->where('ratable_id', $labId)->count(),
        ];

        /* ── Recent Orders ── */
        $recentOrders = LabOrder::with(['patient.user', 'items'])
            ->where('laboratory_id', $labId)
            ->latest()
            ->limit(8)
            ->get();

        /* ── Upcoming Home Collections ── */
        $homeCollections = LabOrder::with(['patient.user'])
            ->where('laboratory_id', $labId)
            ->where('home_collection', true)
            ->whereIn('status', ['pending', 'sample_collected'])
            ->whereDate('collection_date', '>=', today())
            ->orderBy('collection_date')
            ->orderBy('collection_time')
            ->limit(5)
            ->get();

        /* ── Latest Reviews ── */
        $latestReviews = Rating::with('patient.user')
            ->where('ratable_type', 'laboratory')
            ->where('ratable_id', $labId)
            ->latest()
            ->limit(5)
            ->get();

        /* ── Monthly Revenue (6 months) ── */
        $monthlyRevenue = collect(range(5, 0))->map(function ($i) use ($labId) {
            $month = now()->subMonths($i);
            return [
                'label'   => $month->format('M'),
                'revenue' => Payment::where('payee_type', 'laboratory')
                    ->where('payee_id', $labId)
                    ->where('payment_status', 'completed')
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->sum('amount'),
            ];
        });

        /* ── Order Status Breakdown ── */
        $orderStatus = LabOrder::where('laboratory_id', $labId)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        /* ── Unread Notifications ── */
        $unreadCount = DB::table('notifications')
            ->where('notifiable_type', \App\Models\User::class)
            ->where('notifiable_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return view('laboratory.dashboard', compact(
            'lab', 'stats', 'recentOrders', 'homeCollections',
            'latestReviews', 'monthlyRevenue', 'orderStatus', 'unreadCount'
        ));
    }
}
