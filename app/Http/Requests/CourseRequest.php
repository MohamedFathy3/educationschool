<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
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
            'stage_id'         => 'nullable|exists:stages,id',
            'subject_id'       => 'nullable|exists:subjects,id',
            'country_id'       => 'nullable|exists:countries,id',
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'type'             => 'required|in:online,recorded',
            'price'            => 'required|numeric|min:0',
            'discount'         => 'nullable|numeric|min:0|max:100',
            'what_you_will_learn' => 'nullable|string',
            'image'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'intro_video_url'  => 'nullable|url',
        ];
    }
}
