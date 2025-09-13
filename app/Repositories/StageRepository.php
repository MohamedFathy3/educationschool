<?php

namespace App\Repositories;

use App\Interfaces\StageRepositoryInterface;
use App\Models\Stage;
use Illuminate\Database\Eloquent\Model;

class StageRepository extends CrudRepository implements StageRepositoryInterface
{
    protected Model $model;

    public function __construct(Stage $model)
    {
        $this->model = $model;
    }
}
