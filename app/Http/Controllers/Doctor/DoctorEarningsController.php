<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorEarningsController extends Controller
{
    public function index()
    {
        return view('doctor.earnings.index');
    }

    public function export()
    {
        // Export earnings report
        return response()->download('earnings.pdf');
    }

    public function statistics()
    {
        return response()->json(['success' => true]);
    }
}
