<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = ['customer_id', 'post_id'];

    // Optional: if you are not using timestamps
    public $timestamps = true; // default is true, set false if your table doesn't have created_at/updated_at

    // Relationship to Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    // Relationship to Post_
    public function post()
    {
        return $this->belongsTo(Post_::class, 'post_id');
    }
}

