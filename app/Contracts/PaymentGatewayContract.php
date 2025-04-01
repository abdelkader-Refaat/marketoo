<?php

namespace App\Contracts;

use Illuminate\Http\Request;

interface PaymentGatewayContract
{
    public function sendPayment(array $data);

    public function callBack(Request $request);
}
