<?php

namespace App\Services\Auth;

use App\Services\Core\BaseService;
use App\Traits\GeneralTrait;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Users\App\Models\User;


class AuthService extends BaseService
{
    use GeneralTrait, UploadTrait;


    public function loginViaPhone($user): array
    {
        $user->sendVerificationCode();
        return [
            'key' => 'success',
            'msg' => __('auth.send_verification_code_to_phone'),
            'data' => [
                'phone' => $user->phone,
                'country_code' => $user->country_code
            ]
        ];
    }

    public function loginViaMail($user): array
    {
        $token = $user->login();
        return [
            'key' => 'success',
            'msg' => __('auth.success_login'),
            'data' => [
                'token' => $token,
                'user' => $user->refresh(),
            ]
        ];
    }

    public function login(array $credentials): array
    {
        $user = $this->findUser($credentials);
        if (!$user) {
            return $this->authFailed(__('auth.incorrect_key_or_phone_or_email'));
        }
        if (!$this->validPassword($user, $credentials['password'])) {
            return $this->authFailed(__('auth.incorrect_pass'));
        }

        if ($user->is_blocked) {
            return $this->userBlocked($user);
        }

        if (!$user->active) {
            return $this->userInactive($user);
        }

        return $this->authSuccess($user);
    }

    protected function findUser(array $credentials): ?User
    {
        if (isset($credentials['email'])) {
            return User::where('email', $credentials['email'])->first();
        }
        return User::where([
            'phone' => $credentials['phone'],
            'country_code' => $credentials['country_code']
        ])->first();
    }

    protected function authFailed(string $message): array
    {
        return [
            'key' => 'fail',
            'msg' => $message,
            'user' => null
        ];
    }

    protected function validPassword(User $user, string $password): bool
    {
        return Hash::check($password, $user->password);
    }

    protected function userBlocked(User $user): array
    {
        return [
            'key' => 'blocked',
            'msg' => __('auth.blocked'),
            'user' => $user
        ];
    }

    protected function userInactive(User $user): array
    {
        return [
            'key' => 'needActive',
            'msg' => __('auth.not_active'),
            'user' => $user
        ];
    }

    protected function authSuccess(User $user): array
    {
        return [
            'key' => 'success',
            'msg' => __('auth.signed'),
            'user' => $user
        ];
    }

    public function activate($request): array
    {
        $msg = !$request['user']->active ? __('auth.activated') : __('auth.success_login');
        $request['user']->markAsActive();
        // Return the response data
        return [
            'key' => 'success',
            'msg' => $msg,
            'data' => [
                'token' => $request['user']->login(),
                'user' => $request['user']->refresh(),
            ]
        ];
    }

    public function register($request): array
    {
        $auth_user_id = isset($request['user']) ? $request['user']->id : null;
        $user = $this->model::updateOrCreate(['id' => $auth_user_id], $request);

        return [
            'key' => 'success',
            'msg' => __('auth.registered_success'),
            'user' => $user->refresh()
        ];
    }

    public function resendCode($request): array
    {
        $request['user']->sendVerificationCode();
        return [
            'key' => 'success',
            'msg' => __('auth.code_re_send'),
            'user' => $request['user']->refresh()
        ];
    }

    public function deleteAccount(Request $request): bool
    {
        try {
            $user = auth()->user();
            $user->currentAccessToken()->delete();
            if ($request->filled('device_id')) {
                $user->devices()->where('device_id', $request->device_id)->delete();
            }
            $user->delete();
            return true;
        } catch (\Throwable $e) {
            \Log::error('Error deleting account: '.$e->getMessage());
            return false;
        }
    }
}
