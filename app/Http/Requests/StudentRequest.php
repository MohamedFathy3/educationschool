<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
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
            'name' => 'required|string',
            'phone' => 'nullable|string',
            'stage_id' => 'required',
            'country_id' => 'required',
            'birth_day' => 'nullable|date|date_format:Y-m-d',
            'email' => 'nullable|email|unique:students,email,' . $this->student?->id,
            'password'    => 'nullable|string|min:6|confirmed',
            'image'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}



