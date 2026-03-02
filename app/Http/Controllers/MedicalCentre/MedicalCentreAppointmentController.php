<?php

namespace App\Http\Controllers\MedicalCentre;

use App\Http\Controllers\Controller;
use App\Models\MedicalCentre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MedicalCentreAppointmentController extends Controller
{
    private function getMedicalCentre(): MedicalCentre
    {
        return MedicalCentre::where('user_id', Auth::id())->firstOrFail();
    }

    // ══════════════════════════════════════════
    // INDEX — main page with all data
    // ══════════════════════════════════════════
    public function index(Request $request)
    {
        $mc   = $this->getMedicalCentre();
        $mcId = $mc->id;

        // Filters
        $status        = $request->get('status', '');
        $doctorId      = $request->get('doctor_id', '');
        $paymentStatus = $request->get('payment_status', '');
        $dateFrom      = $request->get('date_from', '');
        $dateTo        = $request->get('date_to', '');
        $search        = $request->get('search', '');
        $perPage       = $request->get('per_page', 15);

        // ── Appointments query ──
        $query = DB::table('appointments')
            ->join('patients', 'appointments.patient_id', '=', 'patients.id')
            ->join('doctors',  'appointments.doctor_id',  '=', 'doctors.id')
            ->where('appointments.workplace_type', 'medical_centre')
            ->where('appointments.workplace_id',   $mcId)
            ->select(
                'appointments.id',
                'appointments.appointment_number',
                'appointments.appointment_date',
                'appointments.appointment_time',
                'appointments.status',
                'appointments.reason',
                'appointments.notes',
                'appointments.consultation_fee',
                'appointments.advance_payment',
                'appointments.payment_status',
                'appointments.cancellation_reason',
                'appointments.cancelled_by',
                'appointments.created_at',
                DB::raw("CONCAT(patients.first_name,' ',patients.last_name) AS patient_name"),
                'patients.phone    AS patient_phone',
                'patients.id       AS patient_id',
                DB::raw("CONCAT(doctors.first_name,' ',doctors.last_name)  AS doctor_name"),
                'doctors.specialization',
                'doctors.id        AS doctor_id',
            );

        if ($status)        $query->where('appointments.status', $status);
        if ($doctorId)      $query->where('appointments.doctor_id', $doctorId);
        if ($paymentStatus) $query->where('appointments.payment_status', $paymentStatus);
        if ($dateFrom)      $query->whereDate('appointments.appointment_date', '>=', $dateFrom);
        if ($dateTo)        $query->whereDate('appointments.appointment_date', '<=', $dateTo);
        if ($search) {
            $s = '%' . $search . '%';
            $query->where(function ($q) use ($s) {
                $q->where('appointments.appointment_number', 'like', $s)
                  ->orWhereRaw("CONCAT(patients.first_name,' ',patients.last_name) LIKE ?", [$s])
                  ->orWhereRaw("CONCAT(doctors.first_name,' ',doctors.last_name) LIKE ?",  [$s])
                  ->orWhere('patients.phone', 'like', $s);
            });
        }

        $appointments = $query
            ->orderBy('appointments.appointment_date', 'desc')
            ->orderBy('appointments.appointment_time', 'asc')
            ->paginate($perPage)
            ->withQueryString();

        // ── Summary counts ──
        $summary = DB::table('appointments')
            ->where('workplace_type', 'medical_centre')
            ->where('workplace_id',   $mcId)
            ->selectRaw("
                COUNT(*) AS total,
                SUM(status='pending')   AS pending,
                SUM(status='confirmed') AS confirmed,
                SUM(status='completed') AS completed,
                SUM(status='cancelled') AS cancelled,
                SUM(status='noshow')    AS noshow,
                SUM(payment_status='unpaid')  AS unpaid,
                SUM(payment_status='partial') AS partial,
                SUM(payment_status='paid')    AS paid
            ")
            ->first();

        // ── Stats ──
        $today = Carbon::today();
        $stats = [
            'today'        => DB::table('appointments')
                                ->where('workplace_type','medical_centre')
                                ->where('workplace_id', $mcId)
                                ->whereDate('appointment_date', $today)
                                ->count(),
            'this_month'   => DB::table('appointments')
                                ->where('workplace_type','medical_centre')
                                ->where('workplace_id', $mcId)
                                ->whereBetween('appointment_date',[
                                    Carbon::now()->startOfMonth(),
                                    Carbon::now()->endOfMonth(),
                                ])->count(),
            'pending'      => $summary->pending  ?? 0,
            'revenue'      => DB::table('appointments')
                                ->where('workplace_type','medical_centre')
                                ->where('workplace_id', $mcId)
                                ->where('status','completed')
                                ->whereMonth('appointment_date', Carbon::now()->month)
                                ->sum('consultation_fee'),
            'unpaid'       => DB::table('appointments')
                                ->where('workplace_type','medical_centre')
                                ->where('workplace_id', $mcId)
                                ->whereIn('status',['pending','confirmed'])
                                ->where('payment_status','unpaid')
                                ->count(),
        ];

        // ── Doctors dropdown ──
        $doctors = DB::table('doctor_workplaces')
            ->join('doctors','doctor_workplaces.doctor_id','=','doctors.id')
            ->where('doctor_workplaces.workplace_type','medical_centre')
            ->where('doctor_workplaces.workplace_id',  $mcId)
            ->where('doctor_workplaces.status','approved')
            ->select(
                'doctors.id',
                DB::raw("CONCAT(doctors.first_name,' ',doctors.last_name) AS name"),
                'doctors.specialization'
            )
            ->orderBy('doctors.first_name')
            ->get();

        // ── Filters for view ──
        $filters = compact(
            'status','doctorId','paymentStatus',
            'dateFrom','dateTo','search','perPage'
        );

        return view('medical_centre.appointments.index', compact(
            'appointments', 'summary', 'stats', 'doctors', 'filters', 'mc'
        ));
    }

    // ══════════════════════════════════════════
    // SHOW — detail (used in modal via redirect)
    // ══════════════════════════════════════════
    public function show($id)
    {
        $mcId = $this->getMedicalCentre()->id;

        $appointment = DB::table('appointments')
            ->join('patients', 'appointments.patient_id', '=', 'patients.id')
            ->join('users AS pu','patients.user_id','=','pu.id')
            ->join('doctors',  'appointments.doctor_id',  '=', 'doctors.id')
            ->join('users AS du','doctors.user_id','=','du.id')
            ->where('appointments.id',             $id)
            ->where('appointments.workplace_type', 'medical_centre')
            ->where('appointments.workplace_id',   $mcId)
            ->select(
                'appointments.*',
                DB::raw("CONCAT(patients.first_name,' ',patients.last_name) AS patient_name"),
                'patients.phone         AS patient_phone',
                'patients.nic           AS patient_nic',
                'patients.date_of_birth AS patient_dob',
                'patients.gender        AS patient_gender',
                'patients.blood_group   AS patient_blood_group',
                'patients.address       AS patient_address',
                'patients.city          AS patient_city',
                'patients.profile_image AS patient_image',
                'patients.id            AS patient_id',
                'pu.email               AS patient_email',
                DB::raw("CONCAT(doctors.first_name,' ',doctors.last_name) AS doctor_name"),
                'doctors.specialization',
                'doctors.phone          AS doctor_phone',
                'doctors.profile_image  AS doctor_image',
                'doctors.id             AS doctor_id',
                'du.email               AS doctor_email',
            )
            ->firstOrFail();

        $payments = DB::table('payments')
            ->where('related_type','appointment')
            ->where('related_id',  $id)
            ->orderBy('payment_date','desc')
            ->get();

        return view('medical_centre.appointments.show', compact('appointment','payments'));
    }

    // ══════════════════════════════════════════
    // CONFIRM
    // ══════════════════════════════════════════
    public function confirm(Request $request, $id)
    {
        $mcId = $this->getMedicalCentre()->id;
        $apt  = DB::table('appointments')
                    ->where('id',$id)
                    ->where('workplace_type','medical_centre')
                    ->where('workplace_id',$mcId)
                    ->first();

        if (!$apt) return back()->with('error','Appointment not found.');
        if ($apt->status !== 'pending') return back()->with('error','Only pending appointments can be confirmed.');

        DB::table('appointments')->where('id',$id)->update(['status'=>'confirmed','updated_at'=>now()]);
        $this->notifyPatient($apt->patient_id,'Appointment Confirmed',"Your appointment #{$apt->appointment_number} has been confirmed.",$id);

        return back()->with('success','Appointment confirmed successfully.');
    }

    // ══════════════════════════════════════════
    // COMPLETE
    // ══════════════════════════════════════════
    public function complete(Request $request, $id)
    {
        $mcId = $this->getMedicalCentre()->id;
        $apt  = DB::table('appointments')
                    ->where('id',$id)
                    ->where('workplace_type','medical_centre')
                    ->where('workplace_id',$mcId)
                    ->first();

        if (!$apt) return back()->with('error','Appointment not found.');
        if (!in_array($apt->status,['pending','confirmed'])) return back()->with('error','Cannot complete this appointment.');

        DB::table('appointments')->where('id',$id)->update(['status'=>'completed','updated_at'=>now()]);
        $this->notifyPatient($apt->patient_id,'Appointment Completed',"Your appointment #{$apt->appointment_number} has been marked as completed.",$id);

        return back()->with('success','Appointment marked as completed.');
    }

    // ══════════════════════════════════════════
    // CANCEL
    // ══════════════════════════════════════════
    public function cancel(Request $request, $id)
    {
        $request->validate(['cancellation_reason' => ['required','string','max:500']]);

        $mcId = $this->getMedicalCentre()->id;
        $apt  = DB::table('appointments')
                    ->where('id',$id)
                    ->where('workplace_type','medical_centre')
                    ->where('workplace_id',$mcId)
                    ->first();

        if (!$apt) return back()->with('error','Appointment not found.');
        if (in_array($apt->status,['completed','cancelled'])) return back()->with('error','Cannot cancel this appointment.');

        DB::table('appointments')->where('id',$id)->update([
            'status'              => 'cancelled',
            'cancelled_by'        => Auth::id(),
            'cancellation_reason' => $request->cancellation_reason,
            'updated_at'          => now(),
        ]);
        $this->notifyPatient($apt->patient_id,'Appointment Cancelled',"Your appointment #{$apt->appointment_number} has been cancelled. Reason: {$request->cancellation_reason}",$id);

        return back()->with('success','Appointment cancelled.');
    }

    // ══════════════════════════════════════════
    // NO-SHOW
    // ══════════════════════════════════════════
    public function noShow(Request $request, $id)
    {
        $mcId = $this->getMedicalCentre()->id;
        $apt  = DB::table('appointments')
                    ->where('id',$id)
                    ->where('workplace_type','medical_centre')
                    ->where('workplace_id',$mcId)
                    ->first();

        if (!$apt) return back()->with('error','Appointment not found.');
        if (!in_array($apt->status,['pending','confirmed'])) return back()->with('error','Cannot mark as no-show.');

        DB::table('appointments')->where('id',$id)->update(['status'=>'noshow','updated_at'=>now()]);

        return back()->with('success','Marked as no-show.');
    }

    // ══════════════════════════════════════════
    // UPDATE PAYMENT
    // ══════════════════════════════════════════
    public function updatePayment(Request $request, $id)
    {
        $request->validate([
            'payment_status'  => ['required','in:unpaid,partial,paid'],
            'advance_payment' => ['nullable','numeric','min:0'],
        ]);

        $mcId = $this->getMedicalCentre()->id;
        $apt  = DB::table('appointments')
                    ->where('id',$id)
                    ->where('workplace_type','medical_centre')
                    ->where('workplace_id',$mcId)
                    ->first();

        if (!$apt) return back()->with('error','Appointment not found.');

        DB::table('appointments')->where('id',$id)->update([
            'payment_status'  => $request->payment_status,
            'advance_payment' => $request->advance_payment ?? $apt->advance_payment,
            'updated_at'      => now(),
        ]);

        return back()->with('success','Payment status updated.');
    }

    // ══════════════════════════════════════════
    // EXPORT CSV
    // ══════════════════════════════════════════
    public function export(Request $request)
    {
        $mcId = $this->getMedicalCentre()->id;

        $rows = DB::table('appointments')
            ->join('patients','appointments.patient_id','=','patients.id')
            ->join('doctors', 'appointments.doctor_id', '=','doctors.id')
            ->where('appointments.workplace_type','medical_centre')
            ->where('appointments.workplace_id',  $mcId)
            ->when($request->filled('status'),    fn($q)=>$q->where('appointments.status',$request->status))
            ->when($request->filled('date_from'), fn($q)=>$q->whereDate('appointments.appointment_date','>=',$request->date_from))
            ->when($request->filled('date_to'),   fn($q)=>$q->whereDate('appointments.appointment_date','<=',$request->date_to))
            ->select(
                'appointments.appointment_number',
                'appointments.appointment_date',
                'appointments.appointment_time',
                'appointments.status',
                'appointments.payment_status',
                'appointments.consultation_fee',
                'appointments.advance_payment',
                'appointments.reason',
                DB::raw("CONCAT(patients.first_name,' ',patients.last_name) AS patient_name"),
                'patients.phone AS patient_phone',
                DB::raw("CONCAT(doctors.first_name,' ',doctors.last_name)  AS doctor_name"),
                'doctors.specialization',
            )
            ->orderBy('appointments.appointment_date','desc')
            ->get();

        $filename = 'appointments_' . Carbon::now()->format('Ymd_His') . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($rows) {
            $f = fopen('php://output','w');
            fputcsv($f,['No','Date','Time','Status','Payment','Fee','Advance','Reason','Patient','Phone','Doctor','Specialization']);
            foreach ($rows as $r) {
                fputcsv($f,[
                    $r->appointment_number, $r->appointment_date, $r->appointment_time,
                    ucfirst($r->status), ucfirst($r->payment_status),
                    $r->consultation_fee, $r->advance_payment, $r->reason ?? '',
                    $r->patient_name, $r->patient_phone ?? '',
                    $r->doctor_name,  $r->specialization ?? '',
                ]);
            }
            fclose($f);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ══════════════════════════════════════════
    // PRIVATE HELPER
    // ══════════════════════════════════════════
    private function notifyPatient(int $patientId, string $title, string $message, int $relatedId): void
    {
        try {
            $patient = DB::table('patients')->where('id',$patientId)->first();
            if (!$patient) return;
            DB::table('notifications')->insert([
                'notifiable_type' => 'App\Models\User',
                'notifiable_id'   => $patient->user_id,
                'type'            => 'appointment',
                'title'           => $title,
                'message'         => $message,
                'related_type'    => 'appointment',
                'related_id'      => $relatedId,
                'is_read'         => false,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        } catch (\Exception $e) {
            \Log::warning('Notify failed: '.$e->getMessage());
        }
    }
}
