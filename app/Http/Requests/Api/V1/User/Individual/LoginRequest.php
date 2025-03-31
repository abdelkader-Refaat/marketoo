<?php

namespace app\Http\Requests\Api\V1\User\Individual;

use App\Enums\UserTypesEnum;
use app\Http\Requests\Api\V1\BaseApiRequest;
use Illuminate\Validation\Rule;
use Modules\Users\App\Models\User;

class LoginRequest extends BaseApiRequest
{

    public function rules(): array
    {
        return [
//            'country_code' => ['required', 'numeric', 'digits_between:1,5', Rule::exists('countries', 'key')],
            'country_code' => 'required_if:email,null|numeric|digits_between:1,5|'.Rule::exists('countries', 'key'),
            'phone' => ['nullable', 'required_if:email,null', 'numeric', 'digits_between:9,10'],
            'email' => 'nullable|required_if:phone,null|email|exists:users,email,deleted_at,NULL|max:50',
            'password' => 'required|min:6|max:100',
            'device_id' => 'required|max:250',
            'device_type' => 'required|in:'.$this->deviceTypes(),
            'type' => ['required', Rule::in([UserTypesEnum::INDIVIDUAL->value])],
            'user' => 'nullable'
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'phone' => fixPhone($this->phone),
            'country_code' => fixPhone($this->country_code ?? 966),
            'type' => UserTypesEnum::INDIVIDUAL->value,
            'user' => User::where([
                'phone' => fixPhone($this->phone), 'country_code' => fixPhone($this->country_code)
            ])->first(),
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
