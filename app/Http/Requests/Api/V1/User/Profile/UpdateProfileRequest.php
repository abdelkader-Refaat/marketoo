<?php

namespace app\Http\Requests\Api\V1\User\Profile;

use app\Http\Requests\Api\V1\BaseApiRequest;
use App\Http\Requests\BaseRequest;

class UpdateProfileRequest extends BaseApiRequest
{
    public function rules()
    {
        return [
            'name'    => 'required|max:50',
            'image'   => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'city_id' => ['required', 'numeric', 'exists:cities,id'],
            'user'    => 'nullable',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'user' => auth()->user(),
        ]);
    }
}
