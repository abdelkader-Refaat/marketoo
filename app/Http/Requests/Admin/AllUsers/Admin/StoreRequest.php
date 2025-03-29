<?php

namespace App\Http\Requests\Admin\AllUsers\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'     => 'required|max:191',
            'phone'    => ['required', 'numeric', 'digits_between:9,10', Rule::unique('admins', 'phone')->whereNull('deleted_at')],
            'country_code'                      => 'required|numeric|digits_between:2,5',
            'email'    => ['required', 'email:rfc,dns', 'max:191', Rule::unique('admins', 'email')->whereNull('deleted_at')],
            'password' => [
                'required',
                'string',
                'min:6',
                'max:255',
            ],
            'image'   => 'nullable|image',
            'role_id'  => 'required|exists:roles,id',
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
