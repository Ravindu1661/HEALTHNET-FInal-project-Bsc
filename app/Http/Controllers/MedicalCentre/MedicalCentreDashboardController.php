<?php

namespace App\Http\Controllers\MedicalCentre;

use App\Http\Controllers\Controller;
use App\Models\MedicalCentre;
use App\Models\Appointment;
use App\Models\DoctorWorkplace;
use App\Models\Notification;
use App\Models\Rating;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Carbon\Carbon;

class MedicalCentreDashboardController extends Controller
{
    // ═══════════════════════════════════════════
    // HELPER
    // ═══════════════════════════════════════════
    private function getMedicalCentre(): MedicalCentre
    {
        return MedicalCentre::where('user_id', Auth::id())->firstOrFail();
    }

    // notifiable_type/id shorthand — database ගාව App\Models\User + user_id
    private function notifQuery()
    {
        return Notification::where('notifiable_type', 'App\Models\User')
            ->where('notifiable_id', Auth::id());
    }

    // ═══════════════════════════════════════════
    // DASHBOARD — main view
    // ═══════════════════════════════════════════
    public function index()
    {
        $medicalCentre = $this->getMedicalCentre();
        $mcId          = $medicalCentre->id;
        $today         = Carbon::today();

        $stats = [
            'today_appointments' => Appointment::where('workplace_type', 'medical_centre')
                ->where('workplace_id', $mcId)
                ->whereDate('appointment_date', $today)
                ->whereIn('status', ['pending', 'confirmed'])
                ->count(),

            'total_appointments' => Appointment::where('workplace_type', 'medical_centre')
                ->where('workplace_id', $mcId)
                ->count(),

            'active_doctors' => DoctorWorkplace::where('workplace_type', 'medical_centre')
                ->where('workplace_id', $mcId)
                ->where('status', 'approved')
                ->count(),

            'pending_doctors' => DoctorWorkplace::where('workplace_type', 'medical_centre')
                ->where('workplace_id', $mcId)
                ->where('status', 'pending')
                ->count(),

            'monthly_revenue' => Appointment::where('workplace_type', 'medical_centre')
                ->where('workplace_id', $mcId)
                ->where('status', 'completed')
                ->whereBetween('appointment_date', [
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth(),
                ])
                ->sum('consultation_fee'),

            'total_patients' => Appointment::where('workplace_type', 'medical_centre')
                ->where('workplace_id', $mcId)
                ->distinct('patient_id')
                ->count('patient_id'),

            'avg_rating' => round(
                Rating::where('ratable_type', 'medical_centre')
                    ->where('ratable_id', $mcId)
                    ->avg('rating') ?? 0,
                1
            ),

            'unread_notifications' => $this->notifQuery()
                ->where('is_read', false)
                ->count(),
        ];

        $appointmentStats = [
            'pending'   => Appointment::where('workplace_type', 'medical_centre')->where('workplace_id', $mcId)->where('status', 'pending')->count(),
            'confirmed' => Appointment::where('workplace_type', 'medical_centre')->where('workplace_id', $mcId)->where('status', 'confirmed')->count(),
            'completed' => Appointment::where('workplace_type', 'medical_centre')->where('workplace_id', $mcId)->where('status', 'completed')->count(),
            'cancelled' => Appointment::where('workplace_type', 'medical_centre')->where('workplace_id', $mcId)->where('status', 'cancelled')->count(),
        ];

        $weeklyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date         = Carbon::today()->subDays($i);
            $weeklyData[] = [
                'date'  => $date->format('D'),
                'count' => Appointment::where('workplace_type', 'medical_centre')
                    ->where('workplace_id', $mcId)
                    ->whereDate('appointment_date', $date)
                    ->count(),
            ];
        }

        $todayAppointments = DB::table('appointments')
            ->join('patients', 'appointments.patient_id', '=', 'patients.id')
            ->join('doctors',  'appointments.doctor_id',  '=', 'doctors.id')
            ->where('appointments.workplace_type', 'medical_centre')
            ->where('appointments.workplace_id', $mcId)
            ->whereDate('appointments.appointment_date', $today)
            ->select(
                'appointments.id',
                'appointments.appointment_number',
                'appointments.appointment_time',
                'appointments.status',
                'appointments.consultation_fee',
                'appointments.payment_status',
                DB::raw("CONCAT(patients.first_name, ' ', patients.last_name) AS patient_name"),
                DB::raw("CONCAT(doctors.first_name, ' ', doctors.last_name)  AS doctor_name"),
                'doctors.specialization',
                'patients.phone AS patient_phone',
            )
            ->orderBy('appointments.appointment_time', 'asc')
            ->get();

        $doctors = DB::table('doctor_workplaces')
            ->join('doctors', 'doctor_workplaces.doctor_id', '=', 'doctors.id')
            ->where('doctor_workplaces.workplace_type', 'medical_centre')
            ->where('doctor_workplaces.workplace_id', $mcId)
            ->select(
                'doctors.id',
                DB::raw("CONCAT(doctors.first_name, ' ', doctors.last_name) AS name"),
                'doctors.specialization',
                'doctors.experience_years',
                'doctors.consultation_fee',
                'doctors.rating',
                'doctors.profile_image',
                'doctor_workplaces.employment_type',
                'doctor_workplaces.status AS workplace_status',
                'doctor_workplaces.created_at AS joined_at',
            )
            ->orderByRaw("FIELD(doctor_workplaces.status, 'pending', 'approved', 'rejected')")
            ->get();

        $recentAppointments = DB::table('appointments')
            ->join('patients', 'appointments.patient_id', '=', 'patients.id')
            ->where('appointments.workplace_type', 'medical_centre')
            ->where('appointments.workplace_id', $mcId)
            ->select(
                'appointments.id',
                'appointments.appointment_date',
                'appointments.appointment_time',
                'appointments.status',
                DB::raw("CONCAT(patients.first_name, ' ', patients.last_name) AS patient_name"),
            )
            ->orderBy('appointments.created_at', 'desc')
            ->limit(5)
            ->get();

        $recentRatings = DB::table('ratings')
            ->join('patients', 'ratings.patient_id', '=', 'patients.id')
            ->where('ratings.ratable_type', 'medical_centre')
            ->where('ratings.ratable_id', $mcId)
            ->select(
                'ratings.id',
                'ratings.rating',
                'ratings.review',
                'ratings.created_at',
                DB::raw("CONCAT(patients.first_name, ' ', patients.last_name) AS patient_name"),
            )
            ->orderBy('ratings.created_at', 'desc')
            ->limit(5)
            ->get();

        return view('medical_centre.dashboard', compact(
            'medicalCentre', 'stats', 'appointmentStats',
            'weeklyData', 'todayAppointments', 'doctors',
            'recentAppointments', 'recentRatings',
        ));
    }

    // ═══════════════════════════════════════════
    // NOTIFICATIONS — page view
    // ═══════════════════════════════════════════
    public function notifications(Request $request)
    {
        $filter = $request->input('filter', 'all');
        $type   = $request->input('type', '');

        $query = $this->notifQuery();

        if ($filter === 'unread') $query->where('is_read', false);
        if ($filter === 'read')   $query->where('is_read', true);
        if ($type)                $query->where('type', $type);

        $notifications = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        $unreadCount = $this->notifQuery()
            ->where('is_read', false)
            ->count();

        $topbarNotifications = $this->notifQuery()
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        // notifications view ගාව $mc අවශ්‍ය නම්
        $mc = $this->getMedicalCentre();

        return view('medical_centre.notifications', compact(
            'mc', 'notifications', 'unreadCount',
            'filter', 'type', 'topbarNotifications'
        ));
    }

    // ═══════════════════════════════════════════
    // MARK SINGLE NOTIFICATION READ
    // ═══════════════════════════════════════════
    public function markNotificationRead(Request $request, $id)
    {
        try {
           $this->notifQuery()
    ->where('id', $id)        // ← findOrFail() replace
    ->first()
    ->update(['is_read' => true, 'read_at' => now()]);

            return back()->with('success', 'Notification marked as read.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to mark as read.');
        }
    }

    // ═══════════════════════════════════════════
    // MARK ALL NOTIFICATIONS READ
    // ═══════════════════════════════════════════
    public function markAllNotificationsRead(Request $request)
    {
        try {
            $this->notifQuery()
                ->where('is_read', false)
                ->update(['is_read' => true, 'read_at' => now()]);

            return back()->with('success', 'All notifications marked as read.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to mark all as read.');
        }
    }

    // ═══════════════════════════════════════════
    // DELETE NOTIFICATION
    // ═══════════════════════════════════════════
    public function deleteNotification(Request $request, $id)
    {
        try {
          $this->notifQuery()
    ->where('id', $id)        // ← findOrFail() replace
    ->delete();

            return back()->with('success', 'Notification deleted.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete notification.');
        }
    }

    // ═══════════════════════════════════════════
    // SETTINGS — page view
    // ═══════════════════════════════════════════
    public function settings()
    {
        $mc   = $this->getMedicalCentre();
        if (!$mc) return redirect()->route('medical_centre.dashboard');

        $user = Auth::user();

        return view('medical_centre.settings', compact('mc', 'user'));
    }

    // ═══════════════════════════════════════════
    // UPDATE PASSWORD
    // ═══════════════════════════════════════════
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password'         => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Current password is incorrect.'])
                ->withInput()
                ->with('_settings_tab', 'security');
        }

        $user->update(['password' => Hash::make($request->password)]);

        ActivityLog::create([
            'user_id'     => $user->id,
            'action'      => 'password_changed',
            'description' => 'Medical Centre user changed their password.',
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        return back()
            ->with('success', 'Password updated successfully.')
            ->with('_settings_tab', 'security');
    }

    // ═══════════════════════════════════════════
    // RESEND EMAIL VERIFICATION
    // ═══════════════════════════════════════════
    public function resendVerification(Request $request)
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            return back()->with('info', 'Your email is already verified.');
        }

        $user->sendEmailVerificationNotification();

        return back()->with('success', 'Verification email sent! Please check your inbox.');
    }
}
