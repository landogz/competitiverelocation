<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'firstname',
        'lastname',
        'email',
        'phone',
        'lead_source',
        'lead_type',
        'assigned_agent',
        'status',
        'sales_name',
        'sales_email',
        'sales_location',
        'date',
        'pickup_location',
        'delivery_location',
        'miles',
        'add_mile',
        'mile_rate',
        'service',
        'service_rate',
        'no_of_items',
        'no_of_crew',
        'crew_rate',
        'delivery_rate',
        'subtotal',
        'software_fee',
        'truck_fee',
        'downpayment',
        'grand_total',
        'coupon_code',
        'payment_id',
        'uploaded_image',
        'services',
        'last_synced_at',
        'created_at'
    ];

    protected $casts = [
        'date' => 'datetime',
        'services' => 'array',
        'miles' => 'decimal:2',
        'add_mile' => 'decimal:2',
        'mile_rate' => 'decimal:2',
        'service_rate' => 'decimal:2',
        'crew_rate' => 'decimal:2',
        'delivery_rate' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'software_fee' => 'decimal:2',
        'truck_fee' => 'decimal:2',
        'downpayment' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'last_synced_at' => 'datetime',
        'created_at' => 'datetime'
    ];

    public function inventoryItems()
    {
        return $this->belongsToMany(InventoryItem::class, 'transaction_inventory_items')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function sentEmails()
    {
        return $this->hasMany(SentEmail::class);
    }
    
    public function agent()
    {
        return $this->belongsTo(Agent::class, 'assigned_agent', 'id');
    }
} 