<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabOrderItem extends Model
{
    use HasFactory;

    protected $table = 'lab_order_items';

    protected $fillable = [
        'order_id',
        'test_id',
        'package_id',
        'item_name',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
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
        return $this->belongsTo(LabOrder::class, 'order_id');
    }

    /**
     * Lab test relationship
     */
    public function test()
    {
        return $this->belongsTo(LabTest::class, 'test_id');
    }

    /**
     * Lab package relationship
     */
    public function package()
    {
        return $this->belongsTo(LabPackage::class, 'package_id');
    }
}
