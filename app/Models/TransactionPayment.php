<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
