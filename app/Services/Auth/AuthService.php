<?php

namespace App\Services\Auth;

use App\Services\Core\BaseService;
use App\Traits\GeneralTrait;
use App\Traits\UploadTrait;


class AuthService extends BaseService
{
    use GeneralTrait, UploadTrait;


    public function loginViaPhone($user): array
    {
        $user->sendVerificationCode();

        return [
            'key'  => 'success',
            'msg'  => __('auth.send_verification_code_to_phone'),
            'data' => [
                'phone'        => $user->phone,
                'country_code' => $user->country_code
            ]
        ];
    }

    public function loginViaMail($user): array
    {
        $token = $user->login();
        return [
            'key'  => 'success',
            'msg'  => __('auth.success_login'),
            'data' => [
                'token' => $token,
                'user'  => $user->refresh(),
            ]
        ];
    }

    public function activate($request): array
    {
        $msg = !$request['user']->active ? __('auth.activated') : __('auth.success_login');
        $request['user']->markAsActive();
        // Return the response data
        return [
            'key'  => 'success',
            'msg'  => $msg,
            'data' => [
                'token' => $request['user']->login(),
                'user'  => $request['user']->refresh(),
            ]
        ];
    }


    public function register($request): array
    {
        $auth_user_id = isset($request['user']) ? $request['user']->id : null;
        $user = $this->model::updateOrCreate(['id' => $auth_user_id], $request);

        return [
            'key'  => 'success',
            'msg'  => __('auth.registered_success'),
            'user' => $user->refresh()
        ];
    }

    public function resendCode($request): array
    {
        $request['user']->sendVerificationCode();
        return [
            'key'  => 'success',
            'msg'  => __('auth.code_re_send'),
            'user' => $request['user']->refresh()
        ];
    }
}
