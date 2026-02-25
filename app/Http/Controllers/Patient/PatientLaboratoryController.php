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

        $laboratories = $query->orderBy('rating', 'desc')
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

        // Available tests for this lab
        $labTests = LabTest::where('laboratory_id', $laboratory->id)
            ->where('is_active', true)
            ->orderBy('test_category')
            ->orderBy('test_name')
            ->get();

        // Available packages
        $labPackages = LabPackage::where('laboratory_id', $laboratory->id)
            ->where('is_active', true)
            ->with('tests')
            ->get();

        // Patient's previous orders from this lab
        $previousOrders = null;
        if (Auth::check() && Auth::user()->usertype === 'patient') {
            $patient = Patient::where('user_id', Auth::id())->first();
            if ($patient) {
                $previousOrders = LabOrder::where('patient_id', $patient->id)
                    ->where('laboratory_id', $laboratory->id)
                    ->with('items')
                    ->orderBy('created_at', 'desc')
                    ->take(3)
                    ->get();
            }
        }

        return view('patient.laboratory-profile', compact(
            'laboratory', 'services', 'labTests', 'labPackages', 'previousOrders'
        ));
    }
}
