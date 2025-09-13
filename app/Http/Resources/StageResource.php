<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'postion' => $this->postion ?? null,
            'active' => $this->active ?? null,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'country' => new CountryResource($this->country),
        ];
    }
}


