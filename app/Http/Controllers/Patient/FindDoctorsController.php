<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\MedicalCentre;
use App\Models\Patient;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FindDoctorsController extends Controller
{
    // ══════════════════════════════════════════
    // INDEX — Doctors List
    // ══════════════════════════════════════════
    public function index(Request $request)
    {
        $query = Doctor::with([
            'user',
            'workplaces' => function ($q) {
                $q->where('status', 'approved')
                  ->with(['hospital', 'medicalCentre']);
            }
        ])
        ->where('status', 'approved')
        ->whereHas('user', fn($q) => $q->where('status', 'active'));

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$search}%")
                  ->orWhere('specialization', 'like', "%{$search}%")
                  ->orWhereHas('workplaces.hospital', fn($wq) => $wq->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('workplaces.medicalCentre', fn($wq) => $wq->where('name', 'like', "%{$search}%"));
            });
        }

        // Filter by specialty
        if ($request->filled('specialty')) {
            $query->where('specialization', $request->specialty);
        }

        // Filter by city
        if ($request->filled('location')) {
            $location = $request->location;
            $query->whereHas('workplaces', function ($q) use ($location) {
                $q->where('status', 'approved')
                  ->where(function ($wq) use ($location) {
                      $wq->whereHas('hospital', fn($h) => $h->where('city', $location))
                         ->orWhereHas('medicalCentre', fn($m) => $m->where('city', $location));
                  });
            });
        }

        $specialties = Doctor::where('status', 'approved')
            ->whereNotNull('specialization')
            ->where('specialization', '!=', '')
            ->distinct()->orderBy('specialization')->pluck('specialization');

        $cities = Hospital::where('status', 'approved')
            ->whereNotNull('city')->where('city', '!=', '')->distinct()->pluck('city')
            ->merge(
                MedicalCentre::where('status', 'approved')
                    ->whereNotNull('city')->where('city', '!=', '')->distinct()->pluck('city')
            )
            ->unique()->sort()->values();

        $doctors = $query->orderBy('rating', 'desc')
                         ->orderBy('created_at', 'desc')
                         ->paginate(12);

        return view('patient.find-doctors', compact('doctors', 'specialties', 'cities'));
    }

    // ══════════════════════════════════════════
    // SHOW — Doctor Profile
    // ══════════════════════════════════════════
    public function show($id)
    {
        $doctor = Doctor::with([
            'user',
            'workplaces' => function ($q) {
                $q->where('status', 'approved')
                  ->with(['hospital', 'medicalCentre']);
            },
        ])
        ->where('status', 'approved')
        ->whereHas('user', fn($q) => $q->where('status', 'active'))
        ->findOrFail($id);

        $workplaces = $doctor->workplaces;

        // Reviews from ratings table
        $reviews = Rating::with('patient.user')
            ->where('ratable_type', 'doctor')
            ->where('ratable_id', $id)
            ->latest()
            ->paginate(5);

        $totalAppointments = DB::table('appointments')
            ->where('doctor_id', $id)->count();

        $completedCount = DB::table('appointments')
            ->where('doctor_id', $id)->where('status', 'completed')->count();

        // ── Review permission check ──
        $canReview       = false;
        $alreadyReviewed = false;
        $patient         = null;

        if (Auth::check()) {
            // Migration confirms column: user_type (NOT usertype)
            $userType = Auth::user()->user_type;

            if ($userType === 'patient') {
                $patient = Patient::where('user_id', Auth::id())->first();

                if ($patient) {
                    // Must have at least one completed appointment with this doctor
                    $hasCompleted = DB::table('appointments')
                        ->where('patient_id', $patient->id)
                        ->where('doctor_id', $id)
                        ->where('status', 'completed')
                        ->exists();

                    $alreadyReviewed = Rating::where('patient_id', $patient->id)
                        ->where('ratable_type', 'doctor')
                        ->where('ratable_id', $id)
                        ->exists();

                    $canReview = $hasCompleted && !$alreadyReviewed;
                }
            }
        }

        return view('patient.doctor-profile', compact(
            'doctor',
            'workplaces',
            'reviews',
            'totalAppointments',
            'completedCount',
            'canReview',
            'alreadyReviewed',
            'patient'
        ));
    }

    // ══════════════════════════════════════════
    // STORE REVIEW
    // ══════════════════════════════════════════
    public function storeReview(Request $request, $id)
    {
        // Must be logged in
        if (!Auth::check() || Auth::user()->user_type !== 'patient') {
            return back()->with('error', 'You must be logged in as a patient to leave a review.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        $patient = Patient::where('user_id', Auth::id())->firstOrFail();

        // Verify completed appointment
        $hasCompleted = DB::table('appointments')
            ->where('patient_id', $patient->id)
            ->where('doctor_id', $id)
            ->where('status', 'completed')
            ->exists();

        if (!$hasCompleted) {
            return back()->with('error', 'You can only review a doctor after completing an appointment.');
        }

        // Prevent duplicate review
        $alreadyReviewed = Rating::where('patient_id', $patient->id)
            ->where('ratable_type', 'doctor')
            ->where('ratable_id', $id)
            ->exists();

        if ($alreadyReviewed) {
            return back()->with('error', 'You have already submitted a review for this doctor.');
        }

        // Save review
        Rating::create([
            'patient_id'   => $patient->id,
            'ratable_type' => 'doctor',
            'ratable_id'   => $id,
            'rating'       => $request->rating,
            'review'       => $request->review,
            'related_type' => 'appointment',
            'related_id'   => null,
        ]);

        // Recalculate doctor rating & total_ratings
        $avg = Rating::where('ratable_type', 'doctor')
                     ->where('ratable_id', $id)
                     ->avg('rating');

        $cnt = Rating::where('ratable_type', 'doctor')
                     ->where('ratable_id', $id)
                     ->count();

        Doctor::where('id', $id)->update([
            'rating'        => round($avg, 2),
            'total_ratings' => $cnt,
        ]);

        return back()->with('success', 'Thank you! Your review has been submitted.');
    }
}
