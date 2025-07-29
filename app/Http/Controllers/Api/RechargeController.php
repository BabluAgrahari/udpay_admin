<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\MobileRechargeJob;
use App\Services\Recharge\PayMyRecharge;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\RechargeModel;
use App\Models\User;
use App\Models\LevelCount;
use App\Models\UserKyc;
use App\Models\WalletTransition;
use App\Models\OperatorCode;
use App\Models\Wallet;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RechargeController extends Controller
{

    public function findOperator(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'mobile' => 'required|numeric|digits:10|not_in:0',
            ]);

            if ($validator->fails())
                return validationRes($validator->messages());

            $recharge = new PayMyRecharge();
            $res = $recharge->getOperator($request->mobile);

            if (!empty($res) && $res['status'] == 'error')
                return $this->failRes($res['msg'] ?? '');

            $response = [
                'status'   => 'success',
                'msg'      => $res['msg'] ?? 'success',
                'mob'      => $request->mobile,
                'operator' => $res['operator'] ?? '',
                'circle'   => $res['circle'] ?? '',
                'image'    => $res['image'] ?? ''
            ];

            return $this->recordRes($response);
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }

    public function allPlansTitle(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'operator' => 'required|string|min:2|max:100',
                'circle' => 'required|string|min:2|max:100',
            ]);
            if ($validator->fails())
                return validationRes($validator->messages());

            if($request->circle == 'Delhi')
                $circle = "Delhi NCR";
            $circle = $request->circle;
            
            $recharge = new PayMyRecharge();
            $res = $recharge->findPlanTitle($request->operator, $circle);

            if (!empty($res) && $res['status'] == 'error')
                return $this->failRes($res['msg'] ?? '');

            return $this->recordRes($res['plan_title'] ?? array());
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }


    public function singlePlan(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'mobile'   => 'required|numeric|digits:10|not_in:0',
                'plan'     => 'required|string|min:2|max:100',
                'operator' => 'required|string|min:2|max:100'
            ]);

            if ($validator->fails())
                return validationRes($validator->messages());

            if ($request->plan == 'My Offer') {
                $recharge = new PayMyRecharge();
                $res = $recharge->myOffer($request->mobile, $request->operator);

                if (!empty($res) && $res['status'] == 'error')
                    return $this->failRes($res['msg'] ?? '');

                return $this->recordRes($res['my_offers'] ?? array());
            }
            return $this->recordRes([$request->plan]);
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }


    public function allPlanOffer(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'mobile'   => 'required|numeric|digits:10|not_in:0',
                'circle'   => 'required|string|min:2|max:100',
                'operator' => 'required|string|min:2|max:100',
            ]);

            if ($validator->fails())
                return validationRes($validator->messages());

            $recharge = new PayMyRecharge();

            $res = $recharge->myOffer($request->mobile, $request->operator);
            if (!empty($res) && $res['status'] == 'error')
                return $this->failRes($res['msg'] ?? '');

            $res1 = $recharge->findPlanTitle($request->operator, $request->circle);
            if (!empty($res1) && $res1['status'] == 'error')
                return $this->failRes($res['msg'] ?? '');

            return $this->recordRes([
                'my_offer' => $res['my_offers'] ?? null,
                'plans' => $res1['plans'] ?? null
            ]);
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }
    
    public function recharge(Request $request)
    {
        //return $this->failRes('Try after Sometime.....');
        try {
            $rules = [
                'recharge_type' => 'required|string|in:mob,dth',
                'operator'   => 'required|string',
                'upin'       => 'required|numeric',
                'amount'     => 'required|gt:0'
            ];
            if ($request->recharge_type == 'mob') {
                $rules['mobile'] = 'required|numeric|digits:10|not_in:0';
                $rules['circle'] = 'required|string';
            }

            if ($request->recharge_type == 'dth') {
                $rules['dth_no'] = 'required|numeric|not_in:0';
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails())
                return validationRes($validator->messages());

            if (!verifyUpin(Auth::user()->user_id, $request->upin - 1))
                return $this->failRes('Miss Match UPIN.');
            

            $user = User::where('user_id', Auth::user()->user_id)->first();
            if ($user->restricted == 1)
                return $this->failRes('User Blocked.');

            $kycCount = UserKyc::where('userId', Auth::user()->user_id)->where('kyc_flag', 2)->count();
            if ($kycCount <= 0)
                return $this->failRes('Kyc Not Completed.');

            /*$consumedAmt = RechargeModel::where('user_id', Auth::user()->user_id)->where('status', 'Success')->where('cur_date', 'LIKE', date('Y-m') . '%')->sum('amount');
            $conditionAmt = $request->amount + $consumedAmt;
            if ($conditionAmt <= 2500)*/
                
                //return $this->failRes('Recharge Limit Completed.');

            if ($request->upin) {
                $payload = [
                    'user_id'  => Auth::user()->user_id,
					'user_nm'	=>Auth::user()->user_nm,
                    'mobile'   => $request->recharge_type == 'mob' ? $request->mobile : $request->dth_no,
                    'circle'   => $request->circle ?? '',
                    'operator' => $request->operator ?? '',
                    'amount'   => $request->amount ?? 0,
                    'active'   => Auth::user()->isactive ? 1 : 0,
                    'recharge_type' => $request->recharge_type

                ];
                //return $this->failRes('Temporary Shut down Recharge Services');
                $res =  $this->rechargeFun($payload);

                if ($res['status']) {
                    return $this->recordResMsg($res['array'] ?? array(), $res['msg'] ?? '');
                } else {
                    return $this->failRes($res['msg'] ?? '');
                }
            } else {
                return $this->failRes('Can not Access Recharge Service');
            }
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }

    public function rechargeHistory(Request $request)
    {
        try {
            $perPage = $request->perPage ?? config('global.perPage');
            $page = $request->page ?? 0;
            $res = RechargeModel::where('user_id', Auth::user()->user_id)->orderBy('id', 'desc')->offset($page)->limit($perPage)->get()->map(function ($record) {
                return [
                    'id'               => $record->id,
                    'mobile'           => $record->mobile,
                    'trans_id'         => $record->transId,
                    'operator_code'    => $record->operator_code,
                    'operator'         => $record->operator,
                    'amount'           => (float)$record->amount ?? 0,
                    'bp'               => (float)$record->bp ?? 0,
                    'after_bp_amt'     => (float)$record->after_bp_amt ?? 0,
                    'status'           => $record->status,
                    'date_time'        => $record->create_on,
                    'transaction_id'   => $record->transaction_id,
                    'operatorref'      => $record->operatorref
                ];
            });

            if ($res->isEmpty())
                return $this->notFoundRes();

            return $this->recordsRes($res);
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }



    private function rechargeFun($request)
    {
        $request = (object)$request;
        $operator = $request->operator;
        $userId   = $request->user_id;
		$unm	  = $request->user_nm;
        $mobile   = $request->mobile;
        $amount   = $request->amount;
        $active   = $request->active;
        $circle   = $request->circle;
		
        $transID = date('ymdhs') . rand(1111, 4444);
        $useBp = 0;
        $code = OperatorCode::where('operator_name', $operator)->first();
        if (empty($code))
            return ['status' => false, 'msg' => 'Operator Not Found.'];


        $bp = 0;
        $amt = $amount;
        $userWallet = Wallet::where('userid', $userId)->first();
        /*if ($active == 1) {
            $useBp = $amount * 0.01;
            if ($userWallet->bp >= $useBp) {
                $bp = $amount * 0.01;
                $amt = $amount - ($amount * 0.01);
            } else {
                $bp = 0;
                $amt = $amount;
            }
        }*/
        $bp = 0;
        $amt = $amount;

        $checkWallet = $this->debitUserWallet($unm,$amt, $bp, $userId, $transID, true); // for check balance is avaliable or not
        if (!$checkWallet['status'])
            return ['status' => false, 'msg' => $checkWallet['msg'] ?? ''];

        $qry = new RechargeModel();
		$qry->unm			 = $unm;
        $qry->user_id        = $userId;
        $qry->mobile         = $mobile;
        $qry->operator       = $operator;
        $qry->operator_code  = $code->operator_code;
        $qry->amount         = $amount;
        $qry->transId        = $transID;
        $qry->status         = 'initiate';
        $qry->circleName     = $circle;
        $qry->cur_date       = date('Y-m-d');
        $qry->recharge_type  = $request->recharge_type;
        $qry->operatorref    = '';
        $qry->bp             = 0;
        $qry->after_bp_amt   = 0;
        $qry->transaction_id = '';
        $qry->flag           = 0;
        if ($qry->save()) {
            $last_id = $qry->id;

            $recharge = new PayMyRecharge();
            $apiRes = $recharge->mobileRecharge($mobile, $code->operator_code, $amount, $transID);
			
            if ($apiRes['status'] == 'error' || $apiRes['status'] == 'failed') {
                $updateRecharge = RechargeModel::find($last_id);
                $updateRecharge->status = 'failed';
                $updateRecharge->save();
                $msg = !empty($apiRes['msg']) || $apiRes['msg'] !='0'?$apiRes['msg']:'Recharge Failed';
                return ['status' => false, 'msg' => $msg];
            }


            $update_recharge = RechargeModel::find($last_id);
            $update_recharge->status         = $apiRes['status'] ?? '';
            $update_recharge->transaction_id = $apiRes['APITransID'] ?? 0;
            $update_recharge->bp             = $bp;
            $update_recharge->after_bp_amt   = $amt;
            $update_recharge->operatorref    = $apiRes['operatorref'] ?? 0;
            $update_recharge->save();

            $operatorref  = $apiRes['operatorref'] ?? 0;
            $api_trans_id = $apiRes['APITransID'] ?? 0;

            $checkWallet = $this->debitUserWallet($unm,$amt, $bp, $userId, $transID, false); // debit user wallet
            if (!$checkWallet['status'])
                return ['status' => false, 'msg' => $checkWallet['msg'] ?? ''];

            if (!empty($apiRes['status']) && strtolower($apiRes['status'] == 'pending')) {
                $array = [
                    'status'       => 'success',
                    'cb'           => (float)$amt * 0.10,
                    'operatorref'  => $operatorref,
                    'api_trans_id' => $api_trans_id,
                    'trans_id'     => $transID,
                ];
                return ['status' => true, 'array' => $array, 'msg' => 'Recharge is successfull.'];
            }

            if (!$active) {
               /* $check_availability = RechargeModel::where('user_id', $userId)->where('cur_date', date('Y-m-d'))->where('id','!=',$last_id)->whereIn('status',['Success','success'])->first();
                if (!empty($check_availability)) {
                    
                    $array = [
                        'status'       => 'success',
                        'cb'           => (float)$amt * 0.10,
                        'operatorref'  => $operatorref,
                        'api_trans_id' => $api_trans_id,
                        'trans_id'     => $transID,
                    ];
                    return ['status' => true, 'msg' => $apiRes['status'], 'array' => $array];
                }

                $cashbackAmt  = $amt * 0.03;
                $addWallet    = add_wallet(4, $userId, $cashbackAmt);
                $userWallet = Wallet::where('userid', $userId)->first();
                $balance = $userWallet->unicash + $userWallet->earning + $userWallet->amount;
                //$cbBp = $amt*0.06;
                if ($addWallet) {
                    $transPayload = [
                        'user_id'         => $userId,
                        'transition_type' => 'recharge_cashback',
                        'credit'          => $cashbackAmt,
                        'balance'         => $balance,
                        'in_type'         => "You Earn " . $cashbackAmt . " Unicash and Unibonus ".$cbBp,
                        //not...'in_type'         => "You Earn " . $cashbackAmt . " Unicash and Unibonus ".$cbBp,
                        'order_id'        => $transID
                    ];
                    walletTransaction($transPayload); //credit amount in wallet
                } else {
                    Log::info("$cashbackAmt Recharge Cashback not Added in Wallet - userID[$userId]");
                }

                $firstLvlUser = LevelCount::where('child_id', $userId)->where('level', '<=', 1)->first();
                $lvlCash      = ($cashbackAmt*0.45) / 15;
                $addWallet_   = add_wallet(4, $firstLvlUser->parent_id, $lvlCash);
                $userWallet = Wallet::where('userid', $firstLvlUser->parent_id)->first();
                $balance = $userWallet->unicash + $userWallet->earning + $userWallet->amount;
                if ($addWallet_) {
                    $transPayload = [
                        'user_id'         => $firstLvlUser->parent_id,
                        'transition_type' => 'recharge_cashback',
                        'credit'          => $lvlCash,
                        'balance'         => $balance,
                        'in_type'         => "You Earn " . number_format($lvlCash, 2) . " Unicash ",
                        'order_id'        => $transID
                    ];
                    walletTransaction($transPayload); //credit amount in wallet
                } else {
                    Log::info("$lvlCash Recharge Cashback not Added in Wallet - userID [$firstLvlUser->parent_id]");
                }*/
				$cashbackAmt1 = (float)$amt * 0.06;
				$addWallet1   = add_wallet2(3, $unm, $cashbackAmt1);
                $array = [
                    'status'       => 'success',
                    'cb'           => $cashbackAmt1,
                    'operatorref'  => $operatorref,
                    'api_trans_id' => $api_trans_id,
                    'trans_id'     => $transID,
                ];
                return ['status' => true, 'array' => $array, 'msg' => 'Recharge successfull.'];
            } else {

                //$rechargeAttempt = RechargeModel::where('cur_date', date('Y-m-d'))->where('user_id', $userId)->where('id','!=',$last_id)->whereIn('status',['Success','success'])->count();

                //$consumedAmt = RechargeModel::where('user_id', Auth::user()->user_id)where('id','!=',$last_id)->where('cur_date', 'LIKE', date('Y-m') . '%')->whereIn('status',['Success','success'])->sum('amount');

                $consumedAmt = RechargeModel::where('user_id', Auth::user()->user_id)
                            ->where('id', '!=', $last_id)
                            ->where('cur_date', 'LIKE', date('Y-m') . '%')
                            ->whereIn('status', ['Success', 'success'])
                            ->sum('amount');
                $conditionAmt = (!empty($consumedAmt) || $consumedAmt > 0)
                ? $request->amount + $consumedAmt
                : $request->amount;


                $cashbackAmt  = (float)$amt * 0.03;
                $cashbackAmt1 = (float)$amt * 0.06;
                $lvlCash      = ($amt*0.01) / 15;
				$lvlCash      = (float)$lvlCash;

                if ($conditionAmt <= 2500){
                    //if ($rechargeAttempt > 5) {}
                   
                    $addWallet    = add_wallet2(4, $unm, $cashbackAmt);
                    $addWallet1   = add_wallet2(3, $unm, $cashbackAmt1);
                    $userWallet = Wallet::where('unm', $unm)->first();
                    $balance = $userWallet->unicash + $userWallet->earning + $userWallet->amount;
                
                    if ($addWallet) {
                        $transPayload = [
							'unm'			  =>$unm,
                            'user_id'         => $userId,
                            'transition_type' => 'recharge_cashback',
                            'credit'          => $cashbackAmt,
                            'balance'         => $balance,
                            'in_type'         => "You Earn " . $cashbackAmt . " Unicash and Unibonus ".$cashbackAmt1,
                            'order_id'        => $transID
                        ];
                        walletTransaction($transPayload); //credit amount in wallet
                    } else {
                        Log::info("$cashbackAmt Recharge Cashback not Added in Wallet - userID[$userId]");
                    }
	
                    $firstLvlUser = LevelCount::where('child_id', $unm)->where('level', '<=', 1)->first();
                    $add_wallet = add_wallet2(4, $firstLvlUser->parent_id, $lvlCash);
                    $userWallet = Wallet::where('unm', $firstLvlUser->parent_id)->first();
                    $balance = $userWallet->unicash + $userWallet->earning + $userWallet->amount;
                    if ($add_wallet) {
					$uData = User::where('user_nm',$firstLvlUser->parent_id)->first();
                        $transPayload = [
							'unm'			  =>$firstLvlUser->parent_id,
                            'user_id'         => $uData->user_id,
                            
                            'transition_type' => 'recharge_cashback',
                            'credit'          => $lvlCash,
                            'balance'         => $balance,
                            'in_type'         => "You Earn " . $lvlCash . " Unicash ",
                            'order_id'        => $transID
                        ];
                        walletTransaction($transPayload); //credit amount in wallet
                    } else {
                        Log::info("$lvlCash Recharge Cashback not Added in Wallet - userID[$firstLvlUser->parent_id]");
                    }

                    $dataPayload = [
                        'lvlCash' => $lvlCash,
                        'user_id' => $unm,
                        'transID' => $transID,
                    ];
                    dispatch(new MobileRechargeJob($dataPayload)); //for cahshback distribution

                    $array = [
                        'status'       => 'success',
                        'cb'           => (float)$amt*0.10,
                        'operatorref'  => $operatorref,
                        'api_trans_id' => $api_trans_id,
                        'trans_id'     => $transID,
                    ];

                   return ['status' => true, 'array' => $array,  'msg' => 'Recharge successfull.'];
                    
                }else{
                    $addWallet1   = add_wallet2(3, $unm, $cashbackAmt1);

                    $array = [
                        'status'       => 'success',
                        'cb'           => $amt * 0.06,
                        'operatorref'  => $operatorref,
                        'api_trans_id' => $api_trans_id,
                        'trans_id'     => $transID,
                    ];
                    return ['status' => true, 'msg' => $apiRes['status'], 'array' => $array];
                }

               
                
                
            }
        }
        return ['status' => false, 'msg' => 'Something went wrong Recharge not initiated.'];
    }


    public function debitUserWallet($unm,$amount, $bp, $user_id, $order_id, $checkblance = false)
    {
        $userWalletAmt = $toatlBalance =  0;
        $userWallet = Wallet::where('userid', $user_id)->first();
        $unicashAmt = 0;
        $amtAdd = 0;
        if ($userWallet->earning >= $amount) {

            $userWalletAmt = $userWallet->earning;
            $effectAmt     = $userWallet->earning - $amount;
            $earningAmt    = $amount;
            $toatlBalance  = $effectAmt + $userWallet->amount + $userWallet->unicash;
            // $userWalletData        = array('earning' => $effectAmt, 'bp' => $userWallet->bp - $bp);

            $userWallet->earning = $effectAmt;
            $userWallet->bp = $userWallet->bp - $bp;
        } else if ($userWallet->unicash + $userWallet->earning >= $amount) {

            $remainAmt     = $amount - $userWallet->earning;
            $userWalletAmt = $userWallet->earning + $userWallet->unicash;
            $effect_amt    = $userWallet->unicash + $userWallet->earning - $amount;
            $earningAmt    = $userWallet->earning;
            $unicashAmt    = $remainAmt;
            $toatlBalance  = $effect_amt + $userWallet->amount;
            // $userWalletData        = array('earning' => 0, 'unicash' => ($userWallet->unicash - $remainAmt), 'bp' => $userWallet->bp - $bp);

            $userWallet->earning = 0;
            $userWallet->unicash =  ($userWallet->unicash - $remainAmt);
            $userWallet->bp = $userWallet->bp - $bp;
        } else if ($userWallet->earning + $userWallet->unicash + $userWallet->amount >= $amount) {

            $remainAmt     = $amount - $userWallet->earning;
            $amtAdd        = $remainAmt - $userWallet->unicash;
            $senderAmount  = $userWallet->amount - $amtAdd;
            $userWalletAmt = $userWallet->earning + $userWallet->unicash + $userWallet->amount;
            $earningAmt    = $userWallet->earning;
            $unicashAmt    = $userWallet->unicash;
            $toatlBalance  = $userWallet->unicash + $userWallet->earning + $userWallet->amount - $amount;
            // $userWalletData = array('earning' => 0, 'unicash' => 0, 'amount' => $senderAmount, 'bp' => $userWallet->bp - $bp);

            $userWallet->earning = 0;
            $userWallet->unicash =  0;
            $userWallet->amount  = $senderAmount;
            $userWallet->bp = $userWallet->bp - $bp;
        }
        
        if ($userWalletAmt >= $amount) {
            if ($checkblance)
                return ['status' => true, 'msg' => 'Insufficient Balance.'];

            $userWallet->save();
            //Wallet::where('userid', $user_id)->update($userWalletData);
            $transPayload = [
                'unm'             => $unm,
                'user_id'         => $user_id,
                'transition_type' => 'Recharge Mobile',
                'debit'           => $amount,
                'balance'         => $toatlBalance,
                'in_type'         => 'Your Wallet is Debited ' . $amount . ' for Recharge to UNI' . $order_id,
                'description'     => 'amount : ' . $amtAdd . ' unicash: ' . $unicashAmt . ' earning : ' . $earningAmt . ' uniPoint: ' . $userWallet->bp,
                'amount'          => $amtAdd,
                'unicash'         => $unicashAmt,
                'earning'         => $earningAmt,
                'unipoint'        => $bp,
                'order_id'        => $order_id
            ];

            if (walletTransaction($transPayload)) {
                return ['status' => true, 'msg' => 'Wallet updated Successfully.'];
            } else {
                return ['status' => false, 'msg' => 'Amount not debit.'];
            }
        } else {
            
             Log::info("recharge amount $amount user wallet $userWallet->earning + $userWallet->unicash + $userWallet->amount[$user_id]"); 
            return ['status' => false, 'msg' => 'insufficent Amount.'];
            
        }
    }
}