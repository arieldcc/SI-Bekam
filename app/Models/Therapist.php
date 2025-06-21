<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Therapist extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'specialty',
        'phone_number',
        'address',
    ];

    // Relasi ke user (akun login)
    public function user()
    {
        return $this->hasOne(User::class, 'related_id')->where('role', 'terapis');
    }

    // Relasi ke schedules
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    // Relasi ke rekam medis
    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }
}
