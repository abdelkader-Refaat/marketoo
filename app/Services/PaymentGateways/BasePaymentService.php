<?php

namespace App\Services\PaymentGateways;

use App\Enums\PayStatusEnum;
use App\Enums\PayTypeEnum;
use App\Models\PaymentTransaction;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

abstract class BasePaymentService
{

    protected string $baseUrl;
    protected array $headers = [];
    protected string $gatewayName;
    protected bool $testMode;

    public function __construct()
    {
        $this->gatewayName = $this->getGatewayName();
        $this->testMode = $this->isTestMode();
        $this->initializeConfig();
    }

    abstract protected function getGatewayName(): string;

    protected function isTestMode(): bool
    {
        $config = config("payments.{$this->gatewayName}");
        return isset($config['MODE'])
            ? $config['MODE'] === 'TEST'
            : ($config['TEST_MODE'] ?? true);
    }

    protected function initializeConfig(): void
    {
        $config = config("payments.{$this->gatewayName}");

        $this->baseUrl = $this->testMode
            ? $config['TEST_BASE_URL']
            : ($config['LIVE_BASE_URL'] ?? $config['BASE_URL']);

        $this->headers = $this->getDefaultHeaders();
    }

    abstract protected function getDefaultHeaders(): array;

    protected function createTransaction(
        float $amount,
        string $payableType,
        mixed $payableId,
        string $gatewayTransactionId,
        array $gatewayResponse,
        ?string $payerType = null,
        ?int $payerId = null,
        int $paymentMethod = PayTypeEnum::ONLINE->value,
        int $status = PayStatusEnum::PENDING->value
    ): PaymentTransaction {
        return PaymentTransaction::create([
            'payment_gateway' => $this->gatewayName,
            'gateway_transaction_id' => $gatewayTransactionId,
            'amount' => $amount,
            'currency_code' => 'SAR',
            'status' => $status,
            'payer_type' => $payerType,
            'payer_id' => $payerId,
            'payable_type' => $payableType,
            'payable_id' => $payableId,
            'payment_method' => $paymentMethod,
            'gateway_response' => $gatewayResponse,
        ]);
    }

    protected function updateTransaction(
        string $gatewayTransactionId,
        int $status,
        array $callbackData = null
    ): PaymentTransaction {
        $transaction = PaymentTransaction::where('gateway_transaction_id', $gatewayTransactionId)
            ->firstOrFail();

        $updateData = ['status' => $status];

        if ($status === PayStatusEnum::PAID->value) {
            $updateData['paid_at'] = now();
        }

        if ($callbackData) {
            $updateData['gateway_callback'] = $callbackData;
        }

        $transaction->update($updateData);
        return $transaction->fresh();
    }

    protected function buildRequest(
        string $method,
        string $endpoint,
        ?array $data = null,
        string $contentType = 'json'
    ): array {  // Changed return type from JsonResponse to array
        try {
            $fullUrl = rtrim($this->baseUrl, '/').'/'.ltrim($endpoint, '/');
            $response = Http::withHeaders($this->headers)
                ->timeout(30)
                ->send($method, $fullUrl, [
                    $contentType => $data
                ]);

            return [
                'http_status' => $response->status(),
                'success' => $response->successful(),
                'data' => $response->json(),
                'error' => null
            ];
        } catch (Exception $e) {
            return [
                'http_status' => 500,
                'success' => false,
                'data' => null,
                'error' => $e->getMessage()
            ];
        }
    }
}
