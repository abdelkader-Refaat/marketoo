<?php

namespace App\Http\Requests\Api;

use App\Traits\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseApiRequest extends FormRequest
{
    use ResponseTrait;

    public function authorize()
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        if (in_array(array_keys($validator->errors()->toArray())[0], ['blocked', 'needActive','go_to_complete_data'])) {
            $key = array_keys($validator->errors()->toArray())[0];
            $code = 403;
        } else {
            $code = 422;
            $key = 'fail';
        }
        throw new HttpResponseException($this->jsonResponse(msg: $validator->errors()->first(), code: $code, error: true,errors: $validator->errors()->toArray(),key: $key));
    }
}
