<?php

namespace app\Http\Requests\Api\V1\User\Individual;

use App\Enums\UserTypesEnum;
use app\Http\Requests\Api\V1\BaseApiRequest;
use Illuminate\Validation\Rule;
use Modules\Users\App\Models\User;

class ResendCodeRequest extends BaseApiRequest
{
    public function rules()
    {
        return [
            'country_code' => ['required', 'numeric', 'digits_between:1,5', Rule::exists('countries', 'key')],
            'phone'        => [
                'required',
                'numeric',
                'digits_between:9,10',
                Rule::exists('users', 'phone')->where('country_code', $this->country_code)
            ],
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
