<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse;
use App\Http\Resources\CountryResource;
use App\Interfaces\CountryRepositoryInterface;
use App\Models\Country;
use App\Traits\HttpResponses;
use Exception;
use Illuminate\Http\Request;

class CountryController extends BaseController
{
    protected mixed $crudRepository;
    use HttpResponses;

    public function __construct(CountryRepositoryInterface $pattern)
    {
        $this->crudRepository = $pattern;
    }

    public function index()
    {
        try {
            $countries = CountryResource::collection($this->crudRepository->all(
                [],
                [],
                ['id', 'name', 'key', 'code', 'order_id', 'active',  'created_at', 'updated_at', 'deleted_at']
            ));
            return $countries->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

    public function show(Country $country): CountryResource|\Illuminate\Http\JsonResponse
    {
        try {
            $country = new CountryResource($country);
            return $country->additional(JsonResponse::success());
        } catch (Exception $e) {
            return JsonResponse::respondError($e->getMessage());
        }
    }

}
