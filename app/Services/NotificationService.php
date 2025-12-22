<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    /**
     * Create a notification for a user
     */
    public static function create($userId, $type, $title, $message, $relatedType = null, $relatedId = null)
    {
        try {
            return Notification::create([
                'notifiable_type' => User::class,
                'notifiable_id'   => $userId,
                'type'            => $type,
                'title'           => $title,
                'message'         => $message,
                'related_type'    => $relatedType,
                'related_id'      => $relatedId,
                'is_read'         => false,
            ]);
        } catch (\Exception $e) {
            \Log::error('Notification creation failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Send welcome notification after signup
     */
    public static function sendWelcomeNotification($user)
    {
        return self::create(
            $user->id,
            'general',
            'Welcome to HealthNet!',
            'Thank you for joining HealthNet. Your account has been created successfully. Please verify your email to access all features.'
        );
    }

    /**
     * Send email verification sent notification
     */
    public static function sendVerificationSentNotification($user)
    {
        return self::create(
            $user->id,
            'reminder',
            'Verification Email Sent',
            'A verification email has been sent to ' . $user->email . '. Please check your inbox and click the verification link.'
        );
    }

    /**
     * Send email verified notification
     */
    public static function sendEmailVerifiedNotification($user)
    {
        return self::create(
            $user->id,
            'general',
            'Email Verified Successfully!',
            'Congratulations! Your email has been verified. You now have full access to all HealthNet features.'
        );
    }

    /**
     * Send account approved notification
     */
    public static function sendAccountApprovedNotification($user)
    {
        return self::create(
            $user->id,
            'general',
            'Account Approved',
            'Your account has been approved by admin. You can now access all features of HealthNet.'
        );
    }
}
