<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class Pharmacy extends Model
{
    protected $table = 'pharmacies';

    protected $fillable = [
        'user_id',
        'status',                    // ✅ ADDED
        'name',
        'registration_number',
        'pharmacist_name',
        'pharmacist_license',
        'phone',
        'email',
        'address',
        'city',
        'province',
        'postal_code',
        'latitude',
        'longitude',
        'operating_hours',
        'delivery_available',
        'profile_image',
        'rating',
        'total_ratings',
        'document_path',
        'approved_by',
        'approved_at'
    ];

    protected $casts = [
        'delivery_available' => 'boolean',
        'rating' => 'decimal:2',
        'total_ratings' => 'integer',
        'approved_at' => 'datetime'
    ];

    // Default values
    protected $attributes = [
        'status' => 'pending',       // ✅ Default to pending
        'rating' => 0.00,
        'total_ratings' => 0,
        'delivery_available' => true,
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
        return $this->belongsTo(User::class, 'approved_by');
    }

}
