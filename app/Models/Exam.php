<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $guarded = ['id'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function studentExams()
    {
        return $this->hasMany(StudentExam::class, 'exam_id');
    }
}
