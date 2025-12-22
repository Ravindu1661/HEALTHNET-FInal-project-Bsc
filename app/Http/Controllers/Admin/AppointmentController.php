<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\MedicalCentre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    /**
     * Display a listing of appointments with filters
     */
    public function index(Request $request)
    {
        $query = Appointment::with(['patient.user', 'doctor', 'hospital', 'medicalCentre']);

        // Search functionality
        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('appointment_number', 'LIKE', $search)
                  ->orWhereHas('patient', function ($pq) use ($search) {
                      $pq->where('firstname', 'LIKE', $search)
                         ->orWhere('lastname', 'LIKE', $search);
                  })
                  ->orWhereHas('doctor', function ($dq) use ($search) {
                      $dq->where('firstname', 'LIKE', $search)
                         ->orWhere('lastname', 'LIKE', $search);
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('appointment_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('appointment_date', '<=', $request->date_to);
        }

        // Filter by workplace type
        if ($request->filled('workplace_type')) {
            $query->where('workplace_type', $request->workplace_type);
        }

        $appointments = $query->orderBy('appointment_date', 'desc')
                              ->orderBy('appointment_time', 'desc')
                              ->paginate(15);

        return view('admin.appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new appointment
     */
    public function create()
    {
        $patients = Patient::with('user')->get();
        $doctors = Doctor::where('status', 'approved')->get();
        $hospitals = Hospital::where('status', 'approved')->get();
        $medicalCentres = MedicalCentre::where('status', 'approved')->get();

        return view('admin.appointments.create', compact('patients', 'doctors', 'hospitals', 'medicalCentres'));
    }

    /**
     * Store a newly created appointment
     */
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'workplace_type' => 'required|in:hospital,medicalcentre,private',
            'workplace_id' => 'nullable|integer',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'consultation_fee' => 'required|numeric|min:0',
            'advance_payment' => 'nullable|numeric|min:0',
            'reason' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($request) {
                Appointment::create([
                    'patient_id' => $request->patient_id,
                    'doctor_id' => $request->doctor_id,
                    'workplace_type' => $request->workplace_type,
                    'workplace_id' => $request->workplace_id,
                    'appointment_date' => $request->appointment_date,
                    'appointment_time' => $request->appointment_time,
                    'status' => 'pending',
                    'consultation_fee' => $request->consultation_fee,
                    'advance_payment' => $request->advance_payment ?? 0,
                    'payment_status' => ($request->advance_payment > 0) ? 'partial' : 'unpaid',
                    'reason' => $request->reason,
                    'notes' => $request->notes,
                ]);
            });

            return redirect()->route('admin.appointments.index')
                           ->with('success', 'Appointment created successfully!');
        } catch (\Exception $e) {
            \Log::error('Appointment creation error: ' . $e->getMessage());
            return back()->withInput()
                       ->with('error', 'Failed to create appointment: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified appointment
     */
    public function show($id)
    {
        $appointment = Appointment::with([
            'patient.user',
            'doctor',
            'hospital',
            'medicalCentre',
            'cancelledBy',
            'payment'
        ])->findOrFail($id);

        return view('admin.appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified appointment
     */
    public function edit($id)
    {
        $appointment = Appointment::with(['patient', 'doctor'])->findOrFail($id);
        $patients = Patient::with('user')->get();
        $doctors = Doctor::where('status', 'approved')->get();
        $hospitals = Hospital::where('status', 'approved')->get();
        $medicalCentres = MedicalCentre::where('status', 'approved')->get();

        return view('admin.appointments.edit', compact('appointment', 'patients', 'doctors', 'hospitals', 'medicalCentres'));
    }

    /**
     * Update the specified appointment
     */
    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'workplace_type' => 'required|in:hospital,medicalcentre,private',
            'workplace_id' => 'nullable|integer',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'consultation_fee' => 'required|numeric|min:0',
            'advance_payment' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,confirmed,cancelled,completed,no_show',
            'reason' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($request, $appointment) {
                $appointment->update([
                    'patient_id' => $request->patient_id,
                    'doctor_id' => $request->doctor_id,
                    'workplace_type' => $request->workplace_type,
                    'workplace_id' => $request->workplace_id,
                    'appointment_date' => $request->appointment_date,
                    'appointment_time' => $request->appointment_time,
                    'status' => $request->status,
                    'consultation_fee' => $request->consultation_fee,
                    'advance_payment' => $request->advance_payment ?? 0,
                    'reason' => $request->reason,
                    'notes' => $request->notes,
                ]);

                // Update payment status
                if ($appointment->advance_payment >= $appointment->consultation_fee) {
                    $appointment->update(['payment_status' => 'paid']);
                } elseif ($appointment->advance_payment > 0) {
                    $appointment->update(['payment_status' => 'partial']);
                } else {
                    $appointment->update(['payment_status' => 'unpaid']);
                }
            });

            return redirect()->route('admin.appointments.index')
                           ->with('success', 'Appointment updated successfully!');
        } catch (\Exception $e) {
            \Log::error('Appointment update error: ' . $e->getMessage());
            return back()->withInput()
                       ->with('error', 'Failed to update appointment: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified appointment
     */
    public function destroy($id)
    {
        try {
            $appointment = Appointment::findOrFail($id);
            $appointment->delete();

            return redirect()->route('admin.appointments.index')
                           ->with('success', 'Appointment deleted successfully!');
        } catch (\Exception $e) {
            \Log::error('Appointment deletion error: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete appointment: ' . $e->getMessage());
        }
    }

    /**
     * Confirm appointment
     */
    public function confirm($id)
    {
        try {
            $appointment = Appointment::findOrFail($id);
            $appointment->update(['status' => 'confirmed']);

            return response()->json([
                'success' => true,
                'message' => 'Appointment confirmed successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to confirm appointment!'
            ], 500);
        }
    }

    /**
     * Cancel appointment
     */
    public function cancel(Request $request, $id)
    {
        try {
            $appointment = Appointment::findOrFail($id);
            $appointment->update([
                'status' => 'cancelled',
                'cancelled_by' => auth()->id(),
                'cancellation_reason' => $request->cancellation_reason
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Appointment cancelled successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel appointment!'
            ], 500);
        }
    }

    /**
     * Complete appointment
     */
    public function complete($id)
    {
        try {
            $appointment = Appointment::findOrFail($id);
            $appointment->update(['status' => 'completed']);

            return response()->json([
                'success' => true,
                'message' => 'Appointment marked as completed!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to complete appointment!'
            ], 500);
        }
    }

    /**
     * Mark appointment as no-show
     */
    public function markNoShow($id)
    {
        try {
            $appointment = Appointment::findOrFail($id);
            $appointment->update(['status' => 'no_show']);

            return response()->json([
                'success' => true,
                'message' => 'Appointment marked as no-show!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark as no-show!'
            ], 500);
        }
    }
}
