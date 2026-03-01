<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DoctorReviewController extends Controller
{
    private function getDoctor()
    {
        return Doctor::where('user_id', Auth::id())->firstOrFail();
    }

    // ══════════════════════════════════════════
    //  INDEX
    // ══════════════════════════════════════════
    public function index(Request $request)
    {
        $doctor = $this->getDoctor();

        $query = DB::table('ratings')
            ->join('patients', 'ratings.patient_id', '=', 'patients.id')
            ->where('ratings.ratable_type', 'doctor')
            ->where('ratings.ratable_id', $doctor->id)
            ->select(
                'ratings.id',
                'ratings.rating',
                'ratings.review',
                'ratings.created_at',
                DB::raw("CONCAT(patients.first_name, ' ', patients.last_name) as patient_name"),
                DB::raw("DATE_FORMAT(ratings.created_at, '%d %b %Y') as date")
            );

        if ($request->filled('rating')) {
            $query->where('ratings.rating', $request->rating);
        }

        $reviews = $query->orderByDesc('ratings.created_at')
                         ->paginate(10)
                         ->appends($request->query());

        // Stats
        $avgRating   = DB::table('ratings')
            ->where('ratable_type', 'doctor')
            ->where('ratable_id', $doctor->id)
            ->avg('rating') ?? 0;

        $totalReviews = DB::table('ratings')
            ->where('ratable_type', 'doctor')
            ->where('ratable_id', $doctor->id)
            ->count();

        $breakdown = DB::table('ratings')
            ->where('ratable_type', 'doctor')
            ->where('ratable_id', $doctor->id)
            ->select(DB::raw('rating, COUNT(*) as count'))
            ->groupBy('rating')
            ->pluck('count', 'rating');

        return view('doctor.reviews.index', compact(
            'reviews', 'avgRating', 'totalReviews', 'breakdown', 'doctor'
        ));
    }

    // ══════════════════════════════════════════
    //  SHOW
    // ══════════════════════════════════════════
    public function show($id)
    {
        $doctor = $this->getDoctor();

        $review = DB::table('ratings')
            ->join('patients', 'ratings.patient_id', '=', 'patients.id')
            ->where('ratings.id', $id)
            ->where('ratings.ratable_type', 'doctor')
            ->where('ratings.ratable_id', $doctor->id)
            ->select(
                'ratings.*',
                DB::raw("CONCAT(patients.first_name, ' ', patients.last_name) as patient_name"),
                'patients.phone as patient_phone'
            )
            ->firstOrFail();

        return view('doctor.reviews.show', compact('review', 'doctor'));
    }

    // ══════════════════════════════════════════
    //  REPLY — AJAX
    // ══════════════════════════════════════════
    public function reply(Request $request, $id)
    {
        $request->validate(['reply' => 'required|string|max:1000']);

        try {
            $doctor = $this->getDoctor();

            // Verify review belongs to this doctor
            $exists = DB::table('ratings')
                ->where('id', $id)
                ->where('ratable_type', 'doctor')
                ->where('ratable_id', $doctor->id)
                ->exists();

            if (!$exists) {
                return response()->json(['success' => false, 'message' => 'Review not found.'], 404);
            }

            // ratings table schema has no reply column — store as note or add migration
            // For now: notify patient about doctor response
            $patientUserId = DB::table('ratings')
                ->join('patients', 'ratings.patient_id', '=', 'patients.id')
                ->where('ratings.id', $id)
                ->value('patients.user_id');

            if ($patientUserId) {
                DB::table('notifications')->insert([
                    'notifiable_type' => 'App\Models\User',
                    'notifiable_id'   => $patientUserId,
                    'type'            => 'general',
                    'title'           => 'Doctor Replied to Your Review',
                    'message'         => 'Dr. ' . $doctor->firstname . ' ' . $doctor->lastname .
                                         ' replied to your review: "' . $request->reply . '"',
                    'related_type'    => null,
                    'related_id'      => null,
                    'is_read'         => false,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Reply sent to patient.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
