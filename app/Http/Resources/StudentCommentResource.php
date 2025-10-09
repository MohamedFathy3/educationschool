<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentCommentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'      => $this->id,
            'comment' => $this->comment,
            'rating'  => $this->rating,
            'teacher' => new TeacherResource($this->whenLoaded('teacher')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
