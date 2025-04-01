<?php

namespace App\Services\PaymentGateways;

use App\Contracts\PaymentGatewayContract;
use Illuminate\Http\Request;

class StripeService extends BasePaymentService implements PaymentGatewayContract
{
    public function sendPayment(Request $request): array
    {
        $data = [
            'amount' => $request->amount * 100, // Stripe uses cents
            'currency' => strtolower($request->currency ?? 'SAR'),
            'payment_method_types' => ['card'],
            'metadata' => [
                'payer_type' => $request->payer_type,
                'payer_id' => $request->payer_id,
                'payable_type' => get_class($request->payable),
                'payable_id' => $request->payable->id,
            ],
        ];

        $response = $this->buildRequest('POST', '/v1/payment_intents', $data);
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
                'client_secret' => $responseData['data']['client_secret']
            ];
        }

        return ['success' => false, 'url' => route('payment.failed')];
    }

    public function callBack(Request $request): bool
    {
        $paymentIntent = $request->payment_intent;
        $response = $this->buildRequest('GET', "/v1/payment_intents/{$paymentIntent}");

        $responseData = $response->getData(true);
        $status = $responseData['data']['status'] === 'succeeded';

        $this->updateTransaction(
            gatewayTransactionId: $paymentIntent,
            status: $status ? PayStatusEnum::PAID->value : PayStatusEnum::UNPAID->value,
            callbackData: $responseData
        );

        return $status;
    }

    protected function getGatewayName(): string
    {
        return 'stripe';
    }

    protected function getDefaultHeaders(): array
    {
        return [
            'Authorization' => 'Bearer '.config('payments.stripe.secret_key'),
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];
    }
}
