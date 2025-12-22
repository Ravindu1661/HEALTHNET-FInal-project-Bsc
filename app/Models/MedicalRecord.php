<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $table = 'medical_records';

    protected $fillable = [
        'patient_id',
        'record_type',
        'title',
        'description',
        'record_date',
        'doctor_id',
        'hospital_id',
        'file_path',
        'file_type',
    ];

    protected $casts = [
        'record_date' => 'date',
    ];

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
     * Hospital relationship
     */
    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    // ============================================
    // Accessors
    // ============================================

    /**
     * Get record type badge
     */
    public function getRecordTypeBadgeAttribute()
    {
        $badges = [
            'clinic_visit' => 'primary',
            'xray' => 'info',
            'scan' => 'info',
            'prescription' => 'success',
            'lab_report' => 'warning',
            'other' => 'secondary',
        ];
        return $badges[$this->record_type] ?? 'secondary';
    }

    /**
     * Get file URL
     */
    public function getFileUrlAttribute()
    {
        if ($this->file_path) {
            return asset('storage/' . $this->file_path);
        }
        return null;
    }

    // ============================================
    // Scopes
    // ============================================

    public function scopeForPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('record_type', $type);
    }

    public function scopeByDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }
}
