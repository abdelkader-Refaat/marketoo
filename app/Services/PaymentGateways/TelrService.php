<?php

namespace App\Services\PaymentGateways;

use App\Contracts\PaymentGatewayContract;
use Illuminate\Http\Request;

class TelrService extends BasePaymentService implements PaymentGatewayContract
{
    public function sendPayment(Request $request): array
    {
        $data = [
            'ivp_method' => 'create',
            'ivp_store' => config('payments.telr.store_id'),
            'ivp_authkey' => config('payments.telr.auth_key'),
            // ... other required fields
        ];
        // ... implementation
    }

    public function callBack(Request $request): bool
    { /* ... */
    }

    protected function getGatewayName(): string
    {
        return 'telr';
    }

    protected function getDefaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
        ];
    }
}
