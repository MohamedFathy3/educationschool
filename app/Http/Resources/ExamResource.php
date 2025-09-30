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
            'duration' => $this->duration,
            'course_id'   => $this->course_id,
            'questions'   => QuestionResource::collection($this->whenLoaded('questions')),
            'studentExams' => StudentExamResource::collection(
                $this->whenLoaded('studentExams')
            ),
            'questions_count'=> $this->when(isset($this->questions_count), $this->questions_count, $this->questions->count()),
            'created_at'  => $this->created_at->toDateTimeString(),
        ];
    }
}
