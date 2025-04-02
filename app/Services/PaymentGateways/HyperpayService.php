<?php

namespace App\Services\PaymentGateways;

use App\Contracts\PaymentGatewayContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HyperpayService extends BasePaymentService implements PaymentGatewayContract
{
    public function sendPayment(array $data): array
    {
        try {
            $paymentData = [
                'entityId' => $this->testMode
                    ? config("payments.{$this->gatewayName}.TEST_ENTITY_ID")
                    : config("payments.{$this->gatewayName}.LIVE_ENTITY_ID"),
                'amount' => number_format($data['amount'], 2, '.', ''),
                'currency' => $data['currency'] ?? 'SAR',
                'paymentType' => 'DB',
                'merchantTransactionId' => uniqid(),
                'notificationUrl' => route('api.v1.payment.callback'),
                'customer.email' => $data['email'] ?? 'customer@example.com',
                'customer.givenName' => $data['name'] ?? 'Customer',
            ];

            $apiResponse = $this->buildRequest('POST', '/v1/checkouts', $paymentData, 'form_params');

            if (!$apiResponse['success']) {
                return [
                    'gateway' => 'hyperpay',
                    'status' => 'communication_error',
                    'message' => 'Could not connect to payment gateway',
                    'http_status' => $apiResponse['http_status']
                ];
            }

            $responseData = $apiResponse['data'];

            if (isset($responseData['id'])) {
                // Map HyperPay response to match MyFatoorah's expected format
                return [
                    'gateway' => 'hyperpay',
                    'status' => 'requires_redirect',
                    'message' => 'Payment widget ready for initialization',
                    'payment_url' => config("payments.{$this->gatewayName}.CHECKOUT_URL").'?checkoutId='.$responseData['id'],
                    'invoice_id' => $responseData['id'],
                    'widget_required' => true, // New flag to indicate widget integration
                    'http_status' => 200
                ];
            }

            return [
                'gateway' => 'hyperpay',
                'status' => 'gateway_rejected',
                'message' => $responseData['result']['description'] ?? 'Payment initiation failed',
                'http_status' => 422
            ];
        } catch (\Exception $e) {
            return [
                'gateway' => 'hyperpay',
                'status' => 'processing_error',
                'message' => 'Payment processing error',
                'http_status' => 500,
                'debug' => config('app.debug') ? $e->getMessage() : null
            ];
        }
    }

    public function callBack(Request $request): bool
    {
        try {
            $checkoutId = $request->id;
            $entityId = $this->testMode
                ? config("payments.{$this->gatewayName}.TEST_ENTITY_ID")
                : config("payments.{$this->gatewayName}.LIVE_ENTITY_ID");

            $apiResponse = $this->buildRequest('GET', "/v1/checkouts/{$checkoutId}/payment", [
                'entityId' => $entityId
            ]);

            if (!$apiResponse['success']) {
                return false;
            }
            $responseData = $apiResponse['data'];
            $successCodes = ['000.000.000', '000.100.110', '000.100.111', '000.100.112'];
            return in_array($responseData['result']['code'], $successCodes);
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function getGatewayName(): string
    {
        return ucfirst(config("payments.active_gateway"));
    }

    protected function getDefaultHeaders(): array
    {
        $config = config("payments.{$this->gatewayName}");
        $apiToken = $this->testMode ? $config['TEST_API_TOKEN'] : $config['LIVE_API_TOKEN'];
        return [
            'Authorization' => 'Bearer '.$apiToken,
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Accept' => 'application/json', // Explicitly request JSON response
        ];
    }
}
