<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminProfileController extends Controller
{
    // GET /profile
    public function edit()
    {
        $user = Auth::user();

        return view('admin.profile', compact('user'));
    }

    // POST /profile (same route name 'profile')
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()
            ->route('profile')
            ->with('success', 'Profile updated successfully.');
    }
}
