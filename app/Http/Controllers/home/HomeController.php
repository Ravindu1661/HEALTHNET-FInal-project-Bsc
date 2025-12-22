<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\Laboratory;
use App\Models\Pharmacy;
use App\Models\MedicalCentre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Show the main patient home page after login
     */
    public function mainPage()
    {
        // Check if user is authenticated and is a patient
        if (!Auth::check() || Auth::user()->user_type !== 'patient') {
            return redirect()->route('login');
        }

        // Get featured doctors (top rated, approved, active)
        $featuredDoctors = Doctor::with([
            'user',
            'workplaces' => function($q) {
                $q->where('status', 'approved')
                  ->with(['hospital', 'medicalCentre']);
            }
        ])
        ->where('status', 'approved')
        ->whereHas('user', function($q) {
            $q->where('status', 'active');
        })
        ->orderBy('rating', 'desc')
        ->orderBy('total_ratings', 'desc')
        ->take(8)
        ->get();

        // Get featured hospitals (top 6)
        $featuredHospitals = Hospital::with('user')
            ->where('status', 'approved')
            ->whereHas('user', function($q) {
                $q->where('status', 'active');
            })
            ->orderBy('rating', 'desc')
            ->take(6)
            ->get();

        // Get featured laboratories (top 6)
        $featuredLaboratories = Laboratory::with('user')
            ->where('status', 'approved')
            ->whereHas('user', function($q) {
                $q->where('status', 'active');
            })
            ->orderBy('rating', 'desc')
            ->take(6)
            ->get();

        // Get featured pharmacies (top 6)
        $featuredPharmacies = Pharmacy::with('user')
            ->where('status', 'approved')
            ->whereHas('user', function($q) {
                $q->where('status', 'active');
            })
            ->orderBy('rating', 'desc')
            ->take(6)
            ->get();

        // Get featured medical centres (top 6)
        $featuredMedicalCentres = MedicalCentre::with('user')
            ->where('status', 'approved')
            ->whereHas('user', function($q) {
                $q->where('status', 'active');
            })
            ->orderBy('rating', 'desc')
            ->take(6)
            ->get();

        // Get authenticated patient
        $patient = Auth::user()->patient;

        return view('Main_Home', compact(
            'featuredDoctors',
            'featuredHospitals',
            'featuredLaboratories',
            'featuredPharmacies',
            'featuredMedicalCentres',
            'patient'
        ));
    }
}
