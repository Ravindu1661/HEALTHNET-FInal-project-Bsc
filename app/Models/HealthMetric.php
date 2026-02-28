<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthMetric extends Model
{
    use HasFactory;

    protected $table = 'health_metrics';

    protected $fillable = [
        'patient_id',
        'metric_date',
        'weight',
        'height',
        'blood_pressure_systolic',
        'blood_pressure_diastolic',
        'heart_rate',
        'temperature',
        'blood_sugar',
        'notes',
    ];

    protected $casts = [
        'metric_date'              => 'date',
        'weight'                   => 'decimal:2',
        'height'                   => 'decimal:2',
        'blood_pressure_systolic'  => 'integer',
        'blood_pressure_diastolic' => 'integer',
        'heart_rate'               => 'integer',
        'temperature'              => 'decimal:2',
        'blood_sugar'              => 'decimal:2',
    ];

    // ══ Relationships ══
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // ══ BMI Accessor ══
    public function getBmiAttribute(): ?float
    {
        if (!$this->weight || !$this->height) return null;
        $heightM = $this->height / 100;
        return round($this->weight / ($heightM * $heightM), 1);
    }

    // ══ BMI Category Accessor ══
    public function getBmiCategoryAttribute(): ?string
    {
        $bmi = $this->bmi;
        if (!$bmi) return null;
        if ($bmi < 18.5) return 'Underweight';
        if ($bmi < 25)   return 'Normal';
        if ($bmi < 30)   return 'Overweight';
        return 'Obese';
    }

    // ══ BP Status Accessor ══
    public function getBpStatusAttribute(): string
    {
        $sys = $this->blood_pressure_systolic ?? 0;
        if ($sys >= 140) return 'High';
        if ($sys >= 130) return 'Elevated';
        if ($sys >= 90)  return 'Normal';
        return 'Low';
    }

    // ══ Scopes ══
    public function scopeForPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    public function scopeRecent($query, $months = 6)
    {
        return $query->where('metric_date', '>=', now()->subMonths($months));
    }

    public function scopeLatestFirst($query)
    {
        return $query->orderBy('metric_date', 'desc');
    }
}
