<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class Student extends BaseModel
{
    use HasApiTokens,HasFactory;

    protected $guarded = ['id'];
    
    public function exams()
    {
        return $this->hasMany(StudentExam::class);
    }
}
