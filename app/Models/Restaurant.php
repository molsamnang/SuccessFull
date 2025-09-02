<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'address',
        'phone',
        'location', 
        'images',
        'user_id',
    ];

    protected $casts = [
        'images' => 'array', // JSON → array
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
