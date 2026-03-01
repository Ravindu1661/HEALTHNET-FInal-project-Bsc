<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DoctorEarningsController extends Controller
{
    private function getDoctor()
    {
        $doctor = DB::table('doctors')
            ->where('user_id', Auth::id())
            ->first();

        if (!$doctor) abort(403, 'Doctor profile not found.');
        return $doctor;
    }

    public function index(Request $request)
    {
        $doctor = $this->getDoctor();

        // ── Date Range ──
        $period   = $request->get('period', 'this_month');
        $dateFrom = $request->get('date_from');
        $dateTo   = $request->get('date_to');

        switch ($period) {
            case 'today':
                $dateFrom = today()->toDateString();
                $dateTo   = today()->toDateString();
                break;
            case 'this_week':
                $dateFrom = now()->startOfWeek()->toDateString();
                $dateTo   = now()->endOfWeek()->toDateString();
                break;
            case 'this_month':
                $dateFrom = now()->startOfMonth()->toDateString();
                $dateTo   = now()->endOfMonth()->toDateString();
                break;
            case 'last_month':
                $dateFrom = now()->subMonth()->startOfMonth()->toDateString();
                $dateTo   = now()->subMonth()->endOfMonth()->toDateString();
                break;
            case 'this_year':
                $dateFrom = now()->startOfYear()->toDateString();
                $dateTo   = now()->endOfYear()->toDateString();
                break;
            case 'custom':
                $dateFrom = $dateFrom ?? now()->startOfMonth()->toDateString();
                $dateTo   = $dateTo   ?? now()->toDateString();
                break;
            default:
                $dateFrom = now()->startOfMonth()->toDateString();
                $dateTo   = now()->endOfMonth()->toDateString();
        }

        // ── Summary Stats ──
        $summary = DB::table('appointments')
            ->where('doctor_id', $doctor->id)
            ->where('status', 'completed')
            ->whereBetween(DB::raw('DATE(appointment_date)'), [$dateFrom, $dateTo])
            ->selectRaw('
                COUNT(*)                   AS total_appointments,
                SUM(consultation_fee)      AS total_earnings,
                AVG(consultation_fee)      AS avg_fee,
                MAX(consultation_fee)      AS max_fee
            ')
            ->first();

        // ── All Time Earnings ──
        $allTime = DB::table('appointments')
            ->where('doctor_id', $doctor->id)
            ->where('status', 'completed')
            ->sum('consultation_fee');

        // ── Pending Payments (completed but unpaid/partial) ──
        $pendingAmount = DB::table('appointments')
            ->where('doctor_id', $doctor->id)
            ->where('status', 'completed')
            ->whereIn('payment_status', ['unpaid', 'partial'])
            ->sum('consultation_fee');

        // ── Monthly Trend (last 6 months) ──
        $monthlyTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $m = Carbon::now()->subMonths($i);
            $monthlyTrend[] = [
                'month'    => $m->format('M Y'),
                'earnings' => (float) DB::table('appointments')
                    ->where('doctor_id', $doctor->id)
                    ->where('status', 'completed')
                    ->whereYear('appointment_date', $m->year)
                    ->whereMonth('appointment_date', $m->month)
                    ->sum('consultation_fee'),
                'count'    => DB::table('appointments')
                    ->where('doctor_id', $doctor->id)
                    ->where('status', 'completed')
                    ->whereYear('appointment_date', $m->year)
                    ->whereMonth('appointment_date', $m->month)
                    ->count(),
            ];
        }

        // ── By Workplace ──
        // workplace_type: 'hospital' | 'medical_centre' | 'private'
        $byWorkplace = DB::table('appointments')
            ->leftJoin('hospitals', function ($join) {
                $join->on('appointments.workplace_id', '=', 'hospitals.id')
                     ->where('appointments.workplace_type', '=', 'hospital');
            })
            ->leftJoin('medical_centres', function ($join) {
                $join->on('appointments.workplace_id', '=', 'medical_centres.id')
                     ->where('appointments.workplace_type', '=', 'medical_centre');
            })
            ->where('appointments.doctor_id', $doctor->id)
            ->where('appointments.status', 'completed')
            ->whereBetween(DB::raw('DATE(appointments.appointment_date)'), [$dateFrom, $dateTo])
            ->selectRaw('
                COALESCE(hospitals.name, medical_centres.name, "Private Clinic") AS workplace,
                appointments.workplace_type,
                COUNT(*)                              AS count,
                SUM(appointments.consultation_fee)    AS earnings
            ')
            ->groupBy('appointments.workplace_type', 'appointments.workplace_id',
                      'hospitals.name', 'medical_centres.name')
            ->orderByDesc('earnings')
            ->get();

        // ── By Appointment Type ──
        $byType = DB::table('appointments')
            ->where('doctor_id', $doctor->id)
            ->where('status', 'completed')
            ->whereBetween(DB::raw('DATE(appointment_date)'), [$dateFrom, $dateTo])
            ->selectRaw('
                COALESCE(workplace_type, "private") AS type,
                COUNT(*)              AS count,
                SUM(consultation_fee) AS earnings
            ')
            ->groupBy('workplace_type')
            ->get();

        // ── Transactions (paginated) ──
        $transactions = DB::table('appointments')
            ->join('patients', 'appointments.patient_id', '=', 'patients.id')
            ->leftJoin('hospitals', function ($join) {
                $join->on('appointments.workplace_id', '=', 'hospitals.id')
                     ->where('appointments.workplace_type', '=', 'hospital');
            })
            ->leftJoin('medical_centres', function ($join) {
                $join->on('appointments.workplace_id', '=', 'medical_centres.id')
                     ->where('appointments.workplace_type', '=', 'medical_centre');
            })
            ->where('appointments.doctor_id', $doctor->id)
            ->where('appointments.status', 'completed')
            ->whereBetween(DB::raw('DATE(appointments.appointment_date)'), [$dateFrom, $dateTo])
            ->select(
                'appointments.id',
                'appointments.appointment_number',
                'appointments.appointment_date',
                'appointments.appointment_time',
                'appointments.consultation_fee',
                'appointments.advance_payment',
                'appointments.payment_status',
                'appointments.workplace_type',
                'appointments.reason',
                DB::raw("CONCAT(patients.first_name, ' ', patients.last_name) AS patient_name"),
                DB::raw("COALESCE(hospitals.name, medical_centres.name, 'Private Clinic') AS workplace")
            )
            ->orderByDesc('appointments.appointment_date')
            ->orderByDesc('appointments.appointment_time')
            ->paginate(15)
            ->appends($request->query());

        return view('doctor.earnings.index', compact(
            'doctor',
            'summary',
            'allTime',
            'pendingAmount',
            'monthlyTrend',
            'byWorkplace',
            'byType',
            'transactions',
            'period',
            'dateFrom',
            'dateTo'
        ));
    }
}
