<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Laboratory;
use Illuminate\Http\Request;

class PatientLaboratoryController extends Controller
{
    /**
     * Display a listing of laboratories
     */
    public function index(Request $request)
    {
        // Start query
        $query = Laboratory::query();

        // Only approved laboratories
        $query->where('status', 'approved');

        // Search filter
        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', $search)
                  ->orWhere('city', 'like', $search)
                  ->orWhere('address', 'like', $search);
            });
        }

        // Filter by city
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        // Get unique cities for filter
        $cities = Laboratory::where('status', 'approved')
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        // Paginate results
        $laboratories = $query->orderBy('rating', 'desc')
            ->orderBy('name', 'asc')
            ->paginate(12);

        return view('patient.laboratories', compact('laboratories', 'cities'));
    }

    /**
     * Display the specified laboratory
     */
    public function show($id)
    {
        // Get laboratory with relationships
        $laboratory = Laboratory::with('user')
            ->where('status', 'approved')
            ->findOrFail($id);

        // Parse services
        $services = is_array($laboratory->services)
            ? $laboratory->services
            : json_decode($laboratory->services, true) ?? [];

        return view('patient.laboratory-profile', compact('laboratory', 'services'));
    }
}
