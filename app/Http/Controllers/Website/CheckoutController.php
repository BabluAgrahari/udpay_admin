<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\ApOrder;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderToProduct;
use App\Models\Product;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Wallet;
use App\Services\PaymentGatway\CashFree;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $data['addresses'] = UserAddress::where('user_id', $user->id)
            ->where('add_status', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        $data['user'] = $user;

        $cartItems = Cart::with('product')->where('user_id', $user->id)->cartType()->get();

        if($cartItems->isEmpty()){
            return redirect()->to('/')->with('error', 'Cart is empty');
        }

        $subtotal = $cartItems->sum(function ($item) {
            if (Auth::user()->can('isDistributor') || Auth::user()->can('isCustomer')) {
                return $item->product ? $item->product->product_sale_price * $item->quantity : 0;
            } else {
                return $item->product ? $item->product->guest_price * $item->quantity : 0;
            }
        });

        $totalSv = $cartItems->sum(function ($item) {
            if (Auth::check() && (Auth::user()->role == 'customer' || Auth::user()->role == 'distributor')) {
                return $item->product ? $item->product->sv * $item->quantity : 0;
            } else {
                return 0;
            }
        });

        $total_mrp = $cartItems->sum(function ($item) {
            return $item->product && isset($item->product->mrp) ? $item->product->mrp * $item->quantity : 0;
        });
        $total_saving = $total_mrp - $subtotal;
        $total_items = $cartItems->sum('quantity');

        $discount = 0;
        if (session('applied_coupon.discount_amount') && Auth::user()->can('isGuest')) {
            $discount = session('applied_coupon.discount_amount');
        }

        $data['subtotal'] = $subtotal;
        $data['total_mrp'] = $total_mrp;
        $data['total_sv'] = $totalSv;
        $data['total_saving'] = $total_saving;
        $data['net_amount'] = $subtotal - $discount;
        $data['total_items'] = $total_items;

        return view('Website.checkout', $data);
    }

    public function buyProduct($slug)
    {
        $product = Product::where('slug_url', $slug)->first();
        if (Auth::user()->can('isDistributor') || Auth::user()->can('isCustomer')) {
            $subtotal = $product->product_sale_price;
        } else {
            $subtotal = $product->guest_price;
        }
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

    private function checkWalletBalance($amount)
    {
        $wallet_balance = walletBalance(Auth::user()->user_id);
    }

    public function checkout(Request $request)
    {
        // $payment = new CashFree();
        // $res = $payment->createOrder(100, 'INR', ['customer_id' => '1234567890', 'customer_name' => 'John Doe', 'customer_email' => 'john.doe@example.com', 'customer_phone' => '+919876543210']);

        // print_r($res);
        // die;

        $rules = [
            'address_id' => 'required|exists:user_address,id',
            'payment_mode' => 'required|in:wallet,online',
        ];
        if ($request->payment_mode == 'online') {
            $rules['payment_gateway'] = 'required|in:cashfree,razorpay';
        }
        if (Auth::user()->can('isDistributor') || Auth::user()->can('isCustomer')) {
            $rules['delivery_mode'] = 'required|in:self_pickup,courier';
        }
        $validator = Validator::make($request->all(), $rules, [
            'address_id.required' => 'Address is required.',
            'payment_gateway.required' => 'Payment gateway is required.',
            'payment_gateway.in' => 'Invalid payment gateway.',
            'delivery_mode.required' => 'Delivery mode is required.',
            'delivery_mode.in' => 'Invalid delivery mode.',
            'payment_mode.required' => 'Payment mode is required.',
            'payment_mode.in' => 'Invalid payment mode.',
        ]);

        if ($validator->fails()) {
            return $this->validationMsg($validator->errors()->first());
        }

        $user = Auth::user();
        $address = UserAddress::where('id', $request->address_id)->where('user_id', $user->id)->where('add_status', 1)->first();
        if (!$address) {
            return $this->failMsg('Invalid Address Id.');
        }


        $cartItems = Cart::with('product')->where('user_id', $user->id)->cartType()->get();
        if ($cartItems->isEmpty()) {
            return $this->failMsg('Cart is Empty.');
        }

        if (Auth::user()->can('isDistributor') || Auth::user()->can('isCustomer')) {
            $res = $this->ApOrder($request, $cartItems);
        } else {
            $res = $this->DealOrder($request, $cartItems);
        }
        if (!empty($res['status'])) {
            return $this->successMsg($res['msg'] ?? '', $res['array'] ?? array());
        } else {
            return $this->failMsg($res['msg'] ?? '', $res['array'] ?? array());
        }
    }

    private function DealOrder($request, $cartItems)
    {
        // try {
        DB::beginTransaction();

        $subtotal = 0;
        $total_gst = 0;
        $total_discount = 0;
        foreach ($cartItems as $cartItem) {
            $subtotal += $cartItem->product->guest_price * $cartItem->quantity;
            $total_gst += $cartItem->product->igst * $cartItem->quantity;
            $total_discount += $cartItem->product->discount * $cartItem->quantity;
        }
        $coupon_id = session('applied_coupon.id') ?? null;

        $total_net_amount = ($subtotal + $total_gst) - $total_discount;

        $online_payable_amount = $total_net_amount;
        // if($request->payment_mode == 'wallet'){
        //     $wallet_balance = walletBalance(Auth::user()->user_id);
        //     if($wallet_balance < $total_net_amount){
        //         $online_payable_amount = $total_net_amount - $wallet_balance;
        //     }
        // }

        $user = Auth::user();
        $order = new Order();
        $order->uid = $user->user_id;
        $order->order_id = 'NUTRA-' . time() . '-' . $user->id;
        $order->address_id = $request->address_id;
        $order->coupon_id = $coupon_id;
        $order->amount = $subtotal;
        $order->total_qty = $cartItems->sum('quantity');
        $order->total_gst = $total_gst;
        $order->total_amount = $subtotal + $total_gst;
        $order->total_gross = $subtotal + $total_gst;
        $order->total_discount = $total_discount;
        $order->total_net_amount = $total_net_amount;
        $order->payment_method = 'prepaid';
        $order->payment_status = 'pending';
        $order->payment_gateway = $request->payment_gateway;
        $order->txn_id = '';
        $order->payment_response = json_encode([]);
        $order->status = 'pending';
        if (!$order->save()) {
            DB::rollBack();
            return ['status' => false, 'msg' => 'Failed to create order.'];
        }

        foreach ($cartItems as $cartItem) {
            if ($cartItem->product) {
                $orderToProduct = new OrderToProduct();
                $orderToProduct->order_id = $order->id;
                $orderToProduct->product_id = $cartItem->product_id;
                $orderToProduct->variant_id = $cartItem->variant_id;
                $orderToProduct->attribute_id = null;
                $orderToProduct->quantity = $cartItem->quantity;
                $orderToProduct->price = $cartItem->product->guest_price;
                $orderToProduct->gst = $cartItem->product->igst;
                if (!$orderToProduct->save()) {
                    DB::rollBack();
                    return ['status' => false, 'msg' => 'Failed to create order to product.'];
                }
            }
        }
        Cart::where('user_id', $user->id)->delete();

        DB::commit();

        return ['status' => true, 'msg' => 'Order placed successfully!'];
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return ['status' => false, 'msg' => 'Failed to process order: ' . $e->getMessage()];
        // }
    }

    private function ApOrder($request, $cartItems)
    {
        $user_id = Auth::user()->user_id;
        $userRes = User::where('user_id', $user_id)->first();

        $delivery_mode = $request->delivery_mode;
        $reffer_id = $request->reffer_id;

        $order_id = 'UNI' . date('ymds') . rand(1111, 9999);
        $uniqueOd = 'UNI' . date('ymdis') . rand(11111, 99999);

        $ord_type = 'sv';
        $ord_tp = 'sv_order';
        if ($userRes->role == 'distributor') {
            $ord_type = 'rp';
            $ord_tp = 'rp_order';
        }

        $totalQty = 0;
        $totalAmount = 0;
        $totalGst = 0;
        $totSv = 0;
        $totDiscount = 0;
        $totalNetAmt = 0;
        $totalGross = 0;
        foreach ($cartItems as $cart) {
            $totSv += $cart->sv;
            $totalQty += $cart->qty;
            $totalAmount += $cart->product->product_sale_price * $cart->quantity;

            $gst = 100 / (100 + $cart->product->igst);
            $gross = $totalAmount * $gst;
            $totalGst += $totalAmount - $gross;
            $totalNetAmt += $gross + $totalGst;
            $totalGross += $gross;
        }

        $shippingCharge = 0;
        if (($totalNetAmt < 649) && $delivery_mode != 'self_pickup')
            $shippingCharge = 100;

        $payment_mode = $request->payment_mode;
        $onlinePayableAmount = $totalNetAmt + $shippingCharge;
        $walletPayableAmount = $totalNetAmt + $shippingCharge;
        if (in_array($request->payment_mode, ['wallet'])) {
            $applicableAmt = walletBalance(Auth::user()->user_id);
            if ($applicableAmt < $walletPayableAmount) {
                $onlinePayableAmount = $walletPayableAmount - $applicableAmt;
                $walletPayableAmount = $applicableAmt;
            } else {
                $onlinePayableAmount = $applicableAmt - $walletPayableAmount;
            }
            $orderService = new OrderService();
            $checkWallet = $orderService->checkWallet($walletPayableAmount, $order_id, $ord_tp, $totDiscount, true);
            if (!$checkWallet['status'])
                return ['status' => false, 'msg' => $checkWallet['msg']];

            if ($onlinePayableAmount > 0) {
                $payment_mode = 'wallet_online';
            }
        }

        $save = new ApOrder();
        $save->uid = $user_id;
        $save->order_id = $order_id;
        $save->sv = $totSv;
        $save->total_qty = $totalQty;
        $save->total_amt = $totalAmount;
        $save->discount_amt = 0;
        $save->gst_amt = $totalGst;
        $save->total_gross = $totalGross;
        $save->total_net_amt = ($totalNetAmt + $shippingCharge);
        $save->address_id = $request->address_id;
        $save->shipping_charge = $shippingCharge;
        $save->unique_order_id = $uniqueOd;
        $save->delivery_mode = $request->delivery_mode;
        // $save->order_type = $ord_type;
        $save->status = 'pending';
        if (!$save->save())
            return ['status' => false, 'msg' => 'Something Went Wrong, Order not created.'];

        foreach ($cartItems as $cartItem) {
            if ($cartItem->product) {
                $orderToProduct = new OrderToProduct();
                $orderToProduct->order_id = $save->id;
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

        if (in_array($request->payment_mode, ['wallet']) && $walletPayableAmount > 0) {
            $orderService = new OrderService();
            $checkWallet = $orderService->checkWallet($walletPayableAmount, $order_id, $ord_tp, $totDiscount, false);
            if (!$checkWallet['status'])
                return ['status' => false, 'msg' => ($checkWallet['msg'])];
        }
//  &&
                // $request->payment_mode['status'] == 'success'
        if ((in_array($request->payment_mode, ['online', 'wallet_online']))) {
            $orderService = new OrderService();
            $orderService->distributePayout($totSv, $order_id);

            $directPay = User::where('id', Auth::user()->id)->first();
            $in_type = "Start Bonus from {$directPay->user_nm} - level 1";
            $amount = $totSv * 0.4;
            $insert = insertPayout($amount, $directPay->ref_id, $in_type, $directPay->user_num, $order_id, $totSv, 1);
            if ($insert) {
                addWallet1(1, $directPay->ref_id, $amount, $order_id, 'gen_payout');
            } else {
                Log::info("$amount not inserted at Level 1 >>> Payout user id: {$directPay->ref_id}");
            }

            if ($userRes->isactive1 == 1) {
                $in_type = 'Self Repurchase Bonus';
                $amount = $totSv * 0.05;
                $insert = insertPayoutSelf($amount, $directPay->user_num, $in_type, 0, $order_id, $totSv, 0, 'rp');
                if ($insert) {
                    addWallet1(1, $directPay->user_nm, $amount, $order_id, 'repurchase_payout');
                } else {
                    Log::info("$amount not inserted at Level 1 >>> Payout user id: {$directPay->refid}");
                }
            } else {
                $userLvl = User::where('user_id', $user_id)->first();
                $userLvl->isactive = 1;
                // $userLvl->isactive1 = 1;
                // $userLvl->sv = $userLvl->sv + $totSv;
                $userLvl->role = 'distributor';
                $userLvl->upgrade_date = date('Y-m-d');
                if (!$userLvl->save())
                    return ['status' => false, 'msg' => 'Something Went Wrong, Order not created.'];
            }
            $purstep = 2;
            $bonus = 0;
        } else {
            $bonus = 0;
            $purstep = 1;
        }
        $msg = 'Your Order Received Successfully. Your Order Id is ' . $order_id;
        $message = 'Your Order Received Successfully. Your Order Id is ' . $order_id;

        $resResponse = [
            'order_id' => $order_id,
            'message' => $message,
            'purchase' => $purstep,
            'bonus' => $bonus
        ];
        Cart::where('user_id', Auth::user()->id)->delete();
        return ['status' => true, 'array' => $resResponse, 'msg' => $msg];
    }

    public function paymentProcess()
    {
        $payment = new CashFree();
        $res = $payment->createOrder(100, 'INR', ['customer_id' => '1234567890', 'customer_name' => 'John Doe', 'customer_email' => 'john.doe@example.com', 'customer_phone' => '+919876543210']);

        print_r($res);
        die;
    }
}
