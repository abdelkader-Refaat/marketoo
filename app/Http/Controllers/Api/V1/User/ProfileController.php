<?php

namespace app\Http\Controllers\Api\V1\User;


use App\Enums\AuthUpdatesAttributesEnum;
use App\Http\Controllers\Controller;
use app\Http\Requests\Api\V1\User\Profile\NewEmailRequest;
use app\Http\Requests\Api\V1\User\Profile\NewPhoneRequest;
use app\Http\Requests\Api\V1\User\Profile\UpdateProfileRequest;
use app\Http\Requests\Api\V1\User\Profile\VerifyCodeRequest;
use App\Http\Resources\Api\User\UserResource;
use App\Services\Profile\ProfileService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Modules\Users\App\Models\User;


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
        $updatedProfile = $this->profileService->editProfile($request->validated());
        return $this->jsonResponse(msg: __('apis.updated'), data: $updatedProfile);
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
