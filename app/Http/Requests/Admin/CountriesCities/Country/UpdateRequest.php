<?php

namespace App\Http\Requests\Admin\CountriesCities\Country;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'name'                  => 'required|array',
            'name.*'                => 'required|max:191',
            'key'                   => ['required' ,  Rule::unique('countries','key')->ignore($this->id)],
            'flag'                  => 'required',

        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'key' => fixPhone($this->key),
        ]);
    }
}
