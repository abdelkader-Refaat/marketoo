<?php

namespace app\Http\Requests\Api\V1\General\Complaints;

use App\Enums\ComplaintTypesEnum;
use app\Http\Requests\Api\V1\BaseApiRequest;

class StoreComplaintRequest extends BaseApiRequest
{

    public function rules()
    {
        $requiredIfAuth = auth()->check() ? 'nullable' : 'required';
        return [
            'user_name'          => [$requiredIfAuth, 'max:50'],
            'phone'              => [$requiredIfAuth, 'digits_between:9,15'],
            'country_code'       => [$requiredIfAuth, 'digits_between:2,5'],
            'complaint'          => 'required|max:500',
            'type'               => 'required|in:' . implode(',', array_column(ComplaintTypesEnum::cases(), 'value')),
        ];
    }

    public function prepareForValidation()
    {
        $requiredIfAuth = (bool)auth()->check();
        return $this->merge([
            'phone' => $requiredIfAuth ? null : $this->country_code . $this->phone,
            'user_name' => $requiredIfAuth ? null : $this->user_name,
            'complaintable_type' => $requiredIfAuth ? get_class(auth()->user()) : null,
            'complaintable_id' => $requiredIfAuth ? auth()->id() : null,
        ]);
    }
}
