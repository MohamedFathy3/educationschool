<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentExamResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'exam_id'    => $this->exam_id,
            'student_id' => $this->student_id,
            'score'      => $this->score,
            'attend'     => $this->attend,
        ];
    }
}
