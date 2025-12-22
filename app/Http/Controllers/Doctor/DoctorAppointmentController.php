<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DoctorAppointmentController extends Controller
{
    public function index()
    {
        $doctor = Auth::user()->doctor;
        
        $appointments = DB::table('appointments')
            ->join('patients', 'appointments.patient_id', '=', 'patients.id')
            ->where('appointments.doctor_id', $doctor->id)
            ->select(
                'appointments.*',
                DB::raw("CONCAT(patients.first_name, ' ', patients.last_name) as patient_name"),
                'patients.phone as patient_phone'
            )
            ->orderBy('appointments.appointment_date', 'desc')
            ->paginate(15);
        
        return view('doctor.appointments.index', compact('appointments'));
    }

    public function show($id)
    {
        // Show appointment details
        return view('doctor.appointments.show');
    }

    public function confirm($id)
    {
        // Confirm appointment
        return response()->json(['success' => true]);
    }

    public function cancel($id)
    {
        // Cancel appointment
        return response()->json(['success' => true]);
    }

    public function complete($id)
    {
        // Complete appointment
        return response()->json(['success' => true]);
    }

    public function reschedule($id)
    {
        // Reschedule appointment
        return response()->json(['success' => true]);
    }

    public function addNotes($id)
    {
        // Add notes to appointment
        return response()->json(['success' => true]);
    }
}
