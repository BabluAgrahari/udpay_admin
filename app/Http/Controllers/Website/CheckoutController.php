<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
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

        $cartItems = Cart::with('product')->get();
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

        $data['addresses'] = UserAddress::where('user_id',Auth::user()->id)->where('add_status', 1)->orderBy('created_at', 'desc')->get();
        $data['user'] = Auth::user();

        $data['subtotal'] = $subtotal;
        $data['total_mrp'] = $total_mrp;
        $data['total_saving'] = $total_saving;
        $data['total_items'] = $total_items;
        return view('Website.checkout', $data);
    }

    public function checkout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address_id' => 'required|exists:user_address,id',
            'payment_method' => 'required|in:cod,online',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $user = Auth::user();

            // Verify the address belongs to the user
            $address = UserAddress::where('id', $request->address_id)
                ->where('user_id', $user->id)
                ->where('add_status', 1)
                ->first();

            if (!$address) {
                return back()->with('error', 'Invalid address selected');
            }

            $cartItems = Cart::with('product')->get();

            if ($cartItems->isEmpty()) {
                return back()->with('error', 'Your cart is empty');
            }

            $subtotal = $cartItems->sum(function ($item) {
                return $item->product ? $item->product->product_sale_price * $item->quantity : 0;
            });

            // Create order
            $order = new Order();
            $order->user_id = $user->id;
            $order->order_number = 'ORD-' . time() . '-' . $user->id;
            $order->order_date = now();
            $order->total_amount = $subtotal;
            $order->final_amount = $subtotal;
            $order->payment_method = $request->payment_method;
            $order->payment_status = $request->payment_method === 'cod' ? 'pending' : 'pending';
            $order->order_status = $request->payment_method === 'cod' ? 'pending' : 'pending';
            $order->status = 1;

            // Store address information in delivery_address field
            $deliveryAddress = [
                'name' => $address->user_add_name,
                'mobile' => $address->user_add_mobile,
                'address' => $address->full_address,
                'city' => $address->user_city,
                'state' => $address->user_state,
                'zip' => $address->user_zip_code,
                'country' => $address->user_country,
                'landmark' => $address->land_mark,
            ];
            $order->delivery_address = json_encode($deliveryAddress);

            $order->save();

            // Store products information
            $products = [];
            foreach ($cartItems as $cartItem) {
                if ($cartItem->product) {
                    $products[] = [
                        'product_id' => $cartItem->product_id,
                        'quantity' => $cartItem->quantity,
                        'price' => $cartItem->product->product_sale_price,
                        'total' => $cartItem->product->product_sale_price * $cartItem->quantity,
                        'product_name' => $cartItem->product->product_name ?? '',
                    ];
                }
            }
            $order->products = json_encode($products);

            // Clear cart
            Cart::where('user_id', $user->id)->delete();

            DB::commit();

            if ($request->payment_method === 'cod') {
                return redirect()->route('order.success', $order->id)
                    ->with('success', 'Order placed successfully!');
            } else {
                // Redirect to payment gateway
                return redirect()->route('payment.process', $order->id);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to process order: ' . $e->getMessage());
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
