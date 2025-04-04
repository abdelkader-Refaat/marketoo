<?php

namespace App\Http\Requests\Api\V1\User\Individual;

use App\Http\Requests\Api\V1\BaseApiRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends BaseApiRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50'],
            'avatar' => 'nullable|image|mimes:'.$this->mimesImage().'|max:2048',
            'phone' => [
                'required', 'numeric',
                Rule::unique('users', 'phone')->whereNull('deleted_at'),
            ],
            'password' => ['required', 'confirmed', 'string', 'min:8', 'max:50'],
            'country_code' => 'required|string|max:5|exists:countries,key',
            'email' => [
                'required', 'email:rfc,dns',
                Rule::unique('users', 'email')->whereNull('deleted_at')
            ],
            'country_id' => ['required', Rule::exists('countries', 'id')],
            'city_id' => ['required', Rule::exists('cities', 'id')->where('country_id', $this->country_id)],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'phone' => fixPhone($this->phone),
            'country_code' => fixPhone($this->country_code),
            'is_accept_terms' => filter_var($this->is_accept_terms, FILTER_VALIDATE_BOOLEAN),
        ]);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'The given data was invalid.',
            'errors' => $validator->errors(),
        ], 422));
    }
}
