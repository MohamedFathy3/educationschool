<?php

namespace App\Repositories;

use App\Interfaces\TeacherRepositoryInterface;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Model;

class TeacherRepository extends CrudRepository implements TeacherRepositoryInterface
{
    protected Model $model;

    public function __construct(Teacher $model)
    {
        $this->model = $model;
    }
}
