<?php

namespace App\Http\Requests\Api\User\Profile;

use App\Http\Requests\Api\BaseApiRequest;
use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

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
