<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorWorkplace extends Model
{
    use HasFactory;

    protected $table = 'doctor_workplaces';

    protected $fillable = [
        'doctor_id',
        'workplace_type',       // 'hospital' or 'medical_centre'
        'workplace_id',
        'employment_type',      // 'permanent', 'temporary', 'visiting'
        'status',              // 'pending', 'approved', 'rejected'
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    // Default values
    protected $attributes = [
        'status' => 'pending',
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
     * Polymorphic relationship for workplace
     * Returns Hospital or MedicalCentre based on workplace_type
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
        return $this->belongsTo(Hospital::class, 'workplace_id');
    }

    /**
     * Medical Centre relationship (if workplace is medical centre)
     */
    public function medicalCentre()
    {
        return $this->belongsTo(MedicalCentre::class, 'workplace_id');
    }

    /**
     * Admin who approved this workplace association
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ============================================
    // Accessors
    // ============================================

    /**
     * Get workplace name
     */
    public function getWorkplaceNameAttribute()
    {
        if ($this->workplace_type === 'hospital' && $this->hospital) {
            return $this->hospital->name;
        } elseif ($this->workplace_type === 'medical_centre' && $this->medicalCentre) {
            return $this->medicalCentre->name;
        }
        return 'Unknown Workplace';
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
        ];
        return $badges[$this->status] ?? 'secondary';
    }

    /**
     * Get employment type badge color
     */
    public function getEmploymentTypeBadgeAttribute()
    {
        $badges = [
            'permanent' => 'primary',
            'temporary' => 'info',
            'visiting' => 'secondary',
        ];
        return $badges[$this->employment_type] ?? 'secondary';
    }

    /**
     * Get formatted employment type
     */
    public function getEmploymentTypeFormattedAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->employment_type));
    }

    // ============================================
    // Scopes for filtering
    // ============================================

    /**
     * Pending workplaces
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Approved workplaces
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Rejected workplaces
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * For specific doctor
     */
    public function scopeForDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    /**
     * For specific workplace type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('workplace_type', $type);
    }

    /**
     * Hospitals only
     */
    public function scopeHospitals($query)
    {
        return $query->where('workplace_type', 'hospital');
    }

    /**
     * Medical centres only
     */
    public function scopeMedicalCentres($query)
    {
        return $query->where('workplace_type', 'medical_centre');
    }

    /**
     * Permanent employment
     */
    public function scopePermanent($query)
    {
        return $query->where('employment_type', 'permanent');
    }

    /**
     * Temporary employment
     */
    public function scopeTemporary($query)
    {
        return $query->where('employment_type', 'temporary');
    }

    /**
     * Visiting employment
     */
    public function scopeVisiting($query)
    {
        return $query->where('employment_type', 'visiting');
    }

    // ============================================
    // Helper Methods
    // ============================================

    /**
     * Check if workplace association is approved
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if workplace association is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if workplace association is rejected
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Approve workplace association
     */
    public function approve($approvedBy = null)
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => $approvedBy ?? auth()->id(),
            'approved_at' => now(),
        ]);
    }

    /**
     * Reject workplace association
     */
    public function reject()
    {
        $this->update([
            'status' => 'rejected',
        ]);
    }

    /**
     * Get workplace details array
     */
    public function getWorkplaceDetails()
    {
        $workplace = null;

        if ($this->workplace_type === 'hospital') {
            $workplace = $this->hospital;
        } elseif ($this->workplace_type === 'medical_centre') {
            $workplace = $this->medicalCentre;
        }

        if (!$workplace) {
            return null;
        }

        return [
            'id' => $workplace->id,
            'name' => $workplace->name,
            'type' => $this->workplace_type,
            'address' => $workplace->address ?? 'N/A',
            'city' => $workplace->city ?? 'N/A',
            'phone' => $workplace->phone ?? 'N/A',
        ];
    }
}
