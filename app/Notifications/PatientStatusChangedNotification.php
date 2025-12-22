<?PHP
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PatientStatusChangedNotification extends Notification
{
    use Queueable;

    protected $status; // 'suspended' | 'active' | ...

    public function __construct($status)
    {
        $this->status = strtolower($status);
    }

    public function via($notifiable)
    {
        // Both status changes: mail + database
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $statusUc = ucfirst($this->status);

        switch ($this->status) {
            case 'active':
                $msg = "Your patient account has been activated by admin. You now have full access to HealthNet.";
                break;
            case 'suspended':
                $msg = "Your patient account has been suspended by admin. Please contact support for details.";
                break;
            default:
                $msg = "Your patient account status has changed to '$statusUc'. Please check your dashboard.";
        }

        return (new MailMessage)
            ->subject("Patient Account {$statusUc} - HEALTHNET")
            ->greeting("Hello " . ($notifiable->name ?? '') . ",")
            ->line($msg)
            ->salutation("Best Regards,\nHealthNet Team");
    }

    public function toDatabase($notifiable)
    {
        $statusUc = ucfirst($this->status);
        return [
            'type' => 'general',
            'title' => "Patient Account {$statusUc}",
            'message' => "Your patient account status changed to {$statusUc} by Admin."
        ];
    }
}
