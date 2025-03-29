<?php

namespace App\Http\Requests\Admin\PublicSections\Intros;

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
            'title.ar'                  => 'required',
            'title.en'                  => 'required',
            'description.ar'            => 'required',
            'description.en'            => 'required',
            'image'                     => ['required','image'],
        ];
    }
}
