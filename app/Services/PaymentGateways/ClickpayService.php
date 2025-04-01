<?php

namespace App\Services\PaymentGateways;

use App\Contracts\PaymentGatewayContract;
use Illuminate\Http\Request;

class ClickpayService extends BasePaymentService implements PaymentGatewayContract
{
    public function sendPayment(Request $request): array
    { /* ... */
    }

    public function callBack(Request $request): bool
    { /* ... */
    }

    protected function getGatewayName(): string
    {
        return 'clickpay';
    }

    protected function getDefaultHeaders(): array
    {
        return [
            'Authorization' => config('payments.clickpay.server_key'),
            'Content-Type' => 'application/json',
        ];
    }
}
