<?php

namespace App\Http\Controllers\Api\General;

use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Services\Core\WalletService;
use App\Http\Requests\Api\General\Wallet\ChargeWalletRequest;

class WalletController extends Controller
{
    use ResponseTrait ;


    public function show(){
        $wallet = auth()->user()->wallet ;
        return $this->successData([
            'balance'           => (float) $wallet->balance ,
            'available_balance' => (float) $wallet->available_balance,
            'pending_balance'   => (float) $wallet->pending_balance ,
        ]);
    }


    function charge(ChargeWalletRequest $request){
       return (new WalletService())->charge(auth()->user(), $request->amount);
    }
}
