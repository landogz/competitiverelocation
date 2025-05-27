<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_type',
        'category',
        'title',
        'value_range',
        'rate',
        'unit',
        'description',
        'badge_color',
        'icon'
    ];
}
