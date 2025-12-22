<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $table = 'medications';

    protected $fillable = [
        'pharmacy_id',
        'name',
        'generic_name',
        'category',
        'manufacturer',
        'description',
        'dosage',
        'price',
        'stock_quantity',
        'stock_status',
        'requires_prescription',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'requires_prescription' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected $attributes = [
        'stock_status' => 'in_stock',
        'requires_prescription' => true,
        'is_active' => true,
    ];

    // ============================================
    // Relationships
    // ============================================

    /**
     * Pharmacy relationship
     */
    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    /**
     * Prescription order items
     */
    public function prescriptionOrderItems()
    {
        return $this->hasMany(PrescriptionOrderItem::class, 'medication_id');
    }

    // ============================================
    // Accessors
    // ============================================

    /**
     * Get stock status badge color
     */
    public function getStockStatusBadgeAttribute()
    {
        $badges = [
            'in_stock' => 'success',
            'low_stock' => 'warning',
            'out_of_stock' => 'danger',
        ];
        return $badges[$this->stock_status] ?? 'secondary';
    }

    /**
     * Check if medicine is low stock
     */
    public function getIsLowStockAttribute()
    {
        return $this->stock_quantity > 0 && $this->stock_quantity <= 10;
    }

    /**
     * Check if medicine is out of stock
     */
    public function getIsOutOfStockAttribute()
    {
        return $this->stock_quantity <= 0;
    }

    // ============================================
    // Scopes
    // ============================================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_status', 'in_stock');
    }

    public function scopeLowStock($query)
    {
        return $query->where('stock_status', 'low_stock');
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('stock_status', 'out_of_stock');
    }

    public function scopeRequiresPrescription($query)
    {
        return $query->where('requires_prescription', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByPharmacy($query, $pharmacyId)
    {
        return $query->where('pharmacy_id', $pharmacyId);
    }

    // ============================================
    // Helper Methods
    // ============================================

    /**
     * Update stock quantity
     */
    public function updateStock($quantity)
    {
        $newQuantity = $this->stock_quantity + $quantity;

        $stockStatus = 'in_stock';
        if ($newQuantity <= 0) {
            $stockStatus = 'out_of_stock';
        } elseif ($newQuantity <= 10) {
            $stockStatus = 'low_stock';
        }

        $this->update([
            'stock_quantity' => $newQuantity,
            'stock_status' => $stockStatus,
        ]);
    }

    /**
     * Decrease stock
     */
    public function decreaseStock($quantity)
    {
        return $this->updateStock(-$quantity);
    }

    /**
     * Increase stock
     */
    public function increaseStock($quantity)
    {
        return $this->updateStock($quantity);
    }

    /**
     * Check if medicine can fulfill order
     */
    public function canFulfillOrder($quantity)
    {
        return $this->stock_quantity >= $quantity;
    }
}
