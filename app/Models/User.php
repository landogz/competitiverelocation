<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'privilege',
        'position',
        'profile_image',
        'address',
        'city',
        'state',
        'zip_code',
        'phone',
        'bio',
        'agent_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the user's full address.
     */
    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->zip_code
        ]);
        return implode(', ', $parts);
    }

    /**
     * Get the user's profile image URL.
     */
    public function getProfileImageUrlAttribute()
    {
        if ($this->profile_image && Storage::exists('public/' . $this->profile_image)) {
            return asset('storage/' . $this->profile_image);
        }
        return asset('assets/images/no-profile-image.png');
    }

    public function agent()
    {
        return $this->hasOne(Agent::class);
    }
}
