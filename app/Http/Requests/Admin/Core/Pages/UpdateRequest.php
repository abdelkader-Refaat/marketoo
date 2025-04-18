<?php

namespace App\Http\Requests\Admin\Core\Pages;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title.*'                  => 'required|string',
            'content.*'                  => 'required|string',
        ];
    }
}
