<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PharmacyOrder extends Model
{
    use HasFactory;

    protected $table = 'prescription_orders';

    protected $fillable = [
        'order_number',
        'patient_id',
        'pharmacy_id',
        'prescription_file',
        'status',
        'total_amount',
        'delivery_fee',
        'payment_method',
        'payment_status',
        'delivery_address',
        'delivery_method',
        'tracking_number',
        'pharmacist_notes',
        'cancelled_reason',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'order_date' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'pending',
        'payment_status' => 'unpaid',
        'payment_method' => 'cash_on_delivery',
        'delivery_fee' => 0.00,
    ];

    // ============================================
    // Boot Method
    // ============================================

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = self::generateOrderNumber();
            }
        });
    }

    /**
     * Generate unique order number
     */
    public static function generateOrderNumber()
    {
        do {
            $number = 'PO-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        } while (self::where('order_number', $number)->exists());

        return $number;
    }

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
     * Pharmacy relationship
     */
    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    /**
     * Order items
     */
    public function items()
    {
        return $this->hasMany(PrescriptionOrderItem::class, 'order_id');
    }

    /**
     * Payment
     */
    public function payment()
    {
        return $this->hasOne(Payment::class, 'related_id')
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
            'verified' => 'info',
            'processing' => 'primary',
            'ready' => 'success',
            'dispatched' => 'info',
            'delivered' => 'success',
            'cancelled' => 'danger',
        ];
        return $badges[$this->status] ?? 'secondary';
    }

    /**
     * Get payment status badge
     */
    public function getPaymentStatusBadgeAttribute()
    {
        $badges = [
            'unpaid' => 'danger',
            'paid' => 'success',
        ];
        return $badges[$this->payment_status] ?? 'secondary';
    }

    /**
     * Get total with delivery
     */
    public function getTotalWithDeliveryAttribute()
    {
        return $this->total_amount + $this->delivery_fee;
    }

    /**
     * Get items count
     */
    public function getItemsCountAttribute()
    {
        return $this->items()->count();
    }

    // ============================================
    // Scopes
    // ============================================

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeReady($query)
    {
        return $query->where('status', 'ready');
    }

    public function scopeDispatched($query)
    {
        return $query->where('status', 'dispatched');
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeUnpaid($query)
    {
        return $query->where('payment_status', 'unpaid');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopeForPharmacy($query, $pharmacyId)
    {
        return $query->where('pharmacy_id', $pharmacyId);
    }

    public function scopeForPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    // ============================================
    // Helper Methods
    // ============================================

    /**
     * Check if order can be cancelled
     */
    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'verified', 'processing']);
    }

    /**
     * Mark as verified
     */
    public function markAsVerified()
    {
        $this->update(['status' => 'verified']);
    }

    /**
     * Mark as processing
     */
    public function markAsProcessing()
    {
        $this->update(['status' => 'processing']);
    }

    /**
     * Mark as ready
     */
    public function markAsReady()
    {
        $this->update(['status' => 'ready']);
    }

    /**
     * Mark as dispatched
     */
    public function markAsDispatched($trackingNumber = null)
    {
        $this->update([
            'status' => 'dispatched',
            'tracking_number' => $trackingNumber,
        ]);
    }

    /**
     * Mark as delivered
     */
    public function markAsDelivered()
    {
        $this->update([
            'status' => 'delivered',
            'payment_status' => 'paid',
        ]);
    }

    /**
     * Cancel order
     */
    public function cancel($reason = null)
    {
        $this->update([
            'status' => 'cancelled',
            'cancelled_reason' => $reason,
        ]);
    }
}
