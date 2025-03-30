<?php

namespace app\Http\Requests\Api\V1\General\Wallet;

use app\Http\Requests\Api\V1\BaseApiRequest;

class ChargeWalletRequest extends BaseApiRequest
{

    public function rules()
    {
        return [
            'amount' => 'required|numeric|gt:0',
        ];
    }
}
