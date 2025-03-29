<?php

namespace App\Http\Requests\Admin\CountriesCities\City;

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
            'country_id'            => 'required|exists:countries,id',
        ];
    }
}
