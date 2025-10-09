<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherComment extends Model
{
    protected $fillable = ['teacher_id', 'student_id', 'comment', 'rating'];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}

