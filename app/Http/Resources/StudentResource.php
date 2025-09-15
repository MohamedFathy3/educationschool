<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name ?? null,
            'email' => $this->email ?? null,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'qr_code' => $this->qr_code ?? null,
        ];
    }
}
