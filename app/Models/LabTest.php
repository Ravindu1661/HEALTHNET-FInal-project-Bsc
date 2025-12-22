<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabTest extends Model
{
    use HasFactory;

    protected $table = 'lab_tests';

    protected $fillable = [
        'laboratory_id',
        'test_name',
        'test_category',
        'description',
        'price',
        'duration_hours',
        'requirements',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration_hours' => 'integer',
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
     * Lab order items
     */
    public function orderItems()
    {
        return $this->hasMany(LabOrderItem::class, 'test_id');
    }

    /**
     * Packages containing this test
     */
    public function packages()
    {
        return $this->belongsToMany(LabPackage::class, 'lab_package_tests', 'test_id', 'package_id');
    }

    // ============================================
    // Scopes
    // ============================================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('test_category', $category);
    }

    public function scopeForLaboratory($query, $laboratoryId)
    {
        return $query->where('laboratory_id', $laboratoryId);
    }
}
