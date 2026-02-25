<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabPackage extends Model
{
    protected $fillable = [
        'laboratory_id',
        'package_name',
        'description',
        'price',
        'discount_percentage',
        'is_active',
    ];

    protected $casts = [
        'price'               => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'is_active'           => 'boolean',
    ];

    // ══════════════════════════════════
    // Relationships
    // ══════════════════════════════════

    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class);
    }

    public function tests()
    {
        return $this->belongsToMany(LabTest::class, 'lab_package_tests', 'package_id', 'test_id');
    }

    public function orderItems()
    {
        return $this->hasMany(LabOrderItem::class, 'package_id');
    }

    // ══════════════════════════════════
    // Helpers
    // ══════════════════════════════════

    public function getDiscountedPriceAttribute(): float
    {
        if ($this->discount_percentage > 0) {
            return round($this->price * (1 - $this->discount_percentage / 100), 2);
        }
        return (float) $this->price;
    }

    public function getSavingsAttribute(): float
    {
        return round($this->price - $this->discounted_price, 2);
    }
}
