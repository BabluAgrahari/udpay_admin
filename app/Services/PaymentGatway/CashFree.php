<?php

namespace App\Services\PaymentGatway;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CashFree
{
    private $baseUrl;
    private $clientId;
    private $clientSecret;
    private $apiVersion;

    public function __construct()
    {
        $this->apiVersion   = config('global.cashfree.api_version');
        $this->baseUrl      = config('global.cashfree.base_url');
        $this->clientId     = config('global.cashfree.client_id');
        $this->clientSecret = config('global.cashfree.client_secret');

    }

    public function createOrder($amount, $currency = 'INR', $customerData = [])
    {
        $orderData = [
            'order_amount' => number_format($amount, 2, '.', ''),
            'order_currency' => $currency,
            'order_id' => $customerData['order_id']??'',
            'customer_details' => [
                'customer_id' => (string)$customerData['customer_id'] ?? '',
                'customer_name' => $customerData['customer_name'] ?? '',
                'customer_email' => $customerData['customer_email'] ?? '',
                'customer_phone' => $customerData['customer_phone'] ?? ''
            ],
            'order_meta' => [
                'return_url' => $customerData['return_url'] ?? '',
                'notify_url' => $customerData['webhook_url'] ?? ''
            ]
        ];
        // withOptions([
        //     'verify' => false,
        // ])->
        Log::info('CashFree Payment Request -'.$customerData['order_id'], $orderData);
        $response = Http::withHeaders([
            'X-Client-Secret' => $this->clientSecret,
            'X-Client-Id' => $this->clientId,
            'x-api-version' => $this->apiVersion,
           

            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->post($this->baseUrl . '/orders', $orderData);

        Log::info('CashFree Payment Response -'.$customerData['order_id'], $response->json());
        if ($response->successful() && !empty($response->json()['order_status']) && $response->json()['order_status'] == 'ACTIVE') {
            $res = $response->json();
            return [
                'status' => true,
                'order_amount' => $res['order_amount'] ?? '',
                'cashfree_order_id' => $res['cf_order_id'] ?? '',
                'order_status' => $res['order_status'] ?? '',
                'payment_session_id' => $res['payment_session_id'] ?? '',
                // 'data' => $response->json()
            ];
        } elseif ($response->successful() && !empty($response->json()['error'])) {
            $res = $response->json();
            return [
                'status' => false,
                'msg' => $res['error'] ?? '',
                'code' => $response->status()
            ];
        } else {
            $res = $response->json();
            return [
                'status' => false,
                'msg' => $response->json()['message'] ?? 'Something went wrong in CashFree Side',
                'code' => $response->status()
            ];
        }
    }

    public function getPayment($order_id)
    {
        $response = Http::withHeaders([
            'X-Client-Secret' => $this->clientSecret,
            'X-Client-Id' => $this->clientId,
            'x-api-version' => $this->apiVersion,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->get($this->baseUrl . '/orders/' . $order_id . '/payments');
        Log::info('Get Payment Response -'.$order_id, [$response->json()]);

        if ($response->successful() && !empty($response->json()[0]['payment_status'])) {
            $res = $response->json();
            return [
                'status' => true,
                'bank_reference' => !empty($res[0]['bank_reference']) ? $res[0]['bank_reference'] : '',
                'cf_payment_id' => !empty($res[0]['cf_payment_id']) ? $res[0]['cf_payment_id'] : '',
                'is_captured' => !empty($res[0]['is_captured']) ? $res[0]['is_captured'] : false,
                'order_id' => !empty($res[0]['order_id']) ? $res[0]['order_id'] : '',
                'payment_status' => !empty($res[0]['payment_status']) ? $res[0]['payment_status'] : '',
                'payment_time' => !empty($res[0]['payment_time']) ? $res[0]['payment_time'] : '',
                'payment_method' => !empty($res[0]['payment_method']) ? $res[0]['payment_method'] : '',
                'code' => $response->status()
            ];
        } elseif ($response->successful() && $response->status() != '200') {
            $res = $response->json();
            return [
                'status' => false,
                'msg' => $res['message'] ?? 'Something went wrong in CashFree Side',
                'code' => $response->status()
            ];
        } else {
            $res = $response->json();
            return [
                'status' => false,
                'msg' => $response->json()['message'] ?? 'Something went wrong in CashFree Side',
                'code' => $response->status()
            ];
        }
    }
}
