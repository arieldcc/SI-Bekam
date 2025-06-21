<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'gender',
        'date_of_birth',
        'phone_number',
        'address',
        'height',
        'blood_type',
        'disease_history',
    ];

    // Relasi ke user
    public function user()
    {
        return $this->hasOne(User::class, 'related_id')->where('role', 'pasien');
    }

    // Relasi ke registrations (kunjungan)
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    // Relasi ke appointments
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
