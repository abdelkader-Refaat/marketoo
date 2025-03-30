<?php

namespace app\Http\Controllers\Api\V1\User\Individual;

use App\Http\Controllers\Controller;
use app\Http\Requests\Api\V1\User\Individual\ActivateRequest;
use app\Http\Requests\Api\V1\User\Individual\LoginRequest;
use app\Http\Requests\Api\V1\User\Individual\RegisterRequest;
use app\Http\Requests\Api\V1\User\Individual\ResendCodeRequest;
use App\Http\Resources\Api\User\UserResource;
use App\Http\Resources\RegisterResource;
use App\Services\AllUsers\ClientService;
use App\Services\Auth\AuthService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Modules\Users\App\Models\User;

class AuthController extends Controller
{

    use ResponseTrait;


    private $authService, $userService;

    public function __construct()
    {
        $this->authService = new AuthService(User::class);
        $this->userService = new ClientService();
    }

        public function register(RegisterRequest $request)
        {
            try {
                $user = User::create($request->validated());
                $token = $user->createToken('auth_token')->plainTextToken;
                return response()->json(RegisterResource::make($user, $token), 201)->header('Authorization', "Bearer {$token}");
            }catch(\Exception $exception){
                return response()->json(['error'=>$exception->getMessage()]);
            }
        }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $data = $this->userService->findOrNew(data: $request->only(['phone', 'country_code', 'type']))['data'];
            $data = $this->authService->loginViaPhone($data);
            return $this->jsonResponse(msg: $data['msg'], data: $data['data'] ?? [], key: $data['key']);
        } catch (\Exception $e) {
            return $this->jsonResponse(msg: $e->getMessage(), code: 500, error: true, errors: ['file' => $e->getFile(), 'line' => $e->getLine()]);
        }
    }

    public function activate(ActivateRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = $this->authService->activate($request->validated());
            $go_to_register_step = $this->userService->isRegistered($data['data']['user']);
            DB::commit();
            return $this->jsonResponse(msg: $data['msg'], data: [
                'user'                => UserResource::make($data['data']['user'])->setToken($data['data']['token']),
                'go_to_register_step' => $go_to_register_step
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->jsonResponse(msg: $e->getMessage(), code: $e->getCode(), error: true, errors: ['file' => $e->getFile(), 'line' => $e->getLine()]);
        }
    }

    public function completeData(RegisterRequest $request): JsonResponse
    {
        $data = $this->authService->register(request: $request->validated());
        return $this->jsonResponse(msg: $data['msg'], data: [
            'user' => UserResource::make($data['user'])->setToken($request->headers->get('Authorization'))
        ]);
    }

    public function resendCode(ResendCodeRequest $request): JsonResponse
    {
        $this->authService->resendCode($request->validated());

        return $this->response('success', __('auth.code_re_send'));
    }


}
