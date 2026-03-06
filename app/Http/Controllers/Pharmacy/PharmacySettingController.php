<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PharmacySettingController extends Controller
{
    /* ─────────────────────────────────────────
     |  SETTINGS PAGE
     ─────────────────────────────────────────*/
    public function index()
    {
        $pharmacy = Auth::user()->pharmacy;

        if (!$pharmacy) {
            return redirect()->route('pharmacy.profile.create')
                ->with('error', 'Please complete your pharmacy profile first.');
        }

        return view('pharmacy.settings.index', compact('pharmacy'));
    }

    /* ─────────────────────────────────────────
     |  UPDATE SETTINGS
     ─────────────────────────────────────────*/
    public function update(Request $request)
    {
        $pharmacy = Auth::user()->pharmacy;

        $validated = $request->validate([
            'operating_hours'    => 'nullable|string|max:1000',
            'delivery_available' => 'boolean',
        ]);

        $validated['delivery_available'] = $request->boolean('delivery_available');

        $pharmacy->update($validated);

        return back()->with('success', 'Settings updated successfully.');
    }

    /* ─────────────────────────────────────────
     |  CHANGE PASSWORD
     ─────────────────────────────────────────*/
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Current password is incorrect.'])
                ->withInput();
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password changed successfully.');
    }

    /* ─────────────────────────────────────────
     |  ACCOUNT PAGE
     ─────────────────────────────────────────*/
    public function account()
    {
        $user     = Auth::user();
        $pharmacy = $user->pharmacy;

        return view('pharmacy.settings.account', compact('user', 'pharmacy'));
    }

    /* ─────────────────────────────────────────
     |  UPDATE ACCOUNT
     ─────────────────────────────────────────*/
    public function updateAccount(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        return back()->with('success', 'Account updated successfully.');
    }
}
