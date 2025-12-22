<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorScheduleController extends Controller
{
    public function index()
    {
        return view('doctor.schedule.index');
    }

    public function create()
    {
        return view('doctor.schedule.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('doctor.schedule.index')
            ->with('success', 'Schedule created successfully');
    }

    public function edit($id)
    {
        return view('doctor.schedule.edit');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('doctor.schedule.index')
            ->with('success', 'Schedule updated successfully');
    }

    public function destroy($id)
    {
        return response()->json(['success' => true]);
    }

    public function toggleStatus($id)
    {
        return response()->json(['success' => true]);
    }
}
