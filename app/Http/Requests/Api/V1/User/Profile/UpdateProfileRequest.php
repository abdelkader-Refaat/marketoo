<?php

namespace app\Http\Requests\Api\V1\User\Profile;

use app\Http\Requests\Api\V1\BaseApiRequest;

class UpdateProfileRequest extends BaseApiRequest
{
    public function rules()
    {
        return [
            'name' => 'required|max:50',
            'avatar' => 'nullable|image|mimes:'.$this->mimesImage().'|max:2048',
            'city_id' => ['required', 'numeric', 'exists:cities,id'],
            'user' => 'nullable',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'user' => auth()->user(),
        ]);
    }
}
