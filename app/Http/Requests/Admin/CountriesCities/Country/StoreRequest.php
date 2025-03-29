<?php

namespace App\Http\Requests\Admin\CountriesCities\Country;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'                  => 'required|array',
            'name.*'                => 'required|max:191',
            'key'                    => 'required|unique:countries,key',
            'flag'                   => 'required',
        ];

    }


    public function prepareForValidation()
    {
        $this->merge([
            'key' => fixPhone($this->key),
        ]);
    }
}
