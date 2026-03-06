<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $table = 'medications';

    protected $fillable = [
        'pharmacy_id', 'name', 'generic_name', 'category',
        'manufacturer', 'description', 'dosage', 'price',
        'stock_quantity', 'stock_status', 'requires_prescription', 'is_active',
    ];

    protected $casts = [
        'price'                 => 'decimal:2',
        'stock_quantity'        => 'integer',
        'requires_prescription' => 'boolean',
        'is_active'             => 'boolean',
    ];

    // ✅ Correct DB ENUM: 'in_stock', 'low_stock', 'out_of_stock'
    protected $attributes = [
        'stock_status'          => 'in_stock',
        'requires_prescription' => true,
        'is_active'             => true,
    ];

    // ============================================
    // Boot — Auto-sync stock_status on save
    // ============================================

    protected static function boot(): void
    {
        parent::boot();

        static::saving(function (Medicine $medicine) {
            if ($medicine->isDirty('stock_quantity') || empty($medicine->stock_status)) {
                $qty = (int) $medicine->stock_quantity;

                if ($qty <= 0)      $medicine->stock_status = 'out_of_stock';
                elseif ($qty <= 10) $medicine->stock_status = 'low_stock';
                else                $medicine->stock_status = 'in_stock';
            }
        });
    }

    // ============================================
    // Relationships
    // ============================================

    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function prescriptionOrderItems()
    {
        return $this->hasMany(PrescriptionOrderItem::class, 'medication_id');
    }

    // ============================================
    // Accessors
    // ============================================

    public function getStockStatusBadgeAttribute(): string
    {
        return match ($this->stock_status) {
            'in_stock'    => 'success',
            'low_stock'   => 'warning',
            'out_of_stock'=> 'danger',
            default       => 'secondary',
        };
    }

    public function getStockStatusLabelAttribute(): string
    {
        return match ($this->stock_status) {
            'in_stock'    => 'In Stock',
            'low_stock'   => 'Low Stock',
            'out_of_stock'=> 'Out of Stock',
            default       => 'Unknown',
        };
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->stock_quantity > 0 && $this->stock_quantity <= 10;
    }

    public function getIsOutOfStockAttribute(): bool
    {
        return $this->stock_quantity <= 0;
    }

    public function getIsInStockAttribute(): bool
    {
        return $this->stock_quantity > 10;
    }

    // ============================================
    // Scopes — ✅ correct ENUM values
    // ============================================

    public function scopeActive($query)        { return $query->where('is_active', true); }
    public function scopeInStock($query)       { return $query->where('stock_status', 'in_stock'); }
    public function scopeLowStock($query)      { return $query->where('stock_status', 'low_stock'); }
    public function scopeOutOfStock($query)    { return $query->where('stock_status', 'out_of_stock'); }
    public function scopeRequiresPrescription($query) { return $query->where('requires_prescription', true); }
    public function scopeByCategory($query, $category) { return $query->where('category', $category); }
    public function scopeByPharmacy($query, $pharmacyId) { return $query->where('pharmacy_id', $pharmacyId); }

    // ============================================
    // Helper Methods
    // ============================================

    public function updateStock(int $quantity): void
    {
        $newQty = max(0, $this->stock_quantity + $quantity);

        if ($newQty <= 0)      $status = 'out_of_stock';
        elseif ($newQty <= 10) $status = 'low_stock';
        else                   $status = 'in_stock';

        $this->update([
            'stock_quantity' => $newQty,
            'stock_status'   => $status,
        ]);
    }

    public function decreaseStock(int $quantity): void { $this->updateStock(-abs($quantity)); }
    public function increaseStock(int $quantity): void { $this->updateStock(abs($quantity)); }
    public function canFulfillOrder(int $quantity): bool { return $this->stock_quantity >= $quantity; }
}
