<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Patient;
use App\Models\PharmacyOrder;

class PharmacyPatientController extends Controller
{
    /* ─────────────────────────────────────────
     |  HELPER
     ─────────────────────────────────────────*/
    private function pharmacy()
    {
        return Auth::user()->pharmacy;
    }

    /* ─────────────────────────────────────────
     |  INDEX — Patient List
     ─────────────────────────────────────────*/
    public function index(Request $request)
    {
        $pharmacy = $this->pharmacy();

        // Unique patient IDs who ordered from this pharmacy
        $patientIds = PharmacyOrder::where('pharmacy_id', $pharmacy->id)
            ->distinct()
            ->pluck('patient_id');

        $query = Patient::whereIn('id', $patientIds)->with('user');

        // Search
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('first_name', 'like', "%{$s}%")
                  ->orWhere('last_name',  'like', "%{$s}%")
                  ->orWhere('phone',      'like', "%{$s}%")
                  ->orWhere('nic',        'like', "%{$s}%")
                  ->orWhere('city',       'like', "%{$s}%");
            });
        }

        // Filter by gender
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // Filter by blood group
        if ($request->filled('blood_group')) {
            $query->where('blood_group', $request->blood_group);
        }

        // Sort
        $sortBy  = $request->get('sort_by', 'created_at');
        $sortDir = $request->get('sort_dir', 'desc');
        $allowed = ['first_name', 'last_name', 'created_at', 'city'];
        if (!in_array($sortBy, $allowed)) $sortBy = 'created_at';

        $patients = $query->orderBy($sortBy, $sortDir)->paginate(20)->withQueryString();

        // Attach per-patient order stats using a sub-query
        $statsMap = PharmacyOrder::where('pharmacy_id', $pharmacy->id)
            ->whereIn('patient_id', $patientIds)
            ->select(
                'patient_id',
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(CASE WHEN payment_status = "paid" THEN total_amount ELSE 0 END) as total_spent'),
                DB::raw('MAX(created_at) as last_order_date')
            )
            ->groupBy('patient_id')
            ->get()
            ->keyBy('patient_id');

        // Summary stats
        $totalPatients  = $patientIds->count();
        $totalOrdersAll = PharmacyOrder::where('pharmacy_id', $pharmacy->id)->count();
        $totalRevenue   = PharmacyOrder::where('pharmacy_id', $pharmacy->id)
            ->where('payment_status', 'paid')->sum('total_amount');

        return view('pharmacy.patients.index', compact(
            'patients', 'statsMap', 'totalPatients', 'totalOrdersAll', 'totalRevenue'
        ));
    }

    /* ─────────────────────────────────────────
     |  SHOW — Patient Profile
     ─────────────────────────────────────────*/
    public function show(Patient $patient)
    {
        $pharmacy = $this->pharmacy();

        // Authorization — only patients who ordered from this pharmacy
        $hasOrdered = PharmacyOrder::where('pharmacy_id', $pharmacy->id)
            ->where('patient_id', $patient->id)
            ->exists();

        if (!$hasOrdered) abort(403, 'Unauthorized action.');

        $patient->load('user');

        // Order Statistics
        $ordersCount = PharmacyOrder::where('pharmacy_id', $pharmacy->id)
            ->where('patient_id', $patient->id)->count();

        $totalSpent = PharmacyOrder::where('pharmacy_id', $pharmacy->id)
            ->where('patient_id', $patient->id)
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        $pendingOrders = PharmacyOrder::where('pharmacy_id', $pharmacy->id)
            ->where('patient_id', $patient->id)
            ->where('status', 'pending')->count();

        $deliveredOrders = PharmacyOrder::where('pharmacy_id', $pharmacy->id)
            ->where('patient_id', $patient->id)
            ->where('status', 'delivered')->count();

        $cancelledOrders = PharmacyOrder::where('pharmacy_id', $pharmacy->id)
            ->where('patient_id', $patient->id)
            ->where('status', 'cancelled')->count();

        $lastOrder = PharmacyOrder::where('pharmacy_id', $pharmacy->id)
            ->where('patient_id', $patient->id)
            ->latest()->first();

        // Recent 5 orders
        $recentOrders = PharmacyOrder::where('pharmacy_id', $pharmacy->id)
            ->where('patient_id', $patient->id)
            ->with('items')
            ->latest()
            ->limit(5)
            ->get();

        // Most ordered medicines
        $topMedicines = DB::table('prescription_order_items as oi')
            ->join('prescription_orders as po', 'po.id', '=', 'oi.order_id')
            ->where('po.pharmacy_id', $pharmacy->id)
            ->where('po.patient_id', $patient->id)
            ->whereNotNull('oi.medication_id')
            ->select(
                'oi.medication_name',
                DB::raw('SUM(oi.quantity) as total_qty'),
                DB::raw('COUNT(oi.id) as order_count')
            )
            ->groupBy('oi.medication_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        return view('pharmacy.patients.show', compact(
            'patient', 'ordersCount', 'totalSpent', 'pendingOrders',
            'deliveredOrders', 'cancelledOrders', 'lastOrder',
            'recentOrders', 'topMedicines'
        ));
    }

    /* ─────────────────────────────────────────
     |  ORDERS — All Orders of a Patient
     ─────────────────────────────────────────*/
    public function orders(Request $request, Patient $patient)
    {
        $pharmacy = $this->pharmacy();

        $hasOrdered = PharmacyOrder::where('pharmacy_id', $pharmacy->id)
            ->where('patient_id', $patient->id)->exists();
        if (!$hasOrdered) abort(403, 'Unauthorized action.');

        $patient->load('user');

        $query = PharmacyOrder::where('pharmacy_id', $pharmacy->id)
            ->where('patient_id', $patient->id)
            ->with('items.medication');

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        // Summary
        $totalOrders  = PharmacyOrder::where('pharmacy_id', $pharmacy->id)
            ->where('patient_id', $patient->id)->count();
        $totalSpent   = PharmacyOrder::where('pharmacy_id', $pharmacy->id)
            ->where('patient_id', $patient->id)
            ->where('payment_status', 'paid')->sum('total_amount');

        return view('pharmacy.patients.orders', compact(
            'patient', 'orders', 'totalOrders', 'totalSpent'
        ));
    }

    /* ─────────────────────────────────────────
     |  PRESCRIPTIONS — Prescription Files
     ─────────────────────────────────────────*/
    public function prescriptions(Request $request, Patient $patient)
    {
        $pharmacy = $this->pharmacy();

        $hasOrdered = PharmacyOrder::where('pharmacy_id', $pharmacy->id)
            ->where('patient_id', $patient->id)->exists();
        if (!$hasOrdered) abort(403, 'Unauthorized action.');

        $patient->load('user');

        $query = PharmacyOrder::where('pharmacy_id', $pharmacy->id)
            ->where('patient_id', $patient->id)
            ->whereNotNull('prescription_file');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $prescriptions = $query->latest()->paginate(12)->withQueryString();

        $prescriptionCount = PharmacyOrder::where('pharmacy_id', $pharmacy->id)
            ->where('patient_id', $patient->id)
            ->whereNotNull('prescription_file')->count();

        return view('pharmacy.patients.prescriptions', compact(
            'patient', 'prescriptions', 'prescriptionCount'
        ));
    }
}
