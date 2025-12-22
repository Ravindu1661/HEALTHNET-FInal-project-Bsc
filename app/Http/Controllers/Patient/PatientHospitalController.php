<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use App\Models\Doctor;
use Illuminate\Http\Request;

class PatientHospitalController extends Controller
{
    /**
     * Display a listing of hospitals
     */
    public function index(Request $request)
    {
        $query = Hospital::query();

        // Only approved hospitals
        $query->where('status', 'approved');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by city
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        // Get unique cities for filter
        $cities = Hospital::where('status', 'approved')
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        // Paginate results
        $hospitals = $query->orderBy('rating', 'desc')
                          ->orderBy('name', 'asc')
                          ->paginate(12);

        return view('patient.hospitals', compact('hospitals', 'cities'));
    }

    /**
     * Display the specified hospital
     */
    public function show($id)
    {
        // Get hospital with relationships
        $hospital = Hospital::with(['user'])
            ->where('status', 'approved')
            ->findOrFail($id);

        // Get doctors working at this hospital
        $doctors = Doctor::whereHas('workplaces', function($q) use ($id) {
                $q->where('workplace_type', 'hospital')
                  ->where('workplace_id', $id)
                  ->where('status', 'approved');
            })
            ->where('status', 'approved')
            ->whereHas('user', function($q) {
                $q->where('status', 'active');
            })
            ->with(['user', 'workplaces'])
            ->orderBy('rating', 'desc')
            ->limit(10)
            ->get();

        // Parse specializations
        $specializations = is_array($hospital->specializations)
            ? $hospital->specializations
            : json_decode($hospital->specializations, true) ?? [];

        // Parse facilities
        $facilities = is_array($hospital->facilities)
            ? $hospital->facilities
            : json_decode($hospital->facilities, true) ?? [];

        return view('patient.hospital-profile', compact('hospital', 'doctors', 'specializations', 'facilities'));
    }
}
