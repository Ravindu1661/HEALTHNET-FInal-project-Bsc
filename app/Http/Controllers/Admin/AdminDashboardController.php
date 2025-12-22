<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\Laboratory;
use App\Models\Pharmacy;
use App\Models\MedicalCentre;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Get statistics with proper error handling
        $stats = [
            'total_users' => $this->safeCount(User::class),
            'users_change' => $this->calculatePercentageChange(User::class),
            
            'total_doctors' => $this->safeCount(Doctor::class),
            'doctors_change' => $this->calculatePercentageChange(Doctor::class),
            
            'total_hospitals' => $this->safeCount(Hospital::class),
            'hospitals_change' => $this->calculatePercentageChange(Hospital::class),
            
            'total_laboratories' => $this->safeCount(Laboratory::class),
            'total_pharmacies' => $this->safeCount(Pharmacy::class),
            'total_medical_centres' => $this->safeCount(MedicalCentre::class),
            
            'total_appointments' => DB::table('appointments')->count(),
            'appointments_change' => 0,
            
            // FIXED: Use payment_status instead of status
            'total_revenue' => DB::table('payments')
                ->where('payment_status', 'completed')
                ->sum('amount') ?? 0,
        ];

        // Chart data for visualization
        $chartData = [
            'direct' => 45.8,
            'social' => 18.2,
            'referral' => 22.6,
            'marketing' => 13.4,
        ];

        // Sales statistics - FIXED
        $salesStats = [
            'revenue' => DB::table('payments')
                ->where('payment_status', 'completed')
                ->sum('amount') ?? 0,
            'expenses' => 0,
            'payments' => DB::table('payments')->count(),
        ];

        // Revenue data - FIXED
        $revenueData = [
            'expenses' => 0,
            'earnings' => DB::table('payments')
                ->where('payment_status', 'completed')
                ->sum('amount') ?? 0,
            'revenue' => DB::table('payments')
                ->where('payment_status', 'completed')
                ->sum('amount') ?? 0,
        ];

        // Get pending approvals from all provider types
        $pendingApprovals = $this->getPendingApprovals();

        // Get recent users
        $recentUsers = User::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get notifications for the admin
        $notifications = $this->getAdminNotifications();
        $unreadNotifications = $notifications->where('is_read', false)->count();

        // Customer satisfaction metrics (dummy data - implement real logic later)
        $satisfaction = [
            'overall' => 94.3,
            'doctors' => 92.5,
            'hospitals' => 88.7,
            'laboratories' => 91.2,
        ];

        return view('admin.dashboard', compact(
            'stats',
            'chartData',
            'salesStats',
            'revenueData',
            'pendingApprovals',
            'recentUsers',
            'notifications',
            'unreadNotifications',
            'satisfaction'
        ));
    }

    /**
     * Safely count records with error handling
     */
    private function safeCount($model)
    {
        try {
            if (is_string($model)) {
                return $model::count();
            }
            return 0;
        } catch (\Exception $e) {
            \Log::error("Error counting {$model}: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get pending approvals from all provider types
     */
    private function getPendingApprovals()
    {
        $pendingApprovals = collect();

        try {
            // Get pending doctors
            $pendingDoctors = DB::table('doctors')
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function($doctor) {
                    return (object)[
                        'id' => $doctor->id,
                        'type' => 'doctor',
                        'name' => $doctor->first_name . ' ' . $doctor->last_name,
                        'registration_number' => $doctor->slmc_number ?? 'N/A',
                        'created_at' => Carbon::parse($doctor->created_at),
                    ];
                });
            
            $pendingApprovals = $pendingApprovals->merge($pendingDoctors);

        } catch (\Exception $e) {
            \Log::error('Error fetching pending doctors: ' . $e->getMessage());
        }

        try {
            // Get pending hospitals
            $pendingHospitals = DB::table('hospitals')
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function($hospital) {
                    return (object)[
                        'id' => $hospital->id,
                        'type' => 'hospital',
                        'name' => $hospital->name,
                        'registration_number' => $hospital->registration_number ?? 'N/A',
                        'created_at' => Carbon::parse($hospital->created_at),
                    ];
                });
            
            $pendingApprovals = $pendingApprovals->merge($pendingHospitals);

        } catch (\Exception $e) {
            \Log::error('Error fetching pending hospitals: ' . $e->getMessage());
        }

        try {
            // Get pending laboratories
            $pendingLaboratories = DB::table('laboratories')
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function($lab) {
                    return (object)[
                        'id' => $lab->id,
                        'type' => 'laboratory',
                        'name' => $lab->name,
                        'registration_number' => $lab->registration_number ?? 'N/A',
                        'created_at' => Carbon::parse($lab->created_at),
                    ];
                });
            
            $pendingApprovals = $pendingApprovals->merge($pendingLaboratories);

        } catch (\Exception $e) {
            \Log::error('Error fetching pending laboratories: ' . $e->getMessage());
        }

        try {
            // Get pending pharmacies
            $pendingPharmacies = DB::table('pharmacies')
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function($pharmacy) {
                    return (object)[
                        'id' => $pharmacy->id,
                        'type' => 'pharmacy',
                        'name' => $pharmacy->name,
                        'registration_number' => $pharmacy->registration_number ?? 'N/A',
                        'created_at' => Carbon::parse($pharmacy->created_at),
                    ];
                });
            
            $pendingApprovals = $pendingApprovals->merge($pendingPharmacies);

        } catch (\Exception $e) {
            \Log::error('Error fetching pending pharmacies: ' . $e->getMessage());
        }

        try {
            // Get pending medical centres
            $pendingMedicalCentres = DB::table('medical_centres')
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function($centre) {
                    return (object)[
                        'id' => $centre->id,
                        'type' => 'medicalcentre',
                        'name' => $centre->name,
                        'registration_number' => $centre->registration_number ?? 'N/A',
                        'created_at' => Carbon::parse($centre->created_at),
                    ];
                });
            
            $pendingApprovals = $pendingApprovals->merge($pendingMedicalCentres);

        } catch (\Exception $e) {
            \Log::error('Error fetching pending medical centres: ' . $e->getMessage());
        }

        return $pendingApprovals->sortByDesc('created_at')->take(10);
    }

    /**
     * Get admin notifications
     */
    private function getAdminNotifications()
    {
        try {
            $notifications = DB::table('notifications')
                ->where('user_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function($notification) {
                    return (object)[
                        'id' => $notification->id,
                        'type' => $notification->type ?? 'general',
                        'icon' => $this->getNotificationIcon($notification->type ?? 'general'),
                        'title' => $notification->title ?? 'Notification',
                        'message' => $notification->message ?? '',
                        'is_read' => (bool)($notification->is_read ?? false),
                        'created_at' => Carbon::parse($notification->created_at),
                    ];
                });

            return $notifications;
        } catch (\Exception $e) {
            \Log::error('Error fetching notifications: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Get icon for notification type
     */
    private function getNotificationIcon($type)
    {
        $icons = [
            'appointment' => 'calendar-check',
            'payment' => 'money-bill-wave',
            'prescription' => 'prescription',
            'lab_report' => 'flask',
            'labreport' => 'flask',
            'reminder' => 'bell',
            'announcement' => 'bullhorn',
            'general' => 'info-circle',
        ];

        return $icons[$type] ?? 'bell';
    }

    /**
     * Calculate percentage change for statistics
     */
    private function calculatePercentageChange($model, $isTable = false)
    {
        try {
            $currentMonth = now()->month;
            $currentYear = now()->year;
            $lastMonth = now()->subMonth()->month;
            $lastMonthYear = now()->subMonth()->year;

            if ($isTable) {
                $currentCount = DB::table($model)
                    ->whereMonth('created_at', $currentMonth)
                    ->whereYear('created_at', $currentYear)
                    ->count();
                
                $lastCount = DB::table($model)
                    ->whereMonth('created_at', $lastMonth)
                    ->whereYear('created_at', $lastMonthYear)
                    ->count();
            } else {
                $currentCount = $model::whereMonth('created_at', $currentMonth)
                    ->whereYear('created_at', $currentYear)
                    ->count();
                
                $lastCount = $model::whereMonth('created_at', $lastMonth)
                    ->whereYear('created_at', $lastMonthYear)
                    ->count();
            }

            if ($lastCount == 0) {
                return $currentCount > 0 ? 100 : 0;
            }
            
            return round((($currentCount - $lastCount) / $lastCount) * 100, 1);
        } catch (\Exception $e) {
            \Log::error("Error calculating percentage change: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get dashboard stats API endpoint
     */
    public function getStats()
    {
        try {
            return response()->json([
                'success' => true,
                'stats' => [
                    'total_users' => User::count(),
                    'total_doctors' => Doctor::count(),
                    'total_hospitals' => Hospital::count(),
                    'total_appointments' => DB::table('appointments')->count(),
                    'total_revenue' => DB::table('payments')
                        ->where('payment_status', 'completed')
                        ->sum('amount') ?? 0,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching stats',
            ], 500);
        }
    }
}
