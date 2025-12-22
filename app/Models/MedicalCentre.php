<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalCentre extends Model
{
    protected $table = 'medical_centres';

    protected $fillable = [
        'user_id',
        'status',                    // ✅ ADDED
        'owner_doctor_id',
        'name',
        'registration_number',
        'phone',
        'email',
        'address',
        'city',
        'province',
        'postal_code',
        'latitude',
        'longitude',
        'specializations',
        'facilities',
        'operatinghours',
        'description',
        'profile_image',
        'rating',
        'total_ratings',
        'document_path',
        'approved_by',
        'approved_at'
    ];

    protected $casts = [
        'specializations' => 'array',
        'facilities' => 'array',
        'rating' => 'decimal:2',
        'total_ratings' => 'integer',
        'approved_at' => 'datetime'
    ];

    // Default values
    protected $attributes = [
        'status' => 'pending',       // ✅ Default to pending
        'rating' => 0.00,
        'total_ratings' => 0,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with owner doctor (if applicable)
    public function ownerDoctor()
    {
        return $this->belongsTo(Doctor::class, 'owner_doctor_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
      public function approvedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_by');
    }
    public function getImageUrlAttribute()
    {
        if (!$this->profile_image) {
            return asset('images/default-medical-centre.png');
        }

        // Check if path already contains 'medical_centres/profiles'
        if (strpos($this->profile_image, 'medical_centres/profiles') !== false) {
            return asset('storage/' . $this->profile_image);
        }

        // Otherwise, prepend the path
        return asset('storage/medical_centres/profiles/' . basename($this->profile_image));
    }
}
