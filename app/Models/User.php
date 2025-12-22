<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'email',
        'password',
        'user_type',
        'status',
        'email_verified_at',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relationships
    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }

    public function hospital()
    {
        return $this->hasOne(Hospital::class);
    }

    public function laboratory()
    {
        return $this->hasOne(Laboratory::class);
    }

    public function pharmacy()
    {
        return $this->hasOne(Pharmacy::class);
    }

    public function medicalCentre()
    {
        return $this->hasOne(MedicalCentre::class);
    }
    public function sendEmailVerificationNotification()
{
    $this->notify(new \App\Notifications\VerifyEmailNotification);
}

    // Helper method to get profile based on user type
    public function profile()
    {
       return match($this->usertype) {
            'patient' => $this->patient,
            'doctor' => $this->doctor,
            'hospital' => $this->hospital,
            'laboratory' => $this->laboratory,
            'pharmacy' => $this->pharmacy,
            'medicalcentre' => $this->medicalCentre,
            default => null,
        };
    }
    public function getProfileImageUrlAttribute()
    {
        // Doctor
        if ($this->user_type === 'doctor' && $this->doctor && $this->doctor->profile_image) {
            return asset('storage/' . $this->doctor->profile_image);
        }
        // Hospital
        if ($this->user_type === 'hospital' && $this->hospital && $this->hospital->profile_image) {
            return asset('storage/' . $this->hospital->profile_image);
        }
        // Laboratory
        if ($this->user_type === 'laboratory' && $this->laboratory && $this->laboratory->profile_image) {
            return asset('storage/' . $this->laboratory->profile_image);
        }
        // Pharmacy
        if ($this->user_type === 'pharmacy' && $this->pharmacy && $this->pharmacy->profile_image) {
            return asset('storage/' . $this->pharmacy->profile_image);
        }
        // Medical Centre
        if ($this->user_type === 'medical_centre' && $this->medicalCentre && $this->medicalCentre->profile_image) {
            return asset('storage/' . $this->medicalCentre->profile_image);
        }
        // Patient (optional: implement if your patient table has profile_image)
        if ($this->user_type === 'patient' && $this->patient && $this->patient->profile_image) {
            return asset('storage/' . $this->patient->profile_image);
        }
        // Otherwise
        return asset('images/default-avatar.png');
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'notifiable_id')
                    ->where('notifiable_type', self::class)
                    ->orderBy('created_at', 'desc');
    }


}
