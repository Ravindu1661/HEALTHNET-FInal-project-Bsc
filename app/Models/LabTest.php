<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabTest extends Model
{
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
        'price'     => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class);
    }

    public function packages()
    {
        return $this->belongsToMany(LabPackage::class, 'lab_package_tests', 'test_id', 'package_id');
    }

    public function orderItems()
    {
        return $this->hasMany(LabOrderItem::class, 'test_id');
    }

    // Scope: active tests for a lab
    public function scopeActiveForLab($query, int $labId)
    {
        return $query->where('laboratory_id', $labId)->where('is_active', true);
    }
}
