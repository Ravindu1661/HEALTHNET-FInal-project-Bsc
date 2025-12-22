<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\MedicalCentre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FindDoctorsController extends Controller
{
    /**
     * Display a listing of doctors with search and filtering
     */
    public function index(Request $request)
    {
        // Start query
        $query = Doctor::query();

        // Eager load relationships
        $query->with([
            'user',
            'workplaces' => function($q) {
                $q->where('status', 'approved')
                  ->with(['hospital', 'medicalCentre']);
            }
        ]);

        // Only show approved doctors with active users
        $query->where('status', 'approved')
              ->whereHas('user', function($q) {
                  $q->where('status', 'active');
              });

        // Search by doctor name or hospital/medical centre name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$search}%")
                  ->orWhereHas('workplaces.hospital', function($wq) use ($search) {
                      $wq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('workplaces.medicalCentre', function($wq) use ($search) {
                      $wq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by specialty
        if ($request->filled('specialty')) {
            $query->where('specialization', $request->specialty);
        }

        // Filter by location (city)
        if ($request->filled('location')) {
            $location = $request->location;
            $query->whereHas('workplaces', function($q) use ($location) {
                $q->where('status', 'approved')
                  ->where(function($wq) use ($location) {
                      $wq->whereHas('hospital', function($hq) use ($location) {
                          $hq->where('city', $location);
                      })
                      ->orWhereHas('medicalCentre', function($mq) use ($location) {
                          $mq->where('city', $location);
                      });
                  });
            });
        }

        // Get unique specialties for filter dropdown
        $specialties = Doctor::where('status', 'approved')
            ->whereNotNull('specialization')
            ->where('specialization', '!=', '')
            ->distinct()
            ->orderBy('specialization')
            ->pluck('specialization');

        // Get unique cities from hospitals and medical centres
        $hospitalCities = Hospital::where('status', 'approved')
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->distinct()
            ->pluck('city');

        $medicalCentreCities = MedicalCentre::where('status', 'approved')
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->distinct()
            ->pluck('city');

        $cities = $hospitalCities->merge($medicalCentreCities)
            ->unique()
            ->sort()
            ->values();

        // Get paginated doctors
        $doctors = $query->orderBy('rating', 'desc')
                        ->orderBy('created_at', 'desc')
                        ->paginate(12);

        return view('patient.find-doctors', compact('doctors', 'specialties', 'cities'));
    }

    /**
     * Display the specified doctor's detailed profile
     */
    public function show($id)
    {
        // Get doctor with all relationships
        $doctor = Doctor::with([
            'user',
            'workplaces' => function($q) {
                $q->where('status', 'approved')
                  ->with(['hospital', 'medicalCentre']);
            },
            'appointments' => function($q) {
                $q->where('status', 'completed');
            },
            'reviews' => function($q) {
                $q->with('patient.user')
                  ->latest()
                  ->limit(10);
            }
        ])
        ->where('status', 'approved')
        ->whereHas('user', function($q) {
            $q->where('status', 'active');
        })
        ->findOrFail($id);

        // Get approved workplaces only
        $workplaces = $doctor->workplaces;

        // Get recent reviews
        $reviews = $doctor->reviews;

        // Calculate statistics
        $totalAppointments = $doctor->appointments()->count();
        $completedAppointments = $doctor->appointments()->where('status', 'completed')->count();

        return view('patient.doctor-profile', compact('doctor', 'workplaces', 'reviews', 'totalAppointments', 'completedAppointments'));
    }
}
