<?php

namespace App\Repositories;

use App\Interfaces\CourseDetailRepositoryInterface;
use App\Models\CourseDetail;
use Illuminate\Database\Eloquent\Model;

class CourseDetailRepository extends CrudRepository implements CourseDetailRepositoryInterface
{
    protected Model $model;

    public function __construct(CourseDetail $model)
    {
        $this->model = $model;
    }
}
