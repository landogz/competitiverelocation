<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'message',
        'status',
        'recipient',
        'response_data'
    ];

    protected $casts = [
        'response_data' => 'array'
    ];
}
