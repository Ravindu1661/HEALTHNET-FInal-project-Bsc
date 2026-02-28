<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientHealthData extends Model
{
    use HasFactory;

    protected $table = 'patient_health_data';

    protected $fillable = [
        'patient_id',
        'weight', 'height', 'waist', 'hip',
        'blood_pressure_systolic', 'blood_pressure_diastolic',
        'heart_rate', 'temperature', 'blood_sugar', 'blood_sugar_pp',
        'cholesterol_total', 'cholesterol_hdl', 'cholesterol_ldl',
        'oxygen_saturation',
        'smoking_status', 'alcohol_consumption', 'exercise_frequency',
        'diet_type', 'sleep_hours', 'stress_level',
        'has_diabetes', 'has_hypertension', 'has_heart_disease',
        'has_asthma', 'has_kidney_disease', 'has_thyroid',
        'other_conditions', 'current_medications', 'allergies',
        'family_diabetes', 'family_heart_disease',
        'family_hypertension', 'family_cancer',
        'recorded_date', 'notes',
    ];

    protected $casts = [
        'recorded_date'      => 'date',
        'has_diabetes'       => 'boolean',
        'has_hypertension'   => 'boolean',
        'has_heart_disease'  => 'boolean',
        'has_asthma'         => 'boolean',
        'has_kidney_disease' => 'boolean',
        'has_thyroid'        => 'boolean',
        'family_diabetes'    => 'boolean',
        'family_heart_disease'   => 'boolean',
        'family_hypertension'    => 'boolean',
        'family_cancer'      => 'boolean',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // ══ BMI ══
    public function getBmiAttribute(): ?float
    {
        if (!$this->weight || !$this->height) return null;
        $h = $this->height / 100;
        return round($this->weight / ($h * $h), 1);
    }

    public function getBmiCategoryAttribute(): ?string
    {
        $bmi = $this->bmi;
        if (!$bmi) return null;
        if ($bmi < 18.5) return 'Underweight';
        if ($bmi < 25)   return 'Normal';
        if ($bmi < 30)   return 'Overweight';
        return 'Obese';
    }

    // ══ WHR ══
    public function getWhrAttribute(): ?float
    {
        if (!$this->waist || !$this->hip) return null;
        return round($this->waist / $this->hip, 2);
    }
}
