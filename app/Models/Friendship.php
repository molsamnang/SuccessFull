<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Friendship extends Model
{
   protected $fillable = ['user_id', 'friend_id', 'status'];

    public function user()
    {
        return $this->belongsTo(Customer::class, 'user_id'); // sender
    }

    public function friend()
    {
        return $this->belongsTo(Customer::class, 'friend_id'); // receiver
    }


}
