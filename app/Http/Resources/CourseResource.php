<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray($request)
    {
       return [
            'id'                 => $this->id,
            'title'              => $this->title,
            'description'        => $this->description,
            'type'               => $this->type,
            'original_price'        => $this->original_price ,
            'discount'           => $this->discount,
            'price'              => $this->price,
            'what_you_will_learn'=> $this->what_you_will_learn,
            'image'              => $this->image ? asset('storage/'.$this->image) : null,
            'intro_video_url'    => $this->intro_video_url,
            'views_count'        => $this->views_count,
            'course_type'        => $this->course_type ?? null,
            'count_student'        => $this->count_student ?? null,
            'currency'        => $this->currency ?? null,
            'subscribers_count'  => $this->subscribers_count,
            'active'             => (bool) $this->active,
            'teacher'            => new TeacherResource($this->whenLoaded('teacher')),
            'stage'              => new StageResource($this->whenLoaded('stage')),
            'subject'            => new SubjectResource($this->whenLoaded('subject')),
            'country'            => new CountryResource($this->whenLoaded('country')),
            'details'            => CourseDetailResource::collection($this->whenLoaded('courseDetail')),
            'exams'              => ExamResource::collection($this->whenLoaded('exams')), // --- ADDED ---
            'comments' => CourseCommentResource::collection($this->whenLoaded('comments')),
            'average_rating' => round($this->comments->avg('rating'), 1),
            'students' => StudentResource::collection($this->whenLoaded('students')),
            'created_at'         => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}

