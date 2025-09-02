<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post_ extends Model
{
    use HasFactory;

    protected $table = 'post_s';

    protected $fillable = [
        'content',
        'image',    // optional if you keep single image
        'images',   // JSON column for multiple images
        'status',
        'customer_id',
        'category_id',
    ];

    // Cast the 'images' attribute to an array automatically
    protected $casts = [
        'images' => 'array',
    ];


    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id'); // <-- specify the correct foreign key
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'post_id');
    }


    public function shares()
    {
        return $this->hasMany(Share::class, 'post_id'); // <-- and here
    }
}
