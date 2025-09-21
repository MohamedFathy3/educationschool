<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawRequest extends Model
{
    protected $guarded = ['id'];
    
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
