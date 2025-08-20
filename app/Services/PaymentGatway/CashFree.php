<?php

namespace App\Services\PaymentGatway;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CashFree
{
    private $baseUrl;
    private $clientId;
    private $clientSecret;
    private $apiVersion;

    public function __construct()
    {
        $this->baseUrl = 'https://api.cashfree.com/pg/orders/sessions';  // env('CASHFREE_ENV') === 'production'
        // ? 'https://api.cashfree.com/pg'
        // : 'https://sandbox.cashfree.com/pg';
       
        $this->clientId = '';
        $this->clientSecret = '';
        $this->apiVersion = '2025-01-01';
    }

    /**
     * Create a new payment order
     */
    public function createOrder($amount, $currency = 'INR', $customerData = [])
    {
        // try {
        $orderData = [
            'order_amount' => $amount,
            'order_currency' => $currency,
            'customer_details' => [
                'customer_id' => $customerData['customer_id'] ?? 'USER' . uniqid(),
                'customer_name' => $customerData['customer_name'] ?? 'Customer',
                'customer_email' => $customerData['customer_email'] ?? 'customer@example.com',
                'customer_phone' => $customerData['customer_phone'] ?? '+919876543210'
            ],
            'order_meta' => [
                'return_url' => url('/')
            ]
        ];
        // print_r(json_encode($orderData));
        // echo $this->baseUrl . '/orders';

        $response = Http::withHeaders([
            'X-Client-Secret' => $this->clientSecret,
            'X-Client-Id' => $this->clientId,
            'x-api-version' => $this->apiVersion,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->post($this->baseUrl . '/orders', $orderData);

        if ($response->successful()) {
print_r($response->json());
            $this->getPaymentLink($response->json()['payment_session_id']);
            return [
                'success' => true,
                'data' => $response->json()
            ];
        } else {
            return [
                'success' => false,
                'error' => $response->body(),
                'status' => $response->status()
            ];
        }

        // } catch (\Exception $e) {
        //     return [
        //         'success' => false,
        //         'error' => $e->getMessage()
        //     ];
        // }
    }


    private function getPaymentLink($payment_session_id){
        $orderData = [
            'payment_session_id' => $payment_session_id,
            'payment_method' => [
                'upi' => [
                    'channel' => 'link'
                ]
            ]
        ];
        $response = Http::withHeaders([
            'X-Client-Secret' => $this->clientSecret,
            'X-Client-Id' => $this->clientId,
            'x-api-version' => $this->apiVersion,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->post($this->baseUrl . '/orders/sessions', $orderData);

        print_r($response->json());
        die;
        if ($response->successful()) {
            return [
                'success' => true,
                'data' => $response->json()
            ];
        } else {
            return [
                'success' => false,
                'error' => $response->body(),
                'status' => $response->status()
            ];
        }
    }

    public function handleCallback(Request $request)
    {
        $orderId = $request->get('order_id');

        if (!$orderId) {
            return [
                'success' => false,
                'error' => 'Order ID not provided'
            ];
        }

        $orderResponse = $this->getOrder($orderId);

        if (!$orderResponse['success']) {
            return $orderResponse;
        }

        $order = $orderResponse['data'];

        // Check payment status
        if ($order['order_status'] === 'PAID') {
            return [
                'success' => true,
                'message' => 'Payment Successful!',
                'order_status' => $order['order_status'],
                'data' => $order
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Payment Pending or Failed.',
                'order_status' => $order['order_status'],
                'data' => $order
            ];
        }
    }
}
