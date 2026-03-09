<?php

namespace App\Notifications;

use App\Models\LabOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LabReportReadyNotification extends Notification
{
    use Queueable;

    protected $order;
    protected $labName;

    public function __construct(LabOrder $order, string $labName = 'HealthNet Laboratory')
    {
        $this->order = $order;
        $this->labName = $labName;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $patientName = trim(
            ($this->order->patient->first_name ?? '') . ' ' .
            ($this->order->patient->last_name ?? '')
        );

        return (new MailMessage)
            ->subject('Your Lab Report is Ready - ' . $this->order->order_number)
            ->greeting('Hello ' . ($patientName ?: 'Patient') . ',')
            ->line('Your laboratory report has been uploaded successfully.')
            ->line('Order Number: ' . $this->order->order_number)
            ->line('Reference Number: ' . $this->order->reference_number)
            ->line('Laboratory: ' . $this->labName)
            ->line('Uploaded At: ' . optional($this->order->report_uploaded_at)->format('Y-m-d h:i A'))
            ->action('View / Download Report', url('/patient/lab-orders/' . $this->order->id))
            ->line('Please log in to your HealthNet account to view or download the report.')
            ->line('Thank you for using HealthNet.');
    }
}
