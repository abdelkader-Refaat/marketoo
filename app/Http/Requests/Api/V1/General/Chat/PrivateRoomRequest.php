<?php

namespace app\Http\Requests\Api\V1\General\Chat;

use app\Http\Requests\Api\V1\BaseApiRequest;

class PrivateRoomRequest extends BaseApiRequest {
  public function rules() {
    return [
      'memberable_id'   => 'required|numeric',
      'memberable_type' => "required|in:User,Admin,Provider,Merchant,Delegate",
    ];
  }
}
