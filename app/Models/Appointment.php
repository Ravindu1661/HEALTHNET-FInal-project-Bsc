<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Appointment extends Model
{
    use HasFactory;

    protected $table = 'appointments';

    protected $fillable = [
        'appointment_number',
        'patient_id',
        'doctor_id',
        'workplace_type',
        'workplace_id',
        'appointment_date',
        'appointment_time',
        'status',
        'reason',
        'notes',
        'consultation_fee',
        'advance_payment',
        'payment_status',
        'cancelled_by',
        'cancellation_reason',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'datetime',
        'consultation_fee' => 'decimal:2',
        'advance_payment' => 'decimal:2',
    ];

    // Default values
    protected $attributes = [
        'status' => 'pending',
        'payment_status' => 'unpaid',
        'advance_payment' => 0.00,
    ];

    // ============================================
    // Boot Method - Auto-generate appointment number
    // ============================================
    
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($appointment) {
            if (empty($appointment->appointment_number)) {
                $appointment->appointment_number = self::generateAppointmentNumber();
            }
        });
    }

    /**
     * Generate unique appointment number
     */
    public static function generateAppointmentNumber()
    {
        do {
            $number = 'APT-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        } while (self::where('appointment_number', $number)->exists());
        
        return $number;
    }

    // ============================================
    // Relationships
    // ============================================
    
    /**
     * Patient relationship
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Doctor relationship
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Polymorphic relationship for workplace (Hospital or Medical Centre)
     */
    public function workplace()
    {
        if ($this->workplace_type === 'hospital') {
            return $this->belongsTo(Hospital::class, 'workplace_id');
        } elseif ($this->workplace_type === 'medical_centre') {
            return $this->belongsTo(MedicalCentre::class, 'workplace_id');
        }
        return null;
    }

    /**
     * Hospital relationship (if workplace is hospital)
     */
    public function hospital()
    {
        return $this->belongsTo(Hospital::class, 'workplace_id')
            ->where('workplace_type', 'hospital');
    }

    /**
     * Medical Centre relationship (if workplace is medical centre)
     */
    public function medicalCentre()
    {
        return $this->belongsTo(MedicalCentre::class, 'workplace_id')
            ->where('workplace_type', 'medical_centre');
    }

    /**
     * User who cancelled the appointment
     */
    public function cancelledBy()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    /**
     * Payment for this appointment
     */
    public function payment()
    {
        return $this->hasOne(Payment::class, 'related_id')
            ->where('related_type', 'appointment');
    }

    // ============================================
    // Accessors & Mutators
    // ============================================
    
    /**
     * Get full appointment date and time
     */
    public function getFullAppointmentDateTimeAttribute()
    {
        return $this->appointment_date->format('M d, Y') . ' at ' . 
               \Carbon\Carbon::parse($this->appointment_time)->format('h:i A');
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'confirmed' => 'primary',
            'cancelled' => 'danger',
            'completed' => 'success',
            'no_show' => 'secondary',
        ];
        return $badges[$this->status] ?? 'secondary';
    }

    /**
     * Get payment status badge color
     */
    public function getPaymentStatusBadgeAttribute()
    {
        $badges = [
            'unpaid' => 'danger',
            'partial' => 'warning',
            'paid' => 'success',
        ];
        return $badges[$this->payment_status] ?? 'secondary';
    }

    /**
     * Get remaining payment amount
     */
    public function getRemainingPaymentAttribute()
    {
        return $this->consultation_fee - $this->advance_payment;
    }

    /**
     * Check if appointment is today
     */
    public function getIsTodayAttribute()
    {
        return $this->appointment_date->isToday();
    }

    /**
     * Check if appointment is upcoming
     */
    public function getIsUpcomingAttribute()
    {
        return $this->appointment_date->isFuture();
    }

    /**
     * Check if appointment is past
     */
    public function getIsPastAttribute()
    {
        return $this->appointment_date->isPast();
    }

    /**
     * Get workplace name
     */
    public function getWorkplaceNameAttribute()
    {
        if ($this->workplace_type === 'hospital' && $this->hospital) {
            return $this->hospital->name;
        } elseif ($this->workplace_type === 'medical_centre' && $this->medicalCentre) {
            return $this->medicalCentre->name;
        } elseif ($this->workplace_type === 'private') {
            return 'Private Practice';
        }
        return 'N/A';
    }

    // ============================================
    // Scopes for filtering
    // ============================================
    
    /**
     * Pending appointments
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Confirmed appointments
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Completed appointments
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Cancelled appointments
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Today's appointments
     */
    public function scopeToday($query)
    {
        return $query->whereDate('appointment_date', today());
    }

    /**
     * Upcoming appointments
     */
    public function scopeUpcoming($query)
    {
        return $query->where('appointment_date', '>=', today())
            ->whereIn('status', ['pending', 'confirmed']);
    }

    /**
     * Past appointments
     */
    public function scopePast($query)
    {
        return $query->where('appointment_date', '<', today());
    }

    /**
     * For specific doctor
     */
    public function scopeForDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    /**
     * For specific patient
     */
    public function scopeForPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    /**
     * Unpaid appointments
     */
    public function scopeUnpaid($query)
    {
        return $query->where('payment_status', 'unpaid');
    }

    /**
     * Paid appointments
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    // ============================================
    // Helper Methods
    // ============================================
    
    /**
     * Check if appointment can be cancelled
     */
    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'confirmed']) 
            && $this->appointment_date->isFuture();
    }

    /**
     * Check if appointment can be rescheduled
     */
    public function canBeRescheduled()
    {
        return in_array($this->status, ['pending', 'confirmed']) 
            && $this->appointment_date->isFuture();
    }

    /**
     * Check if appointment can be completed
     */
    public function canBeCompleted()
    {
        return $this->status === 'confirmed' 
            && $this->appointment_date->isToday();
    }

    /**
     * Mark as completed
     */
    public function markAsCompleted()
    {
        $this->update(['status' => 'completed']);
    }

    /**
     * Mark as confirmed
     */
    public function markAsConfirmed()
    {
        $this->update(['status' => 'confirmed']);
    }

    /**
     * Cancel appointment
     */
    public function cancel($reason = null, $cancelledBy = null)
    {
        $this->update([
            'status' => 'cancelled',
            'cancellation_reason' => $reason,
            'cancelled_by' => $cancelledBy ?? auth()->id(),
        ]);
    }
}
