<?php

namespace App\Http\Requests\Site;

use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
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
            'name'      => 'required|max:50',
            'email'     => 'nullable|email:rfc,dns|max:100',
            'phone'     => "required|numeric|digits_between:9,15",
            'message'   => 'required|max:250',
        ];
    }
}
