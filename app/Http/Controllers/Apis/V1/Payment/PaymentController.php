<?php

namespace App\Http\Controllers\Apis\V1\Payment;

use App\Contracts\PaymentGatewayContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\User\PaymentRequest;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentController extends Controller
{
    use ResponseTrait;

    public function __construct(protected PaymentGatewayContract $paymentGateway)
    {
    }

// app/Http/Controllers/Apis/V1/Payment/PaymentController.php

    public function paymentProcess(PaymentRequest $request)
    {
        $gatewayResponse = $this->paymentGateway->sendPayment($request->validated());

        $responseData = [
            'status' => $gatewayResponse['status'],
            'message' => $gatewayResponse['message'],
            'gateway' => $gatewayResponse['gateway'],
        ];

        if ($gatewayResponse['status'] === 'requires_redirect') {
            if ($gatewayResponse['gateway'] === 'hyperpay') {
                $responseData['checkout_id'] = $gatewayResponse['invoice_id'];
                $responseData['widget_url'] = config("payments.Hyperpay.CHECKOUT_URL").'?checkoutId='.$gatewayResponse['invoice_id'];
                $responseData['payment_form_url'] = route('payment.hyperpay.form', [
                    'transaction_id' => $gatewayResponse['invoice_id'],
                    'brand_type' => 'VISA MASTER AMEX'
                ]);

                // For Inertia.js requests
                if ($request->header('X-Inertia')) {
                    return redirect()->route('payment.hyperpay.inertia-form')->with([
                        'transaction_id' => $gatewayResponse['invoice_id'],
                        'brand_type' => 'VISA MASTER AMEX',
                        'widget_url' => $responseData['widget_url']
                    ]);
                }
            } else {
                // MyFatoorah
                $responseData['redirect_url'] = $gatewayResponse['payment_url'];
            }
        }

        return response()->json($responseData, $gatewayResponse['http_status'] ?? 200);
    }

    public function callBack(Request $request)
    {
        try {
            $success = $this->paymentGateway->callBack($request);

            return response()->json([
                'success' => $success,
                'message' => $success ? 'Payment succeeded' : 'Payment failed'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Callback processing failed',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function success()
    {
        return $this->successMsg(__('apis.payed_successfully'));
    }

    public function failed()
    {
        return $this->response('success', msg: __('apis.failed_payment'), code: 400);
    }

    public function status($transaction)
    {
        // Implement your transaction status check logic
        return response()->json(['status' => 'pending']); // Example
    }

    // app/Http/Controllers/PaymentController.php
    public function showHyperpayForm($transaction_id, $brand_type)
    {
        return view('payment.hyperpay.payment', [
            'transaction_id' => $transaction_id,
            'brand_type' => $brand_type
        ]);
    }
}
