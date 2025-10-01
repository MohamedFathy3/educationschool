<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class ParentModel extends BaseModel
{
    use HasApiTokens,HasFactory;
protected $table = 'parent_models';

    protected $guarded = ['id'];
    public function students()
    {
        return $this->hasMany(Student::class, 'parent_id');
    }

}
