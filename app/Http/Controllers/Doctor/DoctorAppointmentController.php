<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DoctorAppointmentController extends Controller
{
    private function getDoctor()
    {
        return Doctor::where('user_id', Auth::id())->firstOrFail();
    }

    // ══════════════════════════════════════════
    //  INDEX — Appointments List
    // ══════════════════════════════════════════
    public function index(Request $request)
    {
        $doctor = $this->getDoctor();

        $query = DB::table('appointments')
            ->join('patients', 'appointments.patient_id', '=', 'patients.id')
            ->leftJoin('hospitals', function ($j) {
                $j->on('appointments.workplace_id', '=', 'hospitals.id')
                  ->where('appointments.workplace_type', '=', 'hospital');
            })
            ->leftJoin('medical_centres', function ($j) {
                $j->on('appointments.workplace_id', '=', 'medical_centres.id')
                  ->where('appointments.workplace_type', '=', 'medicalcentre');
            })
            ->where('appointments.doctor_id', $doctor->id)
            ->select(
                'appointments.id',
                'appointments.appointment_number',
                'appointments.appointment_date',
                'appointments.appointment_time',
                'appointments.status',
                'appointments.payment_status',
                'appointments.consultation_fee',
                'appointments.workplace_type',
                'appointments.reason',
                DB::raw("CONCAT(patients.first_name, ' ', patients.last_name) as patient_name"),
                'patients.phone as patient_phone',
                 'patients.profile_image as patient_profile_image',
                DB::raw("COALESCE(hospitals.name, medical_centres.name, 'Private Clinic') as location")
            );

        // Filters
        if ($request->filled('status')) {
            $query->where('appointments.status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('appointments.appointment_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('appointments.appointment_date', '<=', $request->date_to);
        }
        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('appointments.appointment_number', 'like', $search)
                  ->orWhere('patients.first_name', 'like', $search)
                  ->orWhere('patients.last_name', 'like', $search);
            });
        }

        $appointments = $query
            ->orderByDesc('appointments.appointment_date')
            ->orderByDesc('appointments.appointment_time')
            ->paginate(15)
            ->appends($request->query());

        // Stats for filter badges
        $stats = [
            'pending'   => DB::table('appointments')->where('doctor_id', $doctor->id)->where('status', 'pending')->count(),
            'confirmed' => DB::table('appointments')->where('doctor_id', $doctor->id)->where('status', 'confirmed')->count(),
            'completed' => DB::table('appointments')->where('doctor_id', $doctor->id)->where('status', 'completed')->count(),
            'cancelled' => DB::table('appointments')->where('doctor_id', $doctor->id)->where('status', 'cancelled')->count(),
        ];

        return view('doctor.appointments.index', compact('appointments', 'stats', 'doctor'));
    }

    // ══════════════════════════════════════════
    //  SHOW — Single Appointment
    // ══════════════════════════════════════════
    public function show($id)
    {
        $doctor = $this->getDoctor();

        $appointment = DB::table('appointments')
            ->join('patients', 'appointments.patient_id', '=', 'patients.id')
            // ❌ users join REMOVE කරන්න — profile_image users table එකේ නෑ
            ->leftJoin('hospitals', function ($j) {
                $j->on('appointments.workplace_id', '=', 'hospitals.id')
                ->where('appointments.workplace_type', '=', 'hospital');
            })
            ->leftJoin('medical_centres', function ($j) {
                $j->on('appointments.workplace_id', '=', 'medical_centres.id')
                ->where('appointments.workplace_type', '=', 'medicalcentre');
            })
            ->where('appointments.id', $id)
            ->where('appointments.doctor_id', $doctor->id)
            ->select(
                'appointments.*',
                DB::raw("CONCAT(patients.first_name, ' ', patients.last_name) as patient_name"),
                'patients.phone as patient_phone',
                'patients.date_of_birth',
                'patients.gender',
                'patients.blood_group',
                'patients.address',
                'patients.city',
                'patients.profile_image as patient_profile_image', // ✅ patients table විතරයි
                // ❌ 'users.profile_image as user_profile_image' — REMOVE
                DB::raw("COALESCE(hospitals.name, medical_centres.name, 'Private Clinic') as location")
            )
            ->first();

        if (!$appointment) abort(404);

        return view('doctor.appointments.show', compact('appointment', 'doctor'));
    }


    // ══════════════════════════════════════════
    //  CONFIRM — AJAX
    // ══════════════════════════════════════════
    public function confirm($id)
    {
        try {
            $doctor = $this->getDoctor();

            $updated = DB::table('appointments')
                ->where('id', $id)
                ->where('doctor_id', $doctor->id)
                ->where('status', 'pending')
                ->update(['status' => 'confirmed', 'updated_at' => now()]);

            if (!$updated) {
                return response()->json(['success' => false, 'message' => 'Appointment not found or already confirmed.'], 404);
            }

            // Notify Patient
            $apt = DB::table('appointments')
                ->join('patients', 'appointments.patient_id', '=', 'patients.id')
                ->where('appointments.id', $id)
                ->select('patients.user_id', 'appointments.appointment_number', 'appointments.appointment_date')
                ->first();

            if ($apt) {
                DB::table('notifications')->insert([
                    'notifiable_type' => 'App\Models\User',
                    'notifiable_id'   => $apt->user_id,
                    'type'            => 'appointment',
                    'title'           => 'Appointment Confirmed',
                    'message'         => 'Your appointment ' . $apt->appointment_number . ' on ' .
                                        Carbon::parse($apt->appointment_date)->format('d M Y') .
                                        ' has been confirmed by the doctor.',
                    'related_type'    => 'appointment',
                    'related_id'      => $id,
                    'is_read'         => false,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Appointment confirmed successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ══════════════════════════════════════════
    //  CANCEL — AJAX
    // ══════════════════════════════════════════
    public function cancel(Request $request, $id)
    {
        try {
            $doctor = $this->getDoctor();

            $updated = DB::table('appointments')
                ->where('id', $id)
                ->where('doctor_id', $doctor->id)
                ->whereIn('status', ['pending', 'confirmed'])
                ->update([
                    'status'              => 'cancelled',
                    'cancelled_by'        => Auth::id(),
                    'cancellation_reason' => $request->input('cancellation_reason'),
                    'updated_at'          => now(),
                ]);

            if (!$updated) {
                return response()->json(['success' => false, 'message' => 'Appointment not found or cannot be cancelled.'], 404);
            }

            // Notify Patient
            $apt = DB::table('appointments')
                ->join('patients', 'appointments.patient_id', '=', 'patients.id')
                ->where('appointments.id', $id)
                ->select('patients.user_id', 'appointments.appointment_number')
                ->first();

            if ($apt) {
                DB::table('notifications')->insert([
                    'notifiable_type' => 'App\Models\User',
                    'notifiable_id'   => $apt->user_id,
                    'type'            => 'appointment',
                    'title'           => 'Appointment Cancelled',
                    'message'         => 'Your appointment ' . $apt->appointment_number .
                                        ' has been cancelled by the doctor. Reason: ' .
                                        ($request->input('cancellation_reason') ?? 'No reason provided'),
                    'related_type'    => 'appointment',
                    'related_id'      => $id,
                    'is_read'         => false,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Appointment cancelled successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ══════════════════════════════════════════
    //  COMPLETE — AJAX
    // ══════════════════════════════════════════
    public function complete($id)
    {
        try {
            $doctor = $this->getDoctor();

            $updated = DB::table('appointments')
                ->where('id', $id)
                ->where('doctor_id', $doctor->id)
                ->where('status', 'confirmed')
                ->update(['status' => 'completed', 'updated_at' => now()]);

            if (!$updated) {
                return response()->json(['success' => false, 'message' => 'Appointment not found or not in confirmed state.'], 404);
            }

            return response()->json(['success' => true, 'message' => 'Appointment marked as completed.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ══════════════════════════════════════════
    //  RESCHEDULE — AJAX
    // ══════════════════════════════════════════
    public function reschedule(Request $request, $id)
    {
        $request->validate([
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
        ]);

        try {
            $doctor = $this->getDoctor();

            $updated = DB::table('appointments')
                ->where('id', $id)
                ->where('doctor_id', $doctor->id)
                ->whereIn('status', ['pending', 'confirmed'])
                ->update([
                    'appointment_date' => $request->appointment_date,
                    'appointment_time' => $request->appointment_time,
                    'status'           => 'pending',
                    'updated_at'       => now(),
                ]);

            if (!$updated) {
                return response()->json(['success' => false, 'message' => 'Appointment not found or cannot be rescheduled.'], 404);
            }

            // Notify Patient
            $apt = DB::table('appointments')
                ->join('patients', 'appointments.patient_id', '=', 'patients.id')
                ->where('appointments.id', $id)
                ->select('patients.user_id', 'appointments.appointment_number')
                ->first();

            if ($apt) {
                DB::table('notifications')->insert([
                    'notifiable_type' => 'App\Models\User',
                    'notifiable_id'   => $apt->user_id,
                    'type'            => 'appointment',
                    'title'           => 'Appointment Rescheduled',
                    'message'         => 'Your appointment ' . $apt->appointment_number .
                                        ' has been rescheduled to ' .
                                        Carbon::parse($request->appointment_date)->format('d M Y') .
                                        ' at ' . Carbon::parse($request->appointment_time)->format('h:i A'),
                    'related_type'    => 'appointment',
                    'related_id'      => $id,
                    'is_read'         => false,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Appointment rescheduled successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ══════════════════════════════════════════
    //  ADD NOTES — AJAX
    // ══════════════════════════════════════════
    public function addNotes(Request $request, $id)
    {
        $request->validate(['notes' => 'required|string|max:2000']);

        try {
            $doctor = $this->getDoctor();

            DB::table('appointments')
                ->where('id', $id)
                ->where('doctor_id', $doctor->id)
                ->update(['notes' => $request->notes, 'updated_at' => now()]);

            return response()->json(['success' => true, 'message' => 'Notes saved successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
