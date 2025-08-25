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
use App\Models\ApOrderToProduct;
use App\Models\Wallet;

use App\Services\PaymentGatway\CashFree;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

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

        if ($cartItems->isEmpty()) {
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

        $data['payment_gateway'] = config('global.payment_gateway');

        return view('Website.checkout', $data);
    }

    public function buyProduct($slug)
    {
        $product = Product::where('slug_url', $slug)->first();
        if (Auth::user()->can('isDistributor') || Auth::user()->can('isCustomer')) {
            $subtotal = $product->product_sale_price;
            $total_sv = $product->sv;
        } else {
            $subtotal = $product->guest_price;
            $total_sv = 0;
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
        $data['total_sv'] = $total_sv;
        $data['net_amount'] = $subtotal;
        $data['payment_gateway'] = config('global.payment_gateway');
        return view('Website.checkout', $data);
    }

    private function checkWalletBalance($amount)
    {
        $wallet_balance = walletBalance(Auth::user()->user_id);
    }

    public function checkout(Request $request)
    {
        // $payment = new CashFree();
        //  $res = $payment->createOrder(100, 'INR', ['customer_id' => '1234567890', 'customer_name' => 'John Doe', 'customer_email' => 'john.doe@example.com', 'customer_phone' => '+919876543210']);

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
            'address_id.required' => 'Please select an address.',
            'payment_gateway.required' => 'Please select a payment gateway.',
            'payment_gateway.in' => 'Invalid payment gateway.',
            'delivery_mode.required' => 'Please select a delivery mode.',
            'delivery_mode.in' => 'Invalid delivery mode.',
            'payment_mode.required' => 'Please select a payment method.',
            'payment_mode.in' => 'Invalid payment method.',
        ]);

        if ($validator->fails()) {
            return $this->validationMsg($validator->errors()->first());
        }

        if ($request->payment_gateway == 'razorpay') {
            return $this->failMsg('Razorpay is not available.');
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
        try {
            DB::beginTransaction();

            $subtotal = 0;
            $total_gst = 0;
            $total_discount = 0;
            $totalGross = 0;
            foreach ($cartItems as $cartItem) {
                $subtotal += $cartItem->product->guest_price * $cartItem->quantity;
                $amount = $cartItem->product->guest_price * $cartItem->quantity;
                $gross  = $amount / (100 + $cartItem->product->igst) * 100;
                $gst = $amount - $gross;
                $total_gst += $gst;
                $totalGross += $gross;
                // $total_discount += $cartItem->product->discount * $cartItem->quantity;
            }
            $coupon_id = session('applied_coupon.id') ?? null;
            $discount = session('applied_coupon.discount_amount') ?? 0;

            $total_discount = $discount;
            $total_net_amount = ($subtotal) - $discount;

            $user = Auth::user();
            $order_id = 'NUTRA-' . time() . '-' . $user->id;
            //cashfree
            $address = UserAddress::where('id', $request->address_id)->where('user_id', $user->id)->first();


            $paymentResponse = [];
            if (in_array($request->payment_mode, ['online']) && $request->payment_gateway == 'cashfree') {
                $payload = [
                    'order_id' => $order_id,
                    'customer_id' => $user->user_num,
                    'customer_name' => $address->user_add_name,
                    'customer_email' => $address->user_add_email ?? '',
                    'customer_phone' => $address->user_add_mobile,
                    'return_url' => url('checkout/payment-response/cashfree/deal_order?order_id={order_id}'),
                    'webhook_url' => url('checkout/payment-webhook/cashfree')
                ];

                $payment = new CashFree();
                $paymentResponse = $payment->createOrder($total_net_amount, 'INR', $payload);
                if (empty($paymentResponse['status']) || !$paymentResponse['status']) {
                    DB::rollBack();
                    return ['status' => false, 'msg' => $paymentResponse['msg'] ?? 'Something went wrong in CashFree Side'];
                }
            }

            $order = new Order();
            $order->uid = $user->user_id;
            $order->order_id = $order_id;
            $order->address_id = $request->address_id;
            $order->coupon_id = $coupon_id;
            $order->amount = $subtotal;
            $order->total_qty = $cartItems->sum('quantity');
            $order->total_gst = $total_gst;
            $order->total_amount = $subtotal;
            $order->total_gross = $totalGross;
            $order->total_discount = $total_discount;
            $order->total_net_amount = $total_net_amount;
            $order->payment_method = 'prepaid';
            $order->payment_status = !empty($paymentResponse['cashfree_order_id']) ? 'initiated' : 'failed';
            $order->payment_gateway = $request->payment_gateway;
            $order->txn_id = $paymentResponse['cashfree_order_id'] ?? '';
            $order->payment_response = json_encode($paymentResponse);
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

            return [
                'status' => true,
                'msg' => 'Order placed successfully!',
                'array' => [
                    'payment_type' => $request->payment_mode,
                    'payment_session_id' => $paymentResponse['payment_session_id'],
                    'payment_gateway' => $request->payment_gateway,
                    'online' => true,
                ]
            ];
        } catch (\Exception $e) {
            DB::rollback();
            return ['status' => false, 'msg' => 'Failed to process order: ' . $e->getMessage()];
        }
    }

    private function ApOrder($request, $cartItems)
    {
        try {
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
                $totSv += $cart->product->sv * $cart->quantity;
                $totalQty += $cart->quantity;

                $amount = $cart->product->product_sale_price * $cart->quantity;
                $totalAmount += $amount;

                $gross  = $amount / (100 + $cart->product->igst) * 100;
                $gst = $amount - $gross;
                $totalGst += $gst;

                $totalNetAmt += $amount;
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
                    $onlinePayableAmount = 0;
                }

                $orderService = new OrderService();
                $checkWallet = $orderService->checkWallet($walletPayableAmount, $order_id, $ord_tp, $totDiscount, true);
                if (!$checkWallet['status'])
                    return ['status' => false, 'msg' => $checkWallet['msg']];

                if ($onlinePayableAmount > 0) {
                    $payment_mode = 'wallet_online';
                    if ($payment_mode == 'wallet_online' && empty($request->payment_gateway)) {
                        return ['status' => false, 'msg' => 'You have not sufficient balance. Please select a payment gateway.'];
                    }
                }
            }
            //         echo $payment_mode;
            //         echo $onlinePayableAmount;die;
            // die;
            DB::beginTransaction();
            $user = Auth::user();
            $address = UserAddress::where('id', $request->address_id)->where('user_id', $user->id)->first();
            //cashfree Payment gatway
            $paymentResponse = [];
            if (in_array($payment_mode, ['online', 'wallet_online']) && $request->payment_gateway == 'cashfree') {
                $payload = [
                    'order_id' => $order_id,
                    'customer_id' => $user->user_num,
                    'customer_name' => $address->user_add_name,
                    'customer_email' => $address->user_add_email ?? '',
                    'customer_phone' => $address->user_add_mobile,
                    'return_url' => url('checkout/payment-response/cashfree/ap_order?order_id={order_id}'),
                    'webhook_url' => url('checkout/payment-webhook/cashfree')
                ];

                $payment = new CashFree();
                $paymentResponse = $payment->createOrder($onlinePayableAmount, 'INR', $payload);
                if (empty($paymentResponse['status']) || !$paymentResponse['status']) {
                    DB::rollBack();
                    return ['status' => false, 'msg' => $paymentResponse['msg'] ?? 'Something went wrong in CashFree Side'];
                }
            }

            $save = new ApOrder();
            $save->uid = $user_id;
            $save->order_id = $order_id;
            $save->sv = $totSv;
            $save->total_qty = $totalQty;
            $save->total_amt = $totalAmount;
            $save->discount_amt = $totDiscount;
            $save->gst_amt = $totalGst;
            $save->total_gross = $totalGross;
            $save->total_net_amt = ($totalNetAmt + $shippingCharge);
            $save->address_id = $request->address_id;
            $save->shipping_charge = $shippingCharge;
            $save->unique_order_id = $uniqueOd;
            $save->delivery_mode = $request->delivery_mode;
            $save->order_type = $ord_type;
            $save->payment_method = 'prepaid';

            if (in_array($payment_mode, ['wallet_online', 'online'])) {
                $save->payment_status = !empty($paymentResponse['cashfree_order_id']) ? 'initiated' : 'failed';
                $save->payment_gateway = $request->payment_gateway;
                $save->txn_id = $paymentResponse['cashfree_order_id'] ?? '';
                $save->payment_response = json_encode($paymentResponse);
            }
            $save->payment_type = $payment_mode;
            $save->wallet_payable_amount = $walletPayableAmount;
            $save->status = 'pending';
            if (!$save->save()) {
                DB::rollBack();
                return ['status' => false, 'msg' => 'Something Went Wrong, Order not created.'];
            }

            foreach ($cartItems as $cartItem) {
                if ($cartItem->product) {
                    $orderToProduct = new ApOrderToProduct();
                    $orderToProduct->order_id = $save->id;
                    $orderToProduct->product_id = $cartItem->product_id;
                    $orderToProduct->variant_id = $cartItem->variant_id;
                    $orderToProduct->attribute_id = null;
                    $orderToProduct->quantity = $cartItem->quantity;
                    $orderToProduct->price = $cartItem->product->product_sale_price;
                    $orderToProduct->gst = $cartItem->product->igst;
                    $orderToProduct->sv = $cartItem->product->sv;
                    if (!$orderToProduct->save()) {
                        DB::rollBack();
                        return ['status' => false, 'msg' => 'Something Went Wrong, Order not created.'];
                    }
                }
            }

            if (in_array($payment_mode, ['wallet']) && $walletPayableAmount > 0 && $onlinePayableAmount == 0) {
                $orderService = new OrderService();
                $checkWallet = $orderService->checkWallet($walletPayableAmount, $order_id, $ord_tp, $totDiscount, false);
                if (!$checkWallet['status']) {
                    DB::rollBack();
                    return ['status' => false, 'msg' => ($checkWallet['msg'] ?? 'Something Went Wrong in wallet process.')];
                }
                $this->apOrderUpdate($save);
            }

            $msg = 'Your Order Received Successfully. Your Order Id is ' . $order_id;

            $resResponse = [
                'payment_type' => $payment_mode,
                'payment_gateway' => !empty($request->payment_gateway) ? $request->payment_gateway : '',
                'payment_session_id' => !empty($paymentResponse['payment_session_id']) ? $paymentResponse['payment_session_id'] : '',
                'online' => true,
            ];
            if (in_array($payment_mode, ['wallet'])) {
                $redirect_url = route('order-success', [$order_id, 'ap_order']);
                $resResponse['redirect_url'] = $redirect_url;
                $resResponse['online'] = false;
            }
            Cart::where('user_id', Auth::user()->id)->delete();
            DB::commit();
            return ['status' => true, 'array' => $resResponse, 'msg' => $msg];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['status' => false, 'msg' => 'Something Went Wrong, Order not created.'.$e->getMessage()];
        }
    }



    public function paymentResponse(Request $request, $type)
    {
        Log::info('Payment Redirect cashfree Response -' . $request->order_id, $request->all());

        DB::beginTransaction();
        if ($type == 'ap_order') {
            $order = ApOrder::with(['shipping_address', 'orderToProducts.product'])->where('order_id', $request->order_id)->first();
        } else {
            $order = Order::with(['shipping_address', 'orderToProducts.product'])->where('order_id', $request->order_id)->first();
        }

        $payment = new CashFree();
        $res = $payment->getPayment($request->order_id);

        if (empty($res['status']) || $res['status'] == false) {
            Log::info('Payment Redirect cashfree Response Failed -' . $request->order_id, [$res]);
            // die('Payment Failed');
            DB::rollBack();
            return redirect()->route('order-failed', [$order->order_id, $type]);
        }

        $paymentStatus = ((!empty($res['payment_status']) && $res['payment_status'] == 'SUCCESS') && !empty($res['is_captured'])) ? 'success' : (strtolower($res['payment_status']) ?? 'failed');

        $order->payment_status = $paymentStatus;
        $order->payment_ref_id = $res['bank_reference'] ?? '';
        $order->payment_response = json_encode($res);
        $res = $order->save();
        if ($res) {
            if ($paymentStatus == 'success') {
                Log::info('Payment Redirect cashfree Response Success -' . $request->order_id);
                if ($type == 'ap_order') {

                    /*wallet payable amount start*/
                    $walletPayableAmount = $order->wallet_payable_amount ?? 0;
                    if (in_array($order->payment_type, ['wallet_online']) && $walletPayableAmount > 0) {
                        $ord_tp = (Auth::user()->role == 'distributor')?'rp_order':'sv_order';
                        $orderService = new OrderService();
                        $checkWallet = $orderService->checkWallet($walletPayableAmount, $order->order_id, $ord_tp,$order->discount_amt, false);
                        if (!$checkWallet['status']) {
                            DB::rollBack();
                            return ['status' => false, 'msg' => ($checkWallet['msg'] ?? 'Something Went Wrong in wallet process.')];
                        }
                    }
                    /*wallet payable amount end*/

                    $res = $this->apOrderUpdate($order);
                    Log::info('Payment Redirect cashfree Response Success update Distribute payout -' . $request->order_id, [$res]);
                }
                DB::commit();
                return redirect()->route('order-success', [$order->order_id, $type]);
            } else {
                DB::commit();
                Log::info('Payment Redirect cashfree Response Failed -' . $request->order_id, ['Something went wrong in Payment Status update']);
                // die('Payment Failed');
                return redirect()->route('order-failed', [$order->order_id, $type]);
            }
        } else {
            DB::rollBack();
            Log::info('Payment Redirect cashfree Response Failed -' . $request->order_id, ['Something went wrong in Order update']);
            // die('Payment Failed');
            return redirect()->route('order-failed', [$order->order_id, $type]);
        }
    }

    public function paymentWebhook(Request $request)
    {
        Log::info('Payment Webhook cashfree', [$request->all()]);
        return response()->json('success', 200);
    }


    private function apOrderUpdate($order)
    {
        $totSv = $order->sv;
        $order_id = $order->order_id;
        $user_id = $order->uid;
        $userRes = User::where('user_id', $user_id)->first();

        $orderService = new OrderService();
        $orderService->distributePayout($totSv, $order_id);

        $directPay = User::where('id', Auth::user()->id)->first();

        $in_type = "Start Bonus from {$directPay->user_nm} - level 1";
        $amount = $totSv * 0.4;

        $isActive = $directPay->isactive;
        $insert = insertPayout($amount, $directPay->ref_id, $in_type, $directPay->user_num, $order_id, $totSv, 1, $isActive);
        if ($insert) {
            if ($isActive == 0 || empty($isActive)) {
                addWallet1(1, $directPay->ref_id, $amount, $order_id, 'gen_payout', $isActive);
            }
        } else {
            Log::info("$amount not inserted at Level 1 >>> Payout user id: {$directPay->ref_id}");
        }

        if ($isActive == 1) {
            $in_type = 'Self Repurchase Bonus';
            $amount = $totSv * 0.05;
            $insert = insertPayout($amount, $directPay->user_num, $in_type, 0, $order_id, $totSv, 0, $isActive);
            if ($insert) {
                addWallet1(1, $directPay->user_nm, $amount, $order_id, 'repurchase_payout', $isActive);
            } else {
                Log::info("$amount not inserted at Level 1 >>> Payout user id: {$directPay->ref_id}");
            }
        } else {
            $userLvl = User::where('user_id', $user_id)->first();
            $userLvl->isactive = '1';
            $userLvl->role = 'distributor';
            $userLvl->upgrade_date = date('Y-m-d');
            if (!$userLvl->save()) {
                return ['status' => false, 'msg' => 'Something Went Wrong, Order not created.'];
            }
        }
    }


    public function orderSuccess($order_id, $type)
    {
        if ($type == 'ap_order') {
            $data['order'] = ApOrder::with(['shipping_address', 'orderToProducts.product'])->where('order_id', $order_id)->first();
        } else {
            $data['order'] = Order::with(['shipping_address', 'orderToProducts.product'])->where('order_id', $order_id)->first();
        }
        return view('Website.order_success', $data);
    }

    public function orderFailed($order_id, $type)
    {
        if ($type == 'ap_order') {
            $data['order'] = ApOrder::with(['shipping_address', 'orderToProducts.product'])->where('order_id', $order_id)->first();
        } else {
            $data['order'] = Order::with(['shipping_address', 'orderToProducts.product'])->where('order_id', $order_id)->first();
        }
        return view('Website.order_failed', $data);
    }
}
