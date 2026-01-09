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
        'workplace_type',   // hospital | medical_centre | private
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
        'appointment_time' => 'string',      // TIME column -> keep as string
        'consultation_fee' => 'decimal:2',
        'advance_payment' => 'decimal:2',
    ];

    protected $attributes = [
        'status' => 'pending',
        'payment_status' => 'unpaid',
        'advance_payment' => 0.00,
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($appointment) {
            if (empty($appointment->appointment_number)) {
                $appointment->appointment_number = self::generateAppointmentNumber();
            }
        });
    }

    public static function generateAppointmentNumber()
    {
        do {
            $number = 'APT-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        } while (self::where('appointment_number', $number)->exists());

        return $number;
    }

    // Relationships
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Hospital relation (NO where clauses; hospitals table has no workplace_type column)
     */
    public function hospital()
    {
        return $this->belongsTo(Hospital::class, 'workplace_id');
    }

    /**
     * Medical centre relation (NO where clauses; medicalcentres table has no workplace_type column)
     */
    public function medicalCentre()
    {
        return $this->belongsTo(MedicalCentre::class, 'workplace_id');
    }

    public function cancelledBy()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'related_id')
            ->where('related_type', 'appointment');
    }
}
