<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DoctorDashboardController extends Controller
{
    private function getDoctor()
    {
        return Doctor::where('user_id', Auth::id())->firstOrFail();
    }

    // ══════════════════════════════════════════
    //  DASHBOARD INDEX
    // ══════════════════════════════════════════
    public function index()
    {
        $doctor       = $this->getDoctor();
        $today        = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth   = Carbon::now()->endOfMonth();

        // ── Stats ──
        $todayAppointments = DB::table('appointments')
            ->where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', $today)
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();

        $totalPatients = DB::table('appointments')
            ->where('doctor_id', $doctor->id)
            ->distinct('patient_id')
            ->count('patient_id');

        $monthlyEarnings = DB::table('appointments')
            ->where('doctor_id', $doctor->id)
            ->where('status', 'completed')
            ->whereBetween('appointment_date', [$startOfMonth, $endOfMonth])
            ->sum('consultation_fee');

        $avgRating = DB::table('ratings')
            ->where('ratable_type', 'doctor')
            ->where('ratable_id', $doctor->id)
            ->avg('rating') ?? 0;

        $pendingCount = DB::table('appointments')
            ->where('doctor_id', $doctor->id)
            ->where('status', 'pending')
            ->count();

        // ── Today's Appointments List ──
        // appointments table: workplace_type + workplace_id columns use කරනවා
        $todayList = DB::table('appointments')
            ->join('patients', 'appointments.patient_id', '=', 'patients.id')
            ->leftJoin('hospitals', function ($join) {
                $join->on('appointments.workplace_id', '=', 'hospitals.id')
                     ->where('appointments.workplace_type', '=', 'hospital');
            })
            ->leftJoin('medical_centres', function ($join) {
                $join->on('appointments.workplace_id', '=', 'medical_centres.id')
                     ->where('appointments.workplace_type', '=', 'medical_centre');
            })
            ->where('appointments.doctor_id', $doctor->id)
            ->whereDate('appointments.appointment_date', $today)
            ->select(
                'appointments.id',
                'appointments.appointment_number',
                'appointments.appointment_time as time',
                'appointments.status',
                'appointments.consultation_fee',
                'appointments.workplace_type',
                'appointments.workplace_id',
                DB::raw("CONCAT(patients.first_name, ' ', patients.last_name) as patient_name"),
                'patients.phone as patient_phone',
                DB::raw("COALESCE(hospitals.name, medical_centres.name, 'Private Clinic') as location")
            )
            ->orderBy('appointments.appointment_time', 'asc')
            ->get();

        // ── Recent Patients ──
        $recentPatients = DB::table('appointments')
            ->join('patients', 'appointments.patient_id', '=', 'patients.id')
            ->where('appointments.doctor_id', $doctor->id)
            ->select(
                'patients.id',
                DB::raw("CONCAT(patients.first_name, ' ', patients.last_name) as name"),
                'patients.phone',
                DB::raw('MAX(appointments.appointment_date) as last_visit'),
                DB::raw('COUNT(appointments.id) as visit_count')
            )
            ->groupBy(
                'patients.id',
                'patients.first_name',
                'patients.last_name',
                'patients.phone'
            )
            ->orderByDesc('last_visit')
            ->limit(5)
            ->get();

        // ── Recent Reviews ──
        $recentReviews = DB::table('ratings')
            ->join('patients', 'ratings.patient_id', '=', 'patients.id')
            ->where('ratings.ratable_type', 'doctor')
            ->where('ratings.ratable_id', $doctor->id)
            ->select(
                'ratings.id',
                'ratings.rating',
                'ratings.review',
                DB::raw("CONCAT(patients.first_name, ' ', patients.last_name) as patient_name"),
                DB::raw("DATE_FORMAT(ratings.created_at, '%d %b %Y') as date")
            )
            ->orderByDesc('ratings.created_at')
            ->limit(5)
            ->get();

        // ── Appointment Status Stats ──
        $appointmentStats = [
            'pending'   => DB::table('appointments')->where('doctor_id', $doctor->id)->where('status', 'pending')->count(),
            'confirmed' => DB::table('appointments')->where('doctor_id', $doctor->id)->where('status', 'confirmed')->count(),
            'completed' => DB::table('appointments')->where('doctor_id', $doctor->id)->where('status', 'completed')->count(),
            'cancelled' => DB::table('appointments')->where('doctor_id', $doctor->id)->where('status', 'cancelled')->count(),
        ];
        $appointmentStats['total'] = array_sum($appointmentStats);

        // ── Monthly Trend (Last 6 Months) ──
        $monthlyTrend = collect(range(5, 0))->map(function ($i) use ($doctor) {
            $m = Carbon::now()->subMonths($i);
            return [
                'month'    => $m->format('M'),
                'count'    => DB::table('appointments')
                                ->where('doctor_id', $doctor->id)
                                ->whereYear('appointment_date', $m->year)
                                ->whereMonth('appointment_date', $m->month)
                                ->count(),
                'earnings' => DB::table('appointments')
                                ->where('doctor_id', $doctor->id)
                                ->where('status', 'completed')
                                ->whereYear('appointment_date', $m->year)
                                ->whereMonth('appointment_date', $m->month)
                                ->sum('consultation_fee'),
            ];
        });

        // ── Workplaces ──
        // doctor_workplaces table: workplace_type + workplace_id columns
        $workplaces = DB::table('doctor_workplaces')
            ->leftJoin('hospitals', function ($join) {
                $join->on('doctor_workplaces.workplace_id', '=', 'hospitals.id')
                     ->where('doctor_workplaces.workplace_type', '=', 'hospital');
            })
            ->leftJoin('medical_centres', function ($join) {
                $join->on('doctor_workplaces.workplace_id', '=', 'medical_centres.id')
                     ->where('doctor_workplaces.workplace_type', '=', 'medical_centre');
            })
            ->where('doctor_workplaces.doctor_id', $doctor->id)
            ->where('doctor_workplaces.status', 'approved')
            ->select(
                'doctor_workplaces.id',
                'doctor_workplaces.workplace_type',
                'doctor_workplaces.employment_type',
                DB::raw("COALESCE(hospitals.name, medical_centres.name) as name"),
                DB::raw("COALESCE(hospitals.city, medical_centres.city) as city")
            )
            ->get();

        // ── Unread Notifications Count ──
        $unreadCount = Notification::where('notifiable_type', 'App\Models\User')
            ->where('notifiable_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return view('doctor.dashboard', compact(
            'doctor',
            'todayAppointments',
            'totalPatients',
            'monthlyEarnings',
            'avgRating',
            'pendingCount',
            'todayList',
            'recentPatients',
            'recentReviews',
            'appointmentStats',
            'monthlyTrend',
            'workplaces',
            'unreadCount'
        ));
    }

    // ══════════════════════════════════════════
    //  AJAX — Dashboard Stats
    // ══════════════════════════════════════════
    public function getStats()
    {
        try {
            $doctor       = $this->getDoctor();
            $today        = Carbon::today();
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth   = Carbon::now()->endOfMonth();

            return response()->json([
                'success'            => true,
                'today_appointments' => DB::table('appointments')
                    ->where('doctor_id', $doctor->id)
                    ->whereDate('appointment_date', $today)
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->count(),
                'total_patients'     => DB::table('appointments')
                    ->where('doctor_id', $doctor->id)
                    ->distinct('patient_id')
                    ->count('patient_id'),
                'monthly_earnings'   => number_format(
                    DB::table('appointments')
                        ->where('doctor_id', $doctor->id)
                        ->where('status', 'completed')
                        ->whereBetween('appointment_date', [$startOfMonth, $endOfMonth])
                        ->sum('consultation_fee'), 2
                ),
                'avg_rating'         => round(
                    DB::table('ratings')
                        ->where('ratable_type', 'doctor')
                        ->where('ratable_id', $doctor->id)
                        ->avg('rating') ?? 0, 1
                ),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // ══════════════════════════════════════════
    //  AJAX — Notifications List
    // ══════════════════════════════════════════
    public function getNotifications()
    {
        $notifications = Notification::where('notifiable_type', 'App\Models\User')
            ->where('notifiable_id', Auth::id())
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return response()->json([
            'success'       => true,
            'notifications' => $notifications,
            'unread_count'  => $notifications->where('is_read', false)->count(),
        ]);
    }

    // ══════════════════════════════════════════
    //  AJAX — Mark Single Notification Read
    // ══════════════════════════════════════════
    public function markNotificationRead($id)
    {
        Notification::where('notifiable_type', 'App\Models\User')
            ->where('notifiable_id', Auth::id())
            ->where('id', $id)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    // ══════════════════════════════════════════
    //  AJAX — Mark All Notifications Read
    // ══════════════════════════════════════════
    public function markAllNotificationsRead()
    {
        Notification::where('notifiable_type', 'App\Models\User')
            ->where('notifiable_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }
}
