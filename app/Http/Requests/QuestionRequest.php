<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'question_text' => 'required|string',
            'choices'       => 'required|array|min:2',
            'choices.*.choice_text' => 'required|string',
            'choices.*.is_correct'  => 'required|boolean',
        ];
    }
}
