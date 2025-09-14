<?php

namespace App\Repositories;

use App\Interfaces\CourseRepositoryInterface;
use App\Models\Course;
use Illuminate\Database\Eloquent\Model;

class CourseRepository extends CrudRepository implements CourseRepositoryInterface
{
    protected Model $model;

    public function __construct(Course $model)
    {
        $this->model = $model;
    }
}
