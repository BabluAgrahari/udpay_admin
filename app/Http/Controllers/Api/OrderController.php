<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DealOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        //  try {
        // ->where('uid', Auth::user()->user_id)
        $orders = DealOrder::with('products', 'products.product', 'address')->get()->map(function ($row) {
            return $this->field($row);
        });
        return $this->recordsRes($orders);
        //  } catch (\Exception $e) {
        //     return $this->failRes($e->getMessage());
        //  }
    }

    private function field($record)
    {
        $data = [
            'id' => (int) $record->id ?? 0,
            'uid' => (string) $record->uid ?? '',
            'order_id' => (string) $record->order_id ?? 0,
            'address_id' => (int) $record->address_id ?? 0,
            'amount' => (float) $record->amount ?? 0,
            'total_qty' => (int) $record->total_qty ?? 0,
            'total_gst' => (float) $record->total_gst ?? 0,
            'total_amount' => (float) $record->total_amount ?? 0,
            'total_gross' => (float) $record->total_gross ?? 0,
            'total_discount' => (float) $record->total_discount ?? 0,
            'total_net_amount' => (float) $record->total_net_amount ?? 0,
            'delivery_mode' => (string) $record->delivery_mode ?? '',
            'shipping_charge' => (float) $record->shipping_charge ?? 0,
            'status' => (string) $record->status ?? '',
            'txn_id' => (string) $record->txn_id ?? '',
            'payment_method' => (string) $record->payment_method ?? '',
            'payment_status' => (string) $record->payment_status ?? '',
            'payment_gateway' => (string) $record->payment_gateway ?? '',
            'products' => $record->products->map(function ($product) {
                return [
                    'id' => (int) $product->id ?? 0,
                    'product_id' => (int) $product->product_id ?? 0,
                    'name' => (string) !empty($product->product->product_name) ? $product->product->product_name :'',
                    'quantity' => (int) $product->quantity ?? 0,
                    'image' => (string) !empty($product->product->image) ? $product->product->image :'',
                    'price' => (float) $product->price ?? 0,
                    'gst' => (float) $product->gst ?? 0,
                ];
            }),
            'created_at' => (string) $record->created_at ?? '',
            'updated_at' => (string) $record->updated_at ?? '',
        ];

        if (!empty($record->address)) {
            $data['address'] = [
                'id' => (int) $record->address->id ?? 0,
                'name' => (string) $record->address->user_add_name ?? '',
                'mobile' => (string) $record->address->user_add_mobile ?? '',
                'alternate_mobile' => (string) $record->address->alternate_mob ?? '',
                'address' => (string) $record->address->user_add_1 ?? '',
                'address_2' => (string) $record->address->user_add_2 ?? '',
                'landmark' => (string) $record->address->land_mark ?? '',
                'city' => (string) $record->address->user_city ?? '',
                'state' => (string) $record->address->user_state ?? '',
                'country' => (string) $record->address->user_country ?? '',
                'zipcode' => (string) $record->address->user_zip_code ?? '',
                'address_type' => (string) $record->address->address_type ?? '',
                'address_for' => (string) $record->address->address_for ?? '',
                'is_default' => (int) $record->address->is_default ?? 0,
            ];
        }

        return $data;
    }

    public function show($id)
    {
        try {
            // ->where('uid', Auth::user()->user_id)
            $order = DealOrder::with('products', 'products.product', 'address')->where('id', $id)->first();
            if (!$order)
                return $this->notFoundRes();

            return $this->recordRes($this->field($order));
        } catch (\Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }
}
