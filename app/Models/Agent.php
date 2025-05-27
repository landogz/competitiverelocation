<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Agent extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'external_id',
        'user_id',
        'unique_url',
        'company_name',
        'company_website',
        'contact_name',
        'contact_title',
        'email',
        'phone_number',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
        'website',
        'status',
        'notes',
        'sales_volume',
        'sales_volume_currency',
        'sales_volume_year',
        'number_of_shipments',
        'number_of_shipments_year',
        'services',
        'num_trucks',
        'truck_size',
        'num_crews',
        'truck_image',
        'corporate_sales',
        'consumer_sales',
        'local_sales',
        'long_distance_sales',
        'delivery_service_sales',
        'total_sales',
        'is_active'
    ];

    protected $casts = [
        'services' => 'array',
        'num_trucks' => 'integer',
        'num_crews' => 'integer',
        'corporate_sales' => 'integer',
        'consumer_sales' => 'integer',
        'local_sales' => 'integer',
        'long_distance_sales' => 'integer',
        'delivery_service_sales' => 'integer',
        'total_sales' => 'integer',
        'is_active' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
