<?php

namespace KorbaXchange;
class XChange extends API
{
    private function getHmac($data)
    {
        $client_id = env('EXCHANGE_DEFAULT_CLIENT_ID');
        $secret_key = env('EXCHANGE_DEFAULT_SECRET_KEY');
        $client_key = env('EXCHANGE_DEFAULT_CLIENT_KEY');
        $data = (gettype($data) == 'string') ? json_decode($data, true) : $data;
        $data = array_merge($data, ['client_id' => $client_id]);
        $message = '';
        $i = 0;
        ksort($data);
        foreach ($data as $key => $value) {
            $message .= ($i == 0) ? "{$key}={$value}" : "&{$key}={$value}";
            $i++;
        }
        $hmac_signature = hash_hmac('sha256', $message, $secret_key);
//        return ["Authorization: HMAC {$client_key}:{$hmac_signature}"];
        return "{$client_key}:{$hmac_signature}";
    }


    protected function add_optional_data(&$data, $optional_data)
    {
        foreach ($optional_data as $key => $value) {
            if ($optional_data[$key]) {
                $data[$key] = $value;
            }
        }
    }

    public function sendSms($message, $phoneNumber)
    {
        return parent::sendSms($message, $phoneNumber); // TODO: Change the autogenerated stub
    }

    public function collect(
        $customer_number, $amount, $transaction_id, $network_code, $callback_url,
        $vodafone_voucher_code = null, $description = null, $payer_name = null, $extra_info = null, $redirect_url = null)
    {
        $data = [
            'customer_number' => KorbaHelper::numberGHFormat($customer_number),
            'amount' => $amount,
            'transaction_id' => $transaction_id,
            'network_code' => $network_code,
            'callback_url' => $callback_url,
        ];
        $opt_data = [
            'vodafone_voucher_code' => $vodafone_voucher_code,
            'description' => $description,
            'payer_name' => $payer_name,
            'extra_info' => $extra_info,
            'redirect_url' => $redirect_url
        ];
        $this->add_optional_data($data, $opt_data);
        return parent::hitting_exchange(
            'collect/', $data, 'post', $this->getHmac($data));
    }
}