<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentExam extends Model
{
    protected $guarded = ['id'];

    public function answers()
    {
        return $this->hasMany(StudentAnswer::class);
    }
}
