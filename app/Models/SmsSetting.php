<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'public_key',
        'secret_key',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];
}
