<?php

namespace app\Http\Controllers\Api\V1\General;

use App\Http\Controllers\Controller;
use app\Http\Requests\Api\V1\General\Wallet\ChargeWalletRequest;
use App\Services\Core\WalletService;
use App\Traits\ResponseTrait;

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
