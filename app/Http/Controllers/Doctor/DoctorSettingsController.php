<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DoctorSettingsController extends Controller
{
    private function getDoctor()
    {
        return Doctor::where('user_id', Auth::id())->firstOrFail();
    }

    // ══════════════════════════════════════════
    //  INDEX
    // ══════════════════════════════════════════
    public function index()
    {
        $doctor = $this->getDoctor();
        $user   = Auth::user();
        return view('doctor.settings.index', compact('doctor', 'user'));
    }

    // ══════════════════════════════════════════
    //  UPDATE
    // ══════════════════════════════════════════
    public function update(Request $request)
    {
        $user   = Auth::user();
        $doctor = $this->getDoctor();

        $request->validate([
            'email'            => 'required|email|unique:users,email,' . $user->id,
            'consultation_fee' => 'nullable|numeric|min:0',
            'slmc_number'      => 'required|string|max:50|unique:doctors,slmc_number,' . $doctor->id,
        ]);

        // Update user email
        $user->update(['email' => $request->email]);

        // Update doctor settings
        $doctor->update([
            'slmc_number'      => $request->slmc_number,
            'consultation_fee' => $request->consultation_fee,
        ]);

        return redirect()->route('doctor.settings')
                         ->with('success', 'Settings updated successfully.');
    }
}
