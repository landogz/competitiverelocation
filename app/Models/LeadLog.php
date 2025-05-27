<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'lead_id',
        'type',
        'content',
        'user_id',
    ];

    /**
     * Get the lead that owns the log.
     */
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
