<?php

namespace app\Http\Requests\Auth\Apis\V1;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
           'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
