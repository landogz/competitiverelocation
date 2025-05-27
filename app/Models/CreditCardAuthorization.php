<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditCardAuthorization extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'full_name',
        'name',
        'title',
        'card_type',
        'last_8_digits',
        'cvc',
        'expiration_date',
        'cardholder_name',
        'phone',
        'email',
        'street_address',
        'city',
        'state',
        'zip_code',
        'signature',
        'date',
        'comments',
    ];
} 