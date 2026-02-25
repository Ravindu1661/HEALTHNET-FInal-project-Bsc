<?php

namespace App\Http\Controllers\Hospital;
use App\Models\Notification;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\Hospital;

class HospitalDashboardController extends Controller
{
    private function getHospital()
    {
        return Hospital::where('user_id', Auth::id())->firstOrFail();
    }

    // ─── Dashboard ───────────────────────────────────────────
    public function index()
    {
        $hospital = $this->getHospital();
        return view('hospital.dashboard', compact('hospital'));
    }

   public function getStats()
{
    try {
        $hospital = $this->getHospital();
        $today    = Carbon::today();
        $som      = Carbon::now()->startOfMonth();
        $eom      = Carbon::now()->endOfMonth();

        // appointments table → camelCase columns
        $todayApts = DB::table('appointments')
            ->where('hospitalid', $hospital->id)
            ->whereDate('appointmentdate', $today)
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();

        // doctor_workplaces table → snake_case columns ✅
        $totalDoctors = DB::table('doctor_workplaces')
            ->where('workplace_type', 'hospital')
            ->where('workplace_id', $hospital->id)
            ->where('status', 'approved')
            ->count();

        $totalPatients = DB::table('appointments')
            ->where('hospitalid', $hospital->id)
            ->distinct('patientid')
            ->count('patientid');

        $monthlyRevenue = DB::table('appointments')
            ->where('hospitalid', $hospital->id)
            ->where('status', 'completed')
            ->whereBetween('appointmentdate', [$som, $eom])
            ->sum('consultationfee');

        $avgRating = DB::table('reviews')
            ->where('hospitalid', $hospital->id)
            ->where('status', 'approved')
            ->avg('rating');

        $aptStats = [
            'pending'   => DB::table('appointments')->where('hospitalid', $hospital->id)->where('status', 'pending')->count(),
            'confirmed' => DB::table('appointments')->where('hospitalid', $hospital->id)->where('status', 'confirmed')->count(),
            'completed' => DB::table('appointments')->where('hospitalid', $hospital->id)->where('status', 'completed')->count(),
            'cancelled' => DB::table('appointments')->where('hospitalid', $hospital->id)->where('status', 'cancelled')->count(),
        ];
        $aptStats['total'] = array_sum($aptStats);

        // Monthly trend (last 6 months)
        $trend = [];
        for ($i = 5; $i >= 0; $i--) {
            $m       = Carbon::now()->subMonths($i);
            $trend[] = [
                'month' => $m->format('M'),
                'count' => DB::table('appointments')
                    ->where('hospitalid', $hospital->id)
                    ->whereYear('appointmentdate', $m->year)
                    ->whereMonth('appointmentdate', $m->month)
                    ->count(),
            ];
        }

        return response()->json([
            'success'         => true,
            'today_apt'       => $todayApts,
            'total_doctors'   => $totalDoctors,
            'total_patients'  => $totalPatients,
            'monthly_revenue' => number_format($monthlyRevenue, 2, '.', ''),
            'avg_rating'      => round($avgRating ?? 0, 1),
            'apt_stats'       => $aptStats,
            'trend'           => $trend,
        ]);

    } catch (\Exception $e) {
        \Log::error('Hospital Dashboard Stats: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

    public function getTodayAppointments()
    {
        try {
            $hospital = $this->getHospital();
            $apts = DB::table('appointments')
                ->join('patients', 'appointments.patient_id', '=', 'patients.id')
                ->join('users as pu', 'patients.user_id', '=', 'pu.id')
                ->leftJoin('doctors', 'appointments.doctor_id', '=', 'doctors.id')
                ->leftJoin('users as du', 'doctors.user_id', '=', 'du.id')
                ->where('appointments.hospital_id', $hospital->id)
                ->whereDate('appointments.appointment_date', Carbon::today())
                ->select(
                    'appointments.id',
                    'appointments.appointment_number',
                    'appointments.appointment_time',
                    'appointments.status',
                    'appointments.consultation_fee',
                    'appointments.appointment_type',
                    'appointments.consultation_method',
                    DB::raw("pu.full_name as patient_name"),
                    DB::raw("CONCAT(COALESCE(doctors.first_name,''), ' ', COALESCE(doctors.last_name,'')) as doctor_name")
                )
                ->orderBy('appointments.appointment_time')
                ->get();

            return response()->json(['success' => true, 'appointments' => $apts]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getRecentReviews()
    {
        try {
            $hospital = $this->getHospital();
            $reviews = DB::table('reviews')
                ->join('patients', 'reviews.reviewer_id', '=', 'patients.id')
                ->join('users', 'patients.user_id', '=', 'users.id')
                ->where('reviews.hospital_id', $hospital->id)
                ->where('reviews.status', 'approved')
                ->select(
                    'reviews.id', 'reviews.rating', 'reviews.review_text',
                    'reviews.review_title', 'reviews.created_at',
                    'users.full_name as patient_name'
                )
                ->orderByDesc('reviews.created_at')
                ->limit(5)
                ->get();

            return response()->json(['success' => true, 'reviews' => $reviews]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ─── Appointments ─────────────────────────────────────────
    public function appointments(Request $request)
    {
        $hospital = $this->getHospital();
        return view('hospital.appointments.index', compact('hospital'));
    }

    public function appointmentsData(Request $request)
    {
        try {
            $hospital = $this->getHospital();
            $query = DB::table('appointments')
                ->join('patients', 'appointments.patient_id', '=', 'patients.id')
                ->join('users as pu', 'patients.user_id', '=', 'pu.id')
                ->leftJoin('doctors', 'appointments.doctor_id', '=', 'doctors.id')
                ->where('appointments.hospital_id', $hospital->id)
                ->select(
                    'appointments.*',
                    'pu.full_name as patient_name',
                    DB::raw("CONCAT(COALESCE(doctors.first_name,''), ' ', COALESCE(doctors.last_name,'')) as doctor_name")
                );

            if ($request->filled('status') && $request->status !== 'all') {
                $query->where('appointments.status', $request->status);
            }
            if ($request->filled('date')) {
                $query->whereDate('appointments.appointment_date', $request->date);
            }
            if ($request->filled('search')) {
                $s = '%' . $request->search . '%';
                $query->where(function ($q) use ($s) {
                    $q->where('pu.full_name', 'like', $s)
                      ->orWhere('appointments.appointment_number', 'like', $s);
                });
            }

            $data = $query->orderByDesc('appointments.appointment_date')
                          ->orderByDesc('appointments.appointment_time')
                          ->paginate(15);

            return response()->json(['success' => true, 'appointments' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function confirmAppointment($id)
    {
        DB::table('appointments')->where('id', $id)->update(['status' => 'confirmed']);
        return response()->json(['success' => true, 'message' => 'Appointment confirmed!']);
    }

    public function cancelAppointment(Request $request, $id)
    {
        DB::table('appointments')->where('id', $id)->update([
            'status' => 'cancelled',
            'cancelled_by' => Auth::id(),
            'cancellation_reason' => $request->reason,
        ]);
        return response()->json(['success' => true, 'message' => 'Appointment cancelled.']);
    }

    public function completeAppointment($id)
    {
        DB::table('appointments')->where('id', $id)->update(['status' => 'completed']);
        return response()->json(['success' => true, 'message' => 'Marked as completed!']);
    }

    // ─── Doctors ──────────────────────────────────────────────
    public function doctors(Request $request)
    {
        $hospital = $this->getHospital();
        return view('hospital.doctors.index', compact('hospital'));
    }
public function doctorsData(Request $request)
{
    try {
        $hospital = $this->getHospital();

        $query = DB::table('doctor_workplaces')
            ->join('doctors', 'doctor_workplaces.doctor_id', '=', 'doctors.id')      // ✅ doctor_id
            ->join('users',   'doctors.user_id',             '=', 'users.id')         // ✅ user_id
            ->where('doctor_workplaces.workplace_type', 'hospital')                   // ✅ workplace_type
            ->where('doctor_workplaces.workplace_id',   $hospital->id)               // ✅ workplace_id
            ->select(
                'doctors.id',
                DB::raw("CONCAT(doctors.first_name, ' ', doctors.last_name) AS name"), // ✅
                'doctors.specialization as specialty',                                  // ✅
                'doctors.experience_years',                                             // ✅
                'doctors.consultation_fee',                                             // ✅
                'doctors.rating',
                'doctors.total_ratings',
                'doctors.profile_image',                                                // ✅
                'doctors.bio',
                'doctors.status as doctor_status',
                'doctor_workplaces.employment_type',                                    // ✅
                'doctor_workplaces.status as affiliation_status',
                'users.email'
            );

        if ($request->filled('search')) {
            $s = '%' . $request->search . '%';
            $query->where(function ($q) use ($s) {
                $q->where('doctors.first_name',    'like', $s)  // ✅
                  ->orWhere('doctors.last_name',   'like', $s)  // ✅
                  ->orWhere('doctors.specialization', 'like', $s);
            });
        }

        if ($request->filled('status')) {
            $query->where('doctor_workplaces.status', $request->status);
        }

        $doctors = $query
            ->orderBy('doctors.first_name')
            ->paginate(15);

        return response()->json(['success' => true, 'doctors' => $doctors]);

    } catch (\Exception $e) {
        \Log::error('Hospital Doctors Data: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 500);
    }
}




    // ─── Profile ──────────────────────────────────────────────
    public function profile()
    {
        $hospital = $this->getHospital();
        return view('hospital.profile.index', compact('hospital'));
    }

    public function updateProfile(Request $request)
    {
        $hospital = $this->getHospital();
        $request->validate([
            'name'        => 'required|string|max:255',
            'phone'       => 'required|string|max:20',
            'email'       => 'required|email|max:100',
            'address'     => 'nullable|string',
            'city'        => 'nullable|string|max:100',
            'province'    => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'website'     => 'nullable|url|max:255',
        ]);

        $hospital->update($request->only([
            'name','phone','email','address','city','province','description','website'
        ]));

        return back()->with('success', 'Profile updated successfully!');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate(['profile_image' => 'required|image|max:5120']);
        $hospital = $this->getHospital();
        if ($hospital->profile_image) {
            Storage::disk('public')->delete($hospital->profile_image);
        }
        $path = $request->file('profile_image')->store('hospitals/profiles', 'public');
        $hospital->update(['profile_image' => $path]);
        return back()->with('success', 'Photo updated!');
    }

    public function uploadDocument(Request $request)
    {
        $request->validate(['document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120']);
        $hospital = $this->getHospital();
        if ($hospital->document_path) {
            Storage::disk('public')->delete($hospital->document_path);
        }
        $path = $request->file('document')->store('hospitals/documents', 'public');
        $hospital->update(['document_path' => $path]);
        return back()->with('success', 'Document uploaded!');
    }

    // ─── Notifications ────────────────────────────────────────
   // ============================================
// NOTIFICATIONS
// ============================================
public function notifications()
{
    return view('hospital.notifications.index');
}

public function notificationsData(Request $request)
{
    $user = Auth::user();
    $filter = $request->input('filter', 'all');
    $perPage = 15;

    $query = Notification::where('notifiable_id', $user->id)
        ->where('notifiable_type', User::class)
        ->orderByDesc('created_at');

    // Filter
    if ($filter === 'unread') {
        $query->where('is_read', false);
    } elseif (!in_array($filter, ['all', ''])) {
        $query->where('type', $filter);
    }

    $paginated = $query->paginate($perPage);

    $notifications = $paginated->map(function ($n) {
        return [
            'id'         => $n->id,
            'type'       => $n->type,
            'title'      => $n->title,
            'message'    => $n->message,
            'is_read'    => (bool) $n->is_read,
            'created_at' => $n->created_at->diffForHumans(),
        ];
    });

    return response()->json([
        'success'       => true,
        'notifications' => $notifications,
        'pagination'    => [
            'current_page' => $paginated->currentPage(),
            'last_page'    => $paginated->lastPage(),
            'from'         => $paginated->firstItem() ?? 0,
            'to'           => $paginated->lastItem() ?? 0,
            'total'        => $paginated->total(),
        ],
    ]);
}

public function markNotificationRead(Request $request, $id)
{
    $user = Auth::user();

    $notif = Notification::where('id', $id)
        ->where('notifiable_id', $user->id)
        ->where('notifiable_type', User::class)
        ->first();

    if (!$notif) {
        return response()->json(['success' => false, 'message' => 'Not found'], 404);
    }

    $notif->update([
        'is_read' => true,
        'read_at' => now(),
    ]);

    return response()->json(['success' => true]);
}

public function markAllNotificationsRead(Request $request)
{
    $user = Auth::user();

    Notification::where('notifiable_id', $user->id)
        ->where('notifiable_type', User::class)
        ->where('is_read', false)
        ->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

    return response()->json(['success' => true]);
}


    public function unreadNotifications()
    {
        try {
            $notifs = DB::table('notifications')
                ->where('user_id', Auth::id())
                ->orderByDesc('created_at')
                ->limit(10)
                ->get()
                ->map(fn($n) => [
                    'id'         => $n->id,
                    'title'      => $n->title,
                    'message'    => $n->message,
                    'is_read'    => (bool) $n->is_read,
                    'created_at' => Carbon::parse($n->created_at)->diffForHumans(),
                ]);

            $unread = DB::table('notifications')
                ->where('user_id', Auth::id())
                ->where('is_read', false)->count();

            $pendingApts = DB::table('appointments')
                ->join('hospitals', 'appointments.hospital_id', '=', 'hospitals.id')
                ->where('hospitals.user_id', Auth::id())
                ->where('appointments.status', 'pending')
                ->count();

            return response()->json([
                'count'               => $unread,
                'pending_appointments'=> $pendingApts,
                'notifications'       => $notifs,
            ]);
        } catch (\Exception $e) {
            return response()->json(['count' => 0, 'notifications' => []]);
        }
    }

    // ─── Settings ─────────────────────────────────────────────
    public function settings()
    {
        $hospital = $this->getHospital();
        return view('hospital.settings.index', compact('hospital'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        Auth::user()->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'Password changed successfully!');
    }

    // ─── Reviews ──────────────────────────────────────────────
    public function reviews()
    {
        $hospital = $this->getHospital();
        return view('hospital.reviews.index', compact('hospital'));
    }

    public function reviewsData(Request $request)
    {
        try {
            $hospital = $this->getHospital();
            $reviews = DB::table('reviews')
                ->join('patients', 'reviews.reviewer_id', '=', 'patients.id')
                ->join('users', 'patients.user_id', '=', 'users.id')
                ->where('reviews.hospital_id', $hospital->id)
                ->select(
                    'reviews.*', 'users.full_name as patient_name',
                    DB::raw("DATE_FORMAT(reviews.created_at, '%d %b %Y') as date")
                )
                ->orderByDesc('reviews.created_at')
                ->paginate(10);

            return response()->json(['success' => true, 'reviews' => $reviews]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ─── Reports ──────────────────────────────────────────────
    public function reports()
    {
        $hospital = $this->getHospital();
        return view('hospital.reports.index', compact('hospital'));
    }

    public function reportsData()
    {
        try {
            $hospital = $this->getHospital();
            $months   = [];
            for ($i = 5; $i >= 0; $i--) {
                $m        = Carbon::now()->subMonths($i);
                $months[] = [
                    'month'    => $m->format('M Y'),
                    'total'    => DB::table('appointments')->where('hospital_id', $hospital->id)->whereYear('appointment_date', $m->year)->whereMonth('appointment_date', $m->month)->count(),
                    'completed'=> DB::table('appointments')->where('hospital_id', $hospital->id)->where('status','completed')->whereYear('appointment_date', $m->year)->whereMonth('appointment_date', $m->month)->count(),
                    'revenue'  => DB::table('appointments')->where('hospital_id', $hospital->id)->where('status','completed')->whereYear('appointment_date', $m->year)->whereMonth('appointment_date', $m->month)->sum('consultation_fee'),
                ];
            }

            $byType = DB::table('appointments')
                ->where('hospital_id', $hospital->id)
                ->select('appointment_type', DB::raw('count(*) as count'))
                ->groupBy('appointment_type')
                ->get();

            $byMethod = DB::table('appointments')
                ->where('hospital_id', $hospital->id)
                ->select('consultation_method', DB::raw('count(*) as count'))
                ->groupBy('consultation_method')
                ->get();

            return response()->json([
                'success'  => true,
                'monthly'  => $months,
                'by_type'  => $byType,
                'by_method'=> $byMethod,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

   // ─── Search Available Doctors to Add ──────────────────
public function searchDoctors(Request $request)
{
    try {
        $hospital = $this->getHospital();
        $search   = trim($request->get('q', ''));

        // ✅ Correct column names from DoctorWorkplace model
        $affiliatedMap = DB::table('doctor_workplaces')
            ->where('workplace_type', 'hospital')        // ✅ workplace_type (not workplacetype)
            ->where('workplace_id', $hospital->id)       // ✅ workplace_id (not workplaceid)
            ->pluck('status', 'doctor_id');              // ✅ doctor_id (not doctorid)
        // Returns: [doctor_id => status] e.g. [1 => 'approved', 2 => 'pending']

        // ✅ Correct column names from Doctor model + users table
        $query = DB::table('doctors')
            ->join('users', 'doctors.user_id', '=', 'users.id')  // ✅ user_id (not userid)
            ->where('doctors.status', 'approved')                 // ✅ 'approved' is correct for Doctor
            ->where('users.status', 'active')
            ->select(
                'doctors.id',
                DB::raw("CONCAT(doctors.first_name, ' ', doctors.last_name) AS name"),  // ✅ first_name / last_name
                'doctors.specialization',
                'doctors.experience_years',              // ✅ experience_years
                'doctors.consultation_fee',              // ✅ consultation_fee
                'doctors.profile_image',                 // ✅ profile_image
                'doctors.rating',
                'doctors.total_ratings',
                'users.email'
            );

        // Search filter — only apply if search keyword provided
        if ($search !== '') {
            $like = '%' . $search . '%';
            $query->where(function ($q) use ($like) {
                $q->where('doctors.first_name',   'like', $like)  // ✅
                  ->orWhere('doctors.last_name',  'like', $like)  // ✅
                  ->orWhere(
                      DB::raw("CONCAT(doctors.first_name,' ',doctors.last_name)"),
                      'like', $like
                  )
                  ->orWhere('doctors.specialization', 'like', $like)
                  ->orWhere('users.email',             'like', $like);
            });
        }

        $doctors = $query
            ->orderBy('doctors.first_name')
            ->orderBy('doctors.last_name')
            ->get()
            ->map(function ($doc) use ($affiliatedMap) {
                // ✅ Mark already affiliated doctors (pending/approved/rejected)
                $affiliationStatus = $affiliatedMap->get($doc->id);
                $doc->already_affiliated = $affiliationStatus !== null;
                $doc->affiliation_status = $affiliationStatus ?? null;
                return $doc;
            });

        return response()->json([
            'success' => true,
            'doctors' => $doctors,
            'count'   => $doctors->count(),
        ]);

    } catch (\Exception $e) {
        \Log::error('searchDoctors Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to search doctors: ' . $e->getMessage(),
        ], 500);
    }
}

// ─── Add Doctor to Hospital ────────────────────────────
public function addDoctor(Request $request)
{
    try {
        $hospital = $this->getHospital();

        $request->validate([
            'doctor_id'       => 'required|integer|exists:doctors,id',
            'employment_type' => 'required|in:permanent,temporary,visiting',
        ]);

        $doctorId = (int) $request->doctor_id;

        // ✅ Correct column names for exists check
        $exists = DB::table('doctor_workplaces')
            ->where('doctor_id',      $doctorId)          // ✅ doctor_id
            ->where('workplace_type', 'hospital')         // ✅ workplace_type
            ->where('workplace_id',   $hospital->id)      // ✅ workplace_id
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'This doctor is already affiliated with your hospital.',
            ], 422);
        }

        // ✅ Correct column names for insert — matches DoctorWorkplace $fillable
        DB::table('doctor_workplaces')->insert([
            'doctor_id'       => $doctorId,              // ✅
            'workplace_type'  => 'hospital',             // ✅
            'workplace_id'    => $hospital->id,          // ✅
            'employment_type' => $request->employment_type, // ✅ employment_type
            'status'          => 'pending',              // ✅ default 'pending' per model
            'approved_by'     => null,
            'approved_at'     => null,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // ✅ Correct column names for doctor name fetch
        $doctor = DB::table('doctors')
            ->where('id', $doctorId)
            ->select('first_name', 'last_name')          // ✅
            ->first();

        $name = $doctor
            ? trim($doctor->first_name . ' ' . $doctor->last_name)
            : 'Doctor';

        return response()->json([
            'success' => true,
            'message' => "Dr. {$name} has been added successfully! Status: Pending approval.",
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json(['success' => false, 'message' => $e->errors()], 422);
    } catch (\Exception $e) {
        \Log::error('addDoctor Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to add doctor: ' . $e->getMessage(),
        ], 500);
    }
}
// ─── Update Doctor Affiliation Status ─────────────────
public function updateDoctorStatus(Request $request, $doctorId)
{
    try {
        $hospital = $this->getHospital();

        $request->validate([
            'status' => 'required|in:approved,rejected,pending',
        ]);

        // doctor_workplaces record exists check
        $workplace = DB::table('doctor_workplaces')
            ->where('doctor_id',      $doctorId)
            ->where('workplace_type', 'hospital')
            ->where('workplace_id',   $hospital->id)
            ->first();

        if (!$workplace) {
            return response()->json([
                'success' => false,
                'message' => 'Doctor affiliation not found.',
            ], 404);
        }

        $updateData = [
            'status'     => $request->status,
            'updated_at' => now(),
        ];

        // approved නම් approved_by සහ approved_at set කරන්න
        if ($request->status === 'approved') {
            $updateData['approved_by'] = Auth::id();
            $updateData['approved_at'] = now();
        }

        DB::table('doctor_workplaces')
            ->where('doctor_id',      $doctorId)
            ->where('workplace_type', 'hospital')
            ->where('workplace_id',   $hospital->id)
            ->update($updateData);

        $messages = [
            'approved' => 'Doctor access approved successfully!',
            'rejected' => 'Doctor access revoked.',
            'pending'  => 'Doctor status set to pending.',
        ];

        return response()->json([
            'success' => true,
            'message' => $messages[$request->status] ?? 'Status updated.',
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json(['success' => false, 'message' => $e->errors()], 422);
    } catch (\Exception $e) {
        \Log::error('updateDoctorStatus Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to update status: ' . $e->getMessage(),
        ], 500);
    }
}

// ─── Resend Email Verification ────────────────────────
public function resendVerification(Request $request)
{
    $user = Auth::user();

    if ($user->hasVerifiedEmail()) {
        return response()->json([
            'success' => false,
            'message' => 'Email is already verified.',
        ]);
    }

    $user->sendEmailVerificationNotification();

    return response()->json([
        'success' => true,
        'message' => 'Verification email sent to ' . $user->email,
    ]);
}

}
