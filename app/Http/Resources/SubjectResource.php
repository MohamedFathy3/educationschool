<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubjectResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'postion' => $this->postion ?? null,
            'active' => $this->active ?? null,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'stage' => new StageResource($this->stage),
        ];
    }
}

