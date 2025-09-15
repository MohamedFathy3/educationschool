<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChoiceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'choice_text' => $this->choice_text,
            'is_correct'  => (bool) $this->is_correct, // يظهر هل صحيح أو لا
        ];
    }
}
