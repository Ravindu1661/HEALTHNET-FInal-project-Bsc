<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorPatientController extends Controller
{
    public function index()
    {
        return view('doctor.patients.index');
    }

    public function show($id)
    {
        return view('doctor.patients.show');
    }

    public function history($id)
    {
        return view('doctor.patients.history');
    }

    public function addPrescription($id)
    {
        return response()->json(['success' => true]);
    }

    public function addLabRequest($id)
    {
        return response()->json(['success' => true]);
    }
}
