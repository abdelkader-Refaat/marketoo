<?php

namespace App\Http\Requests\Admin\PublicSettings\Socials;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'icon' => [
                Rule::requiredIf($this->method() == 'POST'), // 'Post' should be 'POST'
                'nullable',
                'image',
                'mimes:jpeg,jpg,png',
                'max:2048',
            ],
            'link'  => 'required|url',
            'name'  => 'required',
        ];
    }
}
