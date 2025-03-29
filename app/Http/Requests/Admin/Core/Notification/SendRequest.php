<?php

namespace App\Http\Requests\Admin\Core\Notification;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SendRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'type'      => ['required', 'in:notify,email,sms'],
            'user_type' => [Rule::requiredIf(is_int($this->id)), 'in:all,admins,users,providers,delegates'],
            'body'      => [Rule::requiredIf($this->type !== 'notify'), 'nullable'],
            'title_ar'  => [Rule::requiredIf($this->type == 'notify'), 'nullable'],
            'title_en'  => [Rule::requiredIf($this->type == 'notify'), 'nullable'],
            'body_ar'   => [Rule::requiredIf($this->type == 'notify'), 'nullable'],
            'body_en'   => [Rule::requiredIf($this->type == 'notify'), 'nullable'],
            'id'        => ['nullable', 'numeric'],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'id' => isset($this->id) && is_numeric($this->id) ? $this->id : null,
        ]);
    }
}
