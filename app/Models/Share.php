<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Share extends Model
{
    protected $fillable = ['customer_id', 'post_id'];
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function post()
    {
        return $this->belongsTo(Post_::class, 'post_id');
    }
}
