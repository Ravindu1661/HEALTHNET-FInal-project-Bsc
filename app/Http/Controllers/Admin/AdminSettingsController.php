<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminSettingsController extends Controller
{
    public function index()
    {
        // view එකේ අපි old() + config() use කරන නිසා මෙහි data pass කරන්නම නෑ
        return view('admin.settings');
    }

    public function updateGeneral(Request $request)
    {
        $data = $request->validate([
            'system_name'   => ['nullable', 'string', 'max:255'],
            'support_email' => ['nullable', 'email'],
            'support_phone' => ['nullable', 'string', 'max:50'],
            'timezone'      => ['nullable', 'string'],
        ]);

        // TODO: later settings table / .env update logic add කරන්න
        // මේ stage එකේ simple success message පමණයි
        return back()->with('success', 'General settings updated (not persisted yet).');
    }

    public function updateMail(Request $request)
    {
        $data = $request->validate([
            'mail_host'          => ['nullable', 'string', 'max:255'],
            'mail_port'          => ['nullable', 'integer'],
            'mail_username'      => ['nullable', 'string', 'max:255'],
            'mail_from_name'     => ['nullable', 'string', 'max:255'],
            'mail_from_address'  => ['nullable', 'email'],
            'mail_encryption'    => ['nullable', 'in:tls,ssl'],
            'mail_password'      => ['nullable', 'string'],
        ]);

        // TODO: later settings save logic add කරන්න
        return back()->with('success', 'Email settings updated (not persisted yet).');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Current password is incorrect.');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password updated successfully.');
    }
}
