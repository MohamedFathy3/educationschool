<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileTeacherRequest extends FormRequest
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
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'national_id' => 'nullable|string|max:50',
            'password'    => 'required|string|min:6|confirmed',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
            'certificate_image' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
            'experience_image' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
            'country_id' => 'required|exists:countries,id',
            'stage_id' => 'required|exists:stages,id',
            'subject_id' => 'required|exists:subjects,id',
            'bank_name' => 'nullable|string|max:255',

            'account_holder_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'iban' => 'nullable|string|max:255',
            'swift_code' => 'nullable|string|max:255',
            'branch_name' => 'nullable|string|max:255',
            'wallets_name' => 'nullable|string|max:255',
            'wallets_number' => 'nullable|string|max:255',
        ];
    }
}
