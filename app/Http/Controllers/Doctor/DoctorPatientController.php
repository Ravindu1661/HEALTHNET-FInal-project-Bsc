<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DoctorPatientController extends Controller
{
    private function getDoctor()
    {
        return Doctor::where('user_id', Auth::id())->firstOrFail();
    }

    // ══════════════════════════════════════════
    //  INDEX — Patient List
    // ══════════════════════════════════════════
    public function index(Request $request)
    {
        $doctor = $this->getDoctor();

        $query = DB::table('appointments')
            ->join('patients', 'appointments.patient_id', '=', 'patients.id')
            ->where('appointments.doctor_id', $doctor->id)
            ->select(
                'patients.id',
                DB::raw("CONCAT(patients.first_name, ' ', patients.last_name) as name"),
                'patients.phone',
                'patients.gender',
                'patients.blood_group',
                'patients.date_of_birth',
                'patients.profile_image', // ✅ ADD
                DB::raw('MAX(appointments.appointment_date) as last_visit'),
                DB::raw('COUNT(appointments.id) as visit_count'),
                DB::raw("SUM(CASE WHEN appointments.status = 'completed' THEN 1 ELSE 0 END) as completed_count")
            )
            ->groupBy(
                'patients.id',
                'patients.first_name',
                'patients.last_name',
                'patients.phone',
                'patients.gender',
                'patients.blood_group',
                'patients.date_of_birth',
                'patients.profile_image'  // ✅ ADD
            );

        if ($request->filled('search')) {
            $s = '%' . $request->search . '%';
            $query->where(function ($q) use ($s) {
                $q->where('patients.first_name', 'like', $s)
                ->orWhere('patients.last_name', 'like', $s)
                ->orWhere('patients.phone', 'like', $s);
            });
        }

        $patients = $query->orderByDesc('last_visit')->paginate(15)->appends($request->query());
        $totalPatients = DB::table('appointments')->where('doctor_id', $doctor->id)->distinct('patient_id')->count('patient_id');

        return view('doctor.patients.index', compact('patients', 'totalPatients', 'doctor'));
    }


    // ══════════════════════════════════════════
    //  SHOW — Patient Detail
    // ══════════════════════════════════════════
   public function show($id)
    {
        $doctor = $this->getDoctor();

        // Doctor ගේ patient කෙනෙක්ද check
        $hasVisited = DB::table('appointments')
            ->where('doctor_id', $doctor->id)
            ->where('patient_id', $id)
            ->exists();

        if (!$hasVisited) abort(403, 'Access denied.');

        // ✅ Fix — firstOrFail() → first() + manual abort
        $patient = DB::table('patients')
            ->join('users', 'patients.user_id', '=', 'users.id')
            ->where('patients.id', $id)
            ->select(
                'patients.*',
                'users.email',
                'users.status as account_status'
            )
            ->first();

        if (!$patient) abort(404, 'Patient not found.');

        // Appointment history with this doctor
        $appointments = DB::table('appointments')
            ->leftJoin('hospitals', function ($j) {
                $j->on('appointments.workplace_id', '=', 'hospitals.id')
                ->where('appointments.workplace_type', '=', 'hospital');
            })
            ->leftJoin('medical_centres', function ($j) {
                $j->on('appointments.workplace_id', '=', 'medical_centres.id')
                ->where('appointments.workplace_type', '=', 'medicalcentre');
            })
            ->where('appointments.doctor_id', $doctor->id)
            ->where('appointments.patient_id', $id)
            ->select(
                'appointments.*',
                DB::raw("COALESCE(hospitals.name, medical_centres.name, 'Private Clinic') as location")
            )
            ->orderBy('appointments.appointment_date', 'desc')
            ->get();

        return view('doctor.patients.show', compact('patient', 'appointments', 'doctor'));
    }


    // ══════════════════════════════════════════
    //  HISTORY — Appointment History
    // ══════════════════════════════════════════
   public function history(Request $request, $id)
{
    $doctor = $this->getDoctor();

    // Access check — doctor ගේ patient කෙනෙක්ද
    $hasVisited = DB::table('appointments')
        ->where('doctor_id', $doctor->id)
        ->where('patient_id', $id)
        ->exists();

    if (!$hasVisited) abort(403, 'Access denied.');

    // Patient info
    $patient = DB::table('patients')
        ->join('users', 'patients.user_id', '=', 'users.id')
        ->where('patients.id', $id)
        ->select(
            'patients.*',
            'users.email',
            'users.status as account_status'
        )
        ->first();

    if (!$patient) abort(404, 'Patient not found.');

    // Appointment history query
    $query = DB::table('appointments')
        ->leftJoin('hospitals', function ($j) {
            $j->on('appointments.workplace_id', '=', 'hospitals.id')
              ->where('appointments.workplace_type', '=', 'hospital');
        })
        ->leftJoin('medical_centres', function ($j) {
            $j->on('appointments.workplace_id', '=', 'medical_centres.id')
              ->where('appointments.workplace_type', '=', 'medicalcentre');
        })
        ->where('appointments.doctor_id', $doctor->id)
        ->where('appointments.patient_id', $id)
        ->select(
            'appointments.id',
            'appointments.appointment_number',
            'appointments.appointment_date',
            'appointments.appointment_time',
            'appointments.status',
            'appointments.payment_status',
            'appointments.consultation_fee',
            'appointments.advance_payment',
            'appointments.workplace_type',
            'appointments.reason',
            'appointments.notes',
            'appointments.cancellation_reason',
            DB::raw("COALESCE(hospitals.name, medical_centres.name, 'Private Clinic') as location")
        )
        ->orderBy('appointments.appointment_date', 'desc');

    // Status filter
    if ($request->filled('status')) {
        $query->where('appointments.status', $request->status);
    }

    $appointments = $query->get();

    return view('doctor.patients.history', compact('patient', 'appointments', 'doctor'));
}

    // ══════════════════════════════════════════
    //  ADD PRESCRIPTION — AJAX
    // ══════════════════════════════════════════
   public function addPrescription(Request $request, $id)
    {
        try {
            $doctor = $this->getDoctor();

            $request->validate([
                'medications'  => 'required|string',
                'instructions' => 'nullable|string',
                'appointment_id' => 'nullable|exists:appointments,id',
            ]);

            DB::table('prescriptions')->insert([
                'doctor_id'      => $doctor->id,
                'patient_id'     => $id,
                'appointment_id' => $request->appointment_id,
                'medications'    => $request->medications,
                'instructions'   => $request->instructions,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Prescription added successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add prescription.'
            ], 500);
        }
    }

    public function addLabRequest(Request $request, $id)
    {
        try {
            $doctor = $this->getDoctor();

            $request->validate([
                'test_name'      => 'required|string',
                'notes'          => 'nullable|string',
                'appointment_id' => 'nullable|exists:appointments,id',
            ]);

            DB::table('lab_requests')->insert([
                'doctor_id'      => $doctor->id,
                'patient_id'     => $id,
                'appointment_id' => $request->appointment_id,
                'test_name'      => $request->test_name,
                'notes'          => $request->notes,
                'status'         => 'pending',
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Lab request added successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add lab request.'
            ], 500);
        }
    }

    public function getRecentPatients()
    {
        try {
            $doctor = $this->getDoctor();

            $patients = DB::table('patients')
                ->join('appointments', 'appointments.patient_id', '=', 'patients.id')
                ->where('appointments.doctor_id', $doctor->id)
                ->select(
                    'patients.id',
                    'patients.firstname',
                    'patients.lastname',
                    'patients.phone',
                    'patients.profile_image',
                    DB::raw('MAX(appointments.appointment_date) as last_visit')
                )
                ->groupBy(
                    'patients.id',
                    'patients.firstname',
                    'patients.lastname',
                    'patients.phone',
                    'patients.profile_image'
                )
                ->orderBy('last_visit', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($p) {
                    return [
                        'id'         => $p->id,
                        'name'       => trim($p->firstname . ' ' . $p->lastname),
                        'phone'      => $p->phone,
                        'last_visit' => Carbon::parse($p->last_visit)->format('d M Y'),
                        'avatar'     => $p->profile_image
                                        ? asset('storage/' . $p->profile_image)
                                        : asset('images/default-avatar.png'),
                    ];
                });

            return response()->json([
                'success'  => true,
                'patients' => $patients
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success'  => false,
                'patients' => []
            ]);
        }
    }

}
