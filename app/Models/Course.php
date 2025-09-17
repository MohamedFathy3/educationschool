<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends BaseModel
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'active' => 'boolean'
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }


    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }


    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'course_student')
                    ->withPivot('created_at')
                    ->withTimestamps();
    }


    public function courseDetail()
    {
        return $this->hasMany(CourseDetail::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
    
    public function exams()
    {
        return $this->hasMany(Exam::class);
    }
}
