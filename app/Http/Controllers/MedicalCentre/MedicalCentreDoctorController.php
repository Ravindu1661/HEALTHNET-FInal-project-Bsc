<?php

namespace App\Http\Controllers\MedicalCentre;

use App\Http\Controllers\Controller;
use App\Models\MedicalCentre;
use App\Models\Doctor;
use App\Models\DoctorWorkplace;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MedicalCentreDoctorController extends Controller
{
    // ═══════════════════════════════════════════
    // HELPER
    // ═══════════════════════════════════════════
    private function getMedicalCentre(): MedicalCentre
    {
        return MedicalCentre::where('user_id', Auth::id())->firstOrFail();
    }

    // ═══════════════════════════════════════════
    // INDEX — list all doctors
    // ═══════════════════════════════════════════
    public function index(Request $request)
    {
        $mc     = $this->getMedicalCentre();
        $search = $request->input('search', '');
        $status = $request->input('status', '');
        $type   = $request->input('type', '');

        $query = DB::table('doctor_workplaces')
            ->join('doctors', 'doctor_workplaces.doctor_id', '=', 'doctors.id')
            ->where('doctor_workplaces.workplace_type', 'medical_centre')
            ->where('doctor_workplaces.workplace_id', $mc->id)
            ->select(
                'doctor_workplaces.id AS workplace_id',
                'doctor_workplaces.status AS workplace_status',
                'doctor_workplaces.employment_type',
                'doctor_workplaces.approved_at',
                'doctor_workplaces.created_at AS joined_at',
                'doctors.id AS doctor_id',
                DB::raw("CONCAT(doctors.first_name, ' ', doctors.last_name) AS name"),
                'doctors.specialization',
                'doctors.experience_years',
                'doctors.consultation_fee',
                'doctors.rating',
                'doctors.total_ratings',
                'doctors.profile_image',
                'doctors.phone',
                'doctors.slmc_number',
                'doctors.status AS doctor_status',
            );

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw("CONCAT(doctors.first_name, ' ', doctors.last_name) LIKE ?", ["%$search%"])
                  ->orWhere('doctors.specialization', 'LIKE', "%$search%")
                  ->orWhere('doctors.slmc_number', 'LIKE', "%$search%");
            });
        }

        if ($status) $query->where('doctor_workplaces.status', $status);
        if ($type)   $query->where('doctor_workplaces.employment_type', $type);

        $doctors = $query->orderByRaw("FIELD(doctor_workplaces.status, 'pending', 'approved', 'rejected')")
            ->orderBy('doctor_workplaces.created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Stats
        $stats = [
            'total'    => DoctorWorkplace::where('workplace_type', 'medical_centre')->where('workplace_id', $mc->id)->count(),
            'approved' => DoctorWorkplace::where('workplace_type', 'medical_centre')->where('workplace_id', $mc->id)->where('status', 'approved')->count(),
            'pending'  => DoctorWorkplace::where('workplace_type', 'medical_centre')->where('workplace_id', $mc->id)->where('status', 'pending')->count(),
            'rejected' => DoctorWorkplace::where('workplace_type', 'medical_centre')->where('workplace_id', $mc->id)->where('status', 'rejected')->count(),
        ];

        return view('medical_centre.doctors.index', compact(
            'mc', 'doctors', 'stats', 'search', 'status', 'type'
        ));
    }

    // ═══════════════════════════════════════════
    // SHOW — single doctor detail
    // ═══════════════════════════════════════════
    public function show($workplaceId)
    {
        $mc = $this->getMedicalCentre();

        $doctor = DB::table('doctor_workplaces')
            ->join('doctors', 'doctor_workplaces.doctor_id', '=', 'doctors.id')
            ->where('doctor_workplaces.id', $workplaceId)
            ->where('doctor_workplaces.workplace_type', 'medical_centre')
            ->where('doctor_workplaces.workplace_id', $mc->id)
            ->select(
                'doctor_workplaces.id AS workplace_id',
                'doctor_workplaces.status AS workplace_status',
                'doctor_workplaces.employment_type',
                'doctor_workplaces.approved_at',
                'doctor_workplaces.created_at AS joined_at',
                'doctors.id AS doctor_id',
                DB::raw("CONCAT(doctors.first_name, ' ', doctors.last_name) AS name"),
                'doctors.first_name',
                'doctors.last_name',
                'doctors.specialization',
                'doctors.qualifications',
                'doctors.experience_years',
                'doctors.consultation_fee',
                'doctors.rating',
                'doctors.total_ratings',
                'doctors.profile_image',
                'doctors.phone',
                'doctors.bio',
                'doctors.slmc_number',
                'doctors.status AS doctor_status',
            )
            ->first();

        if (!$doctor) {
            abort(404, 'Doctor not found.');
        }

        $appointments = DB::table('appointments')
            ->join('patients', 'appointments.patient_id', '=', 'patients.id')
            ->where('appointments.doctor_id', $doctor->doctor_id)
            ->where('appointments.workplace_type', 'medical_centre')
            ->where('appointments.workplace_id', $mc->id)
            ->select(
                'appointments.id',
                'appointments.appointment_number',
                'appointments.appointment_date',
                'appointments.appointment_time',
                'appointments.status',
                'appointments.payment_status',
                'appointments.consultation_fee',
                DB::raw("CONCAT(patients.first_name, ' ', patients.last_name) AS patient_name"),
                'patients.phone AS patient_phone',
            )
            ->orderBy('appointments.appointment_date', 'desc')
            ->limit(10)
            ->get();

        $schedules = DB::table('doctor_schedules')
            ->where('doctor_id', $doctor->doctor_id)
            ->where('workplace_type', 'medical_centre')
            ->where('workplace_id', $mc->id)
            ->orderByRaw("FIELD(day_of_week, 'monday','tuesday','wednesday','thursday','friday','saturday','sunday')")
            ->get();

        $appointmentStats = [
            'total'     => Appointment::where('doctor_id', $doctor->doctor_id)->where('workplace_type', 'medical_centre')->where('workplace_id', $mc->id)->count(),
            'pending'   => Appointment::where('doctor_id', $doctor->doctor_id)->where('workplace_type', 'medical_centre')->where('workplace_id', $mc->id)->where('status', 'pending')->count(),
            'completed' => Appointment::where('doctor_id', $doctor->doctor_id)->where('workplace_type', 'medical_centre')->where('workplace_id', $mc->id)->where('status', 'completed')->count(),
        ];

        return view('medical_centre.doctors.show', compact(
            'mc', 'doctor', 'appointments', 'schedules', 'appointmentStats'
        ));
    }


    // ═══════════════════════════════════════════
    // APPROVE doctor request
    // ═══════════════════════════════════════════
    public function approve(Request $request, $workplaceId)
    {
        try {
            $mc = $this->getMedicalCentre();

            $workplace = DoctorWorkplace::where('id', $workplaceId)
                ->where('workplace_type', 'medical_centre')
                ->where('workplace_id', $mc->id)
                ->where('status', 'pending')
                ->firstOrFail();

            $workplace->update([
                'status'      => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            return back()->with('success', 'Doctor approved successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to approve doctor.');
        }
    }

    // ═══════════════════════════════════════════
    // REJECT doctor request
    // ═══════════════════════════════════════════
    public function reject(Request $request, $workplaceId)
    {
        try {
            $mc = $this->getMedicalCentre();

            $workplace = DoctorWorkplace::where('id', $workplaceId)
                ->where('workplace_type', 'medical_centre')
                ->where('workplace_id', $mc->id)
                ->firstOrFail();

            $workplace->update([
                'status'      => 'rejected',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            return back()->with('success', 'Doctor request rejected.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to reject doctor.');
        }
    }

    // ═══════════════════════════════════════════
    // REMOVE doctor from medical centre
    // ═══════════════════════════════════════════
    public function remove(Request $request, $workplaceId)
    {
        try {
            $mc = $this->getMedicalCentre();

            DoctorWorkplace::where('id', $workplaceId)
                ->where('workplace_type', 'medical_centre')
                ->where('workplace_id', $mc->id)
                ->firstOrFail()
                ->delete();

            return redirect()->route('medical_centre.doctors')
                ->with('success', 'Doctor removed from your medical centre.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to remove doctor.');
        }
    }
}
