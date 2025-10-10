<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WithdrawRequestResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'teacher'   => new TeacherResource($this->whenLoaded('teacher')),
            'amount'    => $this->amount,
            'transfer_type'    => $this->transfer_type,
            'status'    => $this->status,
            'comment'   => $this->comment,
            'created_at'=> $this->created_at->toDateTimeString(),
        ];
    }
}
