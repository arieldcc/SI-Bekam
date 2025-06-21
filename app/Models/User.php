<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'full_name',
        'email',
        'password',
        'role',
        'related_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function therapist()
    {
        return $this->belongsTo(Therapist::class, 'related_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'related_id');
    }

    public function related()
    {
        if ($this->role === 'pasien') {
            return $this->belongsTo(Patient::class, 'related_id');
        }else{
            return $this->belongsTo(Therapist::class, 'related_id');
        }

        return null;
    }
}
