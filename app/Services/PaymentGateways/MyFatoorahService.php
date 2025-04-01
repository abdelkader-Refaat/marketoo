<?php

namespace App\Services\PaymentGateways;

use App\Contracts\PaymentGatewayContract;
use App\Enums\MyFatoorahPaymentMethod;
use App\Enums\PayStatusEnum;
use Illuminate\Http\Request;

class MyFatoorahService extends BasePaymentService implements PaymentGatewayContract
{
    public function sendPayment(array $data): array
    {
        try {
            $method = MyFatoorahPaymentMethod::from($data['payment_method_id']);
            $paymentData = [
                'NotificationOption' => 'LNK',
                'InvoiceValue' => number_format($data['amount'], 2, '.', ''),
                'Currency' => $data['currency'] ?? 'SAR',
                'PaymentMethodId' => $method->value,
                'CustomerName' => $data['name'] ?? 'Customer',
                'CustomerEmail' => $data['email'] ?? 'customer@example.com',
                'CallBackUrl' => route('api.v1.payment.callback'),
                'ErrorUrl' => route('api.v1.payment.failed'),
                'Language' => app()->getLocale(),
                'DisplayCurrencyIso' => 'SAR',
            ];

            $apiResponse = $this->buildRequest('POST', '/v2/ExecutePayment', $paymentData);

            // Handle API communication errors
            if (!$apiResponse['success']) {
                return [
                    'gateway' => 'myfatoorah',
                    'status' => 'communication_error',
                    'message' => 'Could not connect to payment gateway',
                    'gateway_response' => $apiResponse['error'],
                    'http_status' => $apiResponse['http_status']
                ];
            }

            $responseData = $apiResponse['data'];

            // Successful payment initiation
            if ($responseData['IsSuccess'] && isset($responseData['Data']['PaymentURL'])) {
                return [
                    'gateway' => 'myfatoorah',
                    'status' => 'requires_redirect',
                    'message' => $responseData['Message'],
                    'payment_url' => $responseData['Data']['PaymentURL'],
                    'invoice_id' => $responseData['Data']['InvoiceId'],
                    'gateway_response' => $responseData,
                    'http_status' => 200
                ];
            }

            // Gateway-level errors
            return [
                'gateway' => 'myfatoorah',
                'status' => 'gateway_rejected',
                'message' => $responseData['Message'] ?? 'Payment initiation failed',
                'validation_errors' => $responseData['ValidationErrors'] ?? null,
                'gateway_response' => $responseData,
                'http_status' => 422
            ];
        } catch (\ValueError $e) {
            return [
                'gateway' => 'myfatoorah',
                'status' => 'invalid_request',
                'message' => 'Invalid payment method',
                'valid_methods' => array_column(MyFatoorahPaymentMethod::cases(), 'value'),
                'http_status' => 400
            ];
        } catch (\Exception $e) {
            return [
                'gateway' => 'myfatoorah',
                'status' => 'processing_error',
                'message' => 'Payment processing error',
                'debug' => config('app.debug') ? $e->getMessage() : null,
                'http_status' => 500
            ];
        }
    }

    public function callBack(Request $request): bool
    {
        $paymentId = $request->paymentId;
        $response = $this->buildRequest('POST', '/v2/getPaymentStatus', [
            'KeyType' => 'PaymentId',
            'Key' => $paymentId
        ]);

        $responseData = $response->getData(true);

        return $responseData['success'] && $responseData['data']['InvoiceStatus'] === 'Paid';
    }


    protected function getGatewayName(): string
    {
        return 'Myfatoorah';
    }


    protected function getDefaultHeaders(): array
    {
        $config = config("payments.{$this->gatewayName}");
        $apiToken = $this->testMode ? $config['TEST_API_TOKEN'] : $config['LIVE_API_TOKEN'];

        return [
            'Authorization' => 'Bearer '.$apiToken,
            'Content-Type' => 'application/json',
        ];
    }
}
