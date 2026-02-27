<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $table = 'ratings';

    protected $fillable = [
        'patient_id',
        'ratable_type',
        'ratable_id',
        'rating',
        'review',
        'status',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    // protected $attributes = [
    //     'status' => 'pending',
    // ];

    // ============================================
    // Relationships
    // ============================================

    /**
     * Patient who gave the rating
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Polymorphic relationship to ratable (Doctor, Hospital, etc.)
     */
    public function ratable()
    {
        return $this->morphTo();
    }

    // ============================================
    // Scopes
    // ============================================

    /**
     * Approved ratings only
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Pending ratings
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * For specific rating value
     */
    public function scopeWithRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    // ============================================
    // Helper Methods
    // ============================================

    /**
     * Check if rating is approved
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Approve the rating
     */
    public function approve()
    {
        $this->update(['status' => 'approved']);
    }

    /**
     * Reject the rating
     */
    public function reject()
    {
        $this->update(['status' => 'rejected']);
    }
}
