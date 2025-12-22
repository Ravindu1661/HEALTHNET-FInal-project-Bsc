<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorSettingsController extends Controller
{
    public function index()
    {
        return view('doctor.settings.index');
    }

    public function update(Request $request)
    {
        return redirect()->route('doctor.settings')
            ->with('success', 'Settings updated successfully');
    }
}
