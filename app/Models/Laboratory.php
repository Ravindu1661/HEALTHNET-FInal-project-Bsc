<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laboratory extends Model
{
    protected $table = 'laboratories';

    protected $fillable = [
        'user_id',
        'status',                    // ✅ ADDED
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
        'services',
        'operating_hours',
        'description',
        'profile_image',
        'rating',
        'total_ratings',
        'document_path',
        'approved_by',
        'approved_at'
    ];

    protected $casts = [
        'services' => 'array',
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

}
