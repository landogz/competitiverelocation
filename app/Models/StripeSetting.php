<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StripeSetting extends Model
{
    protected $fillable = [
        'public_key',
        'secret_key',
        'is_active',
        'last_error',
        'last_checked_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_checked_at' => 'datetime'
    ];
} 