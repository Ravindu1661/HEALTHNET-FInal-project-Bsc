<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DoctorWorkplaceController extends Controller
{
    // ── Helper: Get Doctor ──────────────────────────────────────
    private function getDoctor()
    {
        $doctor = DB::table('doctors')
            ->where('user_id', Auth::id())
            ->first();

        if (!$doctor) {
            return redirect()->route('doctor.dashboard')
                ->with('error', 'Doctor profile not found.');
        }
        return $doctor;
    }

    // ══════════════════════════════════════════════════════════════
    // INDEX — List all workplaces of this doctor
    // ══════════════════════════════════════════════════════════════
    public function index()
    {
        $doctor = $this->getDoctor();
        if ($doctor instanceof \Illuminate\Http\RedirectResponse) return $doctor;

        // Get all doctor_workplaces with related workplace data
        $workplaces = DB::table('doctor_workplaces')
            ->where('doctor_id', $doctor->id)
            ->orderByDesc('created_at')
            ->get();

        // Attach workplace name/details to each row
        $workplaces = $workplaces->map(function ($wp) {
            if ($wp->workplace_type === 'hospital') {
                $place = DB::table('hospitals')
                    ->where('id', $wp->workplace_id)
                    ->select('id','name','phone','address','city','profile_image')
                    ->first();
            } else {
                $place = DB::table('medical_centres')
                    ->where('id', $wp->workplace_id)
                    ->select('id','name','phone','address','city','profile_image')
                    ->first();
            }
            $wp->place = $place;
            return $wp;
        });

        // Stats
        $total    = $workplaces->count();
        $approved = $workplaces->where('status', 'approved')->count();
        $pending  = $workplaces->where('status', 'pending')->count();
        $rejected = $workplaces->where('status', 'rejected')->count();
        $hospitals       = $workplaces->where('workplace_type', 'hospital')->count();
        $medicalCentres  = $workplaces->where('workplace_type', 'medical_centre')->count();

        return view('doctor.workplaces.index', compact(
            'workplaces',
            'total', 'approved', 'pending', 'rejected',
            'hospitals', 'medicalCentres'
        ));
    }

    // ══════════════════════════════════════════════════════════════
    // CREATE — Show form
    // ══════════════════════════════════════════════════════════════
    public function create()
    {
        $doctor = $this->getDoctor();
        if ($doctor instanceof \Illuminate\Http\RedirectResponse) return $doctor;

        $hospitals = DB::table('hospitals')
            ->where('status', 'approved')
            ->orderBy('name')
            ->get(['id','name','city','address','profile_image']);

        $medicalCentres = DB::table('medical_centres')
            ->where('status', 'approved')
            ->orderBy('name')
            ->get(['id','name','city','address','profile_image']);

        return view('doctor.workplaces.create',
            compact('hospitals', 'medicalCentres'));
    }

    // ══════════════════════════════════════════════════════════════
    // STORE — Save new workplace
    // ══════════════════════════════════════════════════════════════
   public function store(Request $request)
    {
        $doctor = $this->getDoctor();
        if ($doctor instanceof \Illuminate\Http\RedirectResponse) return $doctor;

        $request->validate([
            'workplace_type'  => 'required|in:hospital,medical_centre',
            'workplace_id'    => 'required|integer',
            'employment_type' => 'required|in:permanent,temporary,visiting',
        ]);

        // Check already exists
        $exists = DB::table('doctor_workplaces')
            ->where('doctor_id',      $doctor->id)
            ->where('workplace_type', $request->workplace_type)
            ->where('workplace_id',   $request->workplace_id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'You have already added this workplace.');
        }

        // Verify workplace exists and is approved
        $table     = $request->workplace_type === 'hospital' ? 'hospitals' : 'medical_centres';
        $workplace = DB::table($table)
            ->where('id',     $request->workplace_id)
            ->where('status', 'approved')
            ->first();

        if (!$workplace) {
            return redirect()->back()
                ->with('error', 'Selected workplace is not available.');
        }

        // Insert workplace request
        $workplaceRecordId = DB::table('doctor_workplaces')->insertGetId([
            'doctor_id'       => $doctor->id,
            'workplace_type'  => $request->workplace_type,
            'workplace_id'    => $request->workplace_id,
            'employment_type' => $request->employment_type,
            'status'          => 'pending',
            'approved_by'     => null,
            'approved_at'     => null,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // ── Doctor full name ──────────────────────────────────
        $doctorName     = trim($doctor->first_name . ' ' . $doctor->last_name);
        $workplaceName  = $workplace->name ?? 'Unknown';
        $workplaceType  = $request->workplace_type === 'hospital' ? 'Hospital' : 'Medical Centre';
        $employmentType = ucfirst($request->employment_type);

        // ── 1. Admin ලාට Notification ────────────────────────
        $admins = DB::table('users')->where('user_type', 'admin')->pluck('id');
        foreach ($admins as $adminId) {
            DB::table('notifications')->insert([
                'notifiable_type' => \App\Models\User::class,
                'notifiable_id'   => $adminId,
                'type'            => 'workplace_request',
                'title'           => '🏥 New Workplace Request',
                'message'         => 'Dr. ' . $doctorName
                                   . ' has requested to join ' . $workplaceName
                                   . ' (' . $workplaceType . ') as a ' . $employmentType . ' doctor.'
                                   . ' Pending approval.',
                'related_type'    => 'doctor_workplace',
                'related_id'      => $workplaceRecordId,
                'is_read'         => false,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }

        // ── 2. Hospital / Medical Centre ට Notification ──────
        $providerUserId = $workplace->user_id ?? null;
        if ($providerUserId) {
            DB::table('notifications')->insert([
                'notifiable_type' => \App\Models\User::class,
                'notifiable_id'   => $providerUserId,
                'type'            => 'workplace_request',
                'title'           => '👨‍⚕️ New Doctor Join Request',
                'message'         => 'Dr. ' . $doctorName
                                   . ' (' . ($doctor->specialization ?? 'General Practitioner') . ')'
                                   . ' has requested to join your ' . $workplaceType
                                   . ' as a ' . $employmentType . ' doctor.'
                                   . ' Please review and approve from your Doctors panel.',
                'related_type'    => 'doctor_workplace',
                'related_id'      => $workplaceRecordId,
                'is_read'         => false,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }

        return redirect()->route('doctor.workplaces.index')
            ->with('success', 'Workplace request submitted! Admin and ' . $workplaceName . ' have been notified. Awaiting approval.');
    }

    // ══════════════════════════════════════════════════════════════
    // EDIT — Show edit form (only pending allowed)
    // ══════════════════════════════════════════════════════════════
    public function edit($id)
    {
        $doctor = $this->getDoctor();
        if ($doctor instanceof \Illuminate\Http\RedirectResponse) return $doctor;

        $workplace = DB::table('doctor_workplaces')
            ->where('id',        $id)
            ->where('doctor_id', $doctor->id)
            ->first();

        if (!$workplace) abort(404);

        if ($workplace->status !== 'pending') {
            return redirect()->route('doctor.workplaces.index')
                ->with('error', 'Cannot edit approved or rejected workplaces.');
        }

        // Get place details
        $table = $workplace->workplace_type === 'hospital'
                 ? 'hospitals' : 'medical_centres';
        $place = DB::table($table)
            ->where('id', $workplace->workplace_id)
            ->first();

        return view('doctor.workplaces.edit', compact('workplace', 'place'));
    }

    // ══════════════════════════════════════════════════════════════
    // UPDATE — Update employment_type (only pending)
    // ══════════════════════════════════════════════════════════════
    public function update(Request $request, $id)
    {
        $doctor = $this->getDoctor();
        if ($doctor instanceof \Illuminate\Http\RedirectResponse) return $doctor;

        $workplace = DB::table('doctor_workplaces')
            ->where('id',        $id)
            ->where('doctor_id', $doctor->id)
            ->first();

        if (!$workplace) abort(404);

        if ($workplace->status !== 'pending') {
            return redirect()->route('doctor.workplaces.index')
                ->with('error', 'Cannot edit approved or rejected workplaces.');
        }

        $request->validate([
            'employment_type' => 'required|in:permanent,temporary,visiting',
        ]);

        DB::table('doctor_workplaces')
            ->where('id', $id)
            ->update([
                'employment_type' => $request->employment_type,
                'updated_at'      => now(),
            ]);

        return redirect()->route('doctor.workplaces.index')
            ->with('success', 'Workplace updated successfully!');
    }

    // ══════════════════════════════════════════════════════════════
    // DESTROY — Delete (only pending or rejected)
    // ══════════════════════════════════════════════════════════════
    public function destroy($id)
    {
        $doctor = $this->getDoctor();
        if ($doctor instanceof \Illuminate\Http\RedirectResponse) return $doctor;

        $workplace = DB::table('doctor_workplaces')
            ->where('id',        $id)
            ->where('doctor_id', $doctor->id)
            ->first();

        if (!$workplace) abort(404);

        if ($workplace->status === 'approved') {
            return redirect()->route('doctor.workplaces.index')
                ->with('error', 'Cannot delete approved workplace. Please contact admin.');
        }

        DB::table('doctor_workplaces')->where('id', $id)->delete();

        return redirect()->route('doctor.workplaces.index')
            ->with('success', 'Workplace removed successfully!');
    }

    // ══════════════════════════════════════════════════════════════
    // SEARCH AVAILABLE WORKPLACES (AJAX)
    // ══════════════════════════════════════════════════════════════
    public function search(Request $request)
    {
        try {
            $doctor = $this->getDoctor();
            if ($doctor instanceof \Illuminate\Http\RedirectResponse) {
                return response()->json(['success' => false, 'message' => 'Doctor not found'], 403);
            }

            $q    = trim($request->get('q', ''));
            $type = $request->get('type', 'hospital'); // hospital | medical_centre

            // Already affiliated workplace_ids for this type
            $affiliatedIds = DB::table('doctor_workplaces')
                ->where('doctor_id',      $doctor->id)
                ->where('workplace_type', $type)
                ->pluck('workplace_id')
                ->toArray();

            $table = $type === 'hospital' ? 'hospitals' : 'medical_centres';

            $query = DB::table($table)
                ->where('status', 'approved')
                ->select('id','name','city','address','phone','profile_image');

            if ($q !== '') {
                $like = '%'.$q.'%';
                $query->where(function ($sub) use ($like) {
                    $sub->where('name',    'like', $like)
                        ->orWhere('city',  'like', $like)
                        ->orWhere('address','like', $like);
                });
            }

            $results = $query->orderBy('name')->limit(20)->get()
                ->map(function ($place) use ($affiliatedIds) {
                    $place->already_affiliated = in_array($place->id, $affiliatedIds);
                    return $place;
                });

            return response()->json([
                'success' => true,
                'data'    => $results,
                'count'   => $results->count(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}
