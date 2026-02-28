<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class MedicineReminder extends Model
{
    use HasFactory;

    protected $table = 'medicine_reminders';

    protected $fillable = [
        'patient_id',
        'medicine_name',
        'dosage',
        'frequency',
        'times',
        'start_date',
        'end_date',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'times'      => 'array',
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_active'  => 'boolean',
    ];

    // ── Relationships ──────────────────────────────────────
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // ── Accessor ───────────────────────────────────────────
    public function getFrequencyLabelAttribute(): string
    {
        return match ($this->frequency) {
            'once_daily'        => 'Once Daily',
            'twice_daily'       => 'Twice Daily',
            'thrice_daily'      => 'Three Times Daily',
            'four_times_daily'  => 'Four Times Daily',
            'custom'            => 'Custom',
            default             => ucfirst($this->frequency),
        };
    }
}
