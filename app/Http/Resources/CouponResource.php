<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{
    public function toArray($request)
        {
            return [
                'id' => $this->id,
                'code' => $this->code,
                'type' => $this->type,
                'value' => $this->value,
                'max_uses' => $this->max_uses,
                'used_count' => $this->used_count,
                'starts_at' => optional($this->starts_at)->toDateTimeString(),
                'expires_at' => optional($this->expires_at)->toDateTimeString(),
                'active' => (bool) $this->active,
                'is_valid' => $this->isValid(),
                'created_at' => $this->created_at->toDateTimeString(),
                'updated_at' => $this->updated_at->toDateTimeString(),
            ];
        }
}
