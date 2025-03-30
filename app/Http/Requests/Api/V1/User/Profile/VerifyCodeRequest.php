<?php

namespace app\Http\Requests\Api\V1\User\Profile;

use App\Enums\AuthUpdatesAttributesEnum;
use app\Http\Requests\Api\V1\BaseApiRequest;
use Illuminate\Validation\Rule;

class VerifyCodeRequest extends BaseApiRequest
{

    public function rules(): array
    {
        return [
            'type'         => ['required', 'numeric', 'in:' . implode(',', array_column(AuthUpdatesAttributesEnum::cases(), 'value'))],
            'code'         => 'required|digits:6|numeric',
            'country_code' => [
                Rule::requiredIf(in_array($this->type, [AuthUpdatesAttributesEnum::Phone->value, AuthUpdatesAttributesEnum::NewPhone->value])),
                'nullable',
                'numeric',
                'digits_between:2,5'
            ],
            'phone'        => [
                Rule::requiredIf(in_array($this->type, [AuthUpdatesAttributesEnum::Phone->value, AuthUpdatesAttributesEnum::NewPhone->value])),
                'nullable',
                'numeric',
                'digits_between:9,10',
                'exists:auth_updates,attribute'
            ],
            'email'        => [
                Rule::requiredIf(in_array($this->type, [AuthUpdatesAttributesEnum::Email->value, AuthUpdatesAttributesEnum::NewEmail->value])),
                'nullable',
                'email:rfc,dns',
                'exists:auth_updates,attribute'
            ],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'phone'        => fixPhone($this->phone),
            'country_code' => fixPhone($this->country_code),
        ]);
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $user = auth()->user();
            $row = $user->authUpdates()->where([
                'attribute'    => $this->phone ?? $this->email,
                'country_code' => $this->country_code,
                'type'         => $this->type
            ])->first();

            if (!$row) {
                $msg = in_array($this->type, [AuthUpdatesAttributesEnum::Email->value, AuthUpdatesAttributesEnum::NewEmail->value]) ?
                    __('apis.send_change_mail_request_first') : __('apis.send_change_phone_request_first');
                $validator->errors()->add('not_found', $msg);
            } elseif ($row->code !== $this->code) {
                $validator->errors()->add('not_found', __('apis.invalid_code'));
            }
        });
    }
}
