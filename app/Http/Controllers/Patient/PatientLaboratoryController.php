<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Laboratory;
use App\Models\LabOrder;
use App\Models\LabTest;
use App\Models\LabPackage;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class PatientLaboratoryController extends Controller
{
    // ═══════════════════════════════════════════
    // INDEX — Laboratories List
    // ═══════════════════════════════════════════
    public function index(Request $request)
    {
        $query = Laboratory::where('status', 'approved');

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                  ->orWhere('city', 'like', $search)
                  ->orWhere('address', 'like', $search);
            });
        }

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        $cities = Laboratory::where('status', 'approved')
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        $laboratories = $query
            ->orderBy('rating', 'desc')
            ->orderBy('name', 'asc')
            ->paginate(12);

        return view('patient.laboratories', compact('laboratories', 'cities'));
    }

    // ═══════════════════════════════════════════
    // SHOW — Laboratory Profile
    // ═══════════════════════════════════════════
    public function show($id)
{
    $laboratory = Laboratory::with('user')
        ->where('status', 'approved')
        ->findOrFail($id);

    $services = is_array($laboratory->services)
        ? $laboratory->services
        : (json_decode($laboratory->services, true) ?? []);

    $labTests = LabTest::where('laboratory_id', $laboratory->id)
        ->where('is_active', true)
        ->orderBy('test_category')
        ->orderBy('test_name')
        ->get();

    $labPackages = LabPackage::where('laboratory_id', $laboratory->id)
        ->where('is_active', true)
        ->with('tests')
        ->get();

    // ── Load Reviews ──────────────────────────────────────────────
    $ratings = DB::table('ratings')
        ->join('patients', 'ratings.patient_id', '=', 'patients.id')
        ->where('ratings.ratable_type', 'laboratory')
        ->where('ratings.ratable_id', $laboratory->id)
        ->select(
            'ratings.id',
            'ratings.rating',
            'ratings.review',
            'ratings.created_at',
            'patients.first_name',
            'patients.last_name',
            'patients.profile_image as patient_image'
        )
        ->orderByDesc('ratings.created_at')
        ->paginate(5, ['*'], 'reviews_page');

    // Rating breakdown (1–5 stars count)
    $ratingBreakdown = DB::table('ratings')
        ->where('ratable_type', 'laboratory')
        ->where('ratable_id', $laboratory->id)
        ->selectRaw('rating, COUNT(*) as count')
        ->groupBy('rating')
        ->orderByDesc('rating')
        ->pluck('count', 'rating');

    // Has current patient already reviewed?
    $userRating      = null;
    $canReview       = false;
    $reviewableOrder = null;
    $previousOrders  = null;

    if (Auth::check() && Auth::user()->user_type === 'patient') {
        $patient = Patient::where('user_id', Auth::id())->first();
        if ($patient) {
            $previousOrders = LabOrder::where('patient_id', $patient->id)
                ->where('laboratory_id', $laboratory->id)
                ->with('items')
                ->latest()
                ->take(3)
                ->get();

            // Can review: has a completed order with no review yet
            $reviewableOrder = LabOrder::where('patient_id', $patient->id)
                ->where('laboratory_id', $laboratory->id)
                ->where('status', 'completed')
                ->whereNotExists(function ($q) use ($patient, $laboratory) {
                    $q->from('ratings')
                      ->where('ratings.patient_id',   $patient->id)
                      ->where('ratings.ratable_type', 'laboratory')
                      ->where('ratings.ratable_id',   $laboratory->id)
                      ->whereColumn('ratings.related_id', 'lab_orders.id');
                })
                ->latest()
                ->first();

            $canReview = $reviewableOrder !== null;

            // User's existing rating for this lab
            $userRating = DB::table('ratings')
                ->where('patient_id',   $patient->id)
                ->where('ratable_type', 'laboratory')
                ->where('ratable_id',   $laboratory->id)
                ->orderByDesc('created_at')
                ->first();
        }
    }

    return view('patient.laboratory-profile', compact(
        'laboratory',
        'services',
        'labTests',
        'labPackages',
        'previousOrders',
        'ratings',
        'ratingBreakdown',
        'canReview',
        'reviewableOrder',
        'userRating'
    ));
}


}
