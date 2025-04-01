<?php

namespace App\Services\PaymentGateways;

use App\Contracts\PaymentGatewayContract;
use App\Services\PaymentGateways\BasePaymentService;
use Illuminate\Http\Request;

class PaymobService extends BasePaymentService implements PaymentGatewayContract
{
    private string $authToken;

    public function sendPayment(Request $request): array
    { /* ... */
    }

    public function callBack(Request $request): bool
    { /* ... */
    }

    protected function getGatewayName(): string
    {
        return 'paymob';
    }

    protected function getDefaultHeaders(): array
    {
        $this->authenticate();
        return ['Authorization' => $this->authToken];
    }

    private function authenticate(): void
    {
        $response = $this->buildRequest('POST', '/auth/tokens', [
            'api_key' => config('payments.paymob.api_key')
        ]);
        $this->authToken = $response->getData(true)['data']['token'];
    }
}
