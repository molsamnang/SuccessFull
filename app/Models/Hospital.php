<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'location',
        'address',
        'phone',
        'images',
        'user_id',
    ];
    protected $casts = [
        'images' => 'array', 
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
