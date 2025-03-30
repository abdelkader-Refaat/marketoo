<?php

namespace app\Http\Requests\Api\V1\User\Individual;

use App\Enums\UserTypesEnum;
use app\Http\Requests\Api\V1\BaseApiRequest;
use App\Models\AllUsers\User;
use Illuminate\Validation\Rule;

class LoginRequest extends BaseApiRequest
{

    public function rules()
    {
        return [
            'country_code' => ['required', 'numeric', 'digits_between:1,5', Rule::exists('countries', 'key')],
            'phone'        => ['required', 'numeric', 'digits_between:9,10'],
            'type'         => ['required', Rule::in([UserTypesEnum::INDIVIDUAL->value])],
            'user'         => 'nullable'
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'phone'        => fixPhone($this->phone),
            'country_code' => fixPhone($this->country_code ?? 966),
            'type'         => UserTypesEnum::INDIVIDUAL->value,
            'user'         => User::where(['phone' => fixPhone($this->phone), 'country_code' => fixPhone($this->country_code)])->first(),
        ]);
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            if ($this->user) {
                if ($this->user->is_blocked) {
                    $validator->errors()->add('blocked', trans('auth.blocked'));
                }
            }
        });
    }
}
