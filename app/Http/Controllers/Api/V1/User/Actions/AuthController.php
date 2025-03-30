<?php

namespace app\Http\Controllers\Api\V1\User\Actions;

use App\Http\Controllers\Controller;
use app\Http\Requests\Api\V1\User\Individual\ActivateRequest;
use app\Http\Requests\Api\V1\User\Individual\LoginRequest;
use app\Http\Requests\Api\V1\User\Individual\RegisterRequest;
use app\Http\Requests\Api\V1\User\Individual\ResendCodeRequest;
use App\Http\Resources\Api\User\UserResource;
use App\Models\AllUsers\User;
use App\Services\Auth\AuthService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{

    use ResponseTrait;
    protected $authService;
    public function __construct()
    {
        $this->authService = new AuthService(User::class);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $this->authService->register(request: $request->validated());
        return $this->response($data['key'], $data['msg'], [
            'user'  => UserResource::make($data['user'])
        ]);
    }

    public function resendCode(ResendCodeRequest $request): JsonResponse
    {
        $this->authService->resendCode($request->validated());

        return $this->response('success', __('auth.code_re_send'));
    }

    public function activate(ActivateRequest $request): JsonResponse
    {
        $data = $this->authService->activate($request->validated());

        return $this->response('success', $data['msg'], [
            'user'                  => UserResource::make($data['data']['user'])->setToken($data['data']['token']),
            'go_to_register_step'   => true
        ]);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $data = $this->authService->loginViaPhone($request->user);
        return $this->response($data['key'], $data['msg'], []);
    }
}
