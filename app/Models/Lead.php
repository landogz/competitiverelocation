<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'company',
        'status',
        'source',
        'notes',
        'sales_name',
        'sales_email',
        'sales_location',
        'pickup_location',
        'delivery_location',
        'miles',
        'service',
        'service_rate',
        'no_of_items',
        'no_of_crew',
        'crew_rate',
        'subtotal',
        'software_fee',
        'truck_fee',
        'downpayment',
        'grand_total',
        'uploaded_image',
        'date',
        'transaction_id'
    ];

    protected $casts = [
        'miles' => 'float',
        'service_rate' => 'float',
        'no_of_items' => 'integer',
        'no_of_crew' => 'integer',
        'crew_rate' => 'float',
        'subtotal' => 'float',
        'software_fee' => 'float',
        'truck_fee' => 'float',
        'downpayment' => 'float',
        'grand_total' => 'float',
        'date' => 'datetime',
        'service' => 'array',
    ];

    /**
     * Get the logs for the lead.
     */
    public function logs()
    {
        return $this->hasMany(LeadLog::class);
    }
}
