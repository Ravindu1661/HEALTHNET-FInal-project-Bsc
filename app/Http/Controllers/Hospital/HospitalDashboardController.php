<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class HospitalDashboardController extends Controller
{
    // ════════════════════════════════════════════════
    // HELPER — Get current hospital record
    // ════════════════════════════════════════════════
    private function getHospital()
    {
        return DB::table('hospitals')
            ->where('user_id', Auth::id())
            ->first();
    }

    // ════════════════════════════════════════════════
    // HELPER — Notification query base
    // ════════════════════════════════════════════════
    private function notifQuery()
    {
        return DB::table('notifications')
            ->where('notifiable_type', 'App\Models\User')
            ->where('notifiable_id', Auth::id());
    }


    // ════════════════════════════════════════════════
    // DASHBOARD
    // ════════════════════════════════════════════════

  public function index()
{
    $hospital = $this->getHospital();

    $todayAppointments = 0;
    $totalDoctors      = 0;
    $avgRating         = 0;
    $appointmentStats  = ['total'=>0,'pending'=>0,'confirmed'=>0,'completed'=>0,'cancelled'=>0];
    $todayAptList      = collect();
    $recentReviews     = collect();

    if ($hospital) {

        // Today count
        $todayAppointments = DB::table('appointments')
            ->where('workplace_type', 'hospital')
            ->where('workplace_id', $hospital->id)
            ->whereDate('appointment_date', Carbon::today())
            ->count();

        // Active doctors
        $totalDoctors = DB::table('doctor_workplaces')
            ->where('workplace_type', 'hospital')
            ->where('workplace_id', $hospital->id)
            ->where('status', 'approved')
            ->count();

        // Monthly stats
        $statsRaw = DB::table('appointments')
            ->where('workplace_type', 'hospital')
            ->where('workplace_id', $hospital->id)
            ->whereMonth('appointment_date', Carbon::now()->month)
            ->whereYear('appointment_date',  Carbon::now()->year)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $appointmentStats = [
            'total'     => array_sum($statsRaw),
            'pending'   => $statsRaw['pending']   ?? 0,
            'confirmed' => $statsRaw['confirmed'] ?? 0,
            'completed' => $statsRaw['completed'] ?? 0,
            'cancelled' => $statsRaw['cancelled'] ?? 0,
        ];

        // Avg rating
        $avgRating = round(
            DB::table('ratings')
                ->where('ratable_type', 'hospital')
                ->where('ratable_id', $hospital->id)
                ->avg('rating') ?? 0,
            1
        );

        // Today appointment list
        $todayAptList = DB::table('appointments as a')
            ->join('patients as p', 'a.patient_id', '=', 'p.id')
            ->join('doctors as d',  'a.doctor_id',  '=', 'd.id')
            ->where('a.workplace_type', 'hospital')
            ->where('a.workplace_id',  $hospital->id)
            ->whereDate('a.appointment_date', Carbon::today())
            ->select(
                'a.id',
                'a.status',
                'a.appointment_number',
                DB::raw("CONCAT(p.first_name,' ',p.last_name) as patient_name"),
                'p.phone',
                DB::raw("CONCAT('Dr. ',d.first_name,' ',d.last_name) as doctor_name"),
                'd.specialization',
                DB::raw("DATE_FORMAT(a.appointment_time,'%h:%i %p') as apt_time")
            )
            ->orderBy('a.appointment_time')
            ->limit(10)
            ->get();

        // Recent reviews
        $recentReviews = DB::table('ratings as r')
            ->join('patients as p', 'r.patient_id', '=', 'p.id')
            ->where('r.ratable_type', 'hospital')
            ->where('r.ratable_id',   $hospital->id)
            ->select(
                'r.rating',
                'r.review as comment',
                'r.created_at',
                DB::raw("CONCAT(p.first_name,' ',p.last_name) as patient_name")
            )
            ->orderByDesc('r.created_at')
            ->limit(5)
            ->get();
    }

    // Specializations safe parse
    $specializations = [];
    if ($hospital && $hospital->specializations) {
        $raw = $hospital->specializations;
        if (is_array($raw)) {
            $specializations = $raw;
        } else {
            $decoded = json_decode($raw, true);
            $specializations = is_array($decoded)
                ? $decoded
                : array_map('trim', explode(',', $raw));
        }
    }

    // Facilities safe parse
    $facilities = [];
    if ($hospital && $hospital->facilities) {
        $raw = $hospital->facilities;
        if (is_array($raw)) {
            $facilities = $raw;
        } else {
            $decoded = json_decode($raw, true);
            $facilities = is_array($decoded)
                ? $decoded
                : array_map('trim', explode(',', $raw));
        }
    }

    return view('hospital.dashboard', compact(
        'hospital',
        'todayAppointments',
        'totalDoctors',
        'avgRating',
        'appointmentStats',
        'todayAptList',
        'recentReviews',
        'specializations',
        'facilities'
    ));
}


    public function getTodayAppointments()
    {
        $hospital = $this->getHospital();
        if (!$hospital) {
            return response()->json(['appointments' => []]);
        }

        $appointments = DB::table('appointments as a')
            ->join('patients as p', 'a.patient_id', '=', 'p.id')
            ->join('doctors as d', 'a.doctor_id', '=', 'd.id')
            ->where('a.workplace_type', 'hospital')
            ->where('a.workplace_id', $hospital->id)
            ->whereDate('a.appointment_date', Carbon::today())
            ->select(
                'a.id',
                'a.status',
                'a.appointment_time',
                'a.appointment_number',
                DB::raw("CONCAT(p.first_name, ' ', p.last_name) as patient_name"),
                'p.phone',
                DB::raw("CONCAT('Dr. ', d.first_name, ' ', d.last_name) as doctor_name"),
                'd.specialization'
            )
            ->orderBy('a.appointment_time')
            ->get()
            ->map(fn($apt) => [
                'id'           => $apt->id,
                'patient_name' => $apt->patient_name,
                'doctor_name'  => $apt->doctor_name,
                'specialization' => $apt->specialization,
                'phone'        => $apt->phone,
                'time'         => Carbon::parse($apt->appointment_time)->format('h:i A'),
                'status'       => $apt->status,
                'apt_number'   => $apt->appointment_number,
            ]);

        return response()->json(['appointments' => $appointments]);
    }

    public function getRecentReviews()
    {
        $hospital = $this->getHospital();
        if (!$hospital) {
            return response()->json(['reviews' => []]);
        }

        $reviews = DB::table('ratings as r')
            ->join('patients as p', 'r.patient_id', '=', 'p.id')
            ->where('r.ratable_type', 'hospital')
            ->where('r.ratable_id', $hospital->id)
            ->select(
                'r.id',
                'r.rating',
                'r.review as comment',
                'r.created_at',
                DB::raw("CONCAT(p.first_name, ' ', p.last_name) as patient_name")
            )
            ->orderByDesc('r.created_at')
            ->limit(5)
            ->get()
            ->map(fn($r) => [
                'patient_name' => $r->patient_name,
                'rating'       => $r->rating,
                'comment'      => $r->comment,
                'date'         => Carbon::parse($r->created_at)->diffForHumans(),
            ]);

        return response()->json(['reviews' => $reviews]);
    }


    // ════════════════════════════════════════════════
    // APPOINTMENTS MANAGEMENT
    // ════════════════════════════════════════════════

    public function appointments()
    {
        $hospital = $this->getHospital();
        return view('hospital.appointments', compact('hospital'));
    }

    public function appointmentsData(Request $request)
    {
        $hospital = $this->getHospital();
        if (!$hospital) {
            return response()->json(['data' => [], 'total' => 0]);
        }

        $query = DB::table('appointments as a')
            ->join('patients as p', 'a.patient_id', '=', 'p.id')
            ->join('doctors as d', 'a.doctor_id', '=', 'd.id')
            ->where('a.workplace_type', 'hospital')
            ->where('a.workplace_id', $hospital->id)
            ->select(
                'a.*',
                DB::raw("CONCAT(p.first_name, ' ', p.last_name) as patient_name"),
                'p.phone as patient_phone',
                DB::raw("CONCAT('Dr. ', d.first_name, ' ', d.last_name) as doctor_name"),
                'd.specialization'
            );

        // Filters
        if ($request->filled('status')) {
            $query->where('a.status', $request->status);
        }
        if ($request->filled('date')) {
            $query->whereDate('a.appointment_date', $request->date);
        }
        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where(DB::raw("CONCAT(p.first_name,' ',p.last_name)"), 'like', $search)
                  ->orWhere('a.appointment_number', 'like', $search)
                  ->orWhere('p.phone', 'like', $search);
            });
        }
        if ($request->filled('doctor_id')) {
            $query->where('a.doctor_id', $request->doctor_id);
        }

        $total        = $query->count();
        $perPage      = $request->per_page ?? 15;
        $appointments = $query
            ->orderByDesc('a.appointment_date')
            ->orderBy('a.appointment_time')
            ->paginate($perPage);

        return response()->json($appointments);
    }

    public function confirmAppointment($id)
    {
        $hospital = $this->getHospital();
        $apt = DB::table('appointments')
            ->where('id', $id)
            ->where('workplace_type', 'hospital')
            ->where('workplace_id', $hospital->id)
            ->first();

        if (!$apt) {
            return response()->json(['success' => false, 'message' => 'Appointment not found'], 404);
        }
        if ($apt->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Only pending appointments can be confirmed']);
        }

        DB::table('appointments')
            ->where('id', $id)
            ->update(['status' => 'confirmed', 'updated_at' => now()]);

        return response()->json(['success' => true, 'message' => 'Appointment confirmed']);
    }

    public function cancelAppointment(Request $request, $id)
    {
        $hospital = $this->getHospital();
        $apt = DB::table('appointments')
            ->where('id', $id)
            ->where('workplace_type', 'hospital')
            ->where('workplace_id', $hospital->id)
            ->first();

        if (!$apt) {
            return response()->json(['success' => false, 'message' => 'Appointment not found'], 404);
        }
        if (in_array($apt->status, ['completed', 'cancelled'])) {
            return response()->json(['success' => false, 'message' => 'Cannot cancel this appointment']);
        }

        DB::table('appointments')
            ->where('id', $id)
            ->update([
                'status'              => 'cancelled',
                'cancelled_by'        => Auth::id(),
                'cancellation_reason' => $request->reason ?? 'Cancelled by hospital',
                'updated_at'          => now(),
            ]);

        return response()->json(['success' => true, 'message' => 'Appointment cancelled']);
    }

    public function completeAppointment($id)
    {
        $hospital = $this->getHospital();
        $apt = DB::table('appointments')
            ->where('id', $id)
            ->where('workplace_type', 'hospital')
            ->where('workplace_id', $hospital->id)
            ->first();

        if (!$apt) {
            return response()->json(['success' => false, 'message' => 'Appointment not found'], 404);
        }
        if ($apt->status !== 'confirmed') {
            return response()->json(['success' => false, 'message' => 'Only confirmed appointments can be completed']);
        }

        DB::table('appointments')
            ->where('id', $id)
            ->update(['status' => 'completed', 'updated_at' => now()]);

        return response()->json(['success' => true, 'message' => 'Appointment completed']);
    }


    // ════════════════════════════════════════════════
    // DOCTORS MANAGEMENT
    // ════════════════════════════════════════════════

    public function doctors()
    {
        $hospital = $this->getHospital();
        return view('hospital.doctors', compact('hospital'));
    }

    public function doctorsData(Request $request)
    {
        $hospital = $this->getHospital();
        if (!$hospital) {
            return response()->json(['data' => []]);
        }

        $query = DB::table('doctor_workplaces as dw')
            ->join('doctors as d', 'dw.doctor_id', '=', 'd.id')
            ->join('users as u', 'd.user_id', '=', 'u.id')
            ->where('dw.workplace_type', 'hospital')
            ->where('dw.workplace_id', $hospital->id)
            ->select(
                'd.id',
                'd.first_name',
                'd.last_name',
                'd.specialization',
                'd.phone',
                'd.profile_image',
                'd.slmc_number',
                'd.experience_years',
                'd.consultation_fee',
                'd.rating',
                'd.total_ratings',
                'd.status as doctor_status',
                'dw.id as workplace_id',
                'dw.status as workplace_status',
                'dw.employment_type',
                'dw.created_at as joined_at',
                'u.email'
            );

        // Filters
        if ($request->filled('status')) {
            $query->where('dw.status', $request->status);
        }
        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('d.first_name', 'like', $search)
                  ->orWhere('d.last_name', 'like', $search)
                  ->orWhere('d.specialization', 'like', $search)
                  ->orWhere('d.slmc_number', 'like', $search);
            });
        }

        $doctors = $query
            ->orderByDesc('dw.created_at')
            ->paginate($request->per_page ?? 12);

        return response()->json($doctors);
    }

    public function searchDoctors(Request $request)
    {
        $hospital = $this->getHospital();

        // Already added doctor IDs — exclude them
        $existingIds = DB::table('doctor_workplaces')
            ->where('workplace_type', 'hospital')
            ->where('workplace_id', $hospital->id)
            ->pluck('doctor_id')
            ->toArray();

        $search  = $request->q ?? '';
        $doctors = DB::table('doctors as d')
            ->join('users as u', 'd.user_id', '=', 'u.id')
            ->whereNotIn('d.id', $existingIds)
            ->where('d.status', 'approved')
            ->where('u.status', 'active')
            ->where(function ($q) use ($search) {
                $q->where('d.first_name', 'like', "%{$search}%")
                  ->orWhere('d.last_name', 'like', "%{$search}%")
                  ->orWhere('d.specialization', 'like', "%{$search}%")
                  ->orWhere('d.slmc_number', 'like', "%{$search}%");
            })
            ->select(
                'd.id',
                'd.first_name',
                'd.last_name',
                'd.specialization',
                'd.slmc_number',
                'd.profile_image',
                'd.experience_years',
                'd.consultation_fee',
                'd.rating'
            )
            ->limit(10)
            ->get();

        return response()->json(['doctors' => $doctors]);
    }

    public function addDoctor(Request $request)
    {
        $request->validate([
            'doctor_id'       => 'required|integer|exists:doctors,id',
            'employment_type' => 'required|in:permanent,temporary,visiting',
        ]);

        $hospital = $this->getHospital();

        // Check duplicate
        $exists = DB::table('doctor_workplaces')
            ->where('doctor_id', $request->doctor_id)
            ->where('workplace_type', 'hospital')
            ->where('workplace_id', $hospital->id)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'This doctor is already added to your hospital.',
            ], 422);
        }

        DB::table('doctor_workplaces')->insert([
            'doctor_id'       => $request->doctor_id,
            'workplace_type'  => 'hospital',
            'workplace_id'    => $hospital->id,
            'employment_type' => $request->employment_type,
            'status'          => 'pending',
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Doctor added successfully. Awaiting approval.',
        ]);
    }

    public function updateDoctorStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $hospital = $this->getHospital();

        $workplace = DB::table('doctor_workplaces')
            ->where('id', $id)
            ->where('workplace_type', 'hospital')
            ->where('workplace_id', $hospital->id)
            ->first();

        if (!$workplace) {
            return response()->json(['success' => false, 'message' => 'Record not found'], 404);
        }

        DB::table('doctor_workplaces')
            ->where('id', $id)
            ->update([
                'status'      => $request->status,
                'approved_by' => $request->status === 'approved' ? Auth::id() : null,
                'approved_at' => $request->status === 'approved' ? now() : null,
                'updated_at'  => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Doctor status updated to ' . $request->status,
        ]);
    }


    // ════════════════════════════════════════════════
    // REVIEWS & RATINGS
    // ════════════════════════════════════════════════

    public function reviews()
    {
        $hospital = $this->getHospital();
        return view('hospital.reviews', compact('hospital'));
    }

    public function reviewsData(Request $request)
    {
        $hospital = $this->getHospital();
        if (!$hospital) {
            return response()->json(['data' => []]);
        }

        $query = DB::table('ratings as r')
            ->join('patients as p', 'r.patient_id', '=', 'p.id')
            ->where('r.ratable_type', 'hospital')
            ->where('r.ratable_id', $hospital->id)
            ->select(
                'r.id',
                'r.rating',
                'r.review as comment',
                'r.related_type',
                'r.related_id',
                'r.created_at',
                DB::raw("CONCAT(p.first_name, ' ', p.last_name) as patient_name"),
                'p.profile_image as patient_image'
            );

        // Filters
        if ($request->filled('rating')) {
            $query->where('r.rating', $request->rating);
        }
        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where(DB::raw("CONCAT(p.first_name,' ',p.last_name)"), 'like', $search)
                  ->orWhere('r.review', 'like', $search);
            });
        }

        // Summary stats
        $summary = DB::table('ratings')
            ->where('ratable_type', 'hospital')
            ->where('ratable_id', $hospital->id)
            ->selectRaw('
                COUNT(*) as total,
                AVG(rating) as avg_rating,
                SUM(CASE WHEN rating = 5 THEN 1 ELSE 0 END) as five_star,
                SUM(CASE WHEN rating = 4 THEN 1 ELSE 0 END) as four_star,
                SUM(CASE WHEN rating = 3 THEN 1 ELSE 0 END) as three_star,
                SUM(CASE WHEN rating = 2 THEN 1 ELSE 0 END) as two_star,
                SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END) as one_star
            ')
            ->first();

        $reviews = $query
            ->orderByDesc('r.created_at')
            ->paginate($request->per_page ?? 10);

        return response()->json([
            'reviews' => $reviews,
            'summary' => $summary,
        ]);
    }


    // ════════════════════════════════════════════════
    // REPORTS & ANALYTICS
    // ════════════════════════════════════════════════

    public function reports()
    {
        $hospital = $this->getHospital();
        return view('hospital.reports', compact('hospital'));
    }

    public function reportsData(Request $request)
    {
        $hospital = $this->getHospital();
        if (!$hospital) {
            return response()->json([]);
        }

        $period = $request->period ?? 'monthly';
        $year   = $request->year   ?? Carbon::now()->year;

        // ── Monthly appointment chart data ──
        $monthlyData = DB::table('appointments')
            ->where('workplace_type', 'hospital')
            ->where('workplace_id', $hospital->id)
            ->whereYear('appointment_date', $year)
            ->selectRaw('
                MONTH(appointment_date) as month,
                COUNT(*) as total,
                SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled,
                SUM(CASE WHEN status = "pending"   THEN 1 ELSE 0 END) as pending
            ')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // ── Revenue data (from payments) ──
        $revenueData = DB::table('payments as pay')
            ->join('appointments as a', function ($j) use ($hospital) {
                $j->on('pay.related_id', '=', 'a.id')
                  ->where('pay.related_type', 'appointment')
                  ->where('a.workplace_type', 'hospital')
                  ->where('a.workplace_id', $hospital->id);
            })
            ->whereYear('pay.payment_date', $year)
            ->where('pay.payment_status', 'completed')
            ->selectRaw('
                MONTH(pay.payment_date) as month,
                SUM(pay.amount) as revenue
            ')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // ── Top doctors (by appointment count) ──
        $topDoctors = DB::table('appointments as a')
            ->join('doctors as d', 'a.doctor_id', '=', 'd.id')
            ->where('a.workplace_type', 'hospital')
            ->where('a.workplace_id', $hospital->id)
            ->whereYear('a.appointment_date', $year)
            ->selectRaw("
                CONCAT(d.first_name, ' ', d.last_name) as doctor_name,
                d.specialization,
                d.profile_image,
                COUNT(*) as total_appointments,
                SUM(CASE WHEN a.status = 'completed' THEN 1 ELSE 0 END) as completed
            ")
            ->groupBy('a.doctor_id', 'd.first_name', 'd.last_name', 'd.specialization', 'd.profile_image')
            ->orderByDesc('total_appointments')
            ->limit(5)
            ->get();

        // ── Overall summary ──
        $summary = DB::table('appointments')
            ->where('workplace_type', 'hospital')
            ->where('workplace_id', $hospital->id)
            ->whereYear('appointment_date', $year)
            ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled,
                SUM(CASE WHEN status = "pending"   THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = "confirmed" THEN 1 ELSE 0 END) as confirmed
            ')
            ->first();

        return response()->json([
            'monthly_data' => $monthlyData,
            'revenue_data' => $revenueData,
            'top_doctors'  => $topDoctors,
            'summary'      => $summary,
            'year'         => $year,
        ]);
    }


    // ════════════════════════════════════════════════
    // PROFILE MANAGEMENT
    // ════════════════════════════════════════════════

    public function profile()
    {
        $hospital = $this->getHospital();
        return view('hospital.profile', compact('hospital'));
    }

    public function updateProfile(Request $request)
    {
        $hospital = $this->getHospital();

        $request->validate([
            'name'                => 'required|string|max:255',
            'phone'               => 'nullable|string|max:20',
            'email'               => 'nullable|email|max:255',
            'address'             => 'nullable|string',
            'city'                => 'nullable|string|max:100',
            'province'            => 'nullable|string|max:100',
            'postal_code'         => 'nullable|string|max:10',
            'type'                => 'nullable|in:government,private',
            'description'         => 'nullable|string',
            'website'             => 'nullable|url|max:255',
            'operating_hours'     => 'nullable|string',
            'specializations'     => 'nullable|array',
            'facilities'          => 'nullable|array',
            'latitude'            => 'nullable|numeric|between:-90,90',
            'longitude'           => 'nullable|numeric|between:-180,180',
        ]);

        DB::table('hospitals')
            ->where('id', $hospital->id)
            ->update([
                'name'             => $request->name,
                'phone'            => $request->phone,
                'email'            => $request->email,
                'address'          => $request->address,
                'city'             => $request->city,
                'province'         => $request->province,
                'postal_code'      => $request->postal_code,
                'type'             => $request->type,
                'description'      => $request->description,
                'website'          => $request->website,
                'operating_hours'  => $request->operating_hours,
                'specializations'  => $request->specializations
                    ? json_encode($request->specializations)
                    : null,
                'facilities'       => $request->facilities
                    ? json_encode($request->facilities)
                    : null,
                'latitude'         => $request->latitude,
                'longitude'        => $request->longitude,
                'updated_at'       => now(),
            ]);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $hospital = $this->getHospital();

        // Delete old photo
        if ($hospital->profile_image && Storage::disk('public')->exists($hospital->profile_image)) {
            Storage::disk('public')->delete($hospital->profile_image);
        }

        $path = $request->file('photo')->store('hospitals/photos', 'public');

        DB::table('hospitals')
            ->where('id', $hospital->id)
            ->update(['profile_image' => $path, 'updated_at' => now()]);

        return response()->json([
            'success' => true,
            'path'    => asset('storage/' . $path),
            'message' => 'Photo updated successfully.',
        ]);
    }

    public function uploadDocument(Request $request)
    {
        $request->validate([
            'document' => 'required|mimes:pdf,jpg,jpeg,png|max:5120',
            'type'     => 'nullable|string|max:100',
        ]);

        $hospital = $this->getHospital();

        // Delete old document
        if ($hospital->document_path && Storage::disk('public')->exists($hospital->document_path)) {
            Storage::disk('public')->delete($hospital->document_path);
        }

        $path = $request->file('document')->store('hospitals/documents', 'public');

        DB::table('hospitals')
            ->where('id', $hospital->id)
            ->update(['document_path' => $path, 'updated_at' => now()]);

        return response()->json([
            'success' => true,
            'path'    => asset('storage/' . $path),
            'message' => 'Document uploaded successfully.',
        ]);
    }


    // ════════════════════════════════════════════════
    // NOTIFICATIONS
    // ════════════════════════════════════════════════

    public function notifications()
    {
        $hospital = $this->getHospital();
        return view('hospital.notifications-page', compact('hospital'));
    }

    public function notificationsData(Request $request)
    {
        $query = $this->notifQuery();

        // Filter
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }
        if ($request->filled('is_read') && $request->is_read !== '') {
            $query->where('is_read', (bool) $request->is_read);
        }

        $notifications = $query
            ->orderByDesc('created_at')
            ->paginate($request->per_page ?? 20);

        $unreadCount = $this->notifQuery()->where('is_read', false)->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count'  => $unreadCount,
        ]);
    }

    public function markNotificationRead($id)
    {
        $updated = $this->notifQuery()
            ->where('id', $id)
            ->update([
                'is_read'  => true,
                'read_at'  => now(),
            ]);

        if (!$updated) {
            return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
        }

        return response()->json(['success' => true]);
    }

    public function markAllNotificationsRead()
    {
        $this->notifQuery()
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json(['success' => true, 'message' => 'All notifications marked as read.']);
    }


    // ════════════════════════════════════════════════
    // SETTINGS & ACCOUNT
    // ════════════════════════════════════════════════

    public function settings()
    {
        $hospital = $this->getHospital();
        return view('hospital.settings', compact('hospital'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'The current password is incorrect.',
            ])->withInput();
        }

        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'password'   => Hash::make($request->password),
                'updated_at' => now(),
            ]);

        // Log activity
        DB::table('activity_logs')->insert([
            'user_id'     => $user->id,
            'action'      => 'password_changed',
            'description' => 'Hospital user changed password',
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
            'created_at'  => now(),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    public function resendVerification(Request $request)
    {
        $user = Auth::user();

        if ($user->email_verified_at) {
            return back()->with('info', 'Your email is already verified.');
        }

        $user->sendEmailVerificationNotification();

        return back()->with('success', 'Verification email sent. Please check your inbox.');
    }
}
