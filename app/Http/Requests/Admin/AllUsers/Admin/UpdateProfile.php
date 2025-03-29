<?php

namespace App\Http\Requests\Admin\AllUsers\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfile extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [

            'name'      => 'required|max:191',
            'phone'     => "required|numeric|digits_between:9,15|unique:admins,phone,".auth('admin')->id(),
            'email'     => "required|email|max:191|unique:admins,email,".auth('admin')->id(),
            'image'     => 'nullable|image',
        ];
    }
}
