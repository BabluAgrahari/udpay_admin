<?php

namespace App\Services\Courier;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Icarry
{

    protected $base_url = 'https://www.icarry.in';
    public function __construct() {}

    private function generateToken()
    {
        $payload = [
            'username' => env('ICARRY_USERNAME', 'ela10436'),
            'key' => env('ICARRY_KEY', 'zUXCg05H717LSPQs0QBa8bUiLuBNvaX2bltwAX34KBEQMzxwiJws9ZbGl358IOwQAfZSwkcvz63DxNBbSweddbb1MeeVD3Ri3bdNj5CzEQrGxjC6E5U53OjlVeUUAhuNiYthR7lcqPDFE1EvpN0aKYOq5CQ8MM8FotXnhwW0iRrIh0kncuQD5daDHgfXzHBSQSzM3c9CSiAgmE9FXjUaiMDn5jiiBK06gXfSgeLfFaG3lqfbu9NkLQOajcQlW7nO'),
        ];
        $payload = json_encode($payload);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->base_url . '/api_login',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: OCSESSID=aae0a99e009e00be7bf00ff3cc; currency=INR; language=en-gb'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        $response = (object)json_decode($response, true);
        Log::info('Icarry Token Generation Response: ' . json_encode($response));

        if (!empty($response->api_token)) {
            return ['status' => true, 'token' => $response->api_token];
        } else {
            Log::error('Icarry Token Generation Failed: ' . json_encode($response));
            return ['status' => false, 'msg' => $response->error];
        }
    }



    public function calculateShippingCost($data)
    {
        $tokenResponse = $this->generateToken();
        if (!$tokenResponse['status']) {
            return ['status' => false, 'msg' => 'Failed to generate token'];
        }
        $token = $tokenResponse['token'];

        $request = (object) $data;

        $payload = [
            'length' => $request->length,
            'breadth' => $request->breadth,
            'height' => $request->height,
            'weight' => $request->weight,
            'destination_pincode' => $request->destination_pincode ?? '',
            'origin_pincode' => $request->origin_pincode ?? '',
            'destination_country_code' => $request->destination_country_code ?? 'IN',
            'origin_country_code' => $request->origin_country_code ?? 'IN',
            'shipment_mode' => $request->shipment_mode ?? '',
            'shipment_type' => $request->shipment_type ?? '',
            'shipment_value' => $request->shipment_value ?? 0,
        ];
        $payload = json_encode($payload);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->base_url . '/api_get_estimate&api_token=' . $token,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: OCSESSID=aae0a99e009e00be7bf00ff3cc; currency=INR; language=en-gb'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $response = (object)json_decode($response, true);

        Log::info('Icarry Shipping Cost Calculation Response: ' . json_encode($response));

        if (isset($response->success) && !empty($response->estimate)) {
            return [
                'status' => true,
                'msg' => 'Shipping cost calculated successfully',
                'data' => $response->data
            ];
        } else {
            return [
                'status' => false,
                'msg' => isset($response->error) ? $response->error : 'Failed to calculate shipping cost',
            ];
        }
    }

    public function singleBoxShipment($request)
    {
        $tokenResponse = $this->generateToken();
        if (!$tokenResponse['status']) {
            return ['status' => false, 'msg' => 'Failed to generate token'];
        }
        $token = $tokenResponse['token'];

        $payload = $this->payload($request);
        $payload = json_encode($payload);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->base_url . '/api_add_shipment_surface&api_token=' . $token,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: OCSESSID=aae0a99e009e00be7bf00ff3cc; currency=INR; language=en-gb'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $response = (object)json_decode($response, true);

        Log::info('Icarry Single Box Shipment Response: ' . json_encode($response));

        if (isset($response->success) && !empty($response->shipment_id)) {
            return [
                'status' => true,
                'msg' => 'Shipment created successfully',
                'data' => [
                    'shipment_id' => $response->shipment_id,
                    'courier_name' => $response->courier_name,
                    'courier_id' => $response->courier_id,
                    'partner_shipment_id' => $response->partner_shipment_id,
                    'awb' => $response->awb,
                    'tracking_url' => $response->tracking_url,
                    'pickup_id' => $response->pickup_id ?? '',
                    'client_order_id' => $response->client_order_id ?? '',
                    'cost_estimate' => $response->cost_estimate ?? 0
                ]
            ];
        } else {
            return [
                'status' => false,
                'msg' => isset($response->error) ? $response->error : 'Failed to create shipment',
            ];
        }
    }


    private function payload($request)
    {

        $request = (object) $request;
        $payload = [
            'pickup_address_id' => $request->pickup_address_id ?? '',
            'return_address_id' => $request->return_address_id ?? '',
            'rto_address_id' => $request->rto_address_id ?? '',
            'client_order_id' => $request->client_order_id ?? '',
            'courier_id' => $request->courier_id,
            'consignee' => [
                'name' => $request->consignee_name,
                'mobile' => $request->consignee_mobile,
                'alt_mobile' => $request->consignee_alt_mobile ?? '',
                'address' => $request->consignee_address,
                'city' => $request->consignee_city,
                'state' => $request->consignee_state,
                'pincode' => $request->consignee_pincode,
                'country_code' => $request->consignee_country_code ?? 'IN',
            ],
            'parcel' => [
                'type' => $request->parcel_type ?? 'COD',
                'value' => $request->parcel_value ?? 0,
                'currency' => $request->parcel_currency ?? 'INR',
                'contents' => $request->parcel_contents ?? '',
                'items' => [
                    'name' => $request->item_name,
                    'pid' => $request->item_pid ?? 0,
                    'price' => $request->item_price ?? 0,
                    'quantity' => $request->item_quantity ?? 1
                ],
                'dimensions' => [
                    'length' => $request->parcel_length,
                    'breadth' => $request->parcel_breadth,
                    'height' => $request->parcel_height,
                    'unit' => $request->parcel_unit ?? 'cm'
                ],
                'weight' => [
                    'weight' => $request->parcel_weight,
                    'unit' => $request->parcel_weight_unit ?? 'gm'
                ]
            ]
        ];

        return $payload;
    }



    public function cancelShipment($shipment_id)
    {
        $tokenResponse = $this->generateToken();
        if (!$tokenResponse['status']) {
            return ['status' => false, 'msg' => 'Failed to generate token'];
        }
        $token = $tokenResponse['token'];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->base_url . '/api_cancel_shipment&api_token=' . $token,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS => '{
                    "shipment_id":"' . $shipment_id . '"
                }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: OCSESSID=aae0a99e009e00be7bf00ff3cc; currency=INR; language=en-gb'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $response = (object)json_decode($response, true);

        Log::info('Icarry Cancel Shipment Response: ' . json_encode($response));
        if (isset($response->success) && !empty($response->shipment_id)) {
            return [
                'status' => true,
                'msg' => $response->success ?? 'Shipment cancelled successfully'
            ];
        } else {
            return [
                'status' => false,
                'msg' => isset($response->error) ? $response->error : 'Failed to cancel shipment',
            ];
        }
    }


    public function shipmentStatus($shipment_id)
    {
        $tokenResponse = $this->generateToken();
        if (!$tokenResponse['status']) {
            return ['status' => false, 'msg' => 'Failed to generate token'];
        }
        $token = $tokenResponse['token'];

        $payload = [
            'shipment_ids' => [$shipment_id]
        ];
        $payload = json_encode($payload);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->base_url . '/api_shipment_status_sync&api_token=' . $token,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: OCSESSID=aae0a99e009e00be7bf00ff3cc; currency=INR; language=en-gb'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = (object)json_decode($response, true);

        Log::info('Icarry Shipment Status Response: ' . json_encode($response));

        if (isset($response->success) && !empty($response->msg)) {
            $data = $response->msg[0] ?? [];
            return [
                'status' => true,
                'msg' => $response->success,
                'data' => [
                    'shipment_id' => $data['shipment_id'] ?? '',
                    'status' => $this->statusLabel($data['status'] ?? 0),
                    'status_code' => $data['status'] ?? '',
                    'date_delivered' => $data['date_delivered'] ?? '',
                    'date_picked' => $data['date_picked'] ?? ''
                ]
            ];
        } else {
            return [
                'status' => false,
                'msg' => isset($response->error) ? $response->error : 'Failed to get shipment status',
            ];
        }
    }
    private function statusLabel($statsCode)
    {
        $statusNames = [
            1  => 'Pending Pickup',
            2  => 'Processing',
            3  => 'Shipped',
            7  => 'Canceled',
            12 => 'Damaged',
            14 => 'Lost',
            16 => 'Voided',
            21 => 'Delivered',
            22 => 'In Transit',
            23 => 'Returned to Origin',
            24 => 'Manifested',
            25 => 'Pickup Scheduled',
            26 => 'Out For Delivery',
            27 => 'Pending Return',
        ];

        return isset($statusNames[$statsCode]) ? $statusNames[$statsCode] : 'Unknown Status';
    }




    public function trackShipment($shipment_id)
    {

        $tokenResponse = $this->generateToken();
        if (!$tokenResponse['status']) {
            return ['status' => false, 'msg' => 'Failed to generate token'];
        }
        $token = $tokenResponse['token'];

        $payload = [
            'shipment_ids' => [$shipment_id]
        ];
        $payload = json_encode($payload);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->base_url . '/api_track_shipment&api_token=' . $token,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: OCSESSID=aae0a99e009e00be7bf00ff3cc; currency=INR; language=en-gb'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = (object)json_decode($response, true);
        Log::info('Icarry Track Shipment Response: ', [$response]);

        if (isset($response->success) && !empty($response->msg)) {
            return [
                'status' => true,
                'msg' => $response->success,
                'data' => [
                    'shipment_id' => $response->shipment_id ?? '',
                    'status' => $response->status ?? '',
                    'location' => $response->location ?? '',
                    'datetime' => $response->datetime ?? '',
                    'tracking_details' => $response->details ?? []
                ]
            ];
        } else {
            return [
                'status' => false,
                'msg' => isset($response->error) ? $response->error : 'Failed to get shipment status',
            ];
        }
    }

    public function webhookResponse($request)
    {
        if (empty($request))
            return [
                'status' => false,
                'msg' => 'Invalid request data'
            ];

        $status_code = $request->status ?? 0;
        $status = $this->statusLabel($status_code);
        $data = [
            'status' => $status,
            'status_code' => $status_code,
            'awb' => $request->awb ?? '',
        ];

        return $data;
    }
}
