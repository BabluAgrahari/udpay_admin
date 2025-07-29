<?php
//use Log;
namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Product\Products;
use App\Models\Product\RpTransition;
use App\Models\Product\ApTransition;
use App\Models\Product\Cart;
use App\Models\Product\ApRepurchaseOrder;
use App\Models\Product\RepurchaseOrder;
use App\Models\Deals\DealOrders;
//use DB;
use App\Models\Product\OfferPayout;
use App\Models\BinaryData;
use App\Models\Wallet;
use App\Models\WalletTransition;
use App\Models\MemberBelow;
use App\Models\MemberBv;
use App\Models\LevelCount;
use App\Models\Orgap;
use App\Models\Org;
use Exception;

class UserOrderController extends Controller
{

	public function index(Request $request)
	{
		try {
			$uid = Auth::user()->user_id;
			$records = array();
			$apOrder = DB::table('ap_repurchase_order')->select(DB::raw('sum(rp) as rp, order_id'))->where('uid', $uid)->groupBy('order_id')->orderBy('id', 'desc')->get();

			if ($apOrder->isNotEmpty()) {
				foreach ($apOrder as $cat) {
					$row = ApRepurchaseOrder::select('tot_qty', 'shippingCharge', 'totalNetAmt', 'status', 'url', 'delivery_mode', 'cur_date')->where('order_id', $cat->order_id)->first();
					$records['primary'][] = array(
						'order_id' => $cat->order_id,
						'sv' => $cat->rp,
						'qty' => $row->tot_qty,
						'net_amount' => $row->totalNetAmt,
						'shipping_charges' => $row->shippingCharge,
						'tp' => 'ap',
						'status' => $row->status,
						'tracking_url' => ($row->delivery_mode == 'self_pickup' || $row->delivery_mode == '') ? 'Self Pickup' : $row->url,
						'date' => $row->cur_date
					);
				}
			}



			$dealOrder = DB::table('deals_order')->select(DB::raw('order_id'))->where('uid', $uid)->groupBy('order_id')->orderBy('id', 'desc')->get();
			if ($dealOrder->isNotEmpty()) {
				foreach ($dealOrder as $cat) {
					$row = DealOrders::where('order_id', $cat->order_id)->first();
					$records['deals'][] = array(
						'order_id' => $cat->order_id,
						'qty' => $row->totQty,
						'net_amtount' => $row->totalNetAmt,
						'shipping_charges' => $row->shippingCharge,
						'tp' => 'deals',
						'status' => $row->status,
						'tracking_url' => '',
						'date' => $row->for_date

					);
				}
			}
			return $this->recordRes($records);
		} catch (Exception $e) {
			return $this->failRes($e->getMessage());
		}
	}

	public function save(Request $request)
	{
		try {
			$rules = ['type' => 'required|string|in:deals,other'];
			$rules['shipping_address_id'] = 'required|numeric';
			$rules['delivery_mode'] = 'in:self_pickup,courier';

			if (empty(Auth::user()->isactive) || Auth::user()->isactive == 0) {
				$rules['reffer_id'] = 'nullable|numeric';

			}
			if ($request->type == 'deals') {
				$rules['accept'] = 'required';
			}

			$validator = Validator::make($request->all(), $rules);
			if ($validator->fails())
				return $this->validationRes($validator->messages());

			/*if((empty(Auth::user()->isactive) || Auth::user()->isactive == 0) && empty($request->reffer_id)){
				return $this->failRes('Please Enter Refer Id');
			}*/

			if ($request->type == 'deals') {
				//return $this->failRes('Under Maintinance..');
				$res = $this->saveDealOrder($request);
			} else {
				//return $this->failRes('Please Try after sometime..');
				$res = $this->ApOrder($request);
			}
			if ($res['status']) {
				return $this->recordResMsg($res['array'] ?? array(), $res['msg'] ?? '');
			} else {
				return $this->failRes($res['msg'] ?? "");
			}
		} catch (Exception $e) {
			return $this->failRes($e->getMessage());
		}
	}

	private function ApOrder($request)
	{
		// $pv = 1000;

		$totAp = $totalQty = $totalGross = $totalGst = $totalNetAmt = $totDiscount = $totAmt = 0;

		$user_id = Auth::user()->user_id;
		$userRes = User::where('user_id', $user_id)->first();

		$delivery_mode = $request->delivery_mode;
		$reffer_id = $request->reffer_id;

		$order_id = "UNI" . date('ymds') . rand(1111, 9999);
		$uniqueOd = "UNI" . date('ymdis') . rand(11111, 99999);

		$cart_type = "ap_shopping";
		$ord_type = 'sv';
		if ($userRes->isactive == 1) {
			$cart_type = "shopping";
			$ord_type = 'rp';
		}

		$cartRes = Cart::with('Product')->where('uid', Auth::user()->user_id)->where('status', 0)->where('cart_type', $cart_type)->get();
		if ($cartRes->isEmpty())
			return ['status' => false, 'msg' => 'Your cart is empty or missmatch cart details.'];

		foreach ($cartRes as $cart) {
			$totAp += $cart->rp;
			$totalQty += $cart->qty;
			$totalGross += $cart->grossAmt;
			$totalGst += $cart->gstAmt;
			$totalNetAmt += $cart->netAmt;
			$totDiscount += $cart->disAmt;
			$totAmt += $cart->totAmt;
		}

		$shippingCharge = 0;
		if (($totalNetAmt < 649) && $delivery_mode != 'self_pickup')
			$shippingCharge = 100;

		if (in_array($request->paymentmode, ['wallet'])) {
			$checkWallet = $this->checkWallet(($totalNetAmt + $shippingCharge), $order_id, 'sv_order', $totDiscount, true);
			if (!$checkWallet['status'])
				return ['status' => false, 'msg' => $checkWallet['msg']];
		}

		$inserId = [];
		foreach ($cartRes as $cart) {
			$amount = $cart->amt * $cart->qty;
			$qry = new ApRepurchaseOrder();
			$qry->user_name = $userRes->user_nm;
			$qry->pid = $cart->pid;
			$qry->cart_id = $cart->id;
			$qry->uid = Auth::user()->user_id;
			$qry->order_id = $order_id;
			$qry->qty = $cart->qty;
			$qry->tot_qty = $totalQty;
			$qry->unit_price = $cart->amt;
			$qry->amount = $amount;
			$qry->totAmt = $totAmt;
			$qry->discountRate = $cart->up;
			$qry->netAmt = $cart->netAmt;
			$qry->address = $request->shipping_address_id;
			$qry->shippingCharge = $shippingCharge;
			$qry->cur_date = date('Y-m-d');
			$qry->delivery_mode = ucwords(str_replace('_', ' ', $request->delivery_mode));
			$qry->uniqueOd = $uniqueOd;
			$qry->status = 'pending';
			$qry->rp = $cart->rp;
			$qry->size_id = $cart->size_id;
			$qry->color_id = $cart->color_id;
			$qry->reffer_id = $request->reffer_id;
			$qry->totalGross = $totalGross;
			$qry->totalNetAmt = $totalNetAmt;
			$qry->totalGst = $totalGst;
			$qry->umart_id = 0;
			$qry->mm_id = 0;
			$qry->url = '';
			$qry->delivered_by = '';
			$qry->delivered_by_id = 0;
			$qry->delivered_date = '';
			$qry->flag = 0;
			$qry->ord_tp = $ord_type;

			$discountAmts = 0;//$cart->disAmt;

			$gst = 100 / (100 + $cart->Product->igst); // 0.8474
			$gross = $amount * $gst; //846
			$gst = $amount - $gross; //999-846
			$net = $gross + $gst; //999

			$qry->discountAmt = $cart->disAmt;
			$qry->grossAmt = $gross;
			$qry->netAmt = $net;
			$qry->gstAmt = $gst;

			//payment details
			$qry->payment_mode = $request->paymentmode;
			$qry->payment_txn_id = $request->payment_gateway['payment_id'] ?? '';
			$qry->payment_order_id = $request->payment_gateway['order_id'] ?? '';
			$qry->payment_response = json_encode($request->payment_gateway ?? []);
			$qry->total_ap = $totAp;

			$qry->save();

			$inserId[] = $qry->id;
			$rps[] = $cart->rp * $cart->qty;
		}

		if (empty($inserId))
			return ['status' => false, 'msg' => 'Something Went Wrong,Order not created.'];

		//$checkWallet['status'] = true;
		if (in_array($request->paymentmode, ['wallet'])) {
			$checkWallet = $this->checkWallet(($totalNetAmt + $shippingCharge), $order_id, 'sv_order', $totDiscount, false);
			if (!$checkWallet['status'])
				return ['status' => false, 'msg' => ($checkWallet['msg'])];
		}

		if ($request->paymentmode == 'wallet' || (in_array($request->paymentmode, ['razorpay']) && $request->payment_gateway['status'] == 'success')) {
			$this->distributePayout($totAp, $order_id);

			$uid = Auth::user()->user_id;
			$directPay = User::where('user_id', $uid)->first();
			$in_type = "Start Bonus from {$directPay->user_nm} - level 1";


			$amt = $totAp * 0.40;
			$insert = insert_payout($amt, $directPay->refid, $in_type, $directPay->user_nm, $order_id, $totAp, 1);

			if ($insert) {
				add_wallet1(1, $directPay->refid, $amt, $order_id, 'gen_payout');
			} else {
				Log::info("$amt not inserted at Level 1 >>> Payout user id: {$directPay->refid}");
			}

			/*if (!updateorgPaid($user_id, $totAp, 'orgap', $user_id, $order_id))
			Log::info("Something Went Wrong, orgap not update, UserId- $user_id , OrderId- $order_id");

			if (!updateorgPaid($user_id, 1, 'org', $user_id, $order_id))
				Log::info("Something Went Wrong, org not update, UserId- $user_id , OrderId- $order_id");

			if (!$this->updateMemberBelow($user_id, 'paid'))
				Log::info("Something Went Wrong, member below not update, UserId- $user_id , OrderId- $order_id");*/

			if ($userRes->isactive1 == 1) {
				$in_type = 'Self Repurchase Bonus';
				$amt = $totAp * 0.05;

				$insert = insert_payout_self($amt, $directPay->user_nm, $in_type, 0, $order_id, $totAp, 0, 'rp');

				if ($insert) {
					add_wallet1(1, $directPay->user_nm, $amt, $order_id, 'repurchase_payout');
				} else {
					Log::info("$amt not inserted at Level 1 >>> Payout user id: {$directPay->refid}");
				}

				$bonus = "0";
				$purstep = 2;

			} else {
				$purstep = 2;
				//add_wallet(3, Auth::user()->user_id, $totalNetAmt);
				$bonus = 0;


				$userLvl = User::where('user_id', $user_id)->first();
				$userLvl->isactive = 1;
				$userLvl->isactive1 = 1;
				$userLvl->sv = $userLvl->sv + $totAp;
				$userLvl->upgrade_date = date('Y-m-d');
				if (!$userLvl->save())
					return ['status' => false, 'msg' => 'Something Went Wrong, Order not created.'];
			}
			$msg = 'Your Order Received Successfully. Your Order Id is ' . $order_id;
			$message = 'Your Order Received Successfully. Your Order Id is ' . $order_id;
		} else {
			$bonus = 0;
			$purstep = 1;
			$msg = 'Your Order Received Successfully. Your Order Id is ' . $order_id;
			$message = 'Your Order Received Successfully. Your Order Id is ' . $order_id;
		}


		foreach ($cartRes as $cart) {
			$updateCart = Cart::find($cart->id);
			$updateCart->status = 1;
			$updateCart->save();
		}

		$resResponse = [
			'order_id' => $order_id,
			'message' => $message,
			'purchase' => $purstep,
			'bonus' => $bonus
		];
		return ['status' => true, 'array' => $resResponse, 'msg' => $msg];
	}

	private function RpOrder($request)
	{
		$totAp = $totalQty = $totalGross = $totalGst = $totalNetAmt = $totDiscount = $totAmt = 0;

		$user_id = Auth::user()->user_id;
		$delivery_mode = $request->delivery_mode;
		$order_id = date('ymds') . rand(1111, 9999);
		$uniqueOd = "UNI" . date('ymdis') . rand(11111, 99999);

		$cartRes = Cart::with('Product')->where('uid', $user_id)->where('status', 0)->where('cart_type', 'shopping')->get();

		if ($cartRes->isEmpty())
			return ['status' => false, 'msg' => 'Your cart is empty or missmatch cart details.'];


		foreach ($cartRes as $cart) {
			$totAp += $cart->rp;
			$totalQty += $cart->qty;
			$totalGross += $cart->grossAmt;
			$totalGst += $cart->gstAmt;
			$totalNetAmt += $cart->netAmt;
			$totDiscount += $cart->disAmt;
			$totAmt += $cart->totAmt;
		}

		$shippingCharge = 0;
		if (($totalNetAmt <= 999) && $delivery_mode != 'self_pickup')
			$shippingCharge = 100;

		$checkWallet = $this->checkWallet(($totalNetAmt + $shippingCharge), $order_id, 'repurchase_order', $totDiscount, true);
		if (!$checkWallet['status'])
			return ['status' => false, 'msg' => $checkWallet['msg']];

		$rps = [];
		foreach ($cartRes as $key => $cart) {
			$amount = $cart->amt * $cart->qty;
			$qry = new RepurchaseOrder();
			$qry->pid = $cart->pid;
			$qry->cart_id = $cart->id;
			$qry->uid = Auth::user()->user_id;
			$qry->order_id = $order_id;
			$qry->qty = $cart->qty;
			$qry->tot_qty = $totalQty;
			$qry->unit_price = $cart->amt;
			$qry->amount = $amount;
			$qry->totAmt = $totAmt;
			$qry->discountRate = $cart->up;
			$qry->netAmt = $cart->netAmt;
			$qry->address = $request->shipping_address_id;
			$qry->shippingCharge = $shippingCharge;
			$qry->cur_date = date('Y-m-d');
			$qry->delivery_mode = $request->delivery_mode;
			$qry->uniqueOd = $uniqueOd;
			$qry->status = 'pending';
			$qry->rp = $cart->rp;
			$qry->size_id = $cart->size_id;
			$qry->color_id = $cart->color_id;
			//$qry->reffer_id         = $request->reffer_id;
			$qry->totalGross = $totalGross;
			$qry->totalNetAmt = $totalNetAmt;
			$qry->totalGst = $totalGst;
			//$qry->umart_id          = 0;
			//$qry->mm_id             = 0;
			$qry->url = '';
			$qry->delivered_by = '';
			$qry->delivered_by_id = 0;
			$qry->delivered_date = '';
			$qry->flag = 0;

			$discountAmts = $cart->disAmt;
			$gross = $amount - $discountAmts;
			$gst = $gross * $cart->Product->igst / 100;
			$net = $gross + $gst;

			$qry->discountAmt = $cart->disAmt;
			$qry->grossAmt = $gross;
			$qry->netAmt = $net;
			$qry->gstAmt = $gst;
			$qry->save();

			$inserId[] = $qry->id;
			$rps[] = $cart->rp * $cart->qty;
		}

		if (!empty($inserId)) {

			$checkWallet = $this->checkWallet(($totalNetAmt + $shippingCharge), $order_id, 'repurchase_order', $totDiscount, false);
			if (!$checkWallet['status'])
				return ['status' => false, 'msg' => $checkWallet['msg']];

			if (!$this->updateRp($user_id, array_sum($rps), $user_id, $order_id))
				Log::info("Something Went Wrong, RP not update, UserId- $user_id , OrderId- $order_id");

			foreach ($cartRes as $cart) {
				$updateCart = Cart::find($cart->id);
				$updateCart->status = 1;
				$updateCart->save();
			}
			$response = [
				'order_id' => $order_id,
				'purchase' => 2,
				'bonus' => 0
			];
			return ['status' => true, 'array' => $response, 'msg' => 'Order Received Successfull.'];
		} else {
			return ['status' => false, 'msg' => 'Something Went Wrong.'];
		}
	}

	public function saveDealOrder($request)
	{
		$user_id = Auth::user()->user_id;
		$address = $request->shipping_address_id;
		$delivery_mode = $request->delivery_mode;
		$final_array = array();

		$dis = $totAmt = $totAmtAftDis = $totalNet = $totDis = $totQty = $totGst = $totalGross = 0;
		$shippingAmt = 999;
		$shippingChrg = 0;
		$order_id = "DEAL" . date('ymdis') . rand(11111, 99999);

		$wallet = Wallet::where('userid', $user_id)->first();
		$available = $wallet->bp;

		$cartRes = Cart::with('Product')->where('uid', $user_id)->where('status', 0)->where('cart_type', 'deals')->get();
		if ($cartRes->isEmpty())
			return ['status' => false, 'msg' => 'Your cart is empty or missmatch cart details.'];

		foreach ($cartRes as $cart) {
			if ($request->accept == 1)
				$dis = $cart->up * $cart->qty;
			if ($available >= $dis) {
				$totAmtAftDis = $cart->totAmt - $dis;
			} else {
				$dis = $available;
				$totAmtAftDis = $cart->totAmt - $dis;
			}

			$available = $available - $dis;
			$gross = $totAmtAftDis * 100 / (100 + $cart->Product->igst);
			$gst = $totAmtAftDis - $gross;
			$final_array[] = array(
				'uid' => $user_id,
				'address' => $address,
				'order_id' => $order_id,
				'qty' => $cart->qty,
				'amount' => $cart->amt,
				'gstAmt' => $gst,
				'netAmt' => $totAmtAftDis,
				'totalAmt' => $cart->totAmt,
				'discount' => $dis,
				'grossAmt' => $gross,
				'size_id' => $cart->size_id,
				'color_id' => $cart->color_id,
				'pid' => $cart->pid,
				'cart_id' => $cart->id
			);
			$totAmt += $cart->totAmt;
			$totalNet += $totAmtAftDis;
			$totDis += $dis;
			$totQty += $cart->qty;
			$totGst += $gst;
			$totalGross += $gross;
		}

		$shippingChrg = 0;
		if ($totalNet <= $shippingAmt && $delivery_mode != 'self_pickup')
			$shippingChrg = 100;

		if (in_array($request->paymentmode, ['wallet'])) {
			$checkWallet = $this->checkWallet(($totalNet + $shippingChrg), $order_id, 'deal_order', $totDis, true);
			if (!$checkWallet['status'])
				return ['status' => false, 'msg' => $checkWallet['msg']];
		}


		$ins_id = [];
		foreach ($final_array as $final) {

			$insQry = new DealOrders();
			$insQry->uid = $final['uid'] ?? '';
			$insQry->address = $address;
			$insQry->order_id = $order_id;
			$insQry->qty = $final['qty'] ?? 0;
			$insQry->amount = $final['amount'] ?? 0;
			$insQry->gstAmt = $final['gstAmt'] ?? 0;
			$insQry->netAmt = $final['netAmt'] ?? 0;
			$insQry->totalAmt = $final['totalAmt'] ?? 0;
			$insQry->discount = $final['discount'] ?? 0;
			$insQry->grossAmt = $final['grossAmt'] ?? 0;
			$insQry->size_id = $final['size_id'] ?? 0;
			$insQry->color_id = $final['color_id'] ?? 0;
			$insQry->pid = $final['pid'] ?? 0;
			//$insQry->cart_id = $final['cart_id'];
			$insQry->totalNetAmt = $totalNet;
			$insQry->totalDis = $totDis;
			$insQry->shippingChrg = $shippingChrg;
			$insQry->totQty = $totQty;
			$insQry->totGst = $totGst;
			$insQry->totalGross = $totalGross;
			$insQry->totalAmount = $totAmt;
			$insQry->status = 'pending';
			$insQry->for_date = date('Y-m-d H:i:s');
			$insQry->delivery_mode = ($delivery_mode == 'self_pickup') ? 'Self Pickup' : 'Courier';
		
			//payment details
			$insQry->payment_mode = $request->paymentmode;
			$insQry->payment_txn_id = $request->payment_gateway['payment_id'] ?? '';
			$insQry->payment_order_id = $request->payment_gateway['order_id'] ?? '';
			$insQry->payment_response = json_encode($request->payment_gateway ?? []);

			$insQry->save();
			$ins_id[] = $insQry->id;
		}
		if (empty($ins_id))
			return ['status' => false, 'msg' => 'Something Went wrong, Order not created.'];

		if (in_array($request->paymentmode, ['wallet'])) {
			$checkWallet = $this->checkWallet(($totalNet + $shippingChrg), $order_id, 'deal_order', $totDis, false);
			if (!$checkWallet['status'])
				return ['status' => false, 'msg' => $checkWallet['msg']];
		}

		foreach ($cartRes as $cart) {
			$updateCart = Cart::find($cart->id);
			$updateCart->status = 1;
			$updateCart->save();
		}
		$response = ['order_id' => $order_id];
		return ['status' => true, 'array' => $response, 'msg' => 'Order Received Successfull.'];
	}

	private function distributePayout($totAp, $order_id)
	{
		$bv = 1;
		$level_rules = [
			1 => ['direct' => 0, 'percent' => 0.40],
			2 => ['direct' => 1, 'percent' => 0.08],
			3 => ['direct' => 1, 'percent' => 0.05],
			4 => ['direct' => 2, 'percent' => 0.04],
			5 => ['direct' => 2, 'percent' => 0.03],
			6 => ['direct' => 2, 'percent' => 0.02],
			7 => ['direct' => 3, 'percent' => 0.02],
			8 => ['direct' => 3, 'percent' => 0.02],
			9 => ['direct' => 3, 'percent' => 0.02],
			10 => ['direct' => 4, 'percent' => 0.02],
			11 => ['direct' => 4, 'percent' => 0.01],
			12 => ['direct' => 4, 'percent' => 0.01],
			13 => ['direct' => 5, 'percent' => 0.01],
			14 => ['direct' => 5, 'percent' => 0.01],
			15 => ['direct' => 5, 'percent' => 0.01],
		];

		$level_member = LevelCount::where('child_id', Auth::user()->user_nm)
			->where('level', '>', 1)
			->where('level', '<=', 15)
			->get();

		if ($level_member->isNotEmpty()) {
			foreach ($level_member as $lvl) {
				$level = $lvl->level;
				if (!isset($level_rules[$level]))
					continue;

				$rule = $level_rules[$level];
				$uid = "UNI" . $lvl->child_id;

				$user = User::where('user_nm', $lvl->parent_id)->first();
				if (!$user) {
					Log::debug("User not found for parent_id: {$lvl->parent_id}");
					continue;
				}

				if ($user->isactive != 1 || $user->restricted != 0) {
					Log::info("User {$lvl->parent_id} is inactive or restricted at level $level.");
					continue;
				}

				$direct_count = User::where('refid', $lvl->parent_id)
					->where('isactive', 1)
					->count();

				if ($direct_count >= $rule['direct']) {
					$amt1 = $totAp * $rule['percent'];
					$amt = $amt1 * $bv;
					$in_type = "Start Bonus from {$uid} - level {$level}";

					$insert = insert_payout($amt, $lvl->parent_id, $in_type, $lvl->child_id, $order_id, $totAp, $level);

					if ($insert) {
						add_wallet1(1, $lvl->parent_id, $amt, $order_id, 'gen_payout');
					} else {
						Log::info("$amt not inserted at Level $level >>> Payout user id: {$lvl->parent_id}");
					}
				} else {
					Log::info("User {$lvl->parent_id} missed Level $level bonus. Required directs: {$rule['direct']}, Found: $direct_count");
				}
			}
		}
		Log::debug('Level members:', $level_member->toArray());

		//$levelCount = levelCount($lvl->child_id);


	}


	private function checkWallet($amount, $order_id, $types, $totDiscount, $checkblance = false)
	{
		$wallet = Wallet::where('userid', Auth::user()->user_id)->first();
		if (!$wallet) {
			return ['status' => false, 'msg' => 'Wallet not found.'];
		}
		/*if ($types == 'ap_order') {
			$point = ($wallet->bp >= $totDiscount) ? $totDiscount : $wallet->bp;
		} elseif ($types == 'repurchase_order') {
			$point = ($wallet->bp >= $totDiscount) ? $totDiscount : 0;
		} else {
			$point = $totDiscount;
		}*/
		$point = $totDiscount;
		$bp = $wallet->bp - $point;

		$amt = $amtAdd = 0;
		$unicash = 0;
		$senderAmt = 0;
		if ($wallet->earning >= $amount) {
			$senderAmt = $wallet->earning;
			$effectAmt = $wallet->earning - $amount;
			$earning = $amount;
			$balance = $effectAmt + $wallet->amount + $wallet->unicash;
			$des = 'Order Amount Deducted from amount : 0 unicash: 0 earning : ' . $amount . ' bp :' . $bp;

			$wallet->earning = $effectAmt;
			$wallet->bp = ($wallet->bp - $point);
		} else if ($wallet->unicash + $wallet->earning >= $amount) {

			$remainAmt = $amount - $wallet->earning;//1000-500
			$earning = $wallet->earning;
			$unicash = $remainAmt;//500
			$senderAmt = $wallet->earning + $wallet->unicash;
			$effectAmt = $wallet->unicash + $wallet->earning - $amount;
			$balance = $effectAmt + $wallet->amount;
			$des = 'Order Amount Deducted from amount : 0 unicash: ' . $remainAmt . ' earning : ' . $wallet->earning . ' bp :' . $bp;

			$wallet->earning = 0;
			$wallet->unicash = ($wallet->unicash - $remainAmt);
			$wallet->bp = ($wallet->bp - $point);
		} else if ($wallet->earning + $wallet->unicash + $wallet->amount >= $amount) {

			$amt = $amount - $wallet->earning;
			$remainingAmt = $amt - $wallet->unicash;
			$amtAdd = $remainingAmt;
			$unicash = $wallet->unicash;
			$senderAmt = $wallet->earning + $wallet->unicash + $wallet->amount;
			$balance = $wallet->unicash + $wallet->earning + $wallet->amount - $amount;
			$earning = $wallet->earning;
			$des = 'Order Amount Deducted from amount : ' . $remainingAmt . ' unicash: ' . $wallet->unicash . ' earning : ' . $wallet->earning . ' bp :' . $bp;

			$wallet->earning = 0;
			$wallet->unicash = 0;
			$wallet->amount = ($wallet->amount - $remainingAmt);
			$wallet->bp = ($wallet->bp - $point);
		}

		if ($senderAmt >= $amount) {

			if ($checkblance)
				return ['status' => true, 'msg' => 'For check balance'];

			if (!$wallet->save())
				return ['status' => false, 'msg' => 'Something Went wrong wallet not updated.'];
			$wallet = Wallet::where('userid', Auth::user()->user_id)->first();
			$transPayload = [
				'unm' => $wallet->unm,
				'user_id' => Auth::user()->user_id,
				'transition_type' => $types,
				'debit' => $amount,
				'balance' => $balance,
				'in_type' => 'Your Wallet is Debited ' . $amount . ' for ' . $types . ' to ' . $order_id . '',
				'description' => $des,
				'amount' => $amtAdd,
				'unicash' => $unicash,
				'earning' => $earning,
				'unipoint' => $point,
				'order_id' => $order_id
			];

			if (walletTransaction($transPayload)) {
				return ['status' => true, 'msg' => 'Wallet updated Successfully.'];
			} else {
				return ['status' => false, 'msg' => 'Amount not debit.'];
			}
		} else {
			return ['status' => false, 'msg' => 'insufficent Amount.'];
		}
	}


	private function updateMemberBelow($mid = 0, $type = '')
	{
		$count = 0;
		while ($mid) {
			$binary = BinaryData::where('userid', $mid)->first();
			$position = $binary->position;
			$posid = $binary->posid;
			if ($posid == '0')
				break;

			$save = MemberBelow::where('mid', $posid)->first();
			$save->lfree = ($position == 'L' && $type == 'free') ? $save->lfree + 1 : $save->lfree;
			$save->lpaid = ($position == 'L' && $type == 'paid') ? $save->lpaid + 1 : $save->lpaid;
			$save->rfree = ($position == 'R' && $type == 'free') ? $save->rfree + 1 : $save->rfree;
			$save->rpaid = ($position == 'R' && $type == 'paid') ? $save->rpaid + 1 : $save->rpaid;
			if ($save->save())
				$count++;

			$mid = $posid;
		}
		return $count;
	}


	private function updateRp($mid = 0, $rp = '', $user = '', $order_id = '')
	{
		$count = 0;
		while ($mid) {
			$binary = BinaryData::where('userid', $mid)->first();
			$position = $binary->position;
			$posid = $binary->posid;
			if ($posid == "0")
				break;

			$memberBv = MemberBv::where('mid', $posid)->first();
			if ($position == "L") {
				$rpA = $memberBv->rpApaid;
				$memberBv->rpApaid = $rpA + $rp;
			} else {
				$rpB = $memberBv->rpBpaid;
				$memberBv->rpBpaid = $rpB + $rp;
			}
			$mid = $posid;
			if ($memberBv->save()) {
				$rpTrans = new RpTransition();
				$rpTrans->user_id = $user;
				$rpTrans->ref_id = $mid;
				$rpTrans->order_id = $order_id;
				$rpTrans->rp = $rp;
				$rpTrans->status = 0;
				$rpTrans->position = $position;
				$rpTrans->cur_date = date('Y-m-d');
				$rpTrans->pid = 0;
				if ($rpTrans->save())
					$count++;
			}
		}
		return $count;
	}
}