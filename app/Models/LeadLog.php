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
        'agent_id',
        'lead_id',
        'type',
        'content',
        'user_id'
    ];

    /**
     * Get the user that owns the log.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the transaction that owns the log.
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'lead_id');
    }

    /**
     * Get the agent that owns the log.
     */
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
}
