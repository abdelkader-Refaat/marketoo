<?php

namespace App\Services\PaymentGateways;

use App\Contracts\PaymentGatewayContract;
use Illuminate\Http\Request;

class AlrajhibankService extends BasePaymentService implements PaymentGatewayContract
{
    public function sendPayment(Request $request): array
    { /* ... */
    }

    public function callBack(Request $request): bool
    { /* ... */
    }

    protected function getGatewayName(): string
    {
        return 'alrajhibank';
    }

    protected function getDefaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$this->getAccessToken(),
        ];
    }

    private function getAccessToken(): string
    {
        // Implement OAuth2 token retrieval
    }
}
