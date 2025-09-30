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


    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_student');
    }


    public function watchedLectures()
    {
        return $this->belongsToMany(CourseDetail::class, 'student_course_detail')
            ->withPivot(['course_id', 'started_at', 'watched_duration', 'view'])
            ->withTimestamps();
    }


}
