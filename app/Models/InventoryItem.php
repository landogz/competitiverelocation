<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'item',
        'category_id',
        'cubic_ft'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'transaction_inventory_items')
            ->withPivot('quantity')
            ->withTimestamps();
    }
}
