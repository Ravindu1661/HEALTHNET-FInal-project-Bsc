<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PatientProfileController extends Controller
{
    public function show()
    {
        return view('patient.profile');
    }

    public function update(Request $request)
    {
        $user    = Auth::user();
        $patient = $user->patient;

        $request->validate([
            'first_name'              => 'required|string|max:100',  // ✅
            'last_name'               => 'nullable|string|max:100',  // ✅
            'nic'                     => 'required|string|max:20|unique:patients,nic,' . $patient->id,
            'phone'                   => 'required|string|max:20',
            'date_of_birth'           => 'nullable|date',
            'gender'                  => 'nullable|in:male,female,other',
            'blood_group'             => 'nullable|string|max:5',
            'address'                 => 'nullable|string|max:255',
            'city'                    => 'nullable|string|max:100',
            'province'                => 'nullable|string|max:100',
            'postal_code'             => 'nullable|string|max:10',
            'emergency_contact_name'  => 'nullable|string|max:100',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'profile_image'           => 'nullable|image|max:2048',
        ]);

        // Profile image upload
        if ($request->hasFile('profile_image')) {
            if ($patient->profile_image) {
                Storage::disk('public')->delete($patient->profile_image);
            }
            $patient->profile_image = $request->file('profile_image')
                ->store('patients/profiles', 'public');
        }

        $patient->update([
            'first_name'              => $request->first_name,   // ✅
            'last_name'               => $request->last_name,    // ✅
            'nic'                     => $request->nic,
            'phone'                   => $request->phone,
            'date_of_birth'           => $request->date_of_birth,
            'gender'                  => $request->gender,
            'blood_group'             => $request->blood_group,
            'address'                 => $request->address,
            'city'                    => $request->city,
            'province'                => $request->province,
            'postal_code'             => $request->postal_code,
            'emergency_contact_name'  => $request->emergency_contact_name,
            'emergency_contact_phone' => $request->emergency_contact_phone,
            'profile_image'           => $patient->profile_image,
        ]);

        return back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password updated successfully!');
    }
}
