<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescriptionOrderItem extends Model
{
    use HasFactory;

    protected $table = 'prescription_order_items';

    protected $fillable = [
        'order_id',
        'medication_id',
        'medication_name',
        'quantity',
        'price',
        'subtotal',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public $timestamps = false;

    // ============================================
    // Relationships
    // ============================================

    /**
     * Order relationship
     */
    public function order()
    {
        return $this->belongsTo(PharmacyOrder::class, 'order_id');
    }

    /**
     * Medication relationship
     */
    public function medication()
    {
        return $this->belongsTo(Medicine::class, 'medication_id');
    }

    // ============================================
    // Accessors
    // ============================================

    /**
     * Calculate subtotal
     */
    public function getCalculatedSubtotalAttribute()
    {
        return $this->quantity * $this->price;
    }

    // ============================================
    // Helper Methods
    // ============================================

    /**
     * Update medication stock after order
     */
    public function decreaseMedicationStock()
    {
        if ($this->medication) {
            $this->medication->decreaseStock($this->quantity);
        }
    }
}
