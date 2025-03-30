<?php

namespace app\Http\Requests\Api\V1\User\Profile;

use App\Enums\AuthUpdatesAttributesEnum;
use app\Http\Requests\Api\V1\BaseApiRequest;
use Illuminate\Validation\Rule;

class NewPhoneRequest extends BaseApiRequest
{

    public function rules(): array
    {
        return [
            'country_code' => 'required|numeric|digits_between:2,5',
            'phone'        => ['required', 'numeric', 'digits_between:9,10', Rule::unique('users', 'phone')
            ->whereNull('deleted_at')->ignore(auth()->id())],
            'user' => 'nullable',
            'attribute' => 'required',
            'type'      => 'required'
        ];
    }

    public function prepareForValidation(): void
    {
        $phone = fixPhone($this->phone);
        $country_code = fixPhone($this->country_code);
        $this->merge([
            'attribute' => $phone,
            'country_code' => $country_code,
            'user' => auth()->user(),
            'type' => AuthUpdatesAttributesEnum::NewPhone->value
        ]);
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->phone == $this->user->phone) {
                $validator->errors()->add('same_phone', __('apis.same_phone'));
            } else {
                $oldPhone = $this->user->authUpdates()->where([
                    'attribute'     => $this->user->phone,
                    'country_code'  => $this->user->country_code,
                    'type'          => AuthUpdatesAttributesEnum::Phone->value
                ])->first();
                if (!$oldPhone) {
                    $validator->errors()->add('not_found', __('apis.send_change_phone_request_first'));
                }
            }
        });
    }
}
