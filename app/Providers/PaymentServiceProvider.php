<?php

namespace App\Providers;

use App\Contracts\PaymentGatewayContract;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PaymentGatewayContract::class, function () {
            $gateway = ucfirst(config('payments.active_gateway'));
            $serviceClass = "App\\Services\\PaymentGateways\\{$gateway}Service";
            if (!class_exists($serviceClass)) {
                throw new \RuntimeException("Payment gateway service {$gateway} not found");
            }
            return new $serviceClass();
        });
    }
}
