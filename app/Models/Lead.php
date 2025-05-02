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
        'assigned_to'
    ];

    /**
     * Get the logs for the lead.
     */
    public function logs()
    {
        return $this->hasMany(LeadLog::class);
    }
}
