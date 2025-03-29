<?php

namespace App\Services\Payment;


use App\Contracts\PaymentGatewayContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class MyFatoorahService extends BasePaymentService implements PaymentGatewayContract
{
    /**
     * Create a new class instance.
     */
    protected $api_key;
    public function __construct()
    {
            $this->base_url = config("services.myfatoorah.base_url");
            $this->api_key = config("services.myfatoorah.api_key");
            $this->header = [
            'accept' => 'application/json',
            "Content-Type" => "application/json",
            "Authorization" => "Bearer " . $this->api_key,
        ];
    }

    public function sendPayment(Request $request): array
    {
        $data = $request->all();
        $data['NotificationOption']="LNK";
        $data['Language']="en";
        $data['CallBackUrl']=$request->getSchemeAndHttpHost().'/api/payment/callback';
        $response = $this->buildRequest('POST', '/v2/SendPayment', $data);
        //handel payment response data and return it
        if($response->getData(true)['success']){
            return ['success' => true,'url' => $response->getData(true)['data']['Data']['InvoiceURL']];
        }
        return ['success' => false,'url'=>route('payment.failed')];
    }

    public function callBack(Request $request): bool
    {
        $data=[
            'KeyType' => 'paymentId',
            'Key' => $request->input('paymentId'),
        ];

        $response=$this->buildRequest('POST', '/v2/getPaymentStatus', $data);
        $response_data=$response->getData(true);
        Storage::put('myfatoorah_response.json',json_encode([
            'myfatoorah_callback_response'=>$request->all(),
            'myfatoorah_response_status'=>$response_data
        ]));
        return $response_data['data']['Data']['InvoiceStatus']==='Paid';
    }
}
