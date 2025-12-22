<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use App\Models\PharmacyOrder;

class PharmacyPatientController extends Controller
{
    /**
     * Display patients list
     */
    public function index(Request $request)
    {
        $pharmacy = Auth::user()->pharmacy;

        // Get unique patients who have ordered from this pharmacy
        $patientIds = PharmacyOrder::where('pharmacy_id', $pharmacy->id)
            ->distinct()
            ->pluck('patient_id');

        $query = Patient::whereIn('id', $patientIds)
            ->with('user');

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $patients = $query->paginate(20);

        return view('pharmacy.patients.index', compact('patients'));
    }

    /**
     * Show patient details
     */
    public function show(Patient $patient)
    {
        $pharmacy = Auth::user()->pharmacy;

        // Check if patient has ordered from this pharmacy
        $hasOrdered = PharmacyOrder::where('pharmacy_id', $pharmacy->id)
            ->where('patient_id', $patient->id)
            ->exists();

        if (!$hasOrdered) {
            abort(403, 'Unauthorized action.');
        }

        $patient->load('user');

        // Get orders count
        $ordersCount = PharmacyOrder::where('pharmacy_id', $pharmacy->id)
            ->where('patient_id', $patient->id)
            ->count();

        // Get total spent
        $totalSpent = PharmacyOrder::where('pharmacy_id', $pharmacy->id)
            ->where('patient_id', $patient->id)
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        return view('pharmacy.patients.show', compact('patient', 'ordersCount', 'totalSpent'));
    }

    /**
     * Show patient orders
     */
    public function orders(Patient $patient)
    {
        $pharmacy = Auth::user()->pharmacy;

        $orders = PharmacyOrder::where('pharmacy_id', $pharmacy->id)
            ->where('patient_id', $patient->id)
            ->with('items.medication')
            ->latest()
            ->paginate(20);

        return view('pharmacy.patients.orders', compact('patient', 'orders'));
    }

    /**
     * Show patient prescriptions
     */
    public function prescriptions(Patient $patient)
    {
        $pharmacy = Auth::user()->pharmacy;

        $prescriptions = PharmacyOrder::where('pharmacy_id', $pharmacy->id)
            ->where('patient_id', $patient->id)
            ->whereNotNull('prescription_file')
            ->latest()
            ->paginate(20);

        return view('pharmacy.patients.prescriptions', compact('patient', 'prescriptions'));
    }
}
