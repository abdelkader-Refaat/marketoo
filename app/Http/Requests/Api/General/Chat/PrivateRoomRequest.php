<?php

namespace App\Http\Requests\Api\General\Chat;

use App\Http\Requests\Api\BaseApiRequest;

class PrivateRoomRequest extends BaseApiRequest {
  public function rules() {
    return [
      'memberable_id'   => 'required|numeric',
      'memberable_type' => "required|in:User,Admin,Provider,Merchant,Delegate",
    ];
  }
}
