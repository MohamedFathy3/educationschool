<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LibraryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
       public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // لو update => id موجود في route('library')
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');

        return [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'type' => 'nullable|in:student,teacher',
            // file rules
            'file' => ($isUpdate ? 'nullable|' : 'required|') . 'file|mimes:pdf,mp4,mov,avi,wmv,mkv,jpg,jpeg,png|max:51200',
            // max:51200 => 50MB (size in kilobytes). عدّل حسب احتياجك
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,pdf|max:2048', // 2MB
        ];
    }

    public function messages(): array
    {
        return [
            'file.max' => 'حجم الملف كبير جدًا، الحد الأقصى 50MB.',
            'thumbnail.max' => 'حجم كبير جدًا، الحد الأقصى 2MB.',
        ];
    }
}
