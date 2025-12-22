<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    protected $table = 'hospitals';

    protected $fillable = [
        'user_id',
        'status',                    // ✅ ADDED
        'name',
        'type',
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
        'website',
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
                return asset('images/default-hospital.png');
            }

            // Check if path already contains 'hospitals/profiles'
            if (strpos($this->profile_image, 'hospitals/profiles') !== false) {
                return asset('storage/' . $this->profile_image);
            }

            // Otherwise, prepend the path
            return asset('storage/hospitals/profiles/' . basename($this->profile_image));
        }
}
