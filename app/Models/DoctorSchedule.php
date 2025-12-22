<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorSchedule extends Model
{
    use HasFactory;

    protected $table = 'doctor_schedules';

    protected $fillable = [
        'doctor_id',
        'workplace_type',
        'workplace_id',
        'day_of_week',
        'start_time',
        'end_time',
        'max_appointments',
        'consultation_fee',
        'is_active',
    ];

    protected $casts = [
        'consultation_fee' => 'decimal:2',
        'max_appointments' => 'integer',
        'is_active' => 'boolean',
    ];

    protected $attributes = [
        'is_active' => true,
        'max_appointments' => 20,
    ];

    // ============================================
    // Relationships
    // ============================================

    /**
     * Doctor relationship
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Hospital relationship
     */
    public function hospital()
    {
        return $this->belongsTo(Hospital::class, 'workplace_id')
            ->where('workplace_type', 'hospital');
    }

    /**
     * Medical Centre relationship
     */
    public function medicalCentre()
    {
        return $this->belongsTo(MedicalCentre::class, 'workplace_id')
            ->where('workplace_type', 'medical_centre');
    }

    // ============================================
    // Scopes
    // ============================================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    public function scopeForDay($query, $day)
    {
        return $query->where('day_of_week', $day);
    }

    public function scopeForWorkplace($query, $type, $id)
    {
        return $query->where('workplace_type', $type)
            ->where('workplace_id', $id);
    }
}
