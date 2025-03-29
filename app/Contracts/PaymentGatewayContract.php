<?php

namespace App\Contracts;

use Illuminate\Http\Request;

interface PaymentGatewayContract
{
    public function sendPayment(Request $request);

    public function callBack(Request $request);
}
