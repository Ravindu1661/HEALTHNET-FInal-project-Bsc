<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorProfileController extends Controller
{
    public function edit()
    {
        $doctor = Auth::user()->doctor;
        return view('doctor.profile.edit', compact('doctor'));
    }

    public function update(Request $request)
    {
        return redirect()->route('doctor.profile.edit')
            ->with('success', 'Profile updated successfully');
    }

    public function updateImage(Request $request)
    {
        return response()->json(['success' => true]);
    }

    public function updatePassword(Request $request)
    {
        return response()->json(['success' => true]);
    }
}
