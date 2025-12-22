<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Rating;

class PharmacyRatingController extends Controller
{
    /**
     * Display ratings and reviews
     */
    public function index(Request $request)
    {
        $pharmacy = Auth::user()->pharmacy;

        $query = Rating::where('ratable_type', 'pharmacy')
            ->where('ratable_id', $pharmacy->id)
            ->with('patient.user');

        // Filter by rating
        if ($request->has('rating') && $request->rating != '') {
            $query->where('rating', $request->rating);
        }

        $ratings = $query->latest()->paginate(20);

        // Statistics
        $averageRating = Rating::where('ratable_type', 'pharmacy')
            ->where('ratable_id', $pharmacy->id)
            ->avg('rating');

        $totalRatings = Rating::where('ratable_type', 'pharmacy')
            ->where('ratable_id', $pharmacy->id)
            ->count();

        $ratingDistribution = Rating::where('ratable_type', 'pharmacy')
            ->where('ratable_id', $pharmacy->id)
            ->select('rating', DB::raw('count(*) as count'))
            ->groupBy('rating')
            ->pluck('count', 'rating');

        return view('pharmacy.ratings.index', compact('ratings', 'averageRating', 'totalRatings', 'ratingDistribution'));
    }

    /**
     * Show rating details
     */
    public function show(Rating $rating)
    {
        // Check authorization
        if ($rating->ratable_type !== 'pharmacy' || $rating->ratable_id !== Auth::user()->pharmacy->id) {
            abort(403, 'Unauthorized action.');
        }

        $rating->load('patient.user');

        return view('pharmacy.ratings.show', compact('rating'));
    }
}
