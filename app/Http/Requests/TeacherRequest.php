<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
         return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:teachers,email,' . $this->id,
            'password'    => 'required|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'national_id' => 'nullable|string|max:50',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
            'certificate_image' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
            'experience_image' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
            'country_id' => 'required|exists:countries,id',
            'stage_id' => 'required|exists:stages,id',
            'subject_id' => 'required|exists:subjects,id',
        ];
    }
}



