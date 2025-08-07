<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderToProduct;
use App\Models\UserAddress;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Services\PaymentGatway\CashFree;

class CheckoutController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();

        $data['addresses'] = UserAddress::where('user_id', $user->id)->where('add_status', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        $data['user'] = $user;

        $cartItems = Cart::with('product')->where('user_id', $user->id)->get();
        $subtotal = $cartItems->sum(function ($item) {
            return $item->product ? $item->product->product_sale_price * $item->quantity : 0;
        });
        $total_mrp = $cartItems->sum(function ($item) {
            return $item->product && isset($item->product->mrp) ? $item->product->mrp * $item->quantity : 0;
        });
        $total_saving = $total_mrp - $subtotal;
        $total_items = $cartItems->sum('quantity');

        $data['subtotal'] = $subtotal;
        $data['total_mrp'] = $total_mrp;
        $data['total_saving'] = $total_saving;
        $data['total_items'] = $total_items;

        return view('Website.checkout', $data);
    }


    public function buyProduct($slug)
    {
        $product = Product::where('slug_url', $slug)->first();
        $subtotal = $product->product_sale_price;
        $total_mrp = $product->mrp;
        $total_saving = $total_mrp - $subtotal;
        $total_items = 1;

        $data['addresses'] = UserAddress::where('user_id', Auth::user()->id)->where('add_status', 1)->orderBy('created_at', 'desc')->get();
        $data['user'] = Auth::user();

        $data['subtotal'] = $subtotal;
        $data['total_mrp'] = $total_mrp;
        $data['total_saving'] = $total_saving;
        $data['total_items'] = $total_items;
        return view('Website.checkout', $data);
    }

    public function checkout(Request $request)
    {

        // $payment = new CashFree();
        // $res = $payment->createOrder(100, 'INR', ['customer_id' => '1234567890', 'customer_name' => 'John Doe', 'customer_email' => 'john.doe@example.com', 'customer_phone' => '+919876543210']);

        // print_r($res);
        // die;
        
        $validator = Validator::make($request->all(), [
            'address_id' => 'required|exists:user_address,id',
            'payment_gateway' => 'required|in:cashfree,razorpay',
        ], [
            'address_id.required' => 'Address is required.',
            'payment_gateway.required' => 'Payment gateway is required.',
            'payment_gateway.in' => 'Invalid payment gateway.',
        ]);

        if ($validator->fails()) {
            return $this->validationMsg($validator->errors()->first());
        }

        try {
            DB::beginTransaction();

            $user = Auth::user();

            $address = UserAddress::where('id', $request->address_id)->where('user_id', $user->id)->where('add_status', 1)->first();
            if (!$address) {
                return $this->failMsg('Invalid Address Id.');
            }

            $cartItems = Cart::with('product')->where('user_id', $user->id)->get();
            if ($cartItems->isEmpty()) {
                return $this->failMsg('Cart is Empty.');
            }
            $subtotal = $cartItems->sum(function ($item) {
                return $item->product ? $item->product->product_sale_price * $item->quantity : 0;
            });
            $total_gst = $cartItems->sum(function ($item) {
                return $item->product ? $item->product->igst * $item->product->product_sale_price * $item->quantity / 100 : 0;
            });

            $total_discount = $cartItems->sum(function ($item) {
                return $item->product ? $item->product->discount * $item->quantity : 0;
            });

            $order = new Order();
            $order->uid = $user->id;
            $order->order_id = 'ECOM-' . time() . '-' . $user->id;
            $order->address_id = $request->address_id;
            $order->amount = $subtotal;
            $order->total_qty = $cartItems->sum('quantity');
            $order->total_gst = $total_gst;
            $order->total_amount = $subtotal + $total_gst;
            $order->total_gross = $subtotal + $total_gst;
            $order->total_discount = $total_discount;
            $order->total_net_amount = ($subtotal + $total_gst) - $total_discount;
            $order->payment_method = 'prepaid';
            $order->payment_status = 'pending';
            $order->payment_gateway = $request->payment_gateway;
            $order->txn_id = '';
            $order->payment_response = json_encode([]);
            $order->status ='status';
            if (!$order->save()) {
                DB::rollBack();
                return $this->failMsg('Failed to create order.');
            }

            foreach ($cartItems as $cartItem) {
                if ($cartItem->product) {
                    $orderToProduct = new OrderToProduct();
                    $orderToProduct->order_id = $order->id;
                    $orderToProduct->product_id = $cartItem->product_id;
                    $orderToProduct->variant_id = $cartItem->variant_id;
                    $orderToProduct->attribute_id = null;
                    $orderToProduct->quantity = $cartItem->quantity;
                    $orderToProduct->price = $cartItem->product->product_sale_price;
                    $orderToProduct->gst = $cartItem->product->igst;
                    if (!$orderToProduct->save()) {
                        DB::rollBack();
                        return $this->failMsg('Failed to create order to product.');
                    }
                }
            }
            Cart::where('user_id', $user->id)->delete();

            DB::commit();

            return $this->successMsg('Order placed successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->failMsg('Failed to process order: ' . $e->getMessage());
        }
    }

    public function paymentProcess()
    {

        $payment = new CashFree();
        $res = $payment->createOrder(100, 'INR', ['customer_id' => '1234567890', 'customer_name' => 'John Doe', 'customer_email' => 'john.doe@example.com', 'customer_phone' => '+919876543210']);

        print_r($res);
        die;
    }
}
