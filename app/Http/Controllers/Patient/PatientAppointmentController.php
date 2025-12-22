<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientAppointmentController extends Controller
{
    /**
     * Show the form for creating a new appointment
     */
    public function create(Request $request)
    {
        $doctorId = $request->get('doctor_id');

        if (!$doctorId) {
            return redirect()->route('patient.doctors')
                           ->with('error', 'Please select a doctor first.');
        }

        // Get doctor with workplaces
        $doctor = Doctor::with(['workplaces' => function($q) {
                $q->where('status', 'approved')
                  ->with(['hospital', 'medicalCentre']);
            }])
            ->where('status', 'approved')
            ->findOrFail($doctorId);

        $workplaces = $doctor->workplaces;

        return view('patient.create-appointment', compact('doctor', 'workplaces'));
    }

    /**
     * Store a newly created appointment
     */
    public function store(Request $request, $doctorId)
    {
        $request->validate([
            'workplace_id' => 'required|exists:doctor_workplaces,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'reason' => 'required|string|max:1000',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Get patient record
        $patient = Patient::where('user_id', Auth::id())->first();

        if (!$patient) {
            return redirect()->back()
                           ->with('error', 'Patient profile not found. Please complete your profile first.');
        }

        // Create appointment
        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctorId,
            'doctor_workplace_id' => $request->workplace_id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'reason' => $request->reason,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        return redirect()->route('patient.appointments.index')
                       ->with('success', 'Appointment booked successfully! The doctor will confirm your appointment soon.');
    }

    /**
     * Display patient's appointments
     */
    public function index()
    {
        $patient = Patient::where('user_id', Auth::id())->first();

        if (!$patient) {
            return redirect()->route('patient.dashboard')
                           ->with('error', 'Please complete your profile first.');
        }

        $appointments = Appointment::where('patient_id', $patient->id)
            ->with(['doctor.user', 'workplace'])
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(10);

        return view('patient.appointments', compact('appointments'));
    }
}
