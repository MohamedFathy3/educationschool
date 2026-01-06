<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseDetailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course_id'    => 'required|exists:courses,id',
            'title'        => 'nullable|string|max:255',
            'description'  => 'nullable|string',

            'content_type' => 'required|in:video,pdf,file,zoom',

            // زووم فقط
            'content_link' => 'nullable|url|required_if:content_type,zoom',

            // ملفات عادية
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:5120|required_if:content_type,pdf,file',

            // 🎥 فيديو إجباري لما النوع video
            'video' => 'nullable|file|mimes:mp4,mov,avi,webm|max:5120|required_if:content_type,video',

            'session_date' => 'nullable|date',
            'session_time' => 'nullable|date_format:H:i',
        ];
    }

}
