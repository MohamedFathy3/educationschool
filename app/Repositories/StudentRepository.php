<?php

namespace App\Repositories;

use App\Interfaces\StudentRepositoryInterface;
use App\Models\Student;
use Illuminate\Database\Eloquent\Model;

class StudentRepository extends CrudRepository implements StudentRepositoryInterface
{
    protected Model $model;

    public function __construct(Student $model)
    {
        $this->model = $model;
    }
}
