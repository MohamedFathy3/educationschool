<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $userId = $this->route('user') ? $this->route('user')->id : null;

        return [
            'name' => 'nullable|string',
            'email'         => 'nullable|unique:users,email,' . $userId,
            'title' => 'nullable|in:female,male',
            'position_id' => 'nullable',
            'phone' => 'nullable|string',
            'phone_ext' => 'nullable',
            'cell' => 'nullable',
            'avatar' => ['nullable', 'image', 'max:2048'],
            'active' => 'nullable|boolean',
            'role' => 'nullable|in:employee,help_desk,admin',
            'password' => 'nullable',
            'department_id' => 'nullable',
            'company_id' => 'nullable',
            'city_id' => 'nullable',
            'country_id' => 'nullable',
            'phone_key_id' => 'nullable',
            'branch_id' => 'nullable',



            'sale_man' => 'nullable|boolean',
            'access_all_charges' => 'nullable|boolean',
            'hide_other_records' => 'nullable|boolean',
            'email_password' => 'nullable',
            'email_display_name' => 'nullable',
            'email_host' => 'nullable',
            'email_port' => 'nullable',
            'email_ssl' => 'nullable',
            'local_name' => 'nullable',
            'note' => 'nullable',
            'username' => 'nullable',
            'address' => 'nullable',

        ];
    }
}
