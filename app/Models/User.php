<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function customer()
    {
        // ប្រសិនបើ Customer មាន column user_id:
        return $this->hasOne(Customer::class, 'user_id');
    }

    // Customer.php
    public function user()
    {
        // ប្រសិនបើ Customer មាន column user_id:
        return $this->belongsTo(User::class, 'user_id');
    }
}
