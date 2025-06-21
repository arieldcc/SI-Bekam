<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'contacts';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'phone',
        'email',
        'address',
        'whatsapp_link',
        'map_embed',
        'is_active',
    ];
}
