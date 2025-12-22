<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use Illuminate\Http\Request;

class PatientPharmacyController extends Controller
{
    /**
     * Display a listing of pharmacies
     */
    public function index(Request $request)
    {
        // Start query
        $query = Pharmacy::query();

        // Only approved pharmacies
        $query->where('status', 'approved');

        // Search filter
        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', $search)
                  ->orWhere('city', 'like', $search)
                  ->orWhere('address', 'like', $search)
                  ->orWhere('pharmacist_name', 'like', $search);
            });
        }

        // Filter by city
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        // Filter by delivery available
        if ($request->filled('delivery')) {
            $query->where('delivery_available', $request->delivery == 'yes' ? 1 : 0);
        }

        // Get unique cities for filter
        $cities = Pharmacy::where('status', 'approved')
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        // Paginate results
        $pharmacies = $query->orderBy('rating', 'desc')
            ->orderBy('name', 'asc')
            ->paginate(12);

        return view('patient.pharmacies', compact('pharmacies', 'cities'));
    }

    /**
     * Display the specified pharmacy
     */
    public function show($id)
    {
        // Get pharmacy with relationships
        $pharmacy = Pharmacy::with('user')
            ->where('status', 'approved')
            ->findOrFail($id);

        return view('patient.pharmacy-profile', compact('pharmacy'));
    }
}
