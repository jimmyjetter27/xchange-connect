<?php

namespace KorbaXchange;

use Illuminate\Support\Facades\Http;
use Korba\Util;

class API
{
    protected function hitting_exchange($endpoint, $payload, $method_type, $authorization, $timeout = 0, $connection_timeout = 300)
    {
        if (empty(env('EXCHANGE_URL')))
        {
            return ['success' => false, 'message' => 'Exchange URL is not set'];
        }

        $url = env('EXCHANGE_URL').'/'.$endpoint;
        $proxy_url = env('EXCHANGE_PROXY_URL');
        $payload = array_merge($payload, ['client_id' => env('EXCHANGE_DEFAULT_CLIENT_ID')]);

        $res = Http::withHeaders([
            'Authorization' => 'HMAC '.$authorization,
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
}