<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'payment_intent_id',
        'amount',
        'status',
        'payment_method',
        'currency',
        'raw_response'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'raw_response' => 'array'
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}
