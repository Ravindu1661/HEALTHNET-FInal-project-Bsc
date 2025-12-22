<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PharmacySettingController extends Controller
{
    /**
     * Show settings page
     */
    public function index()
    {
        $pharmacy = Auth::user()->pharmacy;

        return view('pharmacy.settings.index', compact('pharmacy'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $pharmacy = Auth::user()->pharmacy;

        $validatedData = $request->validate([
            'operating_hours' => 'nullable|string',
            'delivery_available' => 'boolean',
        ]);

        $pharmacy->update($validatedData);

        return back()->with('success', 'Settings updated successfully.');
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Current password is incorrect.');
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password changed successfully.');
    }

    /**
     * Account settings
     */
    public function account()
    {
        $user = Auth::user();

        return view('pharmacy.settings.account', compact('user'));
    }

    /**
     * Update account
     */
    public function updateAccount(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($validatedData);

        return back()->with('success', 'Account updated successfully.');
    }
}
