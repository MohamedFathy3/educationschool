<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'      => $this->id,
            'body'    => $this->body,
            'created_at' => $this->created_at->toDateTimeString(),

            'sender' => [
                'id'    => $this->sender?->id,
                'name'  => $this->sender?->name,
                'image' => $this->sender?->image ? asset('storage/' . $this->sender?->image) : null,
                'type'  => $this->sender_type,
            ],
            'image' => $this->image ? asset('storage/' . $this->image) : null,

            'receiver' => [
                'id'    => $this->receiver?->id,
                'name'  => $this->receiver?->name,
                'image' => $this->receiver?->image ? asset('storage/' . $this->receiver?->image) : null,
                'type'  => $this->receiver_type,
            ],
        ];
    }
}
