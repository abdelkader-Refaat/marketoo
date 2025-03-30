<?php

namespace app\Http\Requests\Api\V1\User\Profile;

use App\Enums\AuthUpdatesAttributesEnum;
use app\Http\Requests\Api\V1\BaseApiRequest;
use Illuminate\Validation\Rule;

class NewEmailRequest extends BaseApiRequest
{

    public function rules(): array
    {
        return [
            'email'        => ['required', 'email:rfc,dns', Rule::unique('users', 'email')
                ->whereNull('deleted_at')->ignore(auth()->id())],
            'user' => 'nullable',
            'attribute' => 'required',
            'type'      => 'required'
        ];
    }

    public function prepareForValidation()
    {
        return $this->merge([
            'user' => auth()->user(),
            'attribute' => $this->email,
            'type' => AuthUpdatesAttributesEnum::NewEmail->value
        ]);
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->email == $this->user->email) {
                $validator->errors()->add('same_mail', __('apis.same_mail'));
            } else {
                $oldMail = $this->user->authUpdates()->where([
                    'attribute'     => $this->user->email,
                    'type'          => AuthUpdatesAttributesEnum::Email->value
                ])->first();
                if (!$oldMail) {
                    $validator->errors()->add('not_found', __('apis.send_change_mail_request_first'));
                }
            }
        });
    }
}
