<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorReviewController extends Controller
{
    public function index()
    {
        return view('doctor.reviews.index');
    }

    public function show($id)
    {
        return view('doctor.reviews.show');
    }

    public function reply($id)
    {
        return response()->json(['success' => true]);
    }
}
