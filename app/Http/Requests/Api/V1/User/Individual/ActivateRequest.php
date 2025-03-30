<?php

namespace app\Http\Requests\Api\V1\User\Individual;

use App\Enums\UserTypesEnum;
use app\Http\Requests\Api\V1\BaseApiRequest;
use App\Models\AllUsers\User;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class ActivateRequest extends BaseApiRequest
{
    use GeneralTrait;

    public function rules()
    {
        return [
            'code'         => 'required|digits:6',
            'country_code' => ['required', 'numeric', 'digits_between:1,5', Rule::exists('countries', 'key')],
            'phone'        => ['required', 'numeric', 'digits_between:9,10', Rule::exists('users', 'phone')->where('country_code', fixPhone($this->country_code))],
            'device_id'    => 'required|max:250',
            'device_type'  => 'in:ios,android,web',
            'lang'         => 'in:en,ar',
            'type'         => ['required', Rule::in([UserTypesEnum::INDIVIDUAL->value])],
            'user'         => ['nullable'],
        ];
    }

    public function prepareForValidation()
    {
        $phone = fixPhone($this->phone);
        $country_code = fixPhone($this->country_code ?? 966);
        $this->merge([
            'phone'        => $phone,
            'country_code' => $country_code,
            'type'         => UserTypesEnum::INDIVIDUAL->value,
            'user'         => User::where(['phone' => $phone, 'country_code' => $country_code, 'type' => UserTypesEnum::INDIVIDUAL->value])->first(),
        ]);
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {


            if (!$this->user) {
                $validator->errors()->add('not_user', trans('auth.failed'));
            } else {
                if (!$this->isCodeCorrect($this->user, $this->code)) {
                    $validator->errors()->add('wrong_code', trans('auth.code_invalid'));
                }
                if ($this->user->is_blocked) {
                    $validator->errors()->add('blocked', trans('auth.blocked'));
                }
                if(Carbon::parse($this->user->code_expire)->isPast())
                {
                    $validator->errors()->add('code_expired', trans('auth.code_expired'));
                }
            }
        });
    }
}
