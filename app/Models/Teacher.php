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

    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class ,'stage_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class ,'subject_id');
    }

}

