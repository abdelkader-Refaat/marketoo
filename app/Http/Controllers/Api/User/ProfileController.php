<?php

namespace App\Http\Controllers\Api\User;


use App\Models\AllUsers\User;
use App\Traits\ResponseTrait;
use App\Services\Profile\ProfileService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Enums\AuthUpdatesAttributesEnum;
use App\Http\Resources\Api\User\UserResource;
use App\Http\Requests\Api\User\Profile\NewEmailRequest;
use App\Http\Requests\Api\User\Profile\NewPhoneRequest;
use App\Http\Requests\Api\User\Profile\VerifyCodeRequest;
use App\Http\Requests\Api\User\Profile\UpdateProfileRequest;



class ProfileController extends Controller
{
    use ResponseTrait;

    private $profileService;

    public function __construct()
    {
        $this->profileService = new ProfileService(User::class);
    }

    public function profile(): JsonResponse
    {
        return $this->successData(new UserResource(auth()->user()));
    }

    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $this->profileService->editProfile($request->validated());
        return $this->successMsg(__('apis.updated'));
    }

    // change user phone
    public function changePhoneSendCode(): JsonResponse
    {
        $data = [
            'user' => auth()->user(),
            'type' => AuthUpdatesAttributesEnum::Phone,
            'attribute' => auth()->user()->phone,
            'country_code' => auth()->user()->country_code
        ];
        $result = $this->profileService
            ->storeAtUpdates(request: $data);
        return $this->response($result['key'], $result['msg'], $result['data']);
    }

    public function newPhoneSendCode(NewPhoneRequest $request): JsonResponse
    {
        $result = $this->profileService
            ->storeAtUpdates(request: $request);
        return $this->response($result['key'], $result['msg'], $result['data']);
    }

    public function changeEmailSendCode(): JsonResponse
    {
        $data = [
            'type' => AuthUpdatesAttributesEnum::Email->value,
            'user' => auth()->user(),
            'attribute' => auth()->user()->email
        ];
        $result = $this->profileService->storeAtUpdates(request: $data);
        return $this->response($result['key'], $result['msg'], $result['data']);
    }

    public function newEmailSendCode(NewEmailRequest $request): JsonResponse
    {
        $result = $this->profileService
            ->storeAtUpdates(request: $request);
        return $this->response($result['key'], $result['msg'], $result['data']);
    }

    protected function verifyCode(VerifyCodeRequest $request): JsonResponse
    {
        $result = $this->profileService->verifyCode(user: auth()->user(), request: $request);
        return $this->successMsg($result['msg']);
    }
}
