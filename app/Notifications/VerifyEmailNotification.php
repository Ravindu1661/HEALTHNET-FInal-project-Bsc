<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class VerifyEmailNotification extends Notification 
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verify Your Email Address - HealthNet')
            ->greeting('Hello ' . $this->getUserName($notifiable) . '!')
            ->line('Thank you for registering with HealthNet.')
            ->line('Please click the button below to verify your email address.')
            ->action('Verify Email Address', $verificationUrl)
            ->line('This verification link will expire in 60 minutes.')
            ->line('If you did not create an account, no further action is required.')
            ->salutation('Best Regards, HealthNet Team');
    }

    /**
     * Get the verification URL for the given notifiable.
     */
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    /**
     * Get user name based on profile type
     */
    protected function getUserName($notifiable)
    {
        try {
            $profile = $notifiable->profile();
            
            // ⚠️ firstname → first_name, lastname → last_name වෙනස් කරන්න
            if ($notifiable->user_type === 'patient' && $profile) {
                return $profile->first_name . ' ' . $profile->last_name;
            } elseif ($notifiable->user_type === 'doctor' && $profile) {
                return 'Dr. ' . $profile->first_name . ' ' . $profile->last_name;
            } elseif (in_array($notifiable->user_type, ['hospital', 'laboratory', 'pharmacy', 'medicalcentre']) && $profile) {
                return $profile->name;
            }
        } catch (\Exception $e) {
            \Log::error('Error getting user name: ' . $e->getMessage());
        }
        
        return 'User';
    }
}
