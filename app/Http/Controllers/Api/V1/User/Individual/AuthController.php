<?php

namespace app\Http\Controllers\Api\V1\User\Individual;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\User\Individual\ActivateRequest;
use App\Http\Requests\Api\V1\User\Individual\LoginRequest;
use App\Http\Requests\Api\V1\User\Individual\RegisterRequest;
use App\Http\Requests\Api\V1\User\Individual\ResendCodeRequest;
use App\Http\Resources\Api\User\UserResource;
use App\Http\Resources\RegisterResource;
use App\Services\AllUsers\ClientService;
use App\Services\Auth\AuthService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Users\App\Models\User;

class AuthController extends Controller
{
    use ResponseTrait;

    private $authService;

    private $userService;

    public function __construct()
    {
        $this->authService = new AuthService(User::class);
        $this->userService = new ClientService;
    }

    //    public function login(LoginRequest $request): JsonResponse
    //    {
    //        try {
    //            $data = $this->userService->findOrNew(data: $request->only(['phone', 'country_code', 'type']))['data'];
    //            $data = $this->authService->loginViaPhone($data);
    //            return $this->jsonResponse(msg: $data['msg'], data: $data['data'] ?? [], key: $data['key']);
    //        } catch (\Exception $e) {
    //            return $this->jsonResponse(msg: $e->getMessage(), code: 500, error: true, errors: [
    //                'file' => $e->getFile(), 'line' => $e->getLine()
    //            ]);
    //        }
    //    }
    /**
     * Handle user login request
     */
    public function login(LoginRequest $request)
    {
        $result = $this->authService->login($request->validated());

        return match ($result['key']) {
            'fail' => $this->failMsg($result['msg']),
            'blocked' => $this->blockedReturn($result['user']),
            'needActive' => $this->handleInactiveUser($result['user']),
            'success' => $this->handleSuccessfulLogin($result['user']),
            default => $this->failMsg(__('auth.failed'))
        };
    }

    protected function handleInactiveUser(User $user)
    {
        $user->sendVerificationCode();

        return $this->phoneActivationReturn($user);
    }

    protected function handleSuccessfulLogin(User $user)
    {
        $user->sendVerificationCode();
        $token = $user->login();

        return $this->response(
            'success',
            msg: __('apis.signed'),
            data: ['user' => UserResource::make($user)->setToken($token)]
        );
    }

    public function activate(ActivateRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = $this->authService->activate($request->validated());
            $go_to_register_step = $this->userService->isRegistered($data['data']['user']);
            DB::commit();

            return $this->jsonResponse(msg: $data['msg'], data: [
                'user' => UserResource::make($data['data']['user'])->setToken($data['data']['token']),
                'go_to_register_step' => $go_to_register_step,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->jsonResponse(msg: $e->getMessage(), code: $e->getCode(), error: true,
                errors: ['file' => $e->getFile(), 'line' => $e->getLine()]);
        }
    }

    public function completeData(RegisterRequest $request): JsonResponse
    {
        $data = $this->authService->register(request: $request->validated());

        return $this->jsonResponse(msg: $data['msg'], data: [
            'user' => UserResource::make($data['user'])->setToken($request->headers->get('Authorization')),
        ]);
    }

    public function register(RegisterRequest $request)
    {
        try {
            $user = User::query()->create($request->validated());
            $token = $user->createToken('auth_token')->plainTextToken;
            return $this->successData(RegisterResource::make($user->fresh(), $token), 201)->header('Authorization',
                "Bearer {$token}");
        } catch (\Exception $exception) {
            return $this->failMsg($exception->getMessage());
        }
    }

    public function resendCode(ResendCodeRequest $request): JsonResponse
    {
        $this->authService->resendCode($request->validated());

        return $this->response('success', __('auth.code_re_send'));
    }

    public function deleteAccount(Request $request)
    {
        return $this->authService->deleteAccount($request)
            ? $this->successMsg(__('auth.account_deleted')) : $this->failMsg(__('apis.something_went_wrong'));
    }

    protected function failedLoginResponse(string $message): JsonResponse
    {
        return $this->respondWithError($message, 401);
    }

    protected function blockedUserResponse($user): JsonResponse
    {
        return $this->respondWithError(__('auth.blocked'), 403, [
            'user' => UserResource::make($user),
        ]);
    }

    protected function inactiveUserResponse($user): JsonResponse
    {
        $user->sendVerificationCode();

        return $this->respondWithError(__('auth.not_active'), 403, [
            'user' => UserResource::make($user),
        ]);
    }

    protected function successfulLoginResponse($user): JsonResponse
    {
        $user->sendVerificationCode();
        $token = $user->createAuthToken();

        return $this->respondWithSuccess(__('auth.signed'), [
            'user' => UserResource::make($user)->additional(['token' => $token]),
        ]);
    }
}
