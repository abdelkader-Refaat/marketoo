<?php

namespace App\Http\Controllers\Apis\V1\Payment;

use App\Contracts\PaymentGatewayContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\User\PaymentRequest;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    use ResponseTrait;

    public function __construct(protected PaymentGatewayContract $paymentGateway)
    {
    }

    public function paymentProcess(PaymentRequest $request)
    {
        $gatewayResponse = $this->paymentGateway->sendPayment($request->validated());
        // Standardize the response format
        $responseData = [
            'status' => $gatewayResponse['status'],
            'message' => $gatewayResponse['message'],
            'gateway' => $gatewayResponse['gateway']
        ];

        // Add additional data based on status
        switch ($gatewayResponse['status']) {
            case 'requires_redirect':
                $responseData['redirect_url'] = $gatewayResponse['payment_url'];
                $responseData['invoice_id'] = $gatewayResponse['invoice_id'];
                break;

            case 'invalid_request':
                $responseData['valid_methods'] = $gatewayResponse['valid_methods'];
                break;

            case 'gateway_rejected':
                if ($gatewayResponse['validation_errors']) {
                    $responseData['errors'] = $gatewayResponse['validation_errors'];
                }
                break;
        }
        return response()->json($responseData, $gatewayResponse['http_status']);
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
}
