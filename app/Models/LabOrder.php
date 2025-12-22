<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LabOrder extends Model
{
    use HasFactory;

    protected $table = 'lab_orders';

    protected $fillable = [
        'order_number',
        'reference_number',
        'patient_id',
        'laboratory_id',
        'doctor_id',
        'prescription_file',
        'status',
        'total_amount',
        'payment_status',
        'payment_method',
        'home_collection',
        'collection_address',
        'collection_date',
        'collection_time',
        'report_file',
        'report_uploaded_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'home_collection' => 'boolean',
        'collection_date' => 'date',
        'collection_time' => 'datetime',
        'report_uploaded_at' => 'datetime',
        'order_date' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'pending',
        'payment_status' => 'unpaid',
        'home_collection' => false,
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
            if (empty($order->reference_number)) {
                $order->reference_number = self::generateReferenceNumber();
            }
        });
    }

    /**
     * Generate unique order number
     */
    public static function generateOrderNumber()
    {
        do {
            $number = 'LO-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        } while (self::where('order_number', $number)->exists());

        return $number;
    }

    /**
     * Generate unique reference number
     */
    public static function generateReferenceNumber()
    {
        do {
            $number = 'REF-' . strtoupper(Str::random(8));
        } while (self::where('reference_number', $number)->exists());

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
     * Laboratory relationship
     */
    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class);
    }

    /**
     * Doctor relationship
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Order items
     */
    public function items()
    {
        return $this->hasMany(LabOrderItem::class, 'order_id');
    }

    /**
     * Payment
     */
    public function payment()
    {
        return $this->hasOne(Payment::class, 'related_id')
            ->where('related_type', 'lab_order');
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
            'sample_collected' => 'info',
            'processing' => 'primary',
            'completed' => 'success',
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
     * Check if report is available
     */
    public function getHasReportAttribute()
    {
        return !empty($this->report_file);
    }

    // ============================================
    // Scopes
    // ============================================

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSampleCollected($query)
    {
        return $query->where('status', 'sample_collected');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeForLaboratory($query, $laboratoryId)
    {
        return $query->where('laboratory_id', $laboratoryId);
    }

    public function scopeForPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    // ============================================
    // Helper Methods
    // ============================================

    /**
     * Mark as sample collected
     */
    public function markAsSampleCollected()
    {
        $this->update(['status' => 'sample_collected']);
    }

    /**
     * Mark as processing
     */
    public function markAsProcessing()
    {
        $this->update(['status' => 'processing']);
    }

    /**
     * Mark as completed and upload report
     */
    public function markAsCompleted($reportFile = null)
    {
        $this->update([
            'status' => 'completed',
            'report_file' => $reportFile,
            'report_uploaded_at' => now(),
        ]);
    }

    /**
     * Cancel order
     */
    public function cancel()
    {
        $this->update(['status' => 'cancelled']);
    }
}
