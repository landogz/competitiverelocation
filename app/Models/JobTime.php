<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobTime extends Model
{
    protected $table = 'job_times';
    
    protected $fillable = [
        'transaction_id',
        'user_id',
        'start_time',
        'end_time',
        'start_signature',
        'end_signature'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'transaction_id' => 'string',
        'user_id' => 'integer'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 