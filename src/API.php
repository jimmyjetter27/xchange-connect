<?php

namespace KorbaXchange;

use Illuminate\Support\Facades\Http;

class API
{
    protected function hitting_exchange($endpoint, $payload, $method_type, $authorization, $timeout = 0, $connection_timeout = 300)
    {
        if (empty(env('EXCHANGE_URL'))) {
            return ['success' => false, 'message' => 'Exchange URL is not set'];
        }

        $url = env('EXCHANGE_URL') . '/' . $endpoint;
        $proxy_url = env('EXCHANGE_PROXY_URL');
        $payload = array_merge($payload, ['client_id' => env('EXCHANGE_DEFAULT_CLIENT_ID')]);

        $res = Http::withHeaders([
            'Authorization' => 'HMAC ' . $authorization,
            'Content-Type' => 'application/json'
        ])
            ->withOptions([
                // add proxy if proxy is set
                'proxy' => $proxy_url ?: null

            ])
            ->timeout($timeout)
            ->connectTimeout($connection_timeout)
            ->$method_type($url, $payload);
        return json_decode($res, true);
    }




    protected function sendSms($message, $phoneNumber)
    {

        if (empty(env('SMS_AUTH_TOKEN'))) {
            return ['success' => false, 'message' => 'SMS Auth Token is not set'];
        } elseif (empty(env('SMS_BASE_URL'))) {
            return ['success' => false, 'message' => 'SMS Base URL is not set'];
        } elseif (empty(env('SMS_SENDER_ID'))) {
            return ['success' => false, 'message' => 'SMS Sender ID is not set'];
        }


        $changeNumberFormat = KorbaHelper::numberIntFormat($phoneNumber);
        return $response = Http::withHeaders([
            'Authorization' => 'Token ' . env('SMS_AUTH_TOKEN'),
            'Content-Type' => 'application/json'
        ])
            ->withOptions([
                'debug' => fopen('php://stderr', 'w'),
                'verify' => false
            ])
            ->post(env('SMS_BASE_URL'), [
                'phone_number' => $changeNumberFormat,
                'message' => $message,
                'sender_id' => env('SMS_SENDER_ID')
            ]);
    }
}