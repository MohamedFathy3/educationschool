<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class Teacher extends BaseModel
{
    use HasApiTokens,HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'active' => 'boolean'
    ];

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function stages()
    {
        return $this->belongsToMany(Stage::class, 'stage_teacher');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_teacher');
    }

    public function comments()
    {
        return $this->hasMany(TeacherComment::class);
    }

    public function averageRating()
    {
        return $this->comments()->avg('rating');
    }


}

