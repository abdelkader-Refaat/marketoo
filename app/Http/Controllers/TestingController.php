<?php

namespace App\Http\Controllers;

use App\Services\PaymentGateways\PaymobService;
use App\Services\PaymentGateways\PaypalService;
use Illuminate\Http\Request;

class TestingController extends Controller
{
    public private(set) string $version = '8.4';

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
//        $test = PaymobService::call
    }


}
