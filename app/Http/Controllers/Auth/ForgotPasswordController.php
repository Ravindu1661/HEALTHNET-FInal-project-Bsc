<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    /**
     * Display the forgot password form view.
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send a one-time password (OTP) to the user's email address for password reset.
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.exists' => 'This email is not registered in our system.'
        ]);

        try {
            $user = User::where('email', $request->email)->first();

            // Generate 6-digit numeric OTP
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            // Store OTP and expiry (10 minutes from now)
            $user->otp = $otp;
            $user->otp_expires_at = Carbon::now()->addMinutes(10);
            $user->save();

            // Send OTP email
            Mail::send('emails.otp', [
                'otp' => $otp,
                'user' => $user
            ], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('HEALTHNET - Password Reset OTP');
            });

            return response()->json([
                'success' => true,
                'message' => 'Your OTP code was sent successfully. Please check your email inbox to continue.'
            ]);
        } catch (\Exception $e) {
            \Log::error('OTP Send Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP. Please try again later.'
            ], 500);
        }
    }

    /**
     * Validate the user's submitted OTP.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|digits:6'
        ]);

        try {
            $user = User::where('email', $request->email)->first();

            // Check for OTP match and expiry
            if (!$user->otp || $user->otp !== $request->otp) {
                return response()->json([
                    'success' => false,
                    'message' => 'Incorrect OTP. Please check the code and try again.'
                ], 422);
            }

            if (Carbon::now()->gt($user->otp_expires_at)) {
                return response()->json([
                    'success' => false,
                    'message' => 'This OTP has expired. Please request a new code.'
                ], 422);
            }

            return response()->json([
                'success' => true,
                'message' => 'OTP verified successfully. You may now set a new password.'
            ]);
        } catch (\Exception $e) {
            \Log::error('OTP Verify Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'OTP verification failed due to a server issue. Please try again.'
            ], 500);
        }
    }

    /**
     * Reset the user's password after OTP verification.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|digits:6',
            'password' => 'required|min:8|confirmed'
        ], [
            'password.confirmed' => 'Passwords do not match.'
        ]);

        try {
            $user = User::where('email', $request->email)->first();

            // Double-check OTP before password reset
            if (!$user->otp || $user->otp !== $request->otp) {
                return response()->json([
                    'success' => false,
                    'message' => 'Incorrect OTP. Please try again.'
                ], 422);
            }

            if (Carbon::now()->gt($user->otp_expires_at)) {
                return response()->json([
                    'success' => false,
                    'message' => 'This OTP has expired. Please request a new code.'
                ], 422);
            }

            // Update password and clear OTP fields
            $user->password = bcrypt($request->password);
            $user->otp = null;
            $user->otp_expires_at = null;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Your password was updated successfully. You may now log in.',
                'redirect' => route('login')
            ]);
        } catch (\Exception $e) {
            \Log::error('Password Reset Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during password reset. Please try again later.'
            ], 500);
        }
    }
}
