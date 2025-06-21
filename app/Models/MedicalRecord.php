<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
        'therapist_id',
        'complaints',
        'therapy_area',
        'weight',
        'blood_pressure',
        'pulse',
        'result_notes',
        'actual_start_time',
        'actual_end_time',
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function therapist()
    {
        return $this->belongsTo(Therapist::class);
    }
}
