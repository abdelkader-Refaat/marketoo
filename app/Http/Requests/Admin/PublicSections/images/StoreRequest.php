<?php

namespace App\Http\Requests\Admin\PublicSections\Images;

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
            'image'                => ['required','image'],
        ];
    }
}
