<?php

namespace App\Http\Controllers\MedicalCentre;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MedicalCentreProfileController extends Controller
{
    private function getMedicalCentre()
    {
        return DB::table('medical_centres')
            ->where('user_id', Auth::id())
            ->first();
    }

    // ─────────────────────────────────────────
    // Show Profile
    // ─────────────────────────────────────────
    public function index()
    {
        $mc   = $this->getMedicalCentre();
        if (!$mc) return redirect()->route('medical_centre.dashboard');

        $user = Auth::user();

        // Owner Doctor info
        $ownerDoctor = null;
        if ($mc->owner_doctor_id) {
            $ownerDoctor = DB::table('doctors')
                ->leftJoin('users', 'doctors.user_id', '=', 'users.id')
                ->where('doctors.id', $mc->owner_doctor_id)
                ->select(
                    'doctors.*',
                    'users.email as doctor_email'
                )
                ->first();
        }

        // Stats for profile overview
        $stats = [
            'total_appointments' => DB::table('appointments')
                ->where('workplace_type', 'medicalcentre')
                ->where('workplace_id', $mc->id)
                ->count(),
            'total_doctors' => DB::table('doctor_workplaces')
                ->where('workplace_type', 'medicalcentre')
                ->where('workplace_id', $mc->id)
                ->where('status', 'approved')
                ->count(),
            'total_reviews' => DB::table('ratings')
                ->where('ratable_type', 'medicalcentre')
                ->where('ratable_id', $mc->id)
                ->count(),
            'avg_rating' => round(
                DB::table('ratings')
                    ->where('ratable_type', 'medicalcentre')
                    ->where('ratable_id', $mc->id)
                    ->avg('rating') ?? 0,
                1
            ),
        ];

        return view('medical_centre.profile.index', compact(
            'mc', 'user', 'ownerDoctor', 'stats'
        ));
    }

    // ─────────────────────────────────────────
    // Update General Info
    // ─────────────────────────────────────────
   public function updateInfo(Request $request)
{
    $mc = $this->getMedicalCentre();
    if (!$mc) return redirect()->route('medical_centre.dashboard');

    $request->validate([
        'name'             => 'required|string|max:255',
        'phone'            => 'nullable|string|max:20',
        'email'            => 'nullable|email|max:255',
        'address'          => 'nullable|string',
        'city'             => 'nullable|string|max:100',
        'province'         => 'nullable|string|max:100',
        'postal_code'      => 'nullable|string|max:10',
        'description'      => 'nullable|string',
        'operatinghours'   => 'nullable|string',
    ]);

    DB::table('medical_centres')
        ->where('id', $mc->id)
        ->update([
            'name'           => $request->name,
            'phone'          => $request->phone,
            'email'          => $request->email,
            'address'        => $request->address,
            'city'           => $request->city,
            'province'       => $request->province,
            'postal_code'    => $request->postal_code,
            'description'    => $request->description,
            'operatinghours' => $request->operatinghours,
            'updated_at'     => now(),
        ]);

    return back()->with('success', 'Profile information updated successfully.');
}


    // ─────────────────────────────────────────
    // Update Specializations & Facilities
    // ─────────────────────────────────────────
    public function updateServices(Request $request)
    {
        $mc = $this->getMedicalCentre();
        if (!$mc) return redirect()->route('medical_centre.dashboard');

        $request->validate([
            'specializations'   => 'nullable|array',
            'specializations.*' => 'string|max:100',
            'facilities'        => 'nullable|array',
            'facilities.*'      => 'string|max:100',
        ]);

        DB::table('medical_centres')
            ->where('id', $mc->id)
            ->update([
                'specializations' => json_encode($request->specializations ?? []),
                'facilities'      => json_encode($request->facilities ?? []),
                'updated_at'      => now(),
            ]);

        return back()->with('success', 'Specializations & facilities updated successfully.');
    }

    // ─────────────────────────────────────────
    // Update Location
    // ─────────────────────────────────────────
    public function updateLocation(Request $request)
    {
        $mc = $this->getMedicalCentre();
        if (!$mc) return redirect()->route('medical_centre.dashboard');

        $request->validate([
            'latitude'  => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'address'   => 'nullable|string',
            'city'      => 'nullable|string|max:100',
            'province'  => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
        ]);

        DB::table('medical_centres')
            ->where('id', $mc->id)
            ->update([
                'latitude'    => $request->latitude,
                'longitude'   => $request->longitude,
                'address'     => $request->address,
                'city'        => $request->city,
                'province'    => $request->province,
                'postal_code' => $request->postal_code,
                'updated_at'  => now(),
            ]);

        return back()->with('success', 'Location updated successfully.');
    }

    // ─────────────────────────────────────────
    // Update Profile Image
    // ─────────────────────────────────────────
    public function updatePhoto(Request $request)
    {
        $mc = $this->getMedicalCentre();
        if (!$mc) return redirect()->route('medical_centre.dashboard');

        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        // Delete old image
        if ($mc->profile_image) {
            Storage::disk('public')->delete($mc->profile_image);
        }

        $path = $request->file('profile_image')
            ->store('medical_centres/profiles', 'public');

        DB::table('medical_centres')
            ->where('id', $mc->id)
            ->update([
                'profile_image' => $path,
                'updated_at'    => now(),
            ]);

        return back()->with('success', 'Profile photo updated successfully.');
    }

    // ─────────────────────────────────────────
    // Delete Profile Image
    // ─────────────────────────────────────────
    public function deletePhoto()
    {
        $mc = $this->getMedicalCentre();
        if (!$mc) return redirect()->route('medical_centre.dashboard');

        if ($mc->profile_image) {
            Storage::disk('public')->delete($mc->profile_image);

            DB::table('medical_centres')
                ->where('id', $mc->id)
                ->update([
                    'profile_image' => null,
                    'updated_at'    => now(),
                ]);
        }

        return back()->with('success', 'Profile photo removed.');
    }

    // ─────────────────────────────────────────
    // Upload Document
    // ─────────────────────────────────────────
    public function uploadDocument(Request $request)
    {
        $mc = $this->getMedicalCentre();
        if (!$mc) return redirect()->route('medical_centre.dashboard');

        $request->validate([
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        // Delete old document
        if ($mc->document_path) {
            Storage::disk('public')->delete($mc->document_path);
        }

        $path = $request->file('document')
            ->store('medical_centres/documents', 'public');

        DB::table('medical_centres')
            ->where('id', $mc->id)
            ->update([
                'document_path' => $path,
                'updated_at'    => now(),
            ]);

        return back()->with('success', 'Document uploaded successfully.');
    }

    // ─────────────────────────────────────────
    // Change Password
    // ─────────────────────────────────────────
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Current password is incorrect.'])
                ->withInput();
        }

        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'password'   => Hash::make($request->password),
                'updated_at' => now(),
            ]);

        return back()->with('success', 'Password changed successfully.');
    }

    // ─────────────────────────────────────────
    // Update Account Email
    // ─────────────────────────────────────────
    public function updateEmail(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'email'    => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'required',
        ]);

        if (!Hash::check($request->password, $user->password)) {
            return back()
                ->withErrors(['password' => 'Password is incorrect.'])
                ->withInput();
        }

        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'email'              => $request->email,
                'email_verified_at'  => null,
                'updated_at'         => now(),
            ]);

        return back()->with('success', 'Email updated. Please verify your new email.');
    }
}
