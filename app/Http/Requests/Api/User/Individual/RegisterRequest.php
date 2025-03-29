<?php

namespace App\Http\Requests\Api\User\Individual;

use App\Http\Requests\Api\BaseApiRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends BaseApiRequest
{

    public function rules()
    {
        return [
            'image'           => ['nullable', 'image', 'mimes:jpeg,png,jpg,svg,webp', 'max:2048'],
            'name'            => ['required', 'max:50'],
            'email'           => ['required', 'email:rfc,dns', Rule::unique('users', 'email')->whereNull('deleted_at')->ignore(auth()->user()->id)],
            'country_id'      => ['required', Rule::exists('countries', 'id')],
            'city_id'         => ['required', Rule::exists('cities', 'id')->where('country_id', $this->country_id)],
            'is_accept_terms' => ['required', 'in:1,true'],
            'user'            => 'nullable',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'user' => auth('user')->user(),
        ]);
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            if (!$this->user) {
                $validator->errors()->add('not_user', trans('auth.failed'));
            }
        });
    }
}
