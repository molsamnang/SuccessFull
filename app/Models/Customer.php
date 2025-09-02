<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Use Authenticatable if customers will login
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable,HasApiTokens;
    // app/Models/Customer.php

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'profile_image',
        'gender',
        // 'address',  <-- Removed
    ];
    protected $appends = ['profile_image_url'];

    public function getProfileImageUrlAttribute()
    {
    return $this->profile_image
        ? asset('storage/' . $this->profile_image)
        : asset('assets/images/default-avatar.png');
    }


    /**
     * Automatically hash password when setting it
     */
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            // Hash password only if not already hashed (optional check)
            $this->attributes['password'] = Hash::needsRehash($value) ? Hash::make($value) : $value;
        }
    }

    // Optionally, hide password in JSON output
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
