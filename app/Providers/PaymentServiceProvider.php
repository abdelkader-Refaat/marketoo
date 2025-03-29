<?php

namespace App\Providers;

use App\Contracts\PaymentGatewayContract;
use App\Services\Payment\MyFatoorahService;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PaymentGatewayContract::class, function ($app) {
            $gatewayType = request()->get('gateway_type')?? 'myfatoorah';
            return match ($gatewayType) {
                'myfatoorah' => new MyFatoorahService(),
                default => throw new \Exception("Unsupported gateway type: " . ($gatewayType ?? 'none')),
            };
        });
    }


    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
