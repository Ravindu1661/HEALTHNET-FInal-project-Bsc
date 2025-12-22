<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorWorkplace;
use App\Models\Hospital;
use App\Models\MedicalCentre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorWorkplaceController extends Controller
{
    /**
     * Display doctor's workplaces
     */
    public function index()
    {
        $doctor = Doctor::where('user_id', Auth::id())->first();

        if (!$doctor) {
            return redirect()->route('doctor.dashboard')
                           ->with('error', 'Doctor profile not found.');
        }

        // Get all workplaces with relationships
        $workplaces = DoctorWorkplace::where('doctor_id', $doctor->id)
            ->with(['hospital', 'medicalCentre', 'approvedBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Separate by type
        $hospitals = $workplaces->where('workplace_type', 'hospital');
        $medicalCentres = $workplaces->where('workplace_type', 'medical_centre');

        // Count by status
        $pendingCount = $workplaces->where('status', 'pending')->count();
        $approvedCount = $workplaces->where('status', 'approved')->count();
        $rejectedCount = $workplaces->where('status', 'rejected')->count();

        return view('doctor.workplaces.index', compact(
            'workplaces',
            'hospitals',
            'medicalCentres',
            'pendingCount',
            'approvedCount',
            'rejectedCount'
        ));
    }

    /**
     * Show form to add new workplace
     */
    public function create()
    {
        $doctor = Doctor::where('user_id', Auth::id())->first();

        if (!$doctor) {
            return redirect()->route('doctor.dashboard')
                           ->with('error', 'Doctor profile not found.');
        }

        // Get all approved hospitals and medical centres
        $hospitals = Hospital::where('status', 'approved')
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'city', 'address', 'type']);

        $medicalCentres = MedicalCentre::where('status', 'approved')
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'city', 'address']);

        return view('doctor.workplaces.create', compact('hospitals', 'medicalCentres'));
    }

    /**
     * Store new workplace association
     */
    public function store(Request $request)
    {
        $doctor = Doctor::where('user_id', Auth::id())->first();

        if (!$doctor) {
            return redirect()->route('doctor.dashboard')
                           ->with('error', 'Doctor profile not found.');
        }

        $request->validate([
            'workplace_type' => 'required|in:hospital,medical_centre',
            'workplace_id' => 'required|integer',
            'employment_type' => 'required|in:permanent,temporary,visiting',
        ]);

        // Check if already exists
        $exists = DoctorWorkplace::where('doctor_id', $doctor->id)
            ->where('workplace_type', $request->workplace_type)
            ->where('workplace_id', $request->workplace_id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                           ->with('error', 'You have already added this workplace.');
        }

        // Verify workplace exists and is approved
        if ($request->workplace_type == 'hospital') {
            $workplace = Hospital::where('id', $request->workplace_id)
                                ->where('status', 'approved')
                                ->first();
        } else {
            $workplace = MedicalCentre::where('id', $request->workplace_id)
                                     ->where('status', 'approved')
                                     ->first();
        }

        if (!$workplace) {
            return redirect()->back()
                           ->with('error', 'Selected workplace is not available.');
        }

        // Create workplace association
        DoctorWorkplace::create([
            'doctor_id' => $doctor->id,
            'workplace_type' => $request->workplace_type,
            'workplace_id' => $request->workplace_id,
            'employment_type' => $request->employment_type,
            'status' => 'pending', // Admin will approve
        ]);

        return redirect()->route('doctor.workplaces.index')
                       ->with('success', 'Workplace added successfully! Waiting for admin approval.');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $doctor = Doctor::where('user_id', Auth::id())->first();

        if (!$doctor) {
            return redirect()->route('doctor.dashboard')
                           ->with('error', 'Doctor profile not found.');
        }

        $workplace = DoctorWorkplace::where('id', $id)
            ->where('doctor_id', $doctor->id)
            ->with(['hospital', 'medicalCentre'])
            ->firstOrFail();

        // Can only edit if pending
        if ($workplace->status !== 'pending') {
            return redirect()->route('doctor.workplaces.index')
                           ->with('error', 'Cannot edit approved or rejected workplaces.');
        }

        return view('doctor.workplaces.edit', compact('workplace'));
    }

    /**
     * Update workplace
     */
    public function update(Request $request, $id)
    {
        $doctor = Doctor::where('user_id', Auth::id())->first();

        if (!$doctor) {
            return redirect()->route('doctor.dashboard')
                           ->with('error', 'Doctor profile not found.');
        }

        $workplace = DoctorWorkplace::where('id', $id)
            ->where('doctor_id', $doctor->id)
            ->firstOrFail();

        // Can only edit if pending
        if ($workplace->status !== 'pending') {
            return redirect()->route('doctor.workplaces.index')
                           ->with('error', 'Cannot edit approved or rejected workplaces.');
        }

        $request->validate([
            'employment_type' => 'required|in:permanent,temporary,visiting',
        ]);

        $workplace->update([
            'employment_type' => $request->employment_type,
        ]);

        return redirect()->route('doctor.workplaces.index')
                       ->with('success', 'Workplace updated successfully!');
    }

    /**
     * Remove workplace
     */
    public function destroy($id)
    {
        $doctor = Doctor::where('user_id', Auth::id())->first();

        if (!$doctor) {
            return redirect()->route('doctor.dashboard')
                           ->with('error', 'Doctor profile not found.');
        }

        $workplace = DoctorWorkplace::where('id', $id)
            ->where('doctor_id', $doctor->id)
            ->firstOrFail();

        // Can only delete if pending or rejected
        if ($workplace->status == 'approved') {
            return redirect()->route('doctor.workplaces.index')
                           ->with('error', 'Cannot delete approved workplace. Please contact admin.');
        }

        $workplace->delete();

        return redirect()->route('doctor.workplaces.index')
                       ->with('success', 'Workplace removed successfully!');
    }
}
