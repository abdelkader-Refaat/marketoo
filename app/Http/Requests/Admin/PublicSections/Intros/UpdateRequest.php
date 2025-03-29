<?php

namespace App\Http\Requests\Admin\PublicSections\Intros;

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
            'title.ar'                  => 'required',
            'title.en'                  => 'required',
            'description.ar'            => 'required',
            'description.en'            => 'required',
            'image'                     => ['nullable','image'],
        ];
    }
}
