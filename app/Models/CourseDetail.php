<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseDetail extends BaseModel
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'active' => 'boolean'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function students()
    {
        return $this->belongsToMany(Student::class, 'course_detail_student')
            ->withPivot(['course_id', 'started_at', 'watched_duration', 'view'])
            ->withTimestamps();
    }


}
