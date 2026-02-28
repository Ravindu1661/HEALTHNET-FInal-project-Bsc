<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalHistory extends Model
{
    use HasFactory;

    protected $table = 'medical_history';

    protected $fillable = [
        'patient_id',
        'condition_name',
        'diagnosed_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'diagnosed_date' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
