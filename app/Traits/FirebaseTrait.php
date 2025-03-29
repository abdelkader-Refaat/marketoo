<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

trait  FirebaseTrait
{
    use NotificationMessageTrait;

    public function sendFcmNotification($tokens, $data = [], $lang = 'ar')
    {
       return false;
        $apiurl = 'https://fcm.googleapis.com/v1/projects/' . config('app.project_id') . '/messages:send';   //replace "your-project-id" with...your project ID

        $headers = [
            'Authorization: Bearer ' . $this->getToken(),
            'Content-Type: application/json'
        ];

        // you can modify this based on your needs
        $notification = [];

        if (isset($data['body_' . $lang]) && !empty($data['body_' . $lang])) {
            $notification = [
                'title' => $this->getTitle($data['type'], $lang),
                'body' => $this->getBody($data, $lang),
            ];
        }

        $preparedData = $this->prepareData($data);
        $iosTokens = clone $tokens;

        $this->sendAndroidFcmNotifications($tokens->where(['device_type' => 'android'])->get()->pluck('device_id')->toArray(), $preparedData, $apiurl, $headers, $notification);
        $this->sendIosFcmNotifications($iosTokens->where(['device_type' => 'ios'])->get()->pluck('device_id')->toArray(), $preparedData, $apiurl, $headers, $notification);
    }

    private function prepareData($data)
    {
        foreach ($data as $key => $value) {
            if (is_int($value)) {
                $data[$key] = strval($value);
            } else if (is_bool($value)) {
                $data[$key] = strval($value);
            } else if (is_array($value)) {
                $data[$key] = json_encode($value);
            }
        }
        return $data;
    }

    private function sendAndroidFcmNotifications($tokens, $data, $url, $headers, $notification)
    {
        foreach ($tokens as $token) {
            $message = $this->getAndroidMessageFormat($token, $data, $notification);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));
            $result = curl_exec($ch);
            if ($result === FALSE) {
                //Failed
                die('Curl failed: ' . curl_error($ch));
            }

            curl_close($ch);
            Log::info($result);
            Log::info($message);
        }
    }

    private function getAndroidMessageFormat($token, $data, $notification)
    {
        $lang = $data['lang'] ?? 'ar';
        $result = [];
        if (isset($data['body_' . $lang]) && !empty($data['body_' . $lang])) {
            $result = [
                'title' => $this->getTitle($data['type'], $lang),
                'message' => $this->getBody($data, $lang),
                'type' => $data['type'],
                'order_id' => isset($data['order_id'])? $data['order_id'] : null
            ];
        } else {
            $result = [
                'title' => $this->getTitle($data['type'], $lang),
                'message' => $this->getBody($data, $lang),
                'type' => $data['type'],
                'order_id' => isset($data['order_id'])? $data['order_id'] : null
            ];
        }
        return [
            'message' => [
                'token' => $token,
                'data' => $result,
            ],
        ];
    }

    private function sendIosFcmNotifications($tokens, $data, $url, $headers, $notification)
    {
        foreach ($tokens as $token) {
            $message = $this->getIosMessageFormat($token, $data, $notification);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));
            $result = curl_exec($ch);
            if ($result === FALSE) {
                //Failed
                die('Curl failed: ' . curl_error($ch));
            }

            curl_close($ch);
        }

    }

    private function getIosMessageFormat($token, $data, $notification)
    {
        return [
            'message' => [
                'token' => $token,
                'notification' => $notification,
                'data' => $data,
                'apns' => [
//                    'mutable-content'=> 1,
                    'headers' => [
                        'apns-priority' => '10', // High priority for immediate delivery
                        'apns-push-type' => 'alert', // For alert notifications
                    ],
                    'payload' => [
                        'aps' => [
                            'alert' => $notification,
                            'sound' => 'default',
                        ],
                    ],
                ],
                // 'sound'             => 'default',
            ],
        ];
    }


    private function getToken()
    {

        // Read private key from service account details
        $secret = openssl_get_privatekey(config('app.private_key'));

        // $secret = openssl_get_privatekey(env('PRIVATE_KEY'));

        // Create the token header
        $header = json_encode([
            'typ' => 'JWT',
            'alg' => 'RS256'
        ]);

        // Get seconds since 1 January 1970
        $time = time();

        $payload = json_encode([
            "iss" => config('app.client_email'),
            "scope" => "https://www.googleapis.com/auth/firebase.messaging",
            "aud" => "https://oauth2.googleapis.com/token",
            "exp" => $time + 3600,
            "iat" => $time
        ]);

        // Encode Header
        $base64UrlHeader = $this->base64UrlEncode($header);

        // Encode Payload
        $base64UrlPayload = $this->base64UrlEncode($payload);

        // Create Signature Hash
        $result = openssl_sign($base64UrlHeader . "." . $base64UrlPayload, $signature, $secret, OPENSSL_ALGO_SHA256);

        // Encode Signature to Base64Url String
        $base64UrlSignature = $this->base64UrlEncode($signature);

        // Create JWT
        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

        //-----Request token------
        $client = new Client();

        $response = $client->post('https://oauth2.googleapis.com/token', [
            'form_params' => [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt
            ],
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ]
        ]);

        $responseBody = json_decode($response->getBody());

        if (!isset($responseBody->access_token)) {
            throw new \Exception("Failed to get access token: " . json_encode($responseBody));
        }

        return $responseBody->access_token;
    }

    private function base64UrlEncode($text)
    {
        return str_replace(
            ['+', '/', '='],
            ['-', '_', ''],
            base64_encode($text)
        );
    }
}
