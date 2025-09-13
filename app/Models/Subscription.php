<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscription extends BaseModel
{
    use HasFactory;

    protected $guarded = ['id'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

}
