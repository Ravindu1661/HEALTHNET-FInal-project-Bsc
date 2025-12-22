<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Events\Registered;
use App\Services\NotificationService;

class SocialAuthController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();

            // Find or create user
            $user = User::where('email', $socialUser->getEmail())->first();

            if (!$user) {
                DB::transaction(function () use ($socialUser, &$user) {
                    // Create user - ✅ email_verified_at is NULL (requires verification)
                    $user = User::create([
                        'email' => $socialUser->getEmail(),
                        'password' => Hash::make(Str::random(12)),
                        'user_type' => 'patient',
                        'status' => 'active',
                        'profile_image' => $socialUser->getAvatar() ?? null,
                        'email_verified_at' => null, // ✅ NOT auto-verified
                    ]);

                    // Ensure patient table record exists
                    if (!Patient::where('user_id', $user->id)->exists()) {
                        $name = $socialUser->getName() ?? 'Google User';
                        $name = trim($name);
                        $nameParts = preg_split('/\s+/', $name, 2);
                        $firstName = $nameParts[0] ?? 'Google';
                        $lastName = $nameParts[1] ?? 'User';

                        Patient::create([
                            'user_id' => $user->id,
                            'first_name' => $firstName,
                            'last_name' => $lastName,
                        ]);
                    }

                    // ✅ Send welcome notification
                    NotificationService::sendWelcomeNotification($user);

                    // ✅ Send verification email
                    event(new Registered($user));

                    // ✅ Send notification about verification email
                    NotificationService::sendVerificationSentNotification($user);
                });
            }

            // Login user
            Auth::login($user);

            // Redirect to dashboard
            $dashboardRoute = $this->redirectPathForUser($user->user_type);

            return redirect($dashboardRoute)
                ->with('login_welcome', 'Welcome to HealthNet! Please verify your email.');

        } catch (\Exception $e) {
            \Log::error('Social Login Error: ' . $e->getMessage());
            return redirect()->route('login')
                ->with('error', 'Social login failed. Please try again.');
        }
    }

    protected function redirectPathForUser(string $userType): string
    {
        switch ($userType) {
            case 'admin':
                return route('admin.dashboard');
            case 'doctor':
                return route('doctor.dashboard');
            case 'hospital':
                return route('hospital.dashboard');
            case 'laboratory':
                return route('laboratory.dashboard');
            case 'pharmacy':
                return route('pharmacy.dashboard');
            case 'medicalcentre':
                return route('medical_centre.dashboard');
            case 'patient':
            default:
                return route('patient.dashboard');
        }
    }
}
