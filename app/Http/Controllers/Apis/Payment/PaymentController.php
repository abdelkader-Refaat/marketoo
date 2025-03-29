<?php

namespace App\Http\Controllers\Apis\Payment;

use App\Contracts\PaymentGatewayContract;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct( protected PaymentGatewayContract $paymentGateway)
    {
    }
    public function paymentProcess(Request $request)
    {
        $response= $this->paymentGateway->sendPayment($request);
        if($request->is('api/*')){
            return response()->json($response, 200);
        }
        return redirect($response['url']);
    }
    public function callBack(Request $request): \Illuminate\Http\RedirectResponse
    {
        $response = $this->paymentGateway->callBack($request);
        if ($response) {

            return redirect()->route('payment.success');
        }
        return redirect()->route('payment.failed');
    }


    public function success()
    {
        return view('payment.payment-success');
    }
    public function failed()
    {
        return view('payment.payment-failed');
    }

}
