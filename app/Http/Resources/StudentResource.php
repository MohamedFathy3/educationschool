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
            'phone' => $this->phone ?? null,
            'email' => $this->email ?? null,
            'type' =>"student",
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'qr_code' => $this->qr_code ?? null,
            'total_rate' => $this->total_rate ?? null,
            'courses'    => CourseResource::collection($this->whenLoaded('courses')),



            'comments' => StudentCommentResource::collection($this->whenLoaded('commentStudent')),
            'average_rating' => round($this->comments->avg('rating'), 1),

        ];
    }
}

