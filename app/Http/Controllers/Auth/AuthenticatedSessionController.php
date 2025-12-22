<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show the login view.
     */
    public function create()
    {
        //load to login page
       return view('auth.login');
        
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();

        // Optional: Suspended / Rejected
        if (in_array($user->status, ['suspended', 'rejected'])) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Your account is ' . $user->status . '. Please contact support.');
        }

        // Redirect path by User type
        $redirectPath = $this->redirectPathForUser($user->user_type);

        // **Key change here**
        return redirect()->intended($redirectPath)
            ->with('login_welcome', 'Welcome to HealthNet!');
    }


    /**
     * Log the user out of the application.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        
        // මෙක mostly POST /logout සදහා. දෙකම same redirect.
        return redirect()->route('Home')
            ->with('success', 'You have logged out successfully!');
    }

    /**
     * Decide dashboard route by user_type.
     */
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

            case 'medical_centre':
                return route('medical_centre.dashboard');

            case 'patient':
            default:
                // patient & fallback
                return route('patient.dashboard');
        }
    }
}
