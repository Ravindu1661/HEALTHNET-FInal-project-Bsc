<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\MedicalCentre;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['patient.user', 'doctor.user', 'hospital', 'medicalCentre']);

        // Search
        if ($request->filled('search')) {
            $search = trim((string) $request->search);

            $query->where(function ($q) use ($search) {
                $q->where('appointment_number', 'LIKE', "%{$search}%")
                    ->orWhereHas('patient', function ($pq) use ($search) {
                        $pq->where('firstname', 'LIKE', "%{$search}%")
                           ->orWhere('lastname', 'LIKE', "%{$search}%")
                           ->orWhereHas('user', fn ($uq) => $uq->where('email', 'LIKE', "%{$search}%"));
                    })
                    ->orWhereHas('doctor', function ($dq) use ($search) {
                        $dq->where('firstname', 'LIKE', "%{$search}%")
                           ->orWhere('lastname', 'LIKE', "%{$search}%")
                           ->orWhere('specialization', 'LIKE', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('datefrom')) {
            $query->whereDate('appointment_date', '>=', $request->datefrom);
        }

        if ($request->filled('dateto')) {
            $query->whereDate('appointment_date', '<=', $request->dateto);
        }

        if ($request->filled('workplace_type')) {
            $query->where('workplace_type', $request->workplace_type);
        }

        $appointments = $query
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(15)
            ->appends($request->query());

        return view('admin.appointments.index', compact('appointments'));
    }

    public function create()
    {
        $patients = Patient::with('user')->get();
        $doctors = Doctor::where('status', 'approved')->get();
        $hospitals = Hospital::where('status', 'approved')->get();
        $medicalCentres = MedicalCentre::where('status', 'approved')->get();

        return view('admin.appointments.create', compact('patients', 'doctors', 'hospitals', 'medicalCentres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'doctor_id' => ['required', 'exists:doctors,id'],
            'workplace_type' => ['required', 'in:hospital,medical_centre,private'],
            'workplace_id' => ['nullable', 'integer'],
            'appointment_date' => ['required', 'date'],
            'appointment_time' => ['required'],
            'consultation_fee' => ['nullable', 'numeric', 'min:0'],
            'advance_payment' => ['nullable', 'numeric', 'min:0'],
            'reason' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        try {
            DB::transaction(function () use ($request) {
                $fee = (float) ($request->consultation_fee ?? 0);
                $advance = (float) ($request->advance_payment ?? 0);

                $paymentStatus = 'unpaid';
                if ($fee > 0 && $advance >= $fee) $paymentStatus = 'paid';
                elseif ($advance > 0) $paymentStatus = 'partial';

                Appointment::create([
                    'patient_id' => $request->patient_id,
                    'doctor_id' => $request->doctor_id,
                    'workplace_type' => $request->workplace_type,
                    'workplace_id' => $request->workplace_id,
                    'appointment_date' => $request->appointment_date,
                    'appointment_time' => $request->appointment_time,
                    'status' => 'pending',
                    'reason' => $request->reason,
                    'notes' => $request->notes,
                    'consultation_fee' => $request->consultation_fee,
                    'advance_payment' => $advance,
                    'payment_status' => $paymentStatus,
                ]);
            });

            return redirect()->route('admin.appointments.index')->with('success', 'Appointment created successfully!');
        } catch (\Throwable $e) {
            Log::error('Admin appointment store error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to create appointment: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $appointment = Appointment::with(['patient.user', 'doctor.user', 'hospital', 'medicalCentre', 'cancelledBy', 'payment'])
            ->findOrFail($id);

        return view('admin.appointments.show', compact('appointment'));
    }

    public function edit($id)
    {
        $appointment = Appointment::findOrFail($id);

        $patients = Patient::with('user')->get();
        $doctors = Doctor::where('status', 'approved')->get();
        $hospitals = Hospital::where('status', 'approved')->get();
        $medicalCentres = MedicalCentre::where('status', 'approved')->get();

        return view('admin.appointments.edit', compact('appointment', 'patients', 'doctors', 'hospitals', 'medicalCentres'));
    }

    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'doctor_id' => ['required', 'exists:doctors,id'],
            'workplace_type' => ['required', 'in:hospital,medical_centre,private'],
            'workplace_id' => ['nullable', 'integer'],
            'appointment_date' => ['required', 'date'],
            'appointment_time' => ['required'],
            'consultation_fee' => ['nullable', 'numeric', 'min:0'],
            'advance_payment' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:pending,confirmed,cancelled,completed,noshow'],
            'reason' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        try {
            DB::transaction(function () use ($request, $appointment) {
                $fee = (float) ($request->consultation_fee ?? 0);
                $advance = (float) ($request->advance_payment ?? 0);

                $paymentStatus = 'unpaid';
                if ($fee > 0 && $advance >= $fee) $paymentStatus = 'paid';
                elseif ($advance > 0) $paymentStatus = 'partial';

                $appointment->update([
                    'patient_id' => $request->patient_id,
                    'doctor_id' => $request->doctor_id,
                    'workplace_type' => $request->workplace_type,
                    'workplace_id' => $request->workplace_id,
                    'appointment_date' => $request->appointment_date,
                    'appointment_time' => $request->appointment_time,
                    'status' => $request->status,
                    'reason' => $request->reason,
                    'notes' => $request->notes,
                    'consultation_fee' => $request->consultation_fee,
                    'advance_payment' => $advance,
                    'payment_status' => $paymentStatus,
                ]);
            });

            return redirect()->route('admin.appointments.index')->with('success', 'Appointment updated successfully!');
        } catch (\Throwable $e) {
            Log::error('Admin appointment update error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to update appointment: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            Appointment::findOrFail($id)->delete();
            return redirect()->route('admin.appointments.index')->with('success', 'Appointment deleted successfully!');
        } catch (\Throwable $e) {
            Log::error('Admin appointment delete error: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete appointment: ' . $e->getMessage());
        }
    }

    // AJAX actions
    public function confirm($id)
    {
        try {
            Appointment::findOrFail($id)->update(['status' => 'confirmed']);
            return response()->json(['success' => true, 'message' => 'Appointment confirmed successfully!']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Failed to confirm appointment!'], 500);
        }
    }

    public function cancel(Request $request, $id)
    {
        try {
            Appointment::findOrFail($id)->update([
                'status' => 'cancelled',
                'cancelled_by' => auth()->id(),
                'cancellation_reason' => $request->input('cancellation_reason'),
            ]);

            return response()->json(['success' => true, 'message' => 'Appointment cancelled successfully!']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Failed to cancel appointment!'], 500);
        }
    }

    public function complete($id)
    {
        try {
            Appointment::findOrFail($id)->update(['status' => 'completed']);
            return response()->json(['success' => true, 'message' => 'Appointment marked as completed!']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Failed to complete appointment!'], 500);
        }
    }

    public function markNoShow($id)
    {
        try {
            Appointment::findOrFail($id)->update(['status' => 'noshow']);
            return response()->json(['success' => true, 'message' => 'Appointment marked as no-show!']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Failed to mark as no-show!'], 500);
        }
    }
}
