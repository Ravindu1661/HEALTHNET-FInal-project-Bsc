<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'payment_number',
        'payer_id',
        'payee_type',
        'payee_id',
        'related_type',
        'related_id',
        'amount',
        'payment_method',
        'payment_status',
        'transaction_id',
        'payment_date',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
    ];

    protected $attributes = [
        'payment_status' => 'pending',
    ];

    // ============================================
    // Boot Method
    // ============================================

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (empty($payment->payment_number)) {
                $payment->payment_number = self::generatePaymentNumber();
            }
        });
    }

    /**
     * Generate unique payment number
     */
    public static function generatePaymentNumber()
    {
        do {
            $number = 'PAY-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        } while (self::where('payment_number', $number)->exists());

        return $number;
    }

    // ============================================
    // Relationships
    // ============================================

    /**
     * Payer (User)
     */
    public function payer()
    {
        return $this->belongsTo(User::class, 'payer_id');
    }

    /**
     * Related appointment
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'related_id')
            ->where('related_type', 'appointment');
    }

    /**
     * Related lab order
     */
    public function labOrder()
    {
        return $this->belongsTo(LabOrder::class, 'related_id')
            ->where('related_type', 'lab_order');
    }

    /**
     * Related prescription order
     */
    public function prescriptionOrder()
    {
        return $this->belongsTo(PharmacyOrder::class, 'related_id')
            ->where('related_type', 'prescription_order');
    }

    // ============================================
    // Accessors
    // ============================================

    /**
     * Get status badge color
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'completed' => 'success',
            'failed' => 'danger',
            'refunded' => 'info',
        ];
        return $badges[$this->payment_status] ?? 'secondary';
    }

    // ============================================
    // Scopes
    // ============================================

    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('payment_status', 'completed');
    }

    public function scopeFailed($query)
    {
        return $query->where('payment_status', 'failed');
    }

    public function scopeForPayer($query, $payerId)
    {
        return $query->where('payer_id', $payerId);
    }

    // ============================================
    // Helper Methods
    // ============================================

    /**
     * Mark as completed
     */
    public function markAsCompleted($transactionId = null)
    {
        $this->update([
            'payment_status' => 'completed',
            'transaction_id' => $transactionId,
            'payment_date' => now(),
        ]);
    }

    /**
     * Mark as failed
     */
    public function markAsFailed()
    {
        $this->update(['payment_status' => 'failed']);
    }

    /**
     * Refund payment
     */
    public function refund($notes = null)
    {
        $this->update([
            'payment_status' => 'refunded',
            'notes' => $notes,
        ]);
    }
}
