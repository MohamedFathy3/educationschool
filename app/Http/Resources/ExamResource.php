<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExamResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'course_id'   => $this->course_id,
            'questions'   => QuestionResource::collection($this->whenLoaded('questions')),
            'created_at'  => $this->created_at->toDateTimeString(),
        ];
    }
}
