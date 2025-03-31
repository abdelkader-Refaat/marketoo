<?php

namespace App\Http\Requests\Api\V1\User\Individual;

use App\Http\Requests\Api\V1\BaseApiRequest;
use Illuminate\Validation\Rule;
use Modules\Users\App\Models\User;

class RegisterRequest extends BaseApiRequest
{

    public function rules(): array
    {
        return [
            'name' => ['required', 'max:50'],
            'avatar' => 'nullable|mimes:'.$this->mimesImage(),
            'phone' => [
                'required', 'numeric',
                Rule::unique('users', 'phone')
                    ->whereNull('deleted_at'),
            ],
            'password' => ['required', 'confirmed', 'string', 'min:6', 'max:50'],
            'country_code' => 'required|numeric|digits_between:1,5',
            'email' => [
                'required', 'email:rfc,dns',
                Rule::unique('users', 'email')->whereNull('deleted_at')->ignore(optional(auth()->user())->id)
            ],
            'country_id' => ['required', Rule::exists('countries', 'id')],
            'city_id' => ['required', Rule::exists('cities', 'id')->where('country_id', $this->country_id)],
            'is_accept_terms' => ['required', 'in:1,true'],
        ];
    }


}
