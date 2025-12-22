<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DoctorDashboardController extends Controller
{
    /**
     * Display the doctor dashboard
     */
    public function index()
    {
        $doctor = Auth::user()->doctor;
        
        return view('doctor.dashboard', compact('doctor'));
    }

    /**
     * Get dashboard statistics
     */
    public function getStats(Request $request)
    {
        try {
            $doctorId = Auth::user()->doctor->id;
            $today = Carbon::today();
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();

            // Today's appointments count
            $todayAppointments = DB::table('appointments')
                ->where('doctor_id', $doctorId)
                ->whereDate('appointment_date', $today)
                ->whereIn('status', ['pending', 'confirmed'])
                ->count();

            // Total patients (unique)
            $totalPatients = DB::table('appointments')
                ->where('doctor_id', $doctorId)
                ->distinct('patient_id')
                ->count('patient_id');

            // Monthly earnings
            $monthlyEarnings = DB::table('appointments')
                ->where('doctor_id', $doctorId)
                ->where('status', 'completed')
                ->whereBetween('appointment_date', [$startOfMonth, $endOfMonth])
                ->sum('consultation_fee');

            // Average rating
            $avgRating = DB::table('doctor_reviews')
                ->where('doctor_id', $doctorId)
                ->avg('rating');

            // Appointment statistics
            $appointmentStats = [
                'pending' => DB::table('appointments')
                    ->where('doctor_id', $doctorId)
                    ->where('status', 'pending')
                    ->count(),
                'confirmed' => DB::table('appointments')
                    ->where('doctor_id', $doctorId)
                    ->where('status', 'confirmed')
                    ->count(),
                'completed' => DB::table('appointments')
                    ->where('doctor_id', $doctorId)
                    ->where('status', 'completed')
                    ->count(),
                'cancelled' => DB::table('appointments')
                    ->where('doctor_id', $doctorId)
                    ->where('status', 'cancelled')
                    ->count(),
            ];

            $appointmentStats['total'] = array_sum($appointmentStats);

            return response()->json([
                'success' => true,
                'today_appointments' => $todayAppointments,
                'total_patients' => $totalPatients,
                'monthly_earnings' => number_format($monthlyEarnings, 2, '.', ''),
                'avg_rating' => round($avgRating ?? 0, 1),
                'appointment_stats' => $appointmentStats
            ]);

        } catch (\Exception $e) {
            \Log::error('Doctor Dashboard Stats Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get today's appointments
     */
    public function getTodayAppointments(Request $request)
    {
        try {
            $doctorId = Auth::user()->doctor->id;
            $today = Carbon::today();

            $appointments = DB::table('appointments')
                ->join('patients', 'appointments.patient_id', '=', 'patients.id')
                ->join('users', 'patients.user_id', '=', 'users.id')
                ->leftJoin('hospitals', 'appointments.hospital_id', '=', 'hospitals.id')
                ->leftJoin('medical_centres', 'appointments.medical_centre_id', '=', 'medical_centres.id')
                ->where('appointments.doctor_id', $doctorId)
                ->whereDate('appointments.appointment_date', $today)
                ->select(
                    'appointments.id',
                    'appointments.appointment_time as time',
                    'appointments.duration',
                    'appointments.status',
                    DB::raw("CONCAT(patients.first_name, ' ', patients.last_name) as patient_name"),
                    DB::raw("COALESCE(hospitals.name, medical_centres.name, 'Private Clinic') as location")
                )
                ->orderBy('appointments.appointment_time', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'appointments' => $appointments
            ]);

        } catch (\Exception $e) {
            \Log::error('Today Appointments Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load appointments',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get recent patients
     */
    public function getRecentPatients(Request $request)
    {
        try {
            $doctorId = Auth::user()->doctor->id;

            $patients = DB::table('appointments')
                ->join('patients', 'appointments.patient_id', '=', 'patients.id')
                ->join('users', 'patients.user_id', '=', 'users.id')
                ->where('appointments.doctor_id', $doctorId)
                ->select(
                    'patients.id',
                    DB::raw("CONCAT(patients.first_name, ' ', patients.last_name) as name"),
                    'patients.phone',
                    'users.profile_image as avatar',
                    DB::raw("DATE_FORMAT(MAX(appointments.appointment_date), '%d %b %Y') as last_visit")
                )
                ->groupBy('patients.id', 'patients.first_name', 'patients.last_name', 'patients.phone', 'users.profile_image')
                ->orderBy('last_visit', 'desc')
                ->limit(5)
                ->get();

            return response()->json([
                'success' => true,
                'patients' => $patients
            ]);

        } catch (\Exception $e) {
            \Log::error('Recent Patients Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load patients',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get recent reviews
     */
    public function getRecentReviews(Request $request)
    {
        try {
            $doctorId = Auth::user()->doctor->id;

            $reviews = DB::table('doctor_reviews')
                ->join('patients', 'doctor_reviews.patient_id', '=', 'patients.id')
                ->where('doctor_reviews.doctor_id', $doctorId)
                ->select(
                    'doctor_reviews.id',
                    'doctor_reviews.rating',
                    'doctor_reviews.comment',
                    DB::raw("CONCAT(patients.first_name, ' ', patients.last_name) as patient_name"),
                    DB::raw("DATE_FORMAT(doctor_reviews.created_at, '%d %b %Y') as date")
                )
                ->orderBy('doctor_reviews.created_at', 'desc')
                ->limit(5)
                ->get();

            return response()->json([
                'success' => true,
                'reviews' => $reviews
            ]);

        } catch (\Exception $e) {
            \Log::error('Recent Reviews Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load reviews',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
