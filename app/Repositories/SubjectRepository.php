<?php

namespace App\Repositories;

use App\Interfaces\SubjectRepositoryInterface;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Model;

class SubjectRepository extends CrudRepository implements SubjectRepositoryInterface
{
    protected Model $model;

    public function __construct(Subject $model)
    {
        $this->model = $model;
    }
}
