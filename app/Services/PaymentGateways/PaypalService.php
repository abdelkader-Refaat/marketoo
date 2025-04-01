<?php

namespace App\Services\PaymentGateways;

use App\Contracts\PaymentGatewayContract;
use App\Enums\PayStatusEnum;
use Illuminate\Http\Request;

class PaypalService extends BasePaymentService implements PaymentGatewayContract
{
    private ?string $accessToken = null;

    public function sendPayment(Request $request): array
    {
        $data = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => $request->currency ?? 'SAR',
                        'value' => $request->amount
                    ],
                    'reference_id' => uniqid(),
                ]
            ],
            'application_context' => [
                'return_url' => route('payment.callback'),
                'cancel_url' => route('payment.failed'),
            ]
        ];

        $response = $this->buildRequest('POST', '/v2/checkout/orders', $data);
        $responseData = $response->getData(true);

        if ($responseData['success']) {
            $this->createTransaction(
                amount: $request->amount,
                payableType: get_class($request->payable),
                payableId: $request->payable->id,
                gatewayTransactionId: $responseData['data']['id'],
                gatewayResponse: $responseData,
                payerType: $request->payer_type,
                payerId: $request->payer_id
            );

            return [
                'success' => true,
                'url' => collect($responseData['data']['links'])
                    ->where('rel', 'approve')
                    ->first()['href']
            ];
        }

        return ['success' => false, 'url' => route('payment.failed')];
    }

    public function callBack(Request $request): bool
    {
        $orderId = $request->token;
        $response = $this->buildRequest('POST', "/v2/checkout/orders/{$orderId}/capture");

        $responseData = $response->getData(true);
        $status = $responseData['data']['status'] === 'COMPLETED';

        $this->updateTransaction(
            gatewayTransactionId: $orderId,
            status: $status ? PayStatusEnum::PAID->value : PayStatusEnum::UNPAID->value,
            callbackData: $responseData
        );

        return $status;
    }

    protected function getGatewayName(): string
    {
        return 'paypal';
    }

    protected function getDefaultHeaders(): array
    {
        $this->authenticate();
        return [
            'Authorization' => 'Bearer '.$this->accessToken,
            'Content-Type' => 'application/json',
        ];
    }

    private function authenticate(): void
    {
        $authResponse = Http::withBasicAuth(
            config('payments.paypal.client_id'),
            config('payments.paypal.secret')
        )->post($this->baseUrl.'/v1/oauth2/token', [
            'grant_type' => 'client_credentials'
        ]);

        $this->accessToken = $authResponse->json('access_token');
    }
}
