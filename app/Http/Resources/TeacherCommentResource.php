<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeacherCommentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'       => $this->id,
            'comment'  => $this->comment,
            'rating'   => $this->rating,
            'student'  => new StudentResource($this->whenLoaded('student')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
