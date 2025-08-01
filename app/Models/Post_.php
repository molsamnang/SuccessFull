<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post_ extends Model
{
    use HasFactory;

    protected $table = 'post_s'; // must define due to underscore

    protected $fillable = [
        'title',
        'slug',
        'content',
        'image',
        'poster_head',
        'poster_sizes',
        'status',
        'customer_id',
        'category_id',
    ];

    protected $casts = [
        'poster_sizes' => 'array',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag', 'post_id', 'tag_id');
    }
}
