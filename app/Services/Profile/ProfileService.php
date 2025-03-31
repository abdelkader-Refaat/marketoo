<?php

namespace App\Services\Profile;

use App\Models\Core\AuthUpdate;
use App\Services\Core\BaseService;
use App\Enums\AuthUpdatesAttributesEnum;
use Modules\Users\App\Models\User;

class ProfileService extends BaseService
{

    public function editProfile($request): array
    {
        $request['user']->update($request);
        return ['key' => 'success', 'msg' => __('apis.success'), 'user' => $request['user']->refresh()];
    }

    public function storeAtUpdates($request): array
    {
        $updateData = [
            'type' => $request['type'],
            'code' => ''
        ];

        $updateData['attribute'] = $request['attribute'];
        empty($request['country_code']) ?: $updateData['country_code'] = $request['country_code'];

        $update = $request['user']->authUpdates()->updateOrCreate(['type' => $updateData['type']], $updateData);

        $this->sendCode($request['user'], $update);

        $data = [
            filter_var($update->attribute, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone' => $update->attribute,
        ];
        isset($update->country_code) ? $data['country_code'] = $update->country_code : null;
        return [
            'key' => 'success',
            'msg' => __('apis.code_sent'),
            'data' => $data
        ];
    }

    protected function sendCode($user, $update): void
    {
        match ($update->attribute) {
            filter_var($update->attribute, FILTER_VALIDATE_EMAIL) => $user->sendCodeAtEmail($update->code,
                $update->attribute),
            default => $user->sendCodeAtSms($update->code, $update->country_code.$update->attribute),
        };
    }

    public function verifyCode($user, $request): array
    {
        $row = $user->authUpdates()->where([
            'attribute' => $request['phone'] ?? $request['email'],
            'country_code' => $request['country_code'],
            'code' => $request['code'],
            'type' => $request['type']
        ])->first();


        $result = match ($row->type) {
            AuthUpdatesAttributesEnum::Email->value, AuthUpdatesAttributesEnum::Phone->value, AuthUpdatesAttributesEnum::Password->value => [
                $row->update(['code' => null]),
                'msg' => __('apis.code_verified'),
            ],
            AuthUpdatesAttributesEnum::NewPhone->value => [
                $user->update([
                    'phone' => $row->attribute,
                    'country_code' => $row->country_code,
                ]),
                $user->authUpdates()->whereIn('type',
                    [AuthUpdatesAttributesEnum::Phone->value, AuthUpdatesAttributesEnum::NewPhone->value])->delete(),
                'msg' => __('apis.phone_changed'),
            ],
            AuthUpdatesAttributesEnum::NewEmail->value => [
                $user->update(['email' => $row->attribute]),
                $user->authUpdates()->whereIn('type',
                    [AuthUpdatesAttributesEnum::Email->value, AuthUpdatesAttributesEnum::NewEmail->value])->delete(),
                'msg' => __('apis.email_changed'),
            ],
            default => [
                'msg' => __('apis.invalid_code')
            ],
        };

        return [
            'key' => 'success',
            'msg' => $result['msg'],
            'data' => [
                filter_var($row->attribute, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone' => $row->attribute,
                isset($row->country_code) ? 'country_code' : null => $row->country_code
            ]
        ];
    }

    public function updatePassword($request): array
    {
        $user = $request['user'];
        $update = AuthUpdate::where([
            'updatable_id' => $user->id,
            'updatable_type' => User::class,
            'type' => AuthUpdatesAttributesEnum::Password->value,
        ])->first();
        $user->update(['password' => $request['password']]);
        $update->delete();
        return ['key' => 'success', 'msg' => __('apis.password_updated')];
    }
}
