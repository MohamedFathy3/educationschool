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
            'price'              => $this->price,
            'discount'           => $this->discount,
            'final_price'        => $this->price - ($this->price * $this->discount / 100),
            'what_you_will_learn'=> $this->what_you_will_learn,
            'image'              => $this->image ? asset('storage/'.$this->image) : null,
            'intro_video_url'    => $this->intro_video_url,
            'views_count'        => $this->views_count,
            'subscribers_count'  => $this->subscribers_count,
            'active'             => (bool) $this->active,
            'teacher'            => new TeacherResource($this->whenLoaded('teacher')),
            'stage'              => new StageResource($this->whenLoaded('stage')),
            'subject'            => new SubjectResource($this->whenLoaded('subject')),
            'country'            => new CountryResource($this->whenLoaded('country')),
            'details'            => CourseDetailResource::collection($this->whenLoaded('courseDetail')),
            'created_at'         => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
