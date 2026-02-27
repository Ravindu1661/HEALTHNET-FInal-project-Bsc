<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\MedicalCentre;
use App\Models\Doctor;
use Illuminate\Http\Request;

class PatientMedicalCentreController extends Controller
{
    public function index(Request $request)
    {
        $query = MedicalCentre::query()->where('status', 'approved')->with('ownerDoctor');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        $cities = MedicalCentre::where('status', 'approved')
            ->whereNotNull('city')->where('city', '!=', '')
            ->distinct()->orderBy('city')->pluck('city');

        $medicalCentres = $query->orderBy('rating', 'desc')->orderBy('name')->paginate(12);

        return view('patient.medical-centres', compact('medicalCentres', 'cities'));
    }

    public function show($id)
    {
        $medicalCentre = MedicalCentre::with(['user', 'ownerDoctor.user'])
            ->where('status', 'approved')
            ->findOrFail($id);

        $doctors = Doctor::whereHas('workplaces', function($q) use ($id) {
                $q->where('workplace_type', 'medical_centre')
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

        $specializations = is_array($medicalCentre->specializations)
            ? $medicalCentre->specializations
            : json_decode($medicalCentre->specializations, true) ?? [];

        $facilities = is_array($medicalCentre->facilities)
            ? $medicalCentre->facilities
            : json_decode($medicalCentre->facilities, true) ?? [];

        return view('patient.medical-centre-profile', compact(
            'medicalCentre', 'doctors', 'specializations', 'facilities'
        ));
    }
}
