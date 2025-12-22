<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabPackage extends Model
{
    use HasFactory;

    protected $table = 'lab_packages';

    protected $fillable = [
        'laboratory_id',
        'package_name',
        'description',
        'price',
        'discount_percentage',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected $attributes = [
        'is_active' => true,
    ];

    // ============================================
    // Relationships
    // ============================================

    /**
     * Laboratory relationship
     */
    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class);
    }

    /**
     * Tests included in package
     */
    public function tests()
    {
        return $this->belongsToMany(LabTest::class, 'lab_package_tests', 'package_id', 'test_id');
    }

    /**
     * Order items
     */
    public function orderItems()
    {
        return $this->hasMany(LabOrderItem::class, 'package_id');
    }

    // ============================================
    // Accessors
    // ============================================

    /**
     * Get discounted price
     */
    public function getDiscountedPriceAttribute()
    {
        if ($this->discount_percentage > 0) {
            return $this->price - ($this->price * ($this->discount_percentage / 100));
        }
        return $this->price;
    }

    /**
     * Get discount amount
     */
    public function getDiscountAmountAttribute()
    {
        return $this->price - $this->discounted_price;
    }

    // ============================================
    // Scopes
    // ============================================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForLaboratory($query, $laboratoryId)
    {
        return $query->where('laboratory_id', $laboratoryId);
    }
}
