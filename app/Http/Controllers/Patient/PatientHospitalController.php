<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientHospitalController extends Controller
{
    /**
     * Display a listing of hospitals
     */
    public function index(Request $request)
    {
        $query = Hospital::query()->where('status', 'approved');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('city') && $request->city !== 'All Cities') {
            $query->where('city', $request->city);
        }

        $cities = Hospital::where('status', 'approved')
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        // enum: government, private — DB query නොකර hardcode
        $types = ['government', 'private'];

        $total = (clone $query)->count();

        $hospitals = $query->orderByDesc('rating')
                           ->orderBy('name')
                           ->paginate(12)
                           ->withQueryString();

        return view('patient.hospitals', compact('hospitals', 'cities', 'types', 'total'));
    }

    /**
     * Display the specified hospital profile
     */
    public function show($id)
    {
        $hospital = Hospital::where('status', 'approved')
            ->findOrFail($id);

        $doctors = Doctor::whereHas('workplaces', function ($q) use ($id) {
                $q->where('workplace_type', 'hospital')
                  ->where('workplace_id', $id)
                  ->where('status', 'approved');
            })
            ->where('status', 'approved')
            ->whereHas('user', function ($q) {
                $q->where('status', 'active');
            })
            ->orderByDesc('rating')
            ->limit(12)
            ->get();

        // JSON columns — Hospital model ඇතුළේ cast array නිසා direct access හරි
        $specializations = $hospital->specializations ?? [];
        if (!is_array($specializations)) {
            $specializations = json_decode($specializations, true) ?? [];
        }

        $facilities = $hospital->facilities ?? [];
        if (!is_array($facilities)) {
            $facilities = json_decode($facilities, true) ?? [];
        }

        // Reviews — status column නෑ, related_type null ලෙස hospital direct reviews
        $reviews = Rating::with('patient.user')
            ->where('ratable_type', 'hospital')
            ->where('ratable_id', $id)
            ->whereNull('related_type')
            ->latest()
            ->get();

        // Already reviewed check
        $hasReviewed = false;
        if (Auth::check()) {
            $patient = Patient::where('user_id', Auth::id())->first();
            if ($patient) {
                $hasReviewed = Rating::where('ratable_type', 'hospital')
                    ->where('ratable_id', $id)
                    ->where('patient_id', $patient->id)
                    ->whereNull('related_type')
                    ->exists();
            }
        }

        return view('patient.hospital-profile', compact(
            'hospital',
            'doctors',
            'specializations',
            'facilities',
            'reviews',
            'hasReviewed'
        ));
    }

    /**
     * Store a hospital review
     */
    public function storeReview(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        $hospital = Hospital::where('status', 'approved')->findOrFail($id);

        $patient = Patient::where('user_id', Auth::id())->first();

        if (!$patient) {
            return back()->with('error', 'Patient profile not found.');
        }

        // Duplicate check — unique key: patient_id + ratable_type + ratable_id + related_type(null) + related_id(null)
        $exists = Rating::where('ratable_type', 'hospital')
            ->where('ratable_id', $id)
            ->where('patient_id', $patient->id)
            ->whereNull('related_type')
            ->exists();

        if ($exists) {
            return back()->with('error', 'You have already submitted a review for this hospital.');
        }

        // ✅ status field නෑ — DB columns පමණක්
        Rating::create([
            'patient_id'   => $patient->id,
            'ratable_type' => 'hospital',
            'ratable_id'   => $id,
            'rating'       => (int) $request->rating,
            'review'       => $request->review ?? null,
            'related_type' => null,
            'related_id'   => null,
        ]);

        // Hospital avg rating update
        $this->updateHospitalRating($hospital);

        return back()->with('success', 'Thank you! Your review has been submitted.');
    }

    /**
     * Recalculate hospital average rating from ratings table
     */
    private function updateHospitalRating(Hospital $hospital): void
    {
        $stats = Rating::where('ratable_type', 'hospital')
            ->where('ratable_id', $hospital->id)
            ->whereNull('related_type')
            ->selectRaw('ROUND(AVG(rating), 2) as avg_rating, COUNT(*) as total')
            ->first();

        $hospital->update([
            'rating'        => $stats->avg_rating ?? 0.00,
            'total_ratings' => $stats->total ?? 0,
        ]);
    }
}
