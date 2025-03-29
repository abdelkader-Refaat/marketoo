<?php

namespace App\Http\Requests\Admin\AllUsers\Client;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'name'                              => 'required|max:191',
            'is_blocked'                        => 'required',
            'image'                             => ['nullable', 'image', 'mimes:png,jpg,jpeg'],
            'country_code'                      => 'required|numeric|digits_between:2,5',
            'phone'                             => ['required', 'numeric', 'digits_between:9,10', Rule::unique('users', 'phone')->whereNull('deleted_at')->ignore($this->id)],
            'email'                             => ['required', 'email:rfc,dns', Rule::unique('users', 'email')->whereNull('deleted_at')->ignore($this->id), 'max:50'],
            'country_id'                        => ['required', 'exists:countries,id'],
            'city_id'                           => ['required', Rule::exists('cities', 'id')->where('country_id', $this->country_id)],
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
