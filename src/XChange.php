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

    public function networkLookup($phoneNumber)
    {
        return parent::networkLookup($phoneNumber); // TODO: Change the autogenerated stub
    }

    public function phoneNumberNameLookup($phoneNumber)
    {
        return parent::phoneNumberNameLookup($phoneNumber); // TODO: Change the autogenerated stub
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

    public function ovaBalance()
    {
        return parent::hitting_exchange('get_ova_balance/', [], 'post', $this->getHmac([]));
    }


    public function disburse(
        $customer_number, $amount, $transaction_id, $network_code, $callback_url,
        $description = null, $extra_info = null, $bank_account_number = null,
        $bank_name = null, $bank_branch_name = null, $payer_name = null, $payer_mobile = null)
    {
        $data = [
            'customer_number' => $customer_number,
            'amount' => $amount,
            'transaction_id' => $transaction_id,
            'network_code' => $network_code,
            'callback_url' => $callback_url
        ];
        $opt_data = [
            'description' => $description,
            'extra_info' => $extra_info,
            'bank_account_number' => $bank_account_number,
            'bank_name' => $bank_name,
            'bank_branch_name' => $bank_branch_name,
            'payer_name' => $payer_name,
            'payer_mobile' => $payer_mobile
        ];
        $this->add_optional_data($data, $opt_data);
        return parent::hitting_exchange('disburse/', $data, 'post', $this->getHmac($data));
    }


    public function top_up(
        $customer_number, $amount, $transaction_id, $network_code, $callback_url,
        $description = null, $payer_name = null, $extra_info = null)
    {
        $data = [
            'customer_number' => $customer_number,
            'amount' => $amount,
            'transaction_id' => $transaction_id,
            'network_code' => $network_code,
            'callback_url' => $callback_url
        ];
        $opt_data = [
            'description' => $description,
            'payer_name' => $payer_name,
            'extra_info' => $extra_info
        ];
        $this->add_optional_data($data, $opt_data);
        return parent::hitting_exchange('topup/', $data, 'post', $this->getHmac($data));
    }


    public function airteltigo_purchase(
        $customer_number, $transaction_id, $product_id, $amount, $callback_url,
        $description = null, $payer_name = null, $extra_info = null)
    {
        $data = $this->internet_product_data(
            $customer_number, $transaction_id, $product_id, $amount, $callback_url,
            $description, $payer_name, $extra_info);
        return parent::hitting_exchange('airteltigo_data_topup/', $data, 'post', $this->getHmac($data));
    }


    public function glo_purchase($customer_number, $bundle_id, $amount, $transaction_id, $callback_url, $description = null)
    {
        $data = [
            'customer_number' => $customer_number,
            'bundle_id' => $bundle_id,
            'amount' => $amount,
            'transaction_id' => $transaction_id,
            'callback_url' => $callback_url
        ];
        $opt_data = ['description' => $description];
        $this->add_optional_data($data, $opt_data);

        return parent::hitting_exchange('glo_data_purchase/', $data, 'post', $this->getHmac($data));
    }

    private function internet_product_data(
        $customer_number, $transaction_id, $product_id, $amount, $callback_url,
        $description = null, $payer_name = null, $extra_info = null)
    {
        $data = [
            'customer_number' => $customer_number,
            'transaction_id' => $transaction_id,
            'product_id' => $product_id,
            'amount' => $amount,
            'callback_url' => $callback_url
        ];
        $opt_data = [
            'description' => $description,
            'payer_name' => $payer_name,
            'extra_info' => $extra_info
        ];
        $this->add_optional_data($data, $opt_data);
        return $data;
    }
}
