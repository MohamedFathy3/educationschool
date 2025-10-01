<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseDetailResource extends JsonResource
{
    public function toArray($request)
    {
        $student = auth('students')->user();

        $watchingData = null;

        if ($student) {
            $watchingData = \DB::table('course_detail_student')
                ->where('student_id', $student->id)
                ->where('course_detail_id', $this->id)
                ->select('started_at', 'watched_duration', 'view')
                ->first();

            if ($watchingData) {
                $watchingData = [
                    'started_at'       => $watchingData->started_at,
                    'watched_duration' => $watchingData->watched_duration,
                    'view'             => (bool) $watchingData->view,
                ];
            }
        }

        // Show students for this course detail
        $students = $this->students->map(function ($student) {
            return [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'pivot' => [
                    'course_id'        => $student->pivot->course_id,
                    'started_at'       => $student->pivot->started_at,
                    'watched_duration' => $student->pivot->watched_duration,
                    'view'             => (bool) $student->pivot->view,
                ],
            ];
        });

        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'description'   => $this->description,
            'content_type'  => $this->content_type,
            'content_link'  => $this->content_link,
            'session_date'  => $this->session_date ?? null,
            'session_time'  => $this->session_time ?? null,
            'file_path'     => $this->file_path ? asset('storage/'.$this->file_path) : null,
            'created_at'    => $this->created_at,
            'watching_data' => $watchingData,
            'students'      => $students, // بيانات الطلاب المرتبطين بالدرس
        ];
    }
}

