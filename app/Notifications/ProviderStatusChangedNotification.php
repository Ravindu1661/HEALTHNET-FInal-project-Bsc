<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ProviderStatusChangedNotification extends Notification
{
    use Queueable;

    protected $status;
    protected $providerType;

    public function __construct($status, $providerType)
    {
        $this->status = $status;
        $this->providerType = $providerType; // Doctor / Hospital / Pharmacy etc.
    }

    public function via($notifiable)
    {
        // Approved = email + database
        // Others = only email
        return $this->status === 'approved'
            ? ['mail', 'database']
            : ['mail'];
    }

    public function toMail($notifiable)
    {
        $type = strtolower($this->providerType);

        $msg = $this->status === 'approved'
            ? "Your {$type} account has been approved by HealthNet admin. You now have full access!"
            : "Your {$type} account has been suspended by admin. Please contact support.";

        return (new MailMessage)
            ->subject("{$this->providerType} Account {$this->status} - HEALTHNET")
            ->greeting("Hello " . ($notifiable->name ?? '') . ",")
            ->line($msg)
            ->salutation("Best Regards,\nHealthNet Team");
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'general',
            'title' => ucfirst($this->providerType) . ' ' . ucfirst($this->status),
            'message' => "Your {$this->providerType} account has been {$this->status} by Admin."
        ];
    }
}
