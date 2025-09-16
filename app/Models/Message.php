<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $guarded = ['id'];

    public function sender()
    {
        return $this->morphTo(null, 'sender_type', 'sender_id');
    }

    public function receiver()
    {
        return $this->morphTo(null, 'receiver_type', 'receiver_id');
    }
}
