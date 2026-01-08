<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\SignupController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\HospitalController;
use App\Http\Controllers\Admin\LaboratoryController;
use App\Http\Controllers\Admin\PharmacyController;
use App\Http\Controllers\Admin\MedicalCentreController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Doctor\DoctorDashboardController;
use App\Http\Controllers\Doctor\DoctorAppointmentController;
use App\Http\Controllers\Doctor\DoctorScheduleController;
use App\Http\Controllers\Doctor\DoctorPatientController;
use App\Http\Controllers\Doctor\DoctorWorkplaceController;
use App\Http\Controllers\Doctor\DoctorEarningsController;
use App\Http\Controllers\Doctor\DoctorReviewController;
use App\Http\Controllers\Doctor\DoctorNotificationController;
use App\Http\Controllers\Doctor\DoctorProfileController;
use App\Http\Controllers\Doctor\DoctorSettingsController;
use App\Http\Controllers\Patient\FindDoctorsController;
use App\Http\Controllers\Patient\PatientAppointmentController;
use App\Http\Controllers\Patient\PatientHospitalController;
use App\Http\Controllers\Patient\PatientMedicalCentreController;
use App\Http\Controllers\Patient\PatientLaboratoryController;
use App\Http\Controllers\Patient\PatientPharmacyController;
use App\Http\Controllers\Pharmacy\PharmacyDashboardController;
use App\Http\Controllers\Pharmacy\PharmacyProfileController;
use App\Http\Controllers\Pharmacy\PharmacyMedicineController;
use App\Http\Controllers\Pharmacy\PharmacyOrderController;
use App\Http\Controllers\Pharmacy\PharmacyInventoryController;
use App\Http\Controllers\Pharmacy\PharmacyPatientController;
use App\Http\Controllers\Pharmacy\PharmacyReportController;
use App\Http\Controllers\Pharmacy\PharmacyRatingController;
use App\Http\Controllers\Pharmacy\PharmacySettingController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\MedicalCentre;




/*
|--------------------------------------------------------------------------
| Pharmacy Routes (Require Authentication + Verified Email)
|--------------------------------------------------------------------------
*/

Route::prefix('pharmacy')->name('pharmacy.')->middleware(['auth'])->group(function () {

        // ============================================
    // DASHBOARD
    // ============================================
    Route::get('/dashboard', [PharmacyDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [PharmacyDashboardController::class, 'getStats'])->name('dashboard.stats');

    // ============================================
    // PROFILE MANAGEMENT
    // ============================================
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [PharmacyProfileController::class, 'index'])->name('index');
        Route::get('/create', [PharmacyProfileController::class, 'create'])->name('create');
        Route::post('/store', [PharmacyProfileController::class, 'store'])->name('store');
        Route::get('/edit', [PharmacyProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [PharmacyProfileController::class, 'update'])->name('update');
        Route::post('/upload-image', [PharmacyProfileController::class, 'uploadImage'])->name('upload-image');
        Route::delete('/delete-image', [PharmacyProfileController::class, 'deleteImage'])->name('delete-image');
    });

    // ============================================
    // MEDICINES MANAGEMENT
    // ============================================
    Route::prefix('medicines')->name('medicines.')->group(function () {
        Route::get('/', [PharmacyMedicineController::class, 'index'])->name('index');
        Route::get('/create', [PharmacyMedicineController::class, 'create'])->name('create');
        Route::post('/store', [PharmacyMedicineController::class, 'store'])->name('store');
        Route::get('/{medicine}', [PharmacyMedicineController::class, 'show'])->name('show');
        Route::get('/{medicine}/edit', [PharmacyMedicineController::class, 'edit'])->name('edit');
        Route::put('/{medicine}', [PharmacyMedicineController::class, 'update'])->name('update');
        Route::delete('/{medicine}', [PharmacyMedicineController::class, 'destroy'])->name('destroy');
        Route::post('/{medicine}/update-stock', [PharmacyMedicineController::class, 'updateStock'])->name('update-stock');
        Route::post('/{medicine}/toggle-status', [PharmacyMedicineController::class, 'toggleStatus'])->name('toggle-status');
    });

    // ============================================
    // ORDERS MANAGEMENT
    // ============================================
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [PharmacyOrderController::class, 'index'])->name('index');
        Route::get('/create', [PharmacyOrderController::class, 'create'])->name('create');
        Route::post('/store', [PharmacyOrderController::class, 'store'])->name('store');
        Route::get('/{order}', [PharmacyOrderController::class, 'show'])->name('show');

        // Order Status Actions
        Route::post('/{order}/verify', [PharmacyOrderController::class, 'verify'])->name('verify');
        Route::post('/{order}/process', [PharmacyOrderController::class, 'process'])->name('process');
        Route::post('/{order}/ready', [PharmacyOrderController::class, 'ready'])->name('ready');
        Route::post('/{order}/dispatch', [PharmacyOrderController::class, 'markDispatch'])->name('dispatch');
        Route::post('/{order}/deliver', [PharmacyOrderController::class, 'deliver'])->name('deliver');
        Route::post('/{order}/cancel', [PharmacyOrderController::class, 'cancel'])->name('cancel');

        // Update Status (Generic)
        Route::post('/{order}/update-status', [PharmacyOrderController::class, 'updateStatus'])->name('update-status');

        // Download & Print
        Route::get('/{order}/download-prescription', [PharmacyOrderController::class, 'downloadPrescription'])->name('download-prescription');
        Route::get('/{order}/print-invoice', [PharmacyOrderController::class, 'printInvoice'])->name('print-invoice');
    });

    // ============================================
    // INVENTORY MANAGEMENT
    // ============================================
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/', [PharmacyInventoryController::class, 'index'])->name('index');
        Route::get('/low-stock', [PharmacyInventoryController::class, 'lowStock'])->name('low-stock');
        Route::get('/out-of-stock', [PharmacyInventoryController::class, 'outOfStock'])->name('out-of-stock');
        Route::get('/{medicine}/stock-history', [PharmacyInventoryController::class, 'stockHistory'])->name('stock-history');
    });

    // ============================================
    // PATIENTS
    // ============================================
    Route::prefix('patients')->name('patients.')->group(function () {
        Route::get('/', [PharmacyPatientController::class, 'index'])->name('index');
        Route::get('/{patient}', [PharmacyPatientController::class, 'show'])->name('show');
        Route::get('/{patient}/orders', [PharmacyPatientController::class, 'orders'])->name('orders');
        Route::get('/{patient}/prescriptions', [PharmacyPatientController::class, 'prescriptions'])->name('prescriptions');
    });

    // ============================================
    // REPORTS & ANALYTICS
    // ============================================
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [PharmacyReportController::class, 'index'])->name('index');
        Route::get('/daily', [PharmacyReportController::class, 'daily'])->name('daily');
        Route::get('/monthly', [PharmacyReportController::class, 'monthly'])->name('monthly');
        Route::get('/sales', [PharmacyReportController::class, 'sales'])->name('sales');
        Route::get('/inventory', [PharmacyReportController::class, 'inventory'])->name('inventory');
        Route::post('/generate', [PharmacyReportController::class, 'generate'])->name('generate');
        Route::get('/export', [PharmacyReportController::class, 'export'])->name('export');
    });

    // ============================================
    // RATINGS & REVIEWS
    // ============================================
    Route::prefix('ratings')->name('ratings.')->group(function () {
        Route::get('/', [PharmacyRatingController::class, 'index'])->name('index');
        Route::get('/{rating}', [PharmacyRatingController::class, 'show'])->name('show');
        Route::post('/{rating}/reply', [PharmacyRatingController::class, 'reply'])->name('reply');
    });

    // ============================================
    // NOTIFICATIONS
    // ============================================
    Route::get('/notifications', [PharmacyDashboardController::class, 'notifications'])->name('notifications');
    Route::post('/notifications/{notification}/mark-read', [PharmacyDashboardController::class, 'markNotificationRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [PharmacyDashboardController::class, 'markAllNotificationsRead'])->name('notifications.mark-all-read');

    // ============================================
    // SETTINGS & ACCOUNT
    // ============================================
    Route::get('/settings', [PharmacySettingController::class, 'index'])->name('settings');
    Route::put('/settings/update', [PharmacySettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/change-password', [PharmacySettingController::class, 'changePassword'])->name('settings.change-password');

    Route::get('/account', [PharmacySettingController::class, 'account'])->name('account');
    Route::put('/account/update', [PharmacySettingController::class, 'updateAccount'])->name('account.update');

});

/*
|--------------------------------------------------------------------------
| Public Routes (No Authentication Required)
|--------------------------------------------------------------------------
*/

// Welcome/Home Page - වෙල්කම් පේජ්

Route::get('/', function () {

    $featuredDoctors = Doctor::query()
        ->with(['user', 'workplaces'])   // workplaces show කරන්න
        ->where('status', 'approved')
        ->whereHas('user', fn($q) => $q->where('status', 'active'))
        ->orderByDesc('rating')
        ->orderByDesc('created_at')
        ->limit(12)
        ->get();

    return view('welcome', compact('featuredDoctors'));
})->name('Home');


// Offline Page
Route::get('/offline', function () {
    return response()->file(public_path('offline.html'));
})->name('offline');

// Search Route
Route::get('/search', function () {
    $query = request('q');
    return view('search', compact('query'));
})->name('search');

// Contact Form
Route::post('/contact', function () {
    return redirect()->route('Home')->with('success', 'Message sent successfully!');
})->name('contact.submit');

require __DIR__ . '/auth.php';
/*
|--------------------------------------------------------------------------
| Guest Routes (Only for Unauthenticated Users)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    // Signup Form Pages
    Route::get('/signup', function () {
    return view('auth.signup');
    })->middleware('guest')->name('signup');

    // Patient Signup Page (alternative route)
    Route::view('/signup/patient', 'auth.signup')
        ->middleware('guest');

    // Provider Signup Page - loads provider-signup.blade.php
    Route::get('/provider-signup', function () {
        return view('auth.provider-signup');
    })->middleware('guest')->name('provider-signup');

    // Provider Signup Page (alternative route)
    Route::view('/signup/provider', 'auth.provider-signup')
        ->middleware('guest');


   // Patient Registration POST (from signup.blade.php)
    Route::post('/signup/patient', [SignupController::class, 'registerPatient'])
        ->middleware('guest')
        ->name('signup.patient');

    // Provider Registration POST (from provider-signup.blade.php)
    Route::post('/signup/provider', [SignupController::class, 'registerProvider'])
        ->middleware('guest')
        ->name('signup.provider');

    // Forgot Password Routes
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])
        ->name('password.request');

    Route::post('/forgot-password/send-otp', [ForgotPasswordController::class, 'sendOtp'])
        ->name('password.sendOtp');

    Route::post('/forgot-password/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])
        ->name('password.verifyOtp');

    Route::post('/forgot-password/reset', [ForgotPasswordController::class, 'resetPassword'])
        ->name('password.reset');
});



// ============================================
// PATIENT NOTIFICATIONS ROUTES
// ============================================
Route::prefix('patient')->name('patient.')->middleware(['auth'])->group(function () {
    // Notifications Page
    Route::get('/notifications', [App\Http\Controllers\Patient\PatientNotificationController::class, 'index'])->name('notifications');

    // Mark notification as read
    Route::post('/notifications/{id}/read', [App\Http\Controllers\Patient\PatientNotificationController::class, 'markAsRead']);

    // Mark all notifications as read
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\Patient\PatientNotificationController::class, 'markAllAsRead']);

    // Delete notification
    Route::delete('/notifications/{id}', [App\Http\Controllers\Patient\PatientNotificationController::class, 'delete']);

    // Get notification count (for AJAX)
    Route::get('/notifications/count', [App\Http\Controllers\Patient\PatientNotificationController::class, 'getCount']);
});

/*
|--------------------------------------------------------------------------
| Social OAuth Routes
|--------------------------------------------------------------------------
*/

Route::get('/auth/{driver}', [SocialAuthController::class, 'redirect'])
    ->whereIn('driver', ['google', 'facebook'])
    ->name('oauth.redirect');

Route::get('/auth/{driver}/callback', [SocialAuthController::class, 'callback'])
    ->whereIn('driver', ['google', 'facebook'])
    ->name('oauth.callback');

/*
|--------------------------------------------------------------------------
| Email Verification Routes (Authenticated Users Only)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Email Verification Notice Page
    Route::get('/email/verify', function () {
        // If already verified, redirect to dashboard
        if (auth()->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }
        return view('auth.verify-email');
    })->name('verification.notice');


  // Email Verification Handler
    Route::get('/email/verify/{id}/{hash}', [App\Http\Controllers\Auth\VerifyEmailController::class, '__invoke'])
        ->middleware(['auth', 'signed'])
        ->name('verification.verify');

    // Resend Verification Email
    Route::post('/email/verification-notification', function (Request $request) {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'Verification link sent!');
    })->middleware(['throttle:6,1'])->name('verification.send');

    // Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    //     $request->fulfill();
    //     return redirect()->route('login')->with('verified', 'Email verification successful!');
    // })->middleware(['auth', 'signed'])->name('verification.verify');

});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Require Login)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Universal Dashboard Route (Redirects based on user type)
    Route::get('/dashboard', function () {
        $user = auth()->user();

        // Redirect based on user type
        $route = match($user->user_type) {
            'patient' => 'patient.dashboard',
            'doctor' => 'doctor.dashboard',
            'hospital' => 'hospital.dashboard',
            'laboratory' => 'laboratory.dashboard',
            'pharmacy' => 'pharmacy.dashboard',
            'medicalcentre' => 'medical_centre.dashboard',
            'admin' => 'admin.dashboard',
            default => 'Home',
        };

        return redirect()->route($route);
    })->name('dashboard');

    // Logout Route
    Route::get('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('Home')->with('success', 'You have logged out successfully!');
    })->name('logout');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



/*
|--------------------------------------------------------------------------
| Protected Routes (Require Authentication + Email Verification)
|--------------------------------------------------------------------------
*/

 Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/patient/dashboard', function () {
        return view('patient.dashboard');
    })->name('patient.Main_dashboard');

});


// ============================================
// PATIENT ROUTES - Updated
// ============================================
Route::middleware(['auth'])->prefix('patient')->name('patient.')->group(function() {

    // Find Doctors Routes
    Route::get('/doctors', [FindDoctorsController::class, 'index'])->name('doctors');
    Route::get('/doctors/{id}', [FindDoctorsController::class, 'show'])->name('doctors.show');

    // Hospitals Routes (Patient facing)
    Route::get('/hospitals', [PatientHospitalController::class, 'index'])->name('hospitals');
    Route::get('/hospitals/{id}', [PatientHospitalController::class, 'show'])->name('hospitals.show');

    // Medical Centres Routes (Patient facing)
    Route::get('/medical-centres', [PatientMedicalCentreController::class, 'index'])->name('medical-centres');
    Route::get('/medical-centres/{id}', [PatientMedicalCentreController::class, 'show'])->name('medical-centres.show');

    // Appointments Routes
    Route::get('/appointments', [PatientAppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create', [PatientAppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments/store/{doctor_id}', [PatientAppointmentController::class, 'store'])->name('appointments.store');

    // Patient Laboratory Routes
    Route::get('/laboratories', [PatientLaboratoryController::class, 'index'])->name('laboratories');
    Route::get('/laboratories/{id}', [PatientLaboratoryController::class, 'show'])->name('laboratories.show');

    // Patient Pharmacy Routes
    Route::get('/pharmacies', [PatientPharmacyController::class, 'index'])->name('pharmacies');
    Route::get('/pharmacies/{id}', [PatientPharmacyController::class, 'show'])->name('pharmacies.show');


});




Route::middleware(['auth'])->group(function () {

    // ============================================
    // PATIENT DASHBOARD & ROUTES
    // ============================================

    //MAIN HOME PAGE - ලොග් උනාම පිටුව
    Route::get('/Main-page', function () {
        // Featured Doctors (approved + active user)
        $featuredDoctors = Doctor::query()
            ->with([
                'user',
                'workplaces' => function ($q) {
                    $q->where('status', 'approved')->with(['hospital', 'medicalCentre']);
                }
            ])
            ->where('status', 'approved')
            ->whereHas('user', fn($q) => $q->where('status', 'active'))
            ->orderByDesc('rating')
            ->orderByDesc('created_at')
            ->limit(12)
            ->get();

        // Featured Hospitals (approved)
        $featuredHospitals = Hospital::query()
            ->where('status', 'approved')
            ->orderByDesc('rating')
            ->orderBy('name')
            ->limit(12)
            ->get();

        // Featured Medical Centres (approved) - used when tab is Medical Centres
        $featuredMedicalCentres = MedicalCentre::query()
            ->where('status', 'approved')
            ->orderByDesc('rating')
            ->orderBy('name')
            ->limit(12)
            ->get();


               // ✅ NEW: Load Active Announcements from Database
    $activeAnnouncements = \App\Models\Announcement::query()
        ->where('is_active', 1) // Only active announcements
        ->where(function($q) {
            // Check if start_date is null or in the past/today
            $q->whereNull('start_date')
              ->orWhere('start_date', '<=', now()->toDateString());
        })
        ->where(function($q) {
            // Check if end_date is null or in the future/today
            $q->whereNull('end_date')
              ->orWhere('end_date', '>=', now()->toDateString());
        })
        ->orderByDesc('created_at')
        ->limit(6) // Show only 6 announcements
        ->get();

        return view('Main_Home', compact(
        'featuredDoctors',
        'featuredHospitals',
        'featuredMedicalCentres',
         'activeAnnouncements'
    ));
    })->name('patient.dashboard');


    //FIND DOCTORS PAGE
    // Route::get('/patient/Doctors', function () {
    //     return view('patient.Find-doctors');
    // })->name('patient.doctors');

    // ============================================
    // DOCTOR DASHBOARD & ROUTES
    // ============================================
    Route::get('/doctor/dashboard', function () {
        return view('doctor.dashboard');
    })->name('doctor.dashboard');

    // ============================================
    // HOSPITAL DASHBOARD & ROUTES
    // ============================================
    Route::get('/hospital/dashboard', function () {
        return view('hospital.dashboard');
    })->name('hospital.dashboard');

    // ============================================
    // LABORATORY DASHBOARD & ROUTES
    // ============================================
    Route::get('/laboratory/dashboard', function () {
        return view('laboratory.dashboard');
    })->name('laboratory.dashboard');

    // ============================================
    // PHARMACY DASHBOARD & ROUTES
    // ============================================
    Route::get('/pharmacy/dashboard', function () {
        return view('pharmacy.dashboard');
    })->name('pharmacy.dashboard');

    // ============================================
    // MEDICAL CENTRE DASHBOARD & ROUTES
    // ============================================
    Route::get('/medical-centre/dashboard', function () {
        return view('medicalcentre.dashboard');
    })->name('medical_centre.dashboard');


});

/*
|--------------------------------------------------------------------------
| Admin Routes (Require Authentication + Admin Role)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {

    // Admin Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Dashboard Stats API
    Route::get('/dashboard/stats', function() {
        return response()->json([
            'total_users' => \App\Models\User::count(),
            'total_doctors' => Doctor::count(),
            'total_hospitals' => Hospital::count(),
            'total_appointments' => DB::table('appointments')->count(),
        ]);
    })->name('dashboard.stats');

    // Profile & Settings
    Route::get('/profile', function() {
        return view('admin.profile');
    })->name('profile');

    Route::get('/settings', function() {
        return view('admin.settings');
    })->name('settings');

    Route::get('/approvals', function() {
        return view('admin.approvals');
    })->name('approvals');


    // ============================================
    // USERS MANAGEMENT
    // ============================================
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{id}', [UserController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/suspend', [UserController::class, 'suspend'])->name('suspend');
        Route::post('/{id}/activate', [UserController::class, 'activate'])->name('activate');
    });

    // ============================================
    // DOCTORS MANAGEMENT
    // ============================================
   Route::prefix('doctors')->name('doctors.')->group(function () {
        Route::get('/', [DoctorController::class, 'index'])->name('index');
        Route::get('/create', [DoctorController::class, 'create'])->name('create');
        Route::post('/', [DoctorController::class, 'store'])->name('store');
        Route::get('/{id}', [DoctorController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [DoctorController::class, 'edit'])->name('edit');
        Route::put('/{id}', [DoctorController::class, 'update'])->name('update');
        Route::delete('/{id}', [DoctorController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/approve', [DoctorController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [DoctorController::class, 'reject'])->name('reject');
        Route::post('/{id}/suspend', [DoctorController::class, 'suspend'])->name('suspend');
        Route::post('/{id}/activate', [DoctorController::class, 'activate'])->name('activate');

        Route::post('/workplaces/{id}/approve', [DoctorController::class, 'approveWorkplace'])->name('workplaces.approve');
        Route::post('/workplaces/{id}/reject', [DoctorController::class, 'rejectWorkplace'])->name('workplaces.reject');
        Route::delete('/workplaces/{id}', [DoctorController::class, 'deleteWorkplace'])->name('workplaces.delete');
    });

    // ============================================
    // HOSPITALS MANAGEMENT
    // ============================================
    Route::prefix('hospitals')->name('hospitals.')->group(function () {
        Route::get('/', [HospitalController::class, 'index'])->name('index');
        Route::get('/create', [HospitalController::class, 'create'])->name('create');
        Route::post('/', [HospitalController::class, 'store'])->name('store');
        Route::get('/{id}', [HospitalController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [HospitalController::class, 'edit'])->name('edit');
        Route::put('/{id}', [HospitalController::class, 'update'])->name('update');
        Route::delete('/{id}', [HospitalController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/approve', [HospitalController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [HospitalController::class, 'reject'])->name('reject');
        Route::post('/{id}/suspend', [HospitalController::class, 'suspend'])->name('suspend');
        Route::post('/{id}/activate', [HospitalController::class, 'activate'])->name('activate');
    });

    // ============================================
    // LABORATORIES MANAGEMENT
    // ============================================
    Route::prefix('laboratories')->name('laboratories.')->group(function () {
        Route::get('/', [LaboratoryController::class, 'index'])->name('index');
        Route::get('/create', [LaboratoryController::class, 'create'])->name('create');
        Route::post('/', [LaboratoryController::class, 'store'])->name('store');
        Route::get('/{id}', [LaboratoryController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [LaboratoryController::class, 'edit'])->name('edit');
        Route::put('/{id}', [LaboratoryController::class, 'update'])->name('update');
        Route::delete('/{id}', [LaboratoryController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/approve', [LaboratoryController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [LaboratoryController::class, 'reject'])->name('reject');
        Route::post('/{id}/suspend', [LaboratoryController::class, 'suspend'])->name('suspend');
        Route::post('/{id}/activate', [LaboratoryController::class, 'activate'])->name('activate');
    });

    // ============================================
    // PHARMACIES MANAGEMENT
    // ============================================
    Route::prefix('pharmacies')->name('pharmacies.')->group(function () {
        Route::get('/', [PharmacyController::class, 'index'])->name('index');
        Route::get('/create', [PharmacyController::class, 'create'])->name('create');
        Route::post('/', [PharmacyController::class, 'store'])->name('store');
        Route::get('/{id}', [PharmacyController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [PharmacyController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PharmacyController::class, 'update'])->name('update');
        Route::delete('/{id}', [PharmacyController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/approve', [PharmacyController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [PharmacyController::class, 'reject'])->name('reject');
        Route::post('/{id}/suspend', [PharmacyController::class, 'suspend'])->name('suspend');
        Route::post('/{id}/activate', [PharmacyController::class, 'activate'])->name('activate');
    });

    // ============================================
    // MEDICAL CENTRES MANAGEMENT
    // ============================================
    Route::prefix('medical-centres')->name('medical-centres.')->group(function () {
        Route::get('/', [MedicalCentreController::class, 'index'])->name('index');
        Route::get('/create', [MedicalCentreController::class, 'create'])->name('create');
        Route::post('/', [MedicalCentreController::class, 'store'])->name('store');
        Route::get('/{id}', [MedicalCentreController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [MedicalCentreController::class, 'edit'])->name('edit');
        Route::put('/{id}', [MedicalCentreController::class, 'update'])->name('update');
        Route::delete('/{id}', [MedicalCentreController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/approve', [MedicalCentreController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [MedicalCentreController::class, 'reject'])->name('reject');
        Route::post('/{id}/suspend', [MedicalCentreController::class, 'suspend'])->name('suspend');
        Route::post('/{id}/activate', [MedicalCentreController::class, 'activate'])->name('activate');
    });

    // ============================================
    // PATIENTS MANAGEMENT
    // ============================================
    Route::prefix('patients')->name('patients.')->group(function () {
        Route::get('/', [PatientController::class, 'index'])->name('index');
        Route::get('/create', [PatientController::class, 'create'])->name('create');
        Route::post('/', [PatientController::class, 'store'])->name('store');
        Route::get('/{id}', [PatientController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [PatientController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PatientController::class, 'update'])->name('update');
        Route::delete('/{id}', [PatientController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/approve', [PatientController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [PatientController::class, 'reject'])->name('reject');
        Route::post('/{id}/suspend', [PatientController::class, 'suspend'])->name('suspend');
        Route::post('/{id}/activate', [PatientController::class, 'activate'])->name('activate');
    });

    // ============================================
    // APPOINTMENTS MANAGEMENT
    // ============================================
    Route::prefix('appointments')->name('appointments.')->group(function () {
        Route::get('/', [AppointmentController::class, 'index'])->name('index');
        Route::get('/create', [AppointmentController::class, 'create'])->name('create');
        Route::post('/', [AppointmentController::class, 'store'])->name('store');
        Route::get('/{id}', [AppointmentController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [AppointmentController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AppointmentController::class, 'update'])->name('update');
        Route::delete('/{id}', [AppointmentController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/confirm', [AppointmentController::class, 'confirm'])->name('confirm');
        Route::post('/{id}/cancel', [AppointmentController::class, 'cancel'])->name('cancel');
        Route::post('/{id}/complete', [AppointmentController::class, 'complete'])->name('complete');
        Route::post('/{id}/mark-no-show', [AppointmentController::class, 'markNoShow'])->name('mark-no-show');
    });



    // ============================================
    // LAB ORDERS MANAGEMENT
    // ============================================
    Route::get('/lab-orders', function() {
        return view('admin.lab_orders.index');
    })->name('lab-orders.index');

    Route::get('/lab-orders/{id}', function($id) {
        return view('admin.lab_orders.show', compact('id'));
    })->name('lab-orders.show');

    // ============================================
    // PRESCRIPTIONS MANAGEMENT
    // ============================================
    Route::get('/prescriptions', function() {
        return view('admin.prescriptions.index');
    })->name('prescriptions.index');

    Route::get('/prescriptions/{id}', function($id) {
        return view('admin.prescriptions.show', compact('id'));
    })->name('prescriptions.show');

    // ============================================
    // PAYMENTS MANAGEMENT
    // ============================================
    Route::get('/payments', function() {
        return view('admin.payments.index');
    })->name('payments.index');

    Route::get('/payments/{id}', function($id) {
        return view('admin.payments.show', compact('id'));
    })->name('payments.show');

    // ============================================
    // ANNOUNCEMENTS MANAGEMENT
    // ============================================
   Route::prefix('announcements')->name('announcements.')->group(function () {
        Route::get('/', [AnnouncementController::class, 'index'])->name('index');
        Route::get('/create', [AnnouncementController::class, 'create'])->name('create');
        Route::post('/', [AnnouncementController::class, 'store'])->name('store');

        Route::get('/{announcement}', [AnnouncementController::class, 'show'])->name('show');
        Route::get('/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('edit');
        Route::put('/{announcement}', [AnnouncementController::class, 'update'])->name('update');
        Route::delete('/{announcement}', [AnnouncementController::class, 'destroy'])->name('destroy');

        Route::post('/{announcement}/toggle', [AnnouncementController::class, 'toggleActive'])->name('toggle');
    });
});



    /*
|--------------------------------------------------------------------------
| Doctor Routes (Require Authentication + Verified Email)
|--------------------------------------------------------------------------
*/
    // Route::prefix('doctor')->name('doctor.')->middleware(['auth', 'verified', 'doctor.only'])->group(function () {
        Route::prefix('doctor')->name('doctor.')->middleware(['auth'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [DoctorDashboardController::class, 'index'])->name('dashboard');

        // Dashboard Stats API
        Route::get('/dashboard/stats', [DoctorDashboardController::class, 'getStats'])->name('dashboard.stats');

        // Today's Appointments API
        Route::get('/appointments/today', [DoctorDashboardController::class, 'getTodayAppointments'])->name('appointments.today');

        // Recent Patients API
        Route::get('/patients/recent', [DoctorDashboardController::class, 'getRecentPatients'])->name('patients.recent');

        // Recent Reviews API
        Route::get('/reviews/recent', [DoctorDashboardController::class, 'getRecentReviews'])->name('reviews.recent');

        // ============================================
        // APPOINTMENTS MANAGEMENT
        // ============================================
        Route::prefix('appointments')->name('appointments.')->group(function () {
            Route::get('/', [DoctorAppointmentController::class, 'index'])->name('index');
            Route::get('/{id}', [DoctorAppointmentController::class, 'show'])->name('show');
            Route::post('/{id}/confirm', [DoctorAppointmentController::class, 'confirm'])->name('confirm');
            Route::post('/{id}/cancel', [DoctorAppointmentController::class, 'cancel'])->name('cancel');
            Route::post('/{id}/complete', [DoctorAppointmentController::class, 'complete'])->name('complete');
            Route::post('/{id}/reschedule', [DoctorAppointmentController::class, 'reschedule'])->name('reschedule');
            Route::post('/{id}/add-notes', [DoctorAppointmentController::class, 'addNotes'])->name('add-notes');
        });

        // ============================================
        // SCHEDULE MANAGEMENT
        // ============================================
        Route::prefix('schedule')->name('schedule.')->group(function () {
            Route::get('/', [DoctorScheduleController::class, 'index'])->name('index');
            Route::get('/create', [DoctorScheduleController::class, 'create'])->name('create');
            Route::post('/', [DoctorScheduleController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [DoctorScheduleController::class, 'edit'])->name('edit');
            Route::put('/{id}', [DoctorScheduleController::class, 'update'])->name('update');
            Route::delete('/{id}', [DoctorScheduleController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/toggle-status', [DoctorScheduleController::class, 'toggleStatus'])->name('toggle-status');
        });

        // ============================================
        // PATIENTS MANAGEMENT
        // ============================================
        Route::prefix('patients')->name('patients.')->group(function () {
            Route::get('/', [DoctorPatientController::class, 'index'])->name('index');
            Route::get('/{id}', [DoctorPatientController::class, 'show'])->name('show');
            Route::get('/{id}/history', [DoctorPatientController::class, 'history'])->name('history');
            Route::post('/{id}/add-prescription', [DoctorPatientController::class, 'addPrescription'])->name('add-prescription');
            Route::post('/{id}/add-lab-request', [DoctorPatientController::class, 'addLabRequest'])->name('add-lab-request');
        });

        // ============================================
        // WORKPLACES MANAGEMENT
        // ============================================
        Route::prefix('workplaces')->name('workplaces.')->group(function () {
            Route::get('/', [DoctorWorkplaceController::class, 'index'])->name('index');
            Route::get('/create', [DoctorWorkplaceController::class, 'create'])->name('create');
            Route::post('/', [DoctorWorkplaceController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [DoctorWorkplaceController::class, 'edit'])->name('edit');
            Route::put('/{id}', [DoctorWorkplaceController::class, 'update'])->name('update');
            Route::delete('/{id}', [DoctorWorkplaceController::class, 'destroy'])->name('destroy');
        });

        // ============================================
        // EARNINGS & REPORTS
        // ============================================
        Route::prefix('earnings')->name('earnings.')->group(function () {
            Route::get('/', [DoctorEarningsController::class, 'index'])->name('index');
            Route::get('/export', [DoctorEarningsController::class, 'export'])->name('export');
            Route::get('/statistics', [DoctorEarningsController::class, 'statistics'])->name('statistics');
        });

        // ============================================
        // REVIEWS & RATINGS
        // ============================================
        Route::prefix('reviews')->name('reviews.')->group(function () {
            Route::get('/', [DoctorReviewController::class, 'index'])->name('index');
            Route::get('/{id}', [DoctorReviewController::class, 'show'])->name('show');
            Route::post('/{id}/reply', [DoctorReviewController::class, 'reply'])->name('reply');
        });

        // ============================================
        // NOTIFICATIONS
        // ============================================
        Route::get('/notifications', [DoctorNotificationController::class, 'index'])->name('notifications');
        Route::post('/notifications/{id}/read', [DoctorNotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/mark-all-read', [DoctorNotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
        Route::get('/notifications/count', [DoctorNotificationController::class, 'getUnreadCount'])->name('notifications.count');

        // ============================================
        // PROFILE & SETTINGS
        // ============================================
        Route::get('/profile/edit', [DoctorProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/update', [DoctorProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile/update-image', [DoctorProfileController::class, 'updateImage'])->name('profile.update-image');
        Route::post('/profile/update-password', [DoctorProfileController::class, 'updatePassword'])->name('profile.update-password');

        Route::get('/settings', [DoctorSettingsController::class, 'index'])->name('settings');
        Route::put('/settings/update', [DoctorSettingsController::class, 'update'])->name('settings.update');
    });



/*
|--------------------------------------------------------------------------
| Laravel Breeze Auth Routes
|--------------------------------------------------------------------------
*/


