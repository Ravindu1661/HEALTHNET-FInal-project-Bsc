<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class DoctorProfileController extends Controller
{
    // ── Helper: Get Doctor ──────────────────────────────────────────
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
    // SHOW — Profile view page
    // ══════════════════════════════════════════════════════════════
    public function show()
    {
        $doctor = $this->getDoctor();
        if ($doctor instanceof \Illuminate\Http\RedirectResponse) return $doctor;

        $user = DB::table('users')->where('id', Auth::id())->first();

        // Stats
        $totalAppointments = DB::table('appointments')
            ->where('doctor_id', $doctor->id)
            ->count();

        $completedAppointments = DB::table('appointments')
            ->where('doctor_id', $doctor->id)
            ->where('status', 'completed')
            ->count();

        $pendingAppointments = DB::table('appointments')
            ->where('doctor_id', $doctor->id)
            ->where('status', 'pending')
            ->count();

        $totalWorkplaces = DB::table('doctor_workplaces')
            ->where('doctor_id', $doctor->id)
            ->where('status', 'approved')
            ->count();

        $totalSchedules = DB::table('doctor_schedules')
            ->where('doctor_id', $doctor->id)
            ->where('is_active', true)
            ->count();

        return view('doctor.profile.show', compact(
            'doctor', 'user',
            'totalAppointments',
            'completedAppointments',
            'pendingAppointments',
            'totalWorkplaces',
            'totalSchedules'
        ));
    }

    // ══════════════════════════════════════════════════════════════
    // EDIT — Show edit form
    // ══════════════════════════════════════════════════════════════
    public function edit()
    {
        $doctor = $this->getDoctor();
        if ($doctor instanceof \Illuminate\Http\RedirectResponse) return $doctor;

        $user = DB::table('users')->where('id', Auth::id())->first();

        return view('doctor.profile.edit', compact('doctor', 'user'));
    }

    // ══════════════════════════════════════════════════════════════
    // UPDATE — Save profile changes
    // ══════════════════════════════════════════════════════════════
    public function update(Request $request)
    {
        $doctor = $this->getDoctor();
        if ($doctor instanceof \Illuminate\Http\RedirectResponse) return $doctor;

        $request->validate([
            'first_name'       => 'required|string|max:100',
            'last_name'        => 'required|string|max:100',
            'phone'            => 'nullable|string|max:20',
            'specialization'   => 'nullable|string|max:100',
            'qualifications'   => 'nullable|string',
            'experience_years' => 'nullable|integer|min:0|max:60',
            'bio'              => 'nullable|string|max:1000',
            'consultation_fee' => 'nullable|numeric|min:0|max:99999999',
            'profile_image'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // ── Handle profile image upload ────────────────────────
        $profileImagePath = $doctor->profile_image;

        if ($request->hasFile('profile_image')) {
            // Delete old image
            if ($profileImagePath && Storage::disk('public')->exists($profileImagePath)) {
                Storage::disk('public')->delete($profileImagePath);
            }
            $profileImagePath = $request->file('profile_image')
                ->store('doctors/profile_images', 'public');
        }

        // ── Update doctors table ───────────────────────────────
        DB::table('doctors')
            ->where('id', $doctor->id)
            ->update([
                'first_name'       => trim($request->first_name),
                'last_name'        => trim($request->last_name),
                'phone'            => $request->phone,
                'specialization'   => $request->specialization,
                'qualifications'   => $request->qualifications,
                'experience_years' => $request->experience_years,
                'bio'              => $request->bio,
                'consultation_fee' => $request->consultation_fee,
                'profile_image'    => $profileImagePath,
                'updated_at'       => now(),
            ]);

        return redirect()->route('doctor.profile.show')
            ->with('success', 'Profile updated successfully!');
    }

    // ══════════════════════════════════════════════════════════════
    // UPDATE DOCUMENT — Re-upload SLMC / verification document
    // ══════════════════════════════════════════════════════════════
    public function updateDocument(Request $request)
    {
        $doctor = $this->getDoctor();
        if ($doctor instanceof \Illuminate\Http\RedirectResponse) return $doctor;

        $request->validate([
            'document' => 'required|file|mimes:pdf,jpeg,png,jpg|max:5120',
        ]);

        // Delete old document
        if ($doctor->document_path &&
            Storage::disk('public')->exists($doctor->document_path)) {
            Storage::disk('public')->delete($doctor->document_path);
        }

        $documentPath = $request->file('document')
            ->store('doctors/documents', 'public');

        DB::table('doctors')
            ->where('id', $doctor->id)
            ->update([
                'document_path' => $documentPath,
                'updated_at'    => now(),
            ]);

        return redirect()->route('doctor.profile.show')
            ->with('success', 'Verification document updated successfully!');
    }

    // ══════════════════════════════════════════════════════════════
    // CHANGE PASSWORD
    // ══════════════════════════════════════════════════════════════
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password'          => 'required|string',
            'password'                  => 'required|string|min:8|confirmed',
            'password_confirmation'     => 'required|string',
        ]);

        $user = DB::table('users')->where('id', Auth::id())->first();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'Current password is incorrect.'])
                ->withInput();
        }

        // Check new password is not same as current
        if (Hash::check($request->password, $user->password)) {
            return redirect()->back()
                ->withErrors(['password' => 'New password must be different from your current password.'])
                ->withInput();
        }

        DB::table('users')
            ->where('id', Auth::id())
            ->update([
                'password'   => Hash::make($request->password),
                'updated_at' => now(),
            ]);

        // Log activity
        $this->logActivity('password_changed', 'Doctor changed account password.');

        return redirect()->route('doctor.profile.show')
            ->with('success', 'Password changed successfully!');
    }

    // ══════════════════════════════════════════════════════════════
    // DELETE PROFILE IMAGE
    // ══════════════════════════════════════════════════════════════
    public function deleteProfileImage()
    {
        $doctor = $this->getDoctor();
        if ($doctor instanceof \Illuminate\Http\RedirectResponse) return $doctor;

        if ($doctor->profile_image &&
            Storage::disk('public')->exists($doctor->profile_image)) {
            Storage::disk('public')->delete($doctor->profile_image);
        }

        DB::table('doctors')
            ->where('id', $doctor->id)
            ->update([
                'profile_image' => null,
                'updated_at'    => now(),
            ]);

        return redirect()->route('doctor.profile.show')
            ->with('success', 'Profile image removed successfully!');
    }

    // ══════════════════════════════════════════════════════════════
    // ACCOUNT OVERVIEW (AJAX — for dashboard widget etc.)
    // ══════════════════════════════════════════════════════════════
    public function accountOverview()
    {
        try {
            $doctor = $this->getDoctor();
            if ($doctor instanceof \Illuminate\Http\RedirectResponse) {
                return response()->json(['success' => false], 403);
            }

            $user = DB::table('users')->where('id', Auth::id())->first();

            return response()->json([
                'success' => true,
                'data'    => [
                    'name'              => $doctor->first_name . ' ' . $doctor->last_name,
                    'email'             => $user->email,
                    'slmc_number'       => $doctor->slmc_number,
                    'specialization'    => $doctor->specialization,
                    'experience_years'  => $doctor->experience_years,
                    'phone'             => $doctor->phone,
                    'consultation_fee'  => $doctor->consultation_fee,
                    'rating'            => $doctor->rating,
                    'total_ratings'     => $doctor->total_ratings,
                    'status'            => $doctor->status,
                    'profile_image'     => $doctor->profile_image
                                          ? asset('storage/' . $doctor->profile_image)
                                          : null,
                    'approved_at'       => $doctor->approved_at,
                    'member_since'      => $doctor->created_at,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load profile: ' . $e->getMessage(),
            ], 500);
        }
    }

    // ══════════════════════════════════════════════════════════════
    // PRIVATE — Activity Log Helper
    // ══════════════════════════════════════════════════════════════
    private function logActivity(string $action, string $description = '')
    {
        try {
            DB::table('activity_logs')->insert([
                'user_id'     => Auth::id(),
                'action'      => $action,
                'description' => $description,
                'ip_address'  => request()->ip(),
                'user_agent'  => request()->userAgent(),
                'created_at'  => now(),
            ]);
        } catch (\Exception $e) {
            // Silently fail — don't break main flow
        }
    }
}
