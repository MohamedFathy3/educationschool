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
        return $this->belongsToMany(CourseDetail::class, 'course_detail_student')
            ->withPivot(['course_id', 'started_at', 'watched_duration', 'view'])
            ->withTimestamps();
    }


    public function parent()
    {
        return $this->belongsTo(ParentModel::class, 'parent_id');
    }

    public function stage()
    {
        return $this->belongsTo(Stage::class, 'stage_id');
    }
    
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function comments()
    {
        return $this->hasMany(CourseComment::class);
    }


    public function commentStudent()
    {
        return $this->hasMany(StudentComment::class, 'student_id');
    }

}
