<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'therapist_id',
        'start_datetime',
        'end_datetime',
        'description',
        'status',
    ];

    public function therapist()
    {
        return $this->belongsTo(Therapist::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
