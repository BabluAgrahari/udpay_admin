<?php
namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

use App\Models\ApRepurchaseOrder;
use App\Models\DealOrders;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransition;
use App\Models\BarcodeData;
use App\Models\UniCashDetail;
use App\Models\UsersLvl;
use App\Models\LevelCount;

use Exception;


class WebhookController extends Controller
{

	public function getRazorpayResponse(Request $request)
	{

		$rules = ['status' => 'required'];
		$rules['order_id'] = 'required';
		//$rules['transaction_id']       = 'required';
		$rules['userId'] = 'required|numeric';
		//$rules['gateway_response'] = 'required|numeric';

		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails())
			return $this->validationRes($validator->messages());

		$status = $request->status;
		$order_id = $request->order_id;
		$payment_id = $request->gateway_response['payment_id'] ?? '';
		//$transaction_id = $request->transaction_id;
		$user = $request->userId;
		$gateway_response = $request->gateway_response;

		$q = DB::table('uni_cash_detail')->where('user_id', $user)->where('order_id', $order_id)->first();
		$user_wallet_amount = $this->user_wallet($user);

		if ($status == 'Success' || $status == 'success') {
			//$qryCheck = DB::table('barcode_data')->where('transaction_id',$payment_id)->first();
			//print_r($qryCheck);die;
			$this->paymentUpdate($order_id, 'captured', 'payment successful', $q->amount ?? 0, $payment_id);
			$msg = 'Success';
			$txt = 'Your Wallet Credited......';
		} else {
			$msg = 'In-Process';
			$txt = 'Please Check Your Transaction History.....';
		}

		$arr = ['msg' => $msg, 'txt' => $txt, 'wallet_amount' => $user_wallet_amount];
		Log::info('responseApi', ['gateway_response' => $gateway_response]);
		$response = ['response' => $request->all(), 'ourRes' => $arr];
		return response()->json($response);

	}

	private function user_wallet($user_id)
	{
		$wallet = DB::table('wallet')->where('userid', $user_id)->first();
		return $wallet->amount ?? 0;
	}



	private function paymentUpdate($order_id, $status, $description, $amt, $payment_id)
	{

		Log::info('razorpay payment Initiate updated');

		$order_id = $order_id;
		$status = $status;

		if ($status == 'captured') {

			$transaction_no = $order_id;
			$description = $description;
			$amt = $amt;
			$qry = DB::table('uni_cash_detail')->where('order_id', $order_id)->first();

			if (!empty($qry)) {
				$user = $qry->user_id;

				$exist = DB::table('barcode_data')->where('user_id', $user)->where('transaction_id', $payment_id)->first();
				if (!empty($exist)) {
					Log::info('Payment is Alreday completed.');
					return false;
				}

				$userDtl = DB::table('users_lvl')->where('user_id', $user)->first();
				$qry1 = DB::table('barcode_data')->where('amount', $amt)->where('user_id', $user)->where('transaction_id', $payment_id)->first();
				if (!empty($qry1)) {

					$data = array('status' => 'Success', 'flag' => 2, 'transition_id' => $payment_id, 'unm' => $userDtl->user_nm);
					DB::table('uni_cash_detail')->where('order_id', $order_id)->where('user_id', $user)->update($data);

					$array = array('msg' => 'FAIL', 'txt' => 'already Added......', 'order_id' => $order_id, 'user_id' => $user);
					$our = $array;
				} else {

					$qry2 = DB::table('uni_cash_detail')->where('status', '!=', 'Success')->where('user_id', $user)->where('order_id', $order_id)->first();

					if (!empty($qry2)) {
						$data = array('status' => 'Success', 'flag' => 2, 'unm' => $userDtl->user_nm, 'transition_id' => $payment_id);
						$q = DB::table('uni_cash_detail')->where('order_id', $order_id)->where('user_id', $user)->update($data);
						if ($q) {
							$get_user_wallet = $this->user_wallet($user);

							$after_wallet = $get_user_wallet + $amt;//$this->user_wallet($user);
							$desc = 'PG TYPE :' . $description;
							$upData = array('before_wallet' => $get_user_wallet, 'after_wallet' => $after_wallet, 'transaction_id' => $payment_id, 'user_id' => $user, 'amount' => $amt, 'transition_type' => 'phonepe', 'description' => $desc, 'unm' => $userDtl->user_nm);

							$qryCheck = DB::table('barcode_data')->where('transaction_id', $payment_id)->first();

							if (!empty($qryCheck)) {
								$array = array('msg' => 'FAIL', 'txt' => 'Wallet already Added....');
								$our = array('our' => 'In-Process', 'txt' => 'wallet already added', 'before_wallet' => $get_user_wallet, 'date' => date('Y-m-d H:i:s'));
							} else {
								$qry3 = DB::table('barcode_data')->insert($upData);
								if ($qry3) {
									sleep(3);
									$update_wallet = add_wallet($key = 5, $user, $amt);
									if ($update_wallet) {
										$q = DB::table('wallet')->where('userid', $user)->first();

										$balance = $q->amount + $q->earning + $q->unicash;
										$credit = $amt;
										$intype = "Your Wallet is Creditd " . $amt . " for " . $order_id . " Using " . $desc;
										$transition_type = "add_money";
										$desc1 = "amount : " . $q->amount . " earning : " . $q->earning . " unicash : " . $q->unicash;
										$get_user_wallet2 = $this->user_wallet($user);

										$d = array('transition_type' => $transition_type, 'in_type' => $intype, 'amount' => $amt, 'ord_id' => $order_id, 'balance' => $balance, 'description' => $desc1, 'credit' => $credit, 'user_id' => $user, 'unm' => $q->unm);
										$q1 = DB::table('wallet_transition')->insert($d);

										$our = array(
											'our' => 'wallet added',
											'before_wallet' => $get_user_wallet,
											'amount' => $amt,
											'after_wallet' => $get_user_wallet2,
											'date' => date('Y-m-d H:i:s')
										);
										$array = array('msg' => 'Success', 'txt' => 'Your Wallet Credited Successfully', 'amount' => $get_user_wallet2);
									} else {
										$array = array('msg' => 'FAIL', 'txt' => 'Something Went wrong....');
										$our = array('our' => 'wallet not added user not available', 'before_wallet' => $get_user_wallet, 'date' => date('Y-m-d H:i:s'));
									}
								} else {
									$array = array('msg' => 'FAIL', 'txt' => 'Something Went wrong....');
									$our = array('our' => 'wallet not added', 'date' => date('Y-m-d H:i:s'));
								}
							}
						}
					} else {
						$array = array('msg' => 'FAIL', 'txt' => 'Wallet already Added....');
						$our = array('our' => 'In-Process', 'txt' => 'wallet not added', 'date' => date('Y-m-d H:i:s'));
					}
				}
			} else {

				$array = array('msg' => 'In-Process', 'txt' => 'Transaction In-Process.....');
				$our = array('our' => 'In-Process', 'txt' => 'User Record not found.......', 'date' => date('Y-m-d H:i:s'));
			}
		} else {

			$data = ['status' => 'failed', 'transition_id' => $payment_id];
			DB::table('uni_cash_detail')->where('order_id', $order_id)->update($data);

			$our = array('our' => 'failure', 'date' => date('Y-m-d H:i:s'));
			$array = array('msg' => 'FAIL', 'txt' => 'transaction failed');


		}
		Log::info('Razorpay Payment Captured Response', ['array' => $array, 'our' => $our]);
		return true;


	}


	public function razorpayWebhook(Request $request)
	{

		Log::info('callback resposne razorpay', ['request' => $request->all()]);

		$request = (array) $request->all();
		if (empty($request['payload']['payment']['entity']['order_id'])) {
			Log::info('callback_success_post - Order Id not found - razorpay');
			return false;
		}

		$order_id = $request['payload']['payment']['entity']['order_id'];
		$status = $request['payload']['payment']['entity']['status'];
		$payment_id = $request['payload']['payment']['entity']['id'];
		$description = $request['payload']['payment']['entity']['description'];
		$amt = $request['payload']['payment']['entity']['amount'] / 100;

		$type = $request['payload']['payment']['entity']['notes']['type'] ?? '';
		$order_type = $request['payload']['payment']['entity']['notes']['order_type'] ?? '';


		if ($type == 'order') {

			$this->handleOrder($order_id, $status, $description, $amt, $payment_id, $order_type);

		} else {
			$res = $this->walletResponse($order_id, $status, $payment_id, $description, $amt);
		}

		return response()->json($res);


	}


	private function walletResponse($order_id, $status, $payment_id, $description, $amt)
	{

		if ($status == 'captured') {

			$transaction_no = $order_id;
			//$amt1 = $request->payload['payment']['entity']['amount'];

			$qry = DB::table('uni_cash_detail')->where('order_id', $order_id)->first();

			if (!empty($qry)) {
				$user = $qry->user_id;

				$exist = DB::table('barcode_data')->where('user_id', $user)->where('transaction_id', $payment_id)->first();
				if (!empty($exist)) {
					Log::info('Payment is Alreday completed.- razorpay');
					return false;
				}

				$userDtl = DB::table('users_lvl')->where('user_id', $user)->first();
				$qry1 = DB::table('barcode_data')->where('amount', $amt)->where('user_id', $user)->where('transaction_id', $payment_id)->first();
				if (!empty($qry1)) {

					$data = array('status' => 'Success', 'flag' => 2, 'transition_id' => $payment_id, 'unm' => $userDtl->user_nm);
					DB::table('uni_cash_detail')->where('order_id', $order_id)->where('user_id', $user)->update($data);

					$array = array('msg' => 'FAIL', 'txt' => 'already Added......', 'order_id' => $order_id, 'user_id' => $user);
					$our = $array;
				} else {

					$qry2 = DB::table('uni_cash_detail')->where('status', '!=', 'Success')->where('user_id', $user)->where('order_id', $order_id)->first();
					if (!empty($qry2)) {
						$data = array('status' => 'Success', 'flag' => 2, 'unm' => $userDtl->user_nm, 'transition_id' => $payment_id);
						$q = DB::table('uni_cash_detail')->where('order_id', $order_id)->where('user_id', $user)->update($data);
						if ($q) {
							$get_user_wallet = $this->user_wallet($user);

							$after_wallet = $get_user_wallet + $amt;//$this->user_wallet($user);
							$desc = 'PG TYPE :' . $description;
							$upData = array('before_wallet' => $get_user_wallet, 'after_wallet' => $after_wallet, 'transaction_id' => $payment_id, 'user_id' => $user, 'amount' => $amt, 'transition_type' => 'phonepe', 'description' => $desc, 'unm' => $userDtl->user_nm);

							$qryCheck = DB::table('barcode_data')->where('transaction_id', $payment_id)->first();
							if (!empty($qryCheck)) {
								$array = array('msg' => 'FAIL', 'txt' => 'Wallet already Added....');
								$our = array('our' => 'In-Process', 'txt' => 'wallet not added', 'before_wallet' => $get_user_wallet, 'date' => date('Y-m-d H:i:s'));
							} else {
								$qry3 = DB::table('barcode_data')->insert($upData);
								if ($qry3) {
									sleep(3);
									$update_wallet = add_wallet($key = 5, $user, $amt);
									if ($update_wallet) {
										$q = DB::table('wallet')->where('userid', $user)->first();

										$balance = $q->amount + $q->earning + $q->unicash;
										$credit = $amt;
										$intype = "Your Wallet is Creditd " . $amt . " for " . $order_id . " Using " . $desc;
										$transition_type = "add_money";
										$desc1 = "amount : " . $q->amount . " earning : " . $q->earning . " unicash : " . $q->unicash;
										$get_user_wallet2 = $this->user_wallet($user);

										$d = array('transition_type' => $transition_type, 'in_type' => $intype, 'amount' => $amt, 'ord_id' => $order_id, 'balance' => $balance, 'description' => $desc1, 'credit' => $credit, 'user_id' => $user, 'unm' => $q->unm);
										$q1 = DB::table('wallet_transition')->insert($d);

										$our = array(
											'our' => 'wallet added',
											'before_wallet' => $get_user_wallet,
											'amount' => $amt,
											'after_wallet' => $get_user_wallet2,
											'date' => date('Y-m-d H:i:s')
										);
										$array = array('msg' => 'Success', 'txt' => 'Your Wallet Credited Successfully', 'amount' => $get_user_wallet2);
									} else {
										$array = array('msg' => 'FAIL', 'txt' => 'Something Went wrong....');
										$our = array('our' => 'wallet not added user not available', 'before_wallet' => $get_user_wallet, 'date' => date('Y-m-d H:i:s'));
									}
								} else {
									$array = array('msg' => 'FAIL', 'txt' => 'Something Went wrong....');
									$our = array('our' => 'wallet not added', 'date' => date('Y-m-d H:i:s'));
								}
							}
						}
					} else {
						$array = array('msg' => 'FAIL', 'txt' => 'Wallet already Added....');
						$our = array('our' => 'In-Process', 'txt' => 'wallet not added', 'date' => date('Y-m-d H:i:s'));
					}
				}
			} else {

				$array = array('msg' => 'In-Process', 'txt' => 'Transaction In-Process.....');
				$our = array('our' => 'In-Process', 'txt' => 'User Record not found.......', 'date' => date('Y-m-d H:i:s'));
			}
		} else {
			$q = DB::table('uni_cash_detail')->where('order_id', $order_id)->update(['status' => 'failure', 'flag' => 3, 'transition_id' => $payment_id]);
			$our = array('our' => 'failure', 'date' => date('Y-m-d H:i:s'));
			$array = array('msg' => 'FAIL', 'txt' => 'transaction failed');
		}
		Log::info('callback_success_post razorpay', ['array' => $array, 'our' => $our]);

		return $array;
	}

	private function handleOrder($payment_order_id, $status, $description, $amount, $payment_id,$order_type)
	{

        if($order_type == 'ap'){
		$order = ApRepurchaseOrder::where('payment_order_id', $payment_order_id)->first();
        if(empty($order))
        return ['status' =>false, 'message' => 'Order not found'];

		if ($status == 'captured') {

			$totAp = $order->total_ap ?? 0;
			$order_id = $order->order_id ?? '';
			$this->distributePayout($totAp, $order_id);

			$uid = $order->uid;
			$user_id = $order->user_id;
			$directPay = User::where('user_id', $uid)->first();
			$in_type = "Start Bonus from {$directPay->user_nm} - level 1";

			$amt = $totAp * 0.40;
			$insert = insert_payout($amt, $directPay->refid, $in_type, $directPay->user_nm, $order_id, $totAp, 1);

			if ($insert) {
				add_wallet1(1, $directPay->refid, $amt, $order_id, 'gen_payout');
			} else {
				Log::info("$amt not inserted at Level 1 >>> Payout user id: {$directPay->refid}");
			}

			if ($directPay->isactive1 == 1) {
				$in_type = 'Self Repurchase Bonus';
				$amt = $totAp * 0.05;

				$insert = insert_payout_self($amt, $directPay->user_nm, $in_type, 0, $order_id, $totAp, 0, 'rp');
				if ($insert) {
					add_wallet1(1, $directPay->user_nm, $amt, $order_id, 'repurchase_payout');
				} else {
					Log::info("$amt not inserted at Level 1 >>> Payout user id: {$directPay->refid}");
				}
			} else {
				$userLvl = User::where('user_id', $user_id)->first();
				$userLvl->isactive = 1;
				$userLvl->isactive1 = 1;
				$userLvl->sv = $userLvl->sv + $totAp;
				$userLvl->upgrade_date = date('Y-m-d');
				$userLvl->save();
			}
		}

        $orders = ApRepurchaseOrder::where('payment_order_id', $payment_order_id)->get();
        foreach($orders as $o){
            $o->status = $status=='captured' ? 'success' :$status;
            $o->save();
        }
       }else{

        $orders = DealOrders::where('payment_order_id', $payment_order_id)->get();
        if($orders->isEmpty())
        return ['status' =>false, 'message' => 'Order not found'];

        foreach($orders as $o){
            $o->status = $status=='captured' ? 'success' :$status;
            $o->save();
        }
       }

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


}