<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentUpdateRequest extends FormRequest
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
            'name' => 'nullable|string',
            'phone' => 'nullable|string',
            'stage_id' => 'nullable',
            'country_id' => 'nullable',
            'birth_day' => 'nullable|date|date_format:Y-m-d',
            // 'email' => 'nullable|email|unique:students,email,' . $this->student?->id,
            'password'    => 'nullable|string|min:6|confirmed',
            'image'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}



