<?php

namespace App\Http\Requests\Admin\AllUsers\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => 'required|max:191',
            'phone'    => ['required', 'numeric', 'digits_between:9,10', Rule::unique('admins', 'phone')->whereNull('deleted_at')->ignore($this->id)],
            'country_code'                      => 'required|numeric|digits_between:2,5',
            'email'    => ['required', 'email:rfc,dns', 'max:191', Rule::unique('admins', 'email')->whereNull('deleted_at')->ignore($this->id)],
            'password' => [
                'nullable',
                'string',
                'min:6',
                'max:255',
            ],
            'image'   => 'nullable|image',
            'role_id'  => 'required|exists:roles,id',
            'active'   => 'nullable|in:1,0',
            'is_blocked'  => 'required|in:1,0',
        ];
    }
    public function messages(): array
    {
        return [
            'password' => __('admin.password_must_contain_letters_symbols_and_numbers'),
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'phone' => fixPhone($this->phone),
            'country_code' => fixPhone($this->country_code)
        ]);
    }
}
