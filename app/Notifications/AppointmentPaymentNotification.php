<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AppointmentPaymentNotification extends Notification
{
    use Queueable;

    public Appointment $appointment;
    public string $recipientType; // 'patient', 'doctor', 'provider'
    public string $providerName;
    public string $paymentNumber;

    public function __construct(
        Appointment $appointment,
        string $recipientType,
        string $providerName,
        string $paymentNumber
    ) {
        $this->appointment   = $appointment;
        $this->recipientType = $recipientType;
        $this->providerName  = $providerName;
        $this->paymentNumber = $paymentNumber;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $appointment = $this->appointment;
        $doctor      = $appointment->doctor;
        $patient     = $appointment->patient;
        $apptDate    = \Carbon\Carbon::parse($appointment->appointment_date)->format('D, d M Y');
        $apptTime    = \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A');
        $fee         = number_format($appointment->consultation_fee ?? 0, 2);

        // ── Patient email ──
        if ($this->recipientType === 'patient') {
            return (new MailMessage)
                ->subject('✅ Payment Confirmed – Appointment #' . $appointment->appointment_number)
                ->greeting('Hello ' . ($patient->first_name ?? 'Patient') . ',')
                ->line('Your payment has been received successfully. Your appointment is now **pending confirmation** by the doctor.')
                ->line('---')
                ->line('**Appointment Details**')
                ->line('📋 Reference: ' . $appointment->appointment_number)
                ->line('💳 Payment No: ' . $this->paymentNumber)
                ->line('👨‍⚕️ Doctor: Dr. ' . ($doctor->first_name ?? '') . ' ' . ($doctor->last_name ?? ''))
                ->line('🏥 Location: ' . $this->providerName)
                ->line('📅 Date: ' . $apptDate)
                ->line('🕐 Time: ' . $apptTime)
                ->line('💰 Amount Paid: Rs. ' . $fee)
                ->line('---')
                ->line('⏳ **Status: Pending Confirmation** — The doctor will confirm your appointment shortly.')
                ->action('View My Appointments', route('patient.appointments.index'))
                ->line('Thank you for choosing HealthNet!')
                ->salutation('— HealthNet Team');
        }

        // ── Doctor email ──
        if ($this->recipientType === 'doctor') {
            return (new MailMessage)
                ->subject('🔔 New Paid Appointment – Action Required')
                ->greeting('Hello Dr. ' . ($doctor->first_name ?? '') . ',')
                ->line('A patient has completed payment for an appointment. Please review and confirm.')
                ->line('---')
                ->line('**Patient Details**')
                ->line('👤 Patient: ' . ($patient->first_name ?? '') . ' ' . ($patient->last_name ?? ''))
                ->line('📋 Appointment No: ' . $appointment->appointment_number)
                ->line('💳 Payment No: ' . $this->paymentNumber)
                ->line('🏥 Location: ' . $this->providerName)
                ->line('📅 Date: ' . $apptDate)
                ->line('🕐 Time: ' . $apptTime)
                ->line('💰 Fee: Rs. ' . $fee)
                ->line('📝 Reason: ' . ($appointment->reason ?? 'Not specified'))
                ->line('---')
                ->line('⚠️ **Action Required:** Please confirm or manage this appointment from your dashboard.')
                ->action('View Appointments', route('doctor.appointments.index'))
                ->salutation('— HealthNet System');
        }

        // ── Provider (Hospital / Medical Centre) email ──
        return (new MailMessage)
            ->subject('🔔 New Appointment Booked – ' . $this->providerName)
            ->greeting('Hello,')
            ->line('A new appointment has been confirmed with payment at **' . $this->providerName . '**.')
            ->line('---')
            ->line('**Appointment Details**')
            ->line('👤 Patient: ' . ($patient->first_name ?? '') . ' ' . ($patient->last_name ?? ''))
            ->line('👨‍⚕️ Doctor: Dr. ' . ($doctor->first_name ?? '') . ' ' . ($doctor->last_name ?? ''))
            ->line('📋 Ref No: ' . $appointment->appointment_number)
            ->line('💳 Payment No: ' . $this->paymentNumber)
            ->line('📅 Date: ' . $apptDate)
            ->line('🕐 Time: ' . $apptTime)
            ->line('💰 Amount: Rs. ' . $fee)
            ->line('---')
            ->line('Please ensure the appointment is prepared and the doctor is notified.')
            ->salutation('— HealthNet System');
    }
}
