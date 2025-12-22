<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $table = 'doctors';

    protected $fillable = [
        'user_id',
        'status',                    // ✅ For approval workflow
        'slmc_number',
        'first_name',
        'last_name',
        'specialization',
        'qualifications',
        'experience_years',
        'phone',
        'consultation_fee',
        'bio',
        'profile_image',
        'document_path',
        'rating',
        'total_ratings',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'experience_years' => 'integer',
        'consultation_fee' => 'decimal:2',
        'rating' => 'decimal:2',
        'total_ratings' => 'integer',
        'approved_at' => 'datetime',
    ];

    // Default values
    protected $attributes = [
        'status' => 'pending',
        'rating' => 0.00,
        'total_ratings' => 0,
    ];

    // ============================================
    // Relationships
    // ============================================

    /**
     * User account relationship
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Admin who approved this doctor
     * ✅ THIS IS REQUIRED for show page
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Doctor's appointments
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Doctor's workplaces (hospitals/medical centres)
     */
    public function workplaces()
    {
        return $this->hasMany(DoctorWorkplace::class);
    }

    /**
     * Doctor's schedules
     */
    public function schedules()
    {
        return $this->hasMany(DoctorSchedule::class);
    }

    /**
     * Doctor's reviews/ratings
     */
    public function reviews()
    {
        return $this->hasMany(Rating::class, 'ratable_id')
            ->where('ratable_type', 'doctor');
    }

    // ============================================
    // Accessors
    // ============================================

    /**
     * Get full name attribute
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
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
            'suspended' => 'secondary',
        ];
        return $badges[$this->status] ?? 'secondary';
    }

    // ============================================
    // Scopes for filtering
    // ============================================

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['approved']);
    }

    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

}
