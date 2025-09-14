<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseDetailResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'description'  => $this->description,
            'content_type' => $this->content_type,
            'content_link' => $this->content_link,
            'file_path'    => $this->file_path ? asset('storage/'.$this->file_path) : null,
            'created_at'   => $this->created_at,
        ];
    }
}
