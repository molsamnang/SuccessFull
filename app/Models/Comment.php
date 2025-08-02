<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
     protected $fillable = ['user_id', 'customer_id', 'post_id', 'body'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function post()
    {
        return $this->belongsTo(Post_::class, 'post_id');
    }
     public function user()
    {
        return $this->belongsTo(User::class);
    }
}
