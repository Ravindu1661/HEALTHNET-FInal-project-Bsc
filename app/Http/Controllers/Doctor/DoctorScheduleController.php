<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DoctorScheduleController extends Controller
{
    private function getDoctor()
    {
        $doctor = DB::table('doctors')
            ->where('user_id', Auth::id())
            ->first();

        if (!$doctor) abort(403, 'Doctor profile not found.');
        return $doctor;
    }

    // ══════════════════════════════════════════
    //  REUSABLE — Workplaces Query
    //  FIX: 'medicalcentre' → 'medical_centre'
    // ══════════════════════════════════════════
    private function workplacesQuery(int $doctorId)
    {
        return DB::table('doctor_workplaces')
            ->leftJoin('hospitals', function ($j) {
                $j->on('doctor_workplaces.workplace_id', '=', 'hospitals.id')
                  ->where('doctor_workplaces.workplace_type', '=', 'hospital');
            })
            ->leftJoin('medical_centres', function ($j) {
                $j->on('doctor_workplaces.workplace_id', '=', 'medical_centres.id')
                  ->where('doctor_workplaces.workplace_type', '=', 'medical_centre'); // ✅ FIXED
            })
            ->where('doctor_workplaces.doctor_id', $doctorId)
            ->select(
                'doctor_workplaces.id',
                'doctor_workplaces.workplace_type',
                'doctor_workplaces.workplace_id',
                DB::raw("COALESCE(hospitals.name, medical_centres.name, 'Private Clinic') as name")
            );
    }

    // ══════════════════════════════════════════
    //  INDEX
    // ══════════════════════════════════════════
    public function index(Request $request)
    {
        $doctor = $this->getDoctor();

        $query = DB::table('doctor_schedules')
            ->leftJoin('hospitals', function ($j) {
                $j->on('doctor_schedules.workplace_id', '=', 'hospitals.id')
                  ->where('doctor_schedules.workplace_type', '=', 'hospital');
            })
            ->leftJoin('medical_centres', function ($j) {
                $j->on('doctor_schedules.workplace_id', '=', 'medical_centres.id')
                  ->where('doctor_schedules.workplace_type', '=', 'medical_centre'); // ✅ FIXED
            })
            ->where('doctor_schedules.doctor_id', $doctor->id)
            ->select(
                'doctor_schedules.*',
                DB::raw("COALESCE(hospitals.name, medical_centres.name, 'Private Clinic') as location")
            );

        if ($request->filled('status')) {
            $query->where('doctor_schedules.is_active',
                $request->status === 'active' ? 1 : 0);
        }
        if ($request->filled('day')) {
            $query->where('doctor_schedules.day_of_week', $request->day);
        }
        if ($request->filled('workplace_type')) {
            $query->where('doctor_schedules.workplace_type', $request->workplace_type);
        }

        $schedules = $query
            ->orderBy('doctor_schedules.day_of_week', 'asc')
            ->orderBy('doctor_schedules.start_time', 'asc')
            ->paginate(15);

        $stats = [
            'total'    => DB::table('doctor_schedules')
                            ->where('doctor_id', $doctor->id)->count(),
            'active'   => DB::table('doctor_schedules')
                            ->where('doctor_id', $doctor->id)
                            ->where('is_active', 1)->count(),
            'inactive' => DB::table('doctor_schedules')
                            ->where('doctor_id', $doctor->id)
                            ->where('is_active', 0)->count(),
            'today'    => DB::table('doctor_schedules')
                            ->where('doctor_id', $doctor->id)
                            ->where('day_of_week', strtolower(now()->format('l')))
                            ->where('is_active', 1)->count(),
        ];

        $workplaces = $this->workplacesQuery($doctor->id)->get();

        return view('doctor.schedule.index',
            compact('schedules', 'stats', 'workplaces', 'doctor'));
    }

    // ══════════════════════════════════════════
    //  CREATE
    // ══════════════════════════════════════════
    public function create()
    {
        $doctor     = $this->getDoctor();
        $workplaces = $this->workplacesQuery($doctor->id)->get();
        $days       = ['monday','tuesday','wednesday',
                       'thursday','friday','saturday','sunday'];

        return view('doctor.schedule.create',
            compact('workplaces', 'days', 'doctor'));
    }

    // ══════════════════════════════════════════
    //  STORE
    // ══════════════════════════════════════════
    public function store(Request $request)
    {
        $doctor = $this->getDoctor();

        $request->validate([
            'day_of_week'      => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time'       => 'required',
            'end_time'         => 'required|after:start_time',
            'max_appointments' => 'required|integer|min:1|max:100',
            'consultation_fee' => 'nullable|numeric|min:0',
            'workplace_id'     => 'nullable|integer',
            'workplace_type'   => 'nullable|in:hospital,medical_centre,private', // ✅ FIXED
        ]);

        $exists = DB::table('doctor_schedules')
            ->where('doctor_id',   $doctor->id)
            ->where('day_of_week', $request->day_of_week)
            ->where('start_time',  $request->start_time)
            ->where('workplace_id',$request->workplace_id)
            ->exists();

        if ($exists) {
            return back()->withInput()->withErrors([
                'day_of_week' => 'A schedule already exists for this day and time.'
            ]);
        }

        DB::table('doctor_schedules')->insert([
            'doctor_id'        => $doctor->id,
            'day_of_week'      => $request->day_of_week,
            'start_time'       => $request->start_time,
            'end_time'         => $request->end_time,
            'max_appointments' => $request->max_appointments,
            'consultation_fee' => $request->consultation_fee
                                    ?? $doctor->consultation_fee,
            'workplace_id'     => $request->workplace_id,
            'workplace_type'   => $request->workplace_type ?? 'private',
            'is_active'        => 1,
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        return redirect()->route('doctor.schedule.index')
            ->with('success', 'Schedule created successfully!');
    }

    // ══════════════════════════════════════════
    //  EDIT
    // ══════════════════════════════════════════
    public function edit($id)
    {
        $doctor = $this->getDoctor();

        $schedule = DB::table('doctor_schedules')
            ->where('id', $id)
            ->where('doctor_id', $doctor->id)
            ->first();

        if (!$schedule) abort(404);

        $workplaces = $this->workplacesQuery($doctor->id)->get();
        $days       = ['monday','tuesday','wednesday',
                       'thursday','friday','saturday','sunday'];

        return view('doctor.schedule.edit',
            compact('schedule', 'workplaces', 'days', 'doctor'));
    }

    // ══════════════════════════════════════════
    //  UPDATE
    // ══════════════════════════════════════════
    public function update(Request $request, $id)
    {
        $doctor   = $this->getDoctor();
        $schedule = DB::table('doctor_schedules')
            ->where('id', $id)
            ->where('doctor_id', $doctor->id)
            ->first();

        if (!$schedule) abort(404);

        $request->validate([
            'day_of_week'      => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time'       => 'required',
            'end_time'         => 'required|after:start_time',
            'max_appointments' => 'required|integer|min:1|max:100',
            'consultation_fee' => 'nullable|numeric|min:0',
            'workplace_id'     => 'nullable|integer',
            'workplace_type'   => 'nullable|in:hospital,medical_centre,private', // ✅ FIXED
            'is_active'        => 'required|in:0,1',
        ]);

        DB::table('doctor_schedules')
            ->where('id', $id)
            ->update([
                'day_of_week'      => $request->day_of_week,
                'start_time'       => $request->start_time,
                'end_time'         => $request->end_time,
                'max_appointments' => $request->max_appointments,
                'consultation_fee' => $request->consultation_fee,
                'workplace_id'     => $request->workplace_id,
                'workplace_type'   => $request->workplace_type ?? 'private',
                'is_active'        => $request->is_active,
                'updated_at'       => now(),
            ]);

        return redirect()->route('doctor.schedule.index')
            ->with('success', 'Schedule updated successfully!');
    }

    // ══════════════════════════════════════════
    //  DESTROY
    // ══════════════════════════════════════════
    public function destroy($id)
    {
        $doctor   = $this->getDoctor();
        $schedule = DB::table('doctor_schedules')
            ->where('id', $id)
            ->where('doctor_id', $doctor->id)
            ->first();

        if (!$schedule) abort(404);

        DB::table('doctor_schedules')->where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Schedule deleted successfully!'
        ]);
    }

    // ══════════════════════════════════════════
    //  TOGGLE STATUS — AJAX
    // ══════════════════════════════════════════
    public function toggleStatus($id)
    {
        $doctor   = $this->getDoctor();
        $schedule = DB::table('doctor_schedules')
            ->where('id', $id)
            ->where('doctor_id', $doctor->id)
            ->first();

        if (!$schedule) abort(404);

        $newActive = $schedule->is_active ? 0 : 1;

        DB::table('doctor_schedules')
            ->where('id', $id)
            ->update([
                'is_active'  => $newActive,
                'updated_at' => now(),
            ]);

        return response()->json([
            'success'   => true,
            'is_active' => $newActive,
            'status'    => $newActive ? 'active' : 'inactive',
            'message'   => 'Schedule status updated!'
        ]);
    }
}
