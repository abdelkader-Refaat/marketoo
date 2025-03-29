<?php

namespace App\Http\Requests\Api\General\Wallet;

use App\Http\Requests\Api\BaseApiRequest;

class ChargeWalletRequest extends BaseApiRequest
{

    public function rules()
    {
        return [
            'amount' => 'required|numeric|gt:0',
        ];
    }
}
