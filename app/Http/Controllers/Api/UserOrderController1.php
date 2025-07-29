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

class UserOrderController1 extends Controller
{

	public function index(Request $request)
	{
		try {
			$uid = Auth::user()->user_id;
			$records = array();
			$apOrder = DB::table('ap_repurchase_order')->select(DB::raw('sum(rp) as rp, order_id'))->where('uid', $uid)->groupBy('order_id')->orderBy('id', 'desc')->get();
			if ($apOrder->isNotEmpty()) {
				foreach ($apOrder as $cat) {
					$row = ApRepurchaseOrder::select('tot_qty', 'totalNetAmt', 'status', 'url', 'delivery_mode', 'cur_date')->where('order_id', $cat->order_id)->first();
					$records['primary'][] = array(
						'order_id'     => $cat->order_id,
						'rp'           => $cat->rp,
						'qty'          => $row->tot_qty,
						'net_amount'   => $row->totalNetAmt,
						'tp'           => 'ap',
						'status'       => $row->status,
						'tracking_url' => ($row->delivery_mode == 'self_pickup' || $row->delivery_mode == '') ? 'Self Pickup' : $row->url,
						'date'         => $row->cur_date
					);
				}
			}

			$rpOrder = DB::table('repurchase_order')->select(DB::raw('sum(rp) as rp, order_id'))->where('uid', $uid)->groupBy('order_id')->orderBy('id', 'desc')->get();
			if ($rpOrder->isNotEmpty()) {
				foreach ($rpOrder as $cat) {
					$row = RepurchaseOrder::select('tot_qty', 'totalNetAmt', 'status', 'url', 'delivery_mode', 'cur_date')->where('order_id', $cat->order_id)->first();
					$records['primary'][] = array(
						'order_id'     => $cat->order_id,
						'rp'           => $cat->rp,
						'qty'          => $row->tot_qty,
						'net_amount'   => $row->totalNetAmt,
						'tp'           => 'rp',
						'status'       => $row->status,
						'tracking_url' => ($row->delivery_mode == 'self_pickup' || $row->delivery_mode == '') ? 'Self Pickup' : $row->url,
						'date'         => $row->cur_date
					);
				}
			}

			$dealOrder = DB::table('deals_order')->select(DB::raw('order_id'))->where('uid', $uid)->groupBy('order_id')->orderBy('id', 'desc')->get();
			if ($dealOrder->isNotEmpty()) {
				foreach ($dealOrder as $cat) {
					$row = DealOrders::where('order_id', $cat->order_id)->first();
					$records['deals'][] = array(
						'order_id'     => $cat->order_id,
						'qty'          => $row->totQty,
						'net_amtount'  => $row->totalNetAmt + $row->shippingChrg,
						'tp'           => 'deals',
						'status'       => $row->status,
						'tracking_url' => '',
						'date'         => $row->for_date

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
			$rules = ['type' => '|string|in:deals,other'];
			$rules['shipping_address_id'] = 'required|numeric';
			$rules['delivery_mode']       = 'required|in:self_pickup,courier';
			
			if(empty(Auth::user()->isactive) || Auth::user()->isactive == 0){
					$rules['reffer_id']       = 'nullable|numeric';	
				
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
				$res = $this->saveDealOrder($request);
			} else {
					if (Auth::user()->isactive == 1) {
					$res = $this->RpOrder($request);
				} else {
					$res = $this->ApOrder($request);
				}	
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

		$user_id       = Auth::user()->user_id;
		$delivery_mode = $request->delivery_mode;
		$reffer_id     = $request->reffer_id;

		$order_id = date('ymds') . rand(1111, 9999);
		$uniqueOd = "UNI" . date('ymdis') . rand(11111, 99999);

		$cartRes = Cart::with('Product')->where('uid', Auth::user()->user_id)->where('status', 0)->where('cart_type', 'ap_shopping')->get();
		if ($cartRes->isEmpty())
			return ['status' => false, 'msg' => 'Your cart is empty or missmatch cart details.'];

		foreach ($cartRes as $cart) {
			$totAp       += $cart->rp;
			$totalQty    += $cart->qty;
			$totalGross  += $cart->grossAmt;
			$totalGst    += $cart->gstAmt;
			$totalNetAmt += $cart->netAmt;
			$totDiscount += $cart->disAmt;
			$totAmt 	 += $cart->totAmt;
		}

		$shippingCharge = 0;
		if (($totalNetAmt <= 999) && $delivery_mode != 'self_pickup')
			$shippingCharge = 100;

		$checkWallet =  $this->checkWallet(($totalNetAmt + $shippingCharge), $order_id, 'ap_order', $totDiscount, true);
		if (!$checkWallet['status'])
			return ['status' => false, 'msg' => $checkWallet['msg']];

		$inserId = [];
		foreach ($cartRes as $cart) {
			$amount       = $cart->amt * $cart->qty;
			$qry = new ApRepurchaseOrder();
			$qry->pid               = $cart->pid;
			$qry->cart_id           = $cart->id;
			$qry->uid               = Auth::user()->user_id;
			$qry->order_id          = $order_id;
			$qry->qty               = $cart->qty;
			$qry->tot_qty           = $totalQty;
			$qry->unit_price        = $cart->amt;
			$qry->amount            = $amount;
			$qry->totAmt            = $totAmt;
			$qry->discountRate      = $cart->up;
			$qry->netAmt            = $cart->netAmt;
			$qry->address           = $request->shipping_address_id;
			$qry->shippingCharge    = $shippingCharge;
			$qry->cur_date          = date('Y-m-d');
			$qry->delivery_mode     = ucwords(str_replace('_', ' ', $request->delivery_mode));
			$qry->uniqueOd          = $uniqueOd;
			$qry->status            = 'pending';
			$qry->rp                = $cart->rp;
			$qry->size_id           = $cart->size_id;
			$qry->color_id          = $cart->color_id;
			$qry->reffer_id         = $request->reffer_id;
			$qry->totalGross        = $totalGross;
			$qry->totalNetAmt       = $totalNetAmt;
			$qry->totalGst          = $totalGst;
			$qry->umart_id          = 0;
			$qry->mm_id             = 0;
			$qry->url               = '';
			$qry->delivered_by      = '';
			$qry->delivered_by_id   = 0;
			$qry->delivered_date    = '';
			$qry->flag              = 0;

			$discountAmts   = $cart->disAmt;
			$gross          = $amount - $discountAmts;
			$gst            = $gross * $cart->Product->igst / 100;
			$net            = $gross + $gst;

			$qry->discountAmt      = $cart->disAmt;
			$qry->grossAmt         = $gross;
			$qry->netAmt           = $net;
			$qry->gstAmt           = $gst;
			$qry->save();

			$inserId[] = $qry->id;
			$rps[]    = $cart->rp * $cart->qty;
		}

		if (empty($inserId))
			return ['status' => false, 'msg' => 'Something Went Wrong,Order not created.'];

		$checkWallet = $this->checkWallet(($totalNetAmt + $shippingCharge), $order_id, 'ap_order', $totDiscount, false);
		if (!$checkWallet['status'])
			return ['status' => false, 'msg' => ($checkWallet['msg'])];

		$getSumRp = ApRepurchaseOrder::select(DB::raw('SUM(rp) as rp'), DB::raw('SUM(grossAmt) as totGrossAmnt'))->where('uid', Auth::user()->user_id)->where('flag', 0)->where('status', '!=', 'Refund')->first();
		$rp = $getSumRp->rp; //array_sum($rps);

		$countOrder = ApRepurchaseOrder::select(DB::raw('count(order_id) as order_id'))->where('uid', $user_id)->where('status', '!=', 'Refund')->groupBy('order_id')->get()->count();
		if ($countOrder > 1) {
			$bonus = "0";
			$purstep = 2;
		} else {
			$purstep = 1;
			add_wallet(3, Auth::user()->user_id, $getSumRp->totGrossAmnt);
			$bonus = $getSumRp->totGrossAmnt;
		}
		$this->distributePayout($totAp);

		if ($rp >= 12) {
			if (!updateorgPaid($user_id, $totAp, 'orgap', $user_id, $order_id))
				Log::info("Something Went Wrong, orgap not update, UserId- $user_id , OrderId- $order_id");

			if (!updateorgPaid($user_id, 1, 'org', $user_id, $order_id))
				Log::info("Something Went Wrong, org not update, UserId- $user_id , OrderId- $order_id");

			if (!$this->updateMemberBelow($user_id, 'paid'))
				Log::info("Something Went Wrong, member below not update, UserId- $user_id , OrderId- $order_id");

			$userLvl = User::where('user_id', $user_id)->first();
			$userLvl->isactive = 1;
			$userLvl->upgrade_date = date('Y-m-d');

			$insertOffer = new OfferPayout();
			$insertOffer->uid      = $user_id;
			$insertOffer->refid    = $reffer_id;
			$insertOffer->for_date = date("Y-m-d");
			if (!$userLvl->save() || !$insertOffer->save())
				return ['status' => false, 'msg' => 'Something Went Wrong, Order not created.'];

			$msg = 'Congratulations for Become UNI Distributor....Your Order Received SucupdateRpcessfully. Your Order Id is ' . $order_id;
			$message = 'Total AP : ' . $rp;
		} else {
			$rps = 12 - $getSumRp->rp;
			if (!updateorgPaid($user_id, $totAp, 'orgap', $user_id, $order_id))
				Log::info("Something Went Wrong, orgap not update, UserId- $user_id , OrderId- $order_id");

			$msg = 'Your Order Received Successfully. Your Order Id is ' . $order_id . '....... To Become Distributor you have to purchase ' . $rps . ' more AP Products..... ';
			$message = 'For Become Distributer you have to purchase ' . $rps . ' more AP Products.';
		}

		foreach ($cartRes as $cart) {
			$updateCart = Cart::find($cart->id);
			$updateCart->status = 1;
			$updateCart->save();
		}

		$resResponse = [
			'order_id' => $order_id,
			'message'  => $message,
			'purchase' => $purstep,
			'bonus'    => $bonus
		];
		return ['status' => true, 'array' => $resResponse, 'msg' => $msg];
	}

	private function RpOrder($request)
	{
		$totAp = $totalQty = $totalGross = $totalGst = $totalNetAmt = $totDiscount = $totAmt = 0;

		$user_id       = Auth::user()->user_id;
		$delivery_mode = $request->delivery_mode;
		$order_id      = date('ymds') . rand(1111, 9999);
		$uniqueOd      = "UNI" . date('ymdis') . rand(11111, 99999);

		$cartRes = Cart::with('Product')->where('uid', $user_id)->where('status', 0)->where('cart_type', 'shopping')->get();

		if ($cartRes->isEmpty())
			return ['status' => false, 'msg' => 'Your cart is empty or missmatch cart details.'];


		foreach ($cartRes as $cart) {
			$totAp       += $cart->rp;
			$totalQty    += $cart->qty;
			$totalGross  += $cart->grossAmt;
			$totalGst    += $cart->gstAmt;
			$totalNetAmt += $cart->netAmt;
			$totDiscount += $cart->disAmt;
			$totAmt 	 += $cart->totAmt;
		}

		$shippingCharge = 0;
		if (($totalNetAmt <= 999) && $delivery_mode != 'self_pickup')
			$shippingCharge = 100;

		$checkWallet =  $this->checkWallet(($totalNetAmt + $shippingCharge), $order_id, 'repurchase_order', $totDiscount, true);
		if (!$checkWallet['status'])
			return ['status' => false, 'msg' => $checkWallet['msg']];

		$rps = [];
		foreach ($cartRes as $key => $cart) {
			$amount       = $cart->amt * $cart->qty;
			$qry = new RepurchaseOrder();
			$qry->pid               = $cart->pid;
			$qry->cart_id           = $cart->id;
			$qry->uid               = Auth::user()->user_id;
			$qry->order_id          = $order_id;
			$qry->qty               = $cart->qty;
			$qry->tot_qty           = $totalQty;
			$qry->unit_price        = $cart->amt;
			$qry->amount            = $amount;
			$qry->totAmt            = $totAmt;
			$qry->discountRate      = $cart->up;
			$qry->netAmt            = $cart->netAmt;
			$qry->address           = $request->shipping_address_id;
			$qry->shippingCharge    = $shippingCharge;
			$qry->cur_date          = date('Y-m-d');
			$qry->delivery_mode     = $request->delivery_mode;
			$qry->uniqueOd          = $uniqueOd;
			$qry->status            = 'pending';
			$qry->rp                = $cart->rp;
			$qry->size_id           = $cart->size_id;
			$qry->color_id          = $cart->color_id;
			//$qry->reffer_id         = $request->reffer_id;
			$qry->totalGross        = $totalGross;
			$qry->totalNetAmt       = $totalNetAmt;
			$qry->totalGst          = $totalGst;
			//$qry->umart_id          = 0;
			//$qry->mm_id             = 0;
			$qry->url               = '';
			$qry->delivered_by      = '';
			$qry->delivered_by_id   = 0;
			$qry->delivered_date    = '';
			$qry->flag              = 0;

			$discountAmts   = $cart->disAmt;
			$gross          = $amount - $discountAmts;
			$gst            = $gross * $cart->Product->igst / 100;
			$net            = $gross + $gst;

			$qry->discountAmt      = $cart->disAmt;
			$qry->grossAmt         = $gross;
			$qry->netAmt           = $net;
			$qry->gstAmt           = $gst;
			$qry->save();

			$inserId[] = $qry->id;
			$rps[]     = $cart->rp * $cart->qty;
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
		$shippingAmt  = 999;
		$shippingChrg = 0;
		$order_id     = "DEAL" . date('ymdis') . rand(11111, 99999);

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
				'uid'      => $user_id,
				'address'  => $address,
				'order_id' => $order_id,
				'qty'      => $cart->qty,
				'amount'   => $cart->amt,
				'gstAmt'   => $gst,
				'netAmt'   => $totAmtAftDis,
				'totalAmt' => $cart->totAmt,
				'discount' => $dis,
				'grossAmt' => $gross,
				'size_id'  => $cart->size_id,
				'color_id' => $cart->color_id,
				'pid'      => $cart->pid,
				'cart_id'  => $cart->id
			);
			$totAmt     += $cart->totAmt;
			$totalNet   += $totAmtAftDis;
			$totDis     += $dis;
			$totQty     += $cart->qty;
			$totGst     += $gst;
			$totalGross += $gross;
		}

		$shippingChrg = 0;
		if ($totalNet <= $shippingAmt && $delivery_mode != 'self_pickup')
			$shippingChrg = 100;

		$checkWallet =  $this->checkWallet(($totalNet + $shippingChrg), $order_id, 'deal_order', $totDis, true);
		if (!$checkWallet['status'])
			return ['status' => false, 'msg' => $checkWallet['msg']];


		$ins_id = [];
		foreach ($final_array as $final) {

			$insQry = new DealOrders();
			$insQry->uid      = $final['uid'] ?? '';
			$insQry->address  = $address;
			$insQry->order_id = $order_id;
			$insQry->qty      = $final['qty'] ?? 0;
			$insQry->amount   = $final['amount'] ?? 0;
			$insQry->gstAmt   = $final['gstAmt'] ?? 0;
			$insQry->netAmt   = $final['netAmt'] ?? 0;
			$insQry->totalAmt = $final['totalAmt'] ?? 0;
			$insQry->discount = $final['discount'] ?? 0;
			$insQry->grossAmt = $final['grossAmt'] ?? 0;
			$insQry->size_id  = $final['size_id'] ?? 0;
			$insQry->color_id = $final['color_id'] ?? 0;
			$insQry->pid      = $final['pid'] ?? 0;
			//$insQry->cart_id = $final['cart_id'];
			$insQry->totalNetAmt   = $totalNet;
			$insQry->totalDis      = $totDis;
			$insQry->shippingChrg  = $shippingChrg;
			$insQry->totQty        = $totQty;
			$insQry->totGst        = $totGst;
			$insQry->totalGross    = $totalGross;
			$insQry->totalAmount   = $totAmt;
			$insQry->status        = 'pending';
			$insQry->for_date      = date('Y-m-d H:i:s');
			$insQry->delivery_mode = ($delivery_mode=='self_pickup')?'Self Pickup':'Courier';
			$insQry->save();
			$ins_id[] = $insQry->id;
		}
		if (empty($ins_id))
			return ['status' => false, 'msg' => 'Something Went wrong, Order not created.'];

		$checkWallet =  $this->checkWallet(($totalNet + $shippingChrg), $order_id, 'deal_order', $totDis, false);
		if (!$checkWallet['status'])
			return ['status' => false, 'msg' => $checkWallet['msg']];

		foreach ($cartRes as $cart) {
			$updateCart = Cart::find($cart->id);
			$updateCart->status = 1;
			$updateCart->save();
		}
		$response = ['order_id' => $order_id];
		return ['status' => true, 'array' => $response, 'msg' => 'Order Received Successfull.'];
	}

	private function distributePayout($totAp)
	{
		$level_member = LevelCount::where('child_id', Auth::user()->user_id)->get();

		foreach ($level_member as $key => $lvl) {
			$uid = "UNI" . $lvl->child_id;
			if ($lvl->level == 1) {
				$amt1 = $totAp * 0.2;
				$amt = $amt1 * 20;
				$in_type = 'Start Bonus from ' . $uid . '-level 1';
				$insert = insert_payout($amt, $lvl->parent_id, $in_type, $lvl->child_id);
				if ($insert) {
				} else {
					Log::info("$amt data not insert Level 1 >>> Payout user id -'.$lvl->parent_id.'>>>'.date('Y-m-d')[$uid]");
				}
			} elseif ($lvl->level == 2) {
				$get_row = get_users($lvl->parent_id);
				foreach ($get_row as $key => $user_lvl) {
					if ($user_lvl->isactive == 1 && $user_lvl->restricted == 0) {
						$sumActive = getSumActive($user_lvl->user_id, 1);
						if ($sumActive->sum * 2 >= $lvl->level) {
							$amt1 = $totAp * 0.05;
							$amt = $amt1 * 20;
							$in_type = 'Start Bonus from ' . $uid . '-level 2';
							$insert = insert_payout($amt, $lvl->parent_id, $in_type, $lvl->child_id);
							if ($insert) {
							} else {
								Log::info("$amt data not insert Level 2 >>> Payout user id -'.$lvl->parent_id.'>>>'.date('Y-m-d')[$uid]");
							}
						} else {
							$parent = $lvl->parent_id;
							$msg = $parent . ' Start Bonus missed from Level 2 >>> Payout user id -' . $uid . '>>>' . date('Y-m-d');
							Log::info($msg);
						}
					} else {
						$msg = $lvl->parent_id . 'Start Bonus missed  due to  restricted or isactive  from Level 2>>> Payout user id -' . $uid . '>>>' . date('Y-m-d');
						Log::info($msg);
					}
				}
			} elseif ($lvl->level == 3) {
				$get_row = User::where('user_id', $lvl->parent_id)->get();
				//echo $this->db->last_query();die;
				foreach ($get_row as $key => $user_lvl) {
					if ($user_lvl->isactive == 1 && $user_lvl->restricted == 0) {
						$sumActive = getSumActive($user_lvl->user_id, 1);
						if ($sumActive->sum * 2 >= $lvl->level) {
							//if(1){
							$amt1 = $totAp * 0.03;
							$amt = $amt1 * 20;
							$in_type = 'Start Bonus from ' . $uid . '-level 3';

							$insert = insert_payout($amt, $lvl->parent_id, $in_type, $lvl->child_id);
							if ($insert) {
							} else {
								$msg = $lvl->parent_id . 'data not insert Level 3 >>> Payout user id -' . $uid . '>>>' . date('Y-m-d');
								Log::info($msg);
							}
						} else {
							$msg = $lvl->parent_id . '- Start Bonus missed from Level 3 >>> Payout user id -' . $uid . '>>>' . date('Y-m-d');
							Log::info($msg);
						}
					} else {
						$msg =  $lvl->parent_id . 'Start Bonus missed  due to  restricted or isactive  from Level 3 >>> Payout user id -' . $uid . '>>>' . date('Y-m-d');
						Log::info($msg);
					}
				}
			} elseif ($lvl->level == 4) {
				$get_row = User::where('user_id', $lvl->parent_id)->get();
				//echo $this->db->last_query();die;
				foreach ($get_row as $key => $user_lvl) {
					if ($user_lvl->isactive == 1 && $user_lvl->restricted == 0) {
						$sumActive = getSumActive($user_lvl->user_id, 1);
						if ($sumActive->sum * 2 >= $lvl->level) {
							$amt1 = $totAp * 0.025;
							$amt = $amt1 * 20;
							$in_type = 'Start Bonus from ' . $uid . '-level 4';

							$insert = insert_payout($amt, $lvl->parent_id, $in_type, $lvl->child_id);
							if ($insert) {
							} else {
								$msg = $lvl->parent_id . 'data not insert Level 4 >>> Payout user id -' . $uid . '>>>' . date('Y-m-d');
								Log::info($msg);
							}
						} else {
							$msg = $lvl->parent_id . '- Start Bonus missed from Level 4 >>> Payout user id -' . $uid . '>>>' . date('Y-m-d');
							Log::info($msg);
						}
					} else {
						//echo  $parent = $lvl->parent_id;
						$msg =  $parent = $lvl->parent_id . 'Start Bonus missed  due to  restricted or isactive  from Level 4 >>> Payout user id -' . $uid . '>>>' . date('Y-m-d');
						Log::info($msg);
					}
				}
			} elseif ($lvl->level == 5) {
				$get_row = User::where('user_id', $lvl->parent_id)->get();
				//echo $this->db->last_query();die;
				foreach ($get_row as $key => $user_lvl) {
					if ($user_lvl->isactive == 1 && $user_lvl->restricted == 0) {
						$sumActive = getSumActive($user_lvl->user_id, 1);

						if ($sumActive->sum * 2 >= $lvl->level) {
							//if(1){
							$amt1 = $totAp * 0.025;
							$amt = $amt1 * 20;
							$parent = $lvl->parent_id;
							$in_type = 'Start Bonus from ' . $uid . '-level 5';
							$insert = insert_payout($amt, $lvl->parent_id, $in_type, $lvl->child_id);
							if ($insert) {
							} else {
								$msg = $lvl->parent_id . 'data not insert Level 5 >>> Payout user id -' . $uid . '>>>' . date('Y-m-d');
								Log::info($msg);
							}
						} else {
							$msg = $lvl->parent_id . '- Start Bonus missed from Level 5 >>> Payout user id -' . $uid . '>>>' . date('Y-m-d');
							Log::info($msg);
						}
					} else {

						$msg =  $lvl->parent_id . 'Start Bonus missed  due to  restricted or isactive  from Level 5 >>> Payout user id -' . $uid . '>>>' . date('Y-m-d');
						Log::info($msg);
					}
				}
			} elseif ($lvl->level == 6) { //level 6
				$get_row = User::where('user_id', $lvl->parent_id)->get();
				foreach ($get_row as $key => $user_lvl) {
					if ($user_lvl->isactive == 1 && $user_lvl->restricted == 0) {
						$sumActive = getSumActive($user_lvl->user_id, 1);
						if ($sumActive->sum * 2 >= $lvl->level) {
							$twice = $sumActive->sum * 2;
							$amt1 = $totAp * 0.025;
							$amt = $amt1 * 20;
							$in_type = 'Start Bonus from ' . $uid . '-level 6';

							$insert = insert_payout($amt, $lvl->parent_id, $in_type, $lvl->child_id);
							if ($insert) {
							} else {
								$msg = $lvl->parent_id . 'data not insert Level 6 >>> Payout user id -' . $uid . '>>> ' . date('Y-m-d');
								Log::info($msg);
							}
						} else {
							$msg = $lvl->parent_id . '- Start Bonus missed from Level 6 >>> Payout user id -' . $uid . '>>>' . date('Y-m-d');
							Log::info($msg);
						}
					} else {
						$msg = $lvl->parent_id . '- Start Bonus  missed  due to  restricted or isactive from Level 6 >>> Payout user id -' . $uid . '>>>' . date('Y-m-d');
						Log::info($msg);
					}
				}
			}
			if ($lvl->level == 7) { //level 7
				$get_row = User::where('user_id', $lvl->parent_id)->get();

				foreach ($get_row as $key => $user_lvl) {
					if ($user_lvl->isactive == 1 && $user_lvl->restricted == 0) {
						$sumActive = getSumActive($user_lvl->user_id, 1);

						if ($sumActive->sum * 2 >= $lvl->level) {
							$amt1 = $totAp * 0.025;
							$amt = $amt1 * 20;
							$in_type = 'Start Bonus from ' . $uid . '-level 7';
							$insert = insert_payout($amt, $lvl->parent_id, $in_type, $lvl->child_id);
							if ($insert) {
							} else {
								$msg = $lvl->parent_id . 'data not insert Level 7 >>> Payout user id -' . $uid . '>>> ' . date('Y-m-d');
								Log::info($msg);
							}
						} else {
							$msg = $lvl->parent_id . '- Start Bonus missed from Level 7 >>> Payout user id -' . $uid . '>>>' . date('Y-m-d');
							Log::info($msg);
						}
					} else {
						$msg = $lvl->parent_id . '- Start Bonus  missed  due to  restricted or isactive from Level 7 >>> Payout user id -' . $uid . '>>>' . date('Y-m-d');
						Log::info($msg);
					}
				}
			}
			if ($lvl->level == 8) { //level 8
				$get_row = User::where('user_id', $lvl->parent_id)->get();
				foreach ($get_row as $key => $user_lvl) {
					if ($user_lvl->isactive == 1 && $user_lvl->restricted == 0) {
						$sumActive = getSumActive($user_lvl->user_id, 1);
						if ($sumActive->sum * 2 >= $lvl->level) {
							$amt1 = $totAp * 0.020;
							$amt = $amt1 * 20;
							$in_type = 'Start Bonus from ' . $uid . '-level 8';
							$insert = insert_payout($amt, $lvl->parent_id, $in_type, $lvl->child_id);
							if ($insert) {
							} else {
								$msg = $lvl->parent_id . 'data not insert Level 8 >>> Payout user id -' . $uid . '>>> ' . date('Y-m-d');
								Log::info($msg);
							}
						} else {
							$msg = $lvl->parent_id . '- Start Bonus missed from Level 8 >>> Payout user id -' . $uid . '>>>' . date('Y-m-d');
							Log::info($msg);
						}
					} else {
						$msg =  $lvl->parent_id . 'Start Bonus missed  due to  restricted or isactive  from Level 8 >>> Payout user id -' . $uid . '>>>' . date('Y-m-d');
						Log::info($msg);
					}
				}
			}
			if ($lvl->level == 9) { //level 9 ----------------------------------------
				$get_row = User::where('user_id', $lvl->parent_id)->get();
				foreach ($get_row as $key => $user_lvl) {
					if ($user_lvl->isactive == 1 && $user_lvl->restricted == 0) {
						$sumActive = getSumActive($user_lvl->user_id, 1);
						if ($sumActive->sum * 2 >= $lvl->level) {
							$twice = $sumActive->sum * 2;
							$amt1 = $totAp * 0.020;
							$amt = $amt1 * 20;
							$in_type = 'Start Bonus from ' . $uid . '-level 9';
							$insert = insert_payout($amt, $lvl->parent_id, $in_type, $lvl->child_id);
							if ($insert) {
							} else {
								$msg = $lvl->parent_id . 'data not insert Level 9 >>> Payout user id -' . $uid . '>>> ' . date('Y-m-d');
								Log::info($msg);
							}
						} else {
							$msg = $lvl->parent_id . '- Start Bonus missed from Level 9 >>> Payout user id -' . $uid . '>>>' . date('Y-m-d');
							Log::info($msg);
						}
					} else {
						$msg =  $lvl->parent_id . 'Start Bonus missed  due to  restricted or isactive  from Level 9 >>> Payout user id -' . $uid . '>>>' . date('Y-m-d');
						Log::info($msg);
					}
				}
			}
			if ($lvl->level == 10) { //level 10 -----------------------------
				$get_row = User::where('user_id', $lvl->parent_id)->get();
				foreach ($get_row as $key => $user_lvl) {
					if ($user_lvl->isactive == 1 && $user_lvl->restricted == 0) {
						$sumActive = getSumActive($user_lvl->user_id, 1);
						if ($sumActive->sum * 2 >= $lvl->level) {
							$twice = $sumActive->sum * 2;
							$amt1 = $totAp * 0.03;
							$amt = $amt1 * 20;
							$in_type = 'Start Bonus from ' . $uid . '-level 10';
							$insert = insert_payout($amt, $lvl->parent_id, $in_type, $lvl->child_id);
							if ($insert) {
							} else {
								$msg = 'data not insert Level 10 >>> Payout user id -' . $uid . '>>> ' . date('Y-m-d');
								Log::info($msg);
							}
						} else {
							$msg = $lvl->parent_id . '- Start Bonus missed from Level 10 >>> Payout user id -' . $uid . '>>>' . date('Y-m-d');
							Log::info($msg);
						}
					} else {
						$msg =  'Start Bonus missed  due to  restricted or isactive  from Level 10 >>> Payout user id -' . $uid . '>>>' . date('Y-m-d');
						Log::info($msg);
					}
				}
			}
			$levelCount = levelCount($lvl->child_id);
		}
	}

	private function checkWallet($amount, $order_id, $types, $totDiscount, $checkblance = false)
	{
		$wallet = Wallet::where('userid', Auth::user()->user_id)->first();
		if ($types == 'ap_order') {
			$point = ($wallet->bp >= $totDiscount) ? $totDiscount : $wallet->bp;
		} elseif ($types == 'repurchase_order') {
			$point = ($wallet->bp >= $totDiscount) ? $totDiscount : 0;
		} else {
			$point = $totDiscount;
		}

		$bp = $wallet->bp - $point;

		$amt       = 0;
		$unicash   = 0;
		$senderAmt = 0;
		if ($wallet->earning >= $amount) {
			$senderAmt   = $wallet->earning;
			$effectAmt   = $wallet->earning - $amount;
			$earning     = $amount;
			$balance     = $effectAmt + $wallet->amount + $wallet->unicash;
			$des         = 'Order Amount Deducted from amount : 0 unicash: 0 earning : ' . $amount . ' bp :' . $bp;

			$wallet->earning = $effectAmt;
			$wallet->bp      = ($wallet->bp - $point);
		} else if ($wallet->unicash + $wallet->earning >= $amount) {

			$remainAmt   = $amount - $wallet->earning;
			$earning     = $wallet->earning;
			$unicash     = $remainAmt;
			$senderAmt   = $wallet->earning + $wallet->unicash;
			$effectAmt   = $wallet->unicash + $wallet->earning - $amount;
			$balance     = $effectAmt + $wallet->amount;
			$des         = 'Order Amount Deducted from amount : 0 unicash: ' . $remainAmt . ' earning : ' . $wallet->earning . ' bp :' . $bp;

			$wallet->earning = 0;
			$wallet->unicash = ($wallet->unicash - $remainAmt);
			$wallet->bp      = ($wallet->bp - $point);
		} else if ($wallet->earning + $wallet->unicash + $wallet->amount >= $amount) {

			$amt          = $amount - $wallet->earning;
			$remainingAmt = $amt - $wallet->unicash;
			$unicash      = $wallet->unicash;
			$senderAmt    = $wallet->earning + $wallet->unicash + $wallet->amount;
			$balance      = $wallet->unicash + $wallet->earning + $wallet->amount - $amount;
			$earning      = $wallet->earning;
			$des          = 'Order Amount Deducted from amount : ' . $remainingAmt . ' unicash: ' . $wallet->unicash . ' earning : ' . $wallet->earning . ' bp :' . $bp;

			$wallet->earning = $earning;
			$wallet->unicash = 0;
			$wallet->amount  = ($wallet->amount - $remainingAmt);
			$wallet->bp      = ($wallet->bp - $point);
		}

		if ($senderAmt >= $amount) {

			if ($checkblance)
				return ['status' => true, 'msg' => 'For check balance'];

			if (!$wallet->save())
				return ['status' => false, 'msg' => 'Something Went wrong wallet not updated.'];

			$transPayload = [
				'user_id'         => Auth::user()->user_id,
				'transition_type' => $types,
				'debit'           => $amount,
				'balance'         => $balance,
				'in_type'         => 'Your Wallet is Debited ' . $amount . ' for ' . $types . ' to ' . $order_id . '',
				'description'     => $des,
				'amount'          => $amt,
				'unicash'         => $unicash,
				'earning'         => $earning,
				'unipoint'        => $point,
				'order_id'        => $order_id
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
			$position  = $binary->position;
			$posid     = $binary->posid;
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
			$position  = $binary->position;
			$posid     = $binary->posid;
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
				$rpTrans->user_id  = $user;
				$rpTrans->ref_id   = $mid;
				$rpTrans->order_id = $order_id;
				$rpTrans->rp       = $rp;
				$rpTrans->status   = 0;
				$rpTrans->position = $position;
				$rpTrans->cur_date = date('Y-m-d');
				$rpTrans->pid      = 0;
				if ($rpTrans->save())
					$count++;
			}
		}
		return $count;
	}
}