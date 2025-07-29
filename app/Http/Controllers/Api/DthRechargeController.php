<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RechargeModel;
use App\Models\User;
use App\Models\Level_count;
use App\Models\UserKyc;
use App\Models\WalletTransition;
use App\Models\Operator_code;
use App\Models\Wallet;

class DthRechargeController extends Controller
{
    function dthRecharge(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId'   => 'required|numeric',
            'plan'     => 'required|numeric',
            'vc'       => 'required|numeric',
            'operator' => 'required',
            //'circle' =>'required',
            'amt'      => 'required||gt:0',
        ]);

        if ($validator->fails())
            return validationRes($validator->messages());

        if ($request->plan) {
            $check_restricted = User::where('user_id', $request->userId)->where('restricted', 1)->first();
            if ($check_restricted)
                return $this->failRes('User Blocked....');

            $check = $this->cross_check($request->userId, $request->plan - 1);
            if ($check == false)
                return $this->failRes('Miss Match Upin....');

            $vc = $request->vc;
            $operator = $request->operator;
            $amt1 = $request->amt;
            $circleName = ''; //$request->circle;
            $transID = rand(6, 9999999);
            $get_num = RechargeModel::where('transId', $transID)->first();
            if ($get_num)
                $transID = rand(6, 9999999);

            $check_kyc = UserKyc::where('userId', $request->userId)->where('kyc_flag', 2)->first();
            $check_isactive = User::select('isactive')->where('user_id', $request->userId)->first();

            if (empty($check_kyc))
                return $this->failRes('Kyc Not Completed....');

            $qryAmt = RechargeModel::where('user_id', $request->userId)->where('status', 'Success')->where('cur_date', 'LIKE', date('Y-m') . '%')->sum('amount');
            $conditionAmt = $amt1 + $qryAmt;
            if ($conditionAmt > 3500)
                return $this->failRes('Recharge Limit Completed....');

            return self::rechargecpy($request->userId, $vc, $operator, $amt1, $circleName, $transID, $check_isactive->isactive);
        } else {
            return $this->failRes('Can not Access Recharge Service');
        }
    }

    private function cross_check($uid, $epin)
    {
        if (strlen($epin) == 3) {
            $epin = '0' . $epin;
        } else if (strlen($epin) == 2) {
            $epin = '00' . $epin;
        } else {
            $epin = $epin;
        }
        $epin = User::where('user_id', $uid)->where('epin', md5($epin))->first();
        if ($epin) {
            return true;
        } else {
            return false;
        }
    }

    private function rechargecpy($userId, $mob, $operator, $amt1, $circleName = '', $transID, $active)
    {
        $useBp = $cbs = $cc = 0;
        $type = 'recharge_cashback';

        $code = Operator_code::where('type', 'DTH')->where('operator_name', 'like', '%' . $operator . '%')->first();
        if (empty($code))
            return $this->failRes('Operator Not Found.......');

        $qry = new RechargeModel();
        $qry->user_id = $userId;
        $qry->mobile = $mob;
        $qry->operator = $operator;
        $qry->operator_code = $code->operator_code;
        $qry->amount = $amt1;
        $qry->transId = $transID;
        $qry->status = 'Cancel';
        $qry->circleName = $circleName;
        $qry->cur_date = date('Y-m-d');
        $qry->recharge_type = 'dth';
        $qry->operatorref = '';
        $qry->bp = 0;
        $qry->after_bp_amt = 0;
        $qry->transaction_id = '';
        $qry->flag = 0;
        if ($qry->save()) {
            $last_id = $qry->id;
            $res = Wallet::where('userid', $userId)->first();
            if (!empty($res)) {
                $amt = $amt1 - $amt1 * 0.01;
                $useBp = $amt1 * 0.01;

                if ($active == 1) {
                    if ($res->bp >= $useBp) {
                        $bp = $amt1 * 0.01;
                        $amt = $amt;
                    } else {
                        $bp = 0;
                        $amt = $amt1;
                    }
                } else {
                    $bp = 0;
                    $amt = $amt1;
                }

                $check_wallet = self::check_wallet($amt, $bp, $userId, $transID);
                if ($check_wallet['msg'] == 'done') {
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://paymyrecharge.in/api/V5/Rechargenew.asmx/RechargeNow',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => 'Token=' . rcToken . '&mobileno=' . $mob . '&Operator=' . $code->operator_code . '&Amount=' . $amt1 . '&transID=' . $transID,
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/x-www-form-urlencoded',
                            'memberid: API568506',
                            'password: *%U#ni@#'
                        ),
                    ));
                    $response = curl_exec($curl);
                    $array['response'] = $response;
                    curl_close($curl);
                    $a = json_decode($response);
                    $cc = $amt * 0.09;
                    $cbs = number_format($bp + $cc, 2);
                    $update_recharge = RechargeModel::find($last_id);

                    $update_recharge->status = $a->status;
                    $update_recharge->transaction_id = $a->APITransID;
                    $update_recharge->bp = $bp;
                    $update_recharge->after_bp_amt = $amt;
                    $update_recharge->operatorref = $a->operatorref;
                    if ($update_recharge->save()) {
                        if ($a->status == 'Success') {
                            if ($active == 0) {
                                $check_availability = RechargeModel::where('user_id', $userId)->where('cur_date', date('Y-m-d'))->where('status', 'Success')->first();
                                if (!empty($check_availability)) {

                                    $cbs = (float)$amt * 0.01;

                                    $arr = array('msg' => $a->status, 'response' => $a, 'op_code' => $code->operator_code, 'cb' => $cbs);
                                    return $this->successRes($arr);
                                } else {
                                    $cashbackAmt = $amt * 0.01;
                                    $cashbackAmt1 = $amt * 0.09;
                                    $lvlCash = $cashbackAmt / 15;
                                    $addWallet = add_wallet($key = 4, $userId, $cashbackAmt);
                                    if ($addWallet) {
                                        $balance = self::userWallet($userId);
                                        $intype = "You Earn " . $cashbackAmt . " Unicash";

                                        $ins = new WalletTransition();
                                        $ins->transition_type = 'recharge_cashback';
                                        $ins->user_id = $userId;
                                        $ins->credit = $cashbackAmt;
                                        $ins->balance = $balance;
                                        $ins->in_type = $intype;
                                        $ins->ord_id = $transID;
                                        $ins->save();

                                        $first_lvl_user = Level_count::where('child_id', $userId)->where('level', '<=', 1)->first();
                                        $add_wallet = add_wallet($key = 4, $first_lvl_user->parent_id, $lvlCash);
                                        if ($add_wallet) {
                                            $intype = "You Earn " . number_format($lvlCash, 2) . " Unicash ";
                                            $balance1 = $this->userWallet($userId);
                                            $ins1 = new WalletTransition();
                                            $ins1->transition_type = 'recharge_cashback';
                                            $ins1->user_id = $first_lvl_user->parent_id;
                                            $ins1->credit = $lvlCash;
                                            $ins1->balance = $balance1;
                                            $ins1->in_type = $intype;
                                            $ins1->ord_id = $transID;
                                            $ins1->save();

                                            $distribute = $this->cash_back_distribution($lvlCash, $userId, $transID, $type);
                                            if (!empty($distribute) || $distribute != 0) {
                                                $arr = array('msg' => $a->status, 'response' => $a);
                                                return $this->recordRes($arr);
                                            } else {
                                                $arr = array('msg' => $a->status, 'response' => $a);
                                                return $this->recordRes($arr);
                                            }
                                        }
                                    }
                                }
                            } else {
                                $check_availability1 = RechargeModel::where('cur_date', date('Y-m-d'))->where('user_id', $userId)->where('status', 'Success')->count();
                                if ($check_availability1 > 5) {

                                    $arr = array('msg' => $a->status, 'response' => $a, 'op_code' => $code->operator_code, 'cb' => 0);
                                    return $this->recordRes($arr);
                                } else {
                                    $cashbackAmt = $amt * 0.01;
                                    $lvlCash = $cashbackAmt / 15;
                                    $addWallet = add_wallet($key = 4, $userId, $cashbackAmt);
                                    $addWallet1 = add_wallet($key = 3, $userId, $cashbackAmt1);
                                    if ($addWallet && $addWallet1) {
                                        $balance = self::userWallet($userId);
                                        $intype = "You Earn " . $cashbackAmt . " Unicash and UniBonus " . $cashbackAmt1;
                                        $ins = new WalletTransition();
                                        $ins->transition_type = 'recharge_cashback';
                                        $ins->user_id = $userId;
                                        $ins->credit = (float)$cashbackAmt;
                                        $ins->balance = (float)$balance;
                                        $ins->in_type = $intype;
                                        $ins->ord_id = $transID;
                                        $ins->save();

                                        $first_lvl_user = Level_count::where('child_id', $userId)->where('level', '<=', 1)->first();

                                        $add_wallet = add_wallet($key = 4, $first_lvl_user->parent_id, $lvlCash);
                                        if ($add_wallet) {
                                            $intype = "You Earn " . $lvlCash . " Unicash ";
                                            $balance1 = $this->userWallet($userId);
                                            $ins1 = new WalletTransition();
                                            $ins1->transition_type = 'recharge_cashback';
                                            $ins1->user_id = $first_lvl_user->parent_id;
                                            $ins1->credit = $lvlCash;
                                            $ins1->balance = $balance1;
                                            $ins1->in_type = $intype;
                                            $ins1->ord_id = $transID;
                                            $ins1->save();

                                            $distribute = self::cash_back_distribution($lvlCash, $userId, $transID, $type);
                                            if (!empty($distribute) || $distribute != 0) {

                                                $cc = $amt * 0.09;
                                                $cbs = (float)$bp + $cc;
                                                $arr = array('msg' => 'Success', 'response' => $a, 'op_code' => $code->operator_code, 'cb' => $cbs);
                                                return $this->recordRes($arr);
                                            } else {

                                                //$arr = array('msg'=>'Remain not added...');
                                                $cc = $amt * 0.09;
                                                $cbs = (float)$bp + $cc;
                                                $arr = array('msg' => 'Success', 'response' => $a, 'op_code' => $code->operator_code, 'cb' => $cbs);
                                                return $this->recordRes($arr);
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            //Failed
                            if ($a->status == 'Failed') {
                                $user_wallet = Wallet::where('userid', $userId)->first();

                                $updates = Wallet::find($user_wallet->id);

                                $updates->earning = $user_wallet->earning + $check_wallet['earn'];
                                $updates->amount = $user_wallet->amount + $check_wallet['amt'];
                                $updates->unicash = $user_wallet->unicash + $check_wallet['uni'];
                                $updates->bp = $user_wallet->bp + $bp;

                                if ($updates->save()) {
                                    $wallet_row = WalletTransition::where('ord_id', $transID)->first();

                                    $description = "earning : " . $wallet_row->earning . " unicash : " . $wallet_row->unicash . " amount :" . $wallet_row->amount;

                                    $intype = "Refund " . $amt . " for " . $transID;
                                    $balance = $check_wallet['balance'] + $amt;

                                    $ins = new WalletTransition();
                                    $ins->transition_type = 'refund_recharge';
                                    $ins->user_id = $userId;
                                    $ins->credit = $amt;
                                    $ins->balance = $balance;
                                    $ins->in_type = $intype;
                                    $ins->ord_id = $transID;
                                    $ins->description = $description;
                                    $ins->earning = $wallet_row->earning;
                                    $ins->unicash = $wallet_row->unicash;
                                    $ins->amount = $wallet_row->amount;
                                    $ins->unipoint = $bp;
                                    $ins->debit = 0;
                                    $ins->remark = '';
                                    $ins->save();
                                }

                                $cbs = 0;
                                $arr = array('msg' => 'fail', 'response' => $a, 'op_code' => $code->operator_code, 'cb' => $cbs);
                                return $this->successRes($arr);
                            } else {
                                $check_availability1 = RechargeModel::where('cur_date', date('Y-m-d'))->where('user_id', $userId)->where('status', 'Success')->count();
                                if ($active == 1) {
                                    if ($check_availability1->num_rows() > 5) {
                                        $cbs = 0;
                                    } else {
                                        $cc = $amt * 0.09;
                                        $cbs = number_format($bp + $cc, 2);
                                    }
                                } else {
                                    if ($check_availability1->num_rows() > 1) {
                                        $cbs = 0;
                                    } else {
                                        $cc = $amt * 0.01;
                                        $cbs = number_format($cc, 2);
                                    }
                                }
                                $arr = array('msg' => $a->status, 'response' => $a, 'op_code' => $code->operator_code, 'cb' => $cbs);
                                return $this->recordRes($arr);
                            }
                        }
                    } else {
                        return $this->failRes('something went wrong');
                    }
                } else {
                    return $this->failRes($check_wallet['msg']);
                }
            }
        }
    }

    private function userWallet($user)
    {
        $qry =  Wallet::where('userid', $user)->first();
        return $qry->unicash;
    }
    private function check_wallet($amount, $bp, $user_id, $order_id)
    {
        $senderAmt = $balance =  0;
        $sender_wallet = Wallet::where('userid', $user_id)->first();
        if ($sender_wallet->earning >= $amount) {
            $senderAmt = $sender_wallet->earning;
            $effect_amt = $sender_wallet->earning - $amount;
            $earn =  $senderAmt - $amount;
            $upData = array('earning' => $effect_amt, 'bp' => $sender_wallet->bp - $bp);
            $earnAdd = $amount;
            $uniAdd = $amtAdd = 0;
            $balance = $effect_amt + $sender_wallet->amount + $sender_wallet->unicash;
        } else if ($sender_wallet->unicash + $sender_wallet->earning >= $amount) {
            $earn = 0;

            $remainAmt = $amount - $sender_wallet->earning;
            $uni = $sender_wallet->unicash - $remainAmt;
            $senderAmt = $sender_wallet->earning + $sender_wallet->unicash;
            $effect_amt = $sender_wallet->unicash + $sender_wallet->earning - $amount;
            $upData = array('earning' => $earn, 'unicash' => $uni, 'bp' => $sender_wallet->bp - $bp);
            $earnAdd = $sender_wallet->earning;
            $uniAdd = $remainAmt;
            $amtAdd = 0;
            $balance = $effect_amt + $sender_wallet->amount;
        } else if ($sender_wallet->earning + $sender_wallet->unicash + $sender_wallet->amount >= $amount) {
            //echo 100;
            //amt = 100 red = 50 uni = 110
            $earn = 0;
            $uni = 0;
            $remainAmt = $amount - $sender_wallet->earning;
            $remainAmt1 = $remainAmt - $sender_wallet->unicash;

            $senderAmount = $sender_wallet->amount - $remainAmt1;
            $senderAmt = $sender_wallet->earning + $sender_wallet->unicash + $sender_wallet->amount;
            $effect_amt = $sender_wallet->unicash + $sender_wallet->earning + $sender_wallet->amount - $amount;
            $upData = array('earning' => $earn, 'unicash' => $uni, 'amount' => $senderAmount, 'bp' => $sender_wallet->bp - $bp);
            $earnAdd = $sender_wallet->earning;
            $uniAdd = $sender_wallet->unicash;
            $amtAdd = $remainAmt1;
            $balance = $effect_amt;
        }

        if ($senderAmt >= $amount) {
            $q = Wallet::where('userid', $user_id)->update($upData);
            if ($q) {
                $walletBal = Wallet::where('userid', $user_id)->first();
                //echo $amount;die;
                $qq = new WalletTransition();
                $qq->user_id = $user_id;
                $qq->debit = $amount;
                $qq->balance = $balance;
                $qq->transition_type = 'Recharge DTH';
                $qq->in_type = 'Your Wallet is Debited ' . $amount . ' for Recharge to UNI' . $order_id . '';
                $qq->description = 'amount : ' . $amtAdd . ' unicash: ' . $uniAdd . ' earning : ' . $earnAdd . ' uniPoint: ' . $walletBal->bp;
                $qq->amount = $amtAdd;
                $qq->unicash = $uniAdd;
                $qq->earning = $earnAdd;
                $qq->unipoint = $bp;
                $qq->ord_id = $order_id;
                $qq->credit = 0;
                $qq->remark = '';
                if ($qq->save()) {
                    $out = array('msg' => 'done', 'earn' => $earnAdd, 'uni' => $uniAdd, 'amt' => $amtAdd, 'balance' => $balance);
                } else {
                    $out = array('msg' => 'not-done-transition');
                }
            } else {
                $out = array('msg' => 'err');
            }
        } else {
            $out = array('msg' => 'Insufficient Balance.....');
        }
        $senderAmt = $sender_wallet->earning + $sender_wallet->amount + $sender_wallet->unicash;

        return $out;
    }
    private function cash_back_distribution($lvlCash, $user_id, $transID, $type)
    {
        $out = array();
        $totalCash = 0;

        $get_num = Level_count::select('User.user_id')
            ->join('User', 'User.user_id', '=', 'Level_count.parent_id')
            ->where('Level_count.child_id', $user_id)
            ->where('Level_count.level', '>', 1)
            ->where('Level_count.level', '<=', 15)
            ->where('User.isactive', 1)
            ->get();
        if ($get_num) {
            $i = 0;
            foreach ($get_num as $res) {
                $i++;
                $userWallet = self::userWallet($res->user_id);
                $walletBal = $userWallet + $lvlCash;

                $addReceiver = add_wallet($key = 4, $res->user_id, $lvlCash);
                if ($addReceiver) {
                    $intype = "You Earn " . $lvlCash . " Unicash";
                    $ins1 = new Wallet_transition();
                    $ins1->transition_type = $type;
                    $ins1->user_id = $res->user_id;
                    $ins1->credit = $lvlCash;
                    $ins1->balance = $walletBal;
                    $ins1->in_type = $intype;
                    $ins1->ord_id = $transID;
                    $ins1->save();

                    $totalCash += $lvlCash;
                    $out = array('msg' => $totalCash);
                    $error[] = $lvlCash . ' Level cashback Added to UNI' . $res->user_id;
                } else {
                    $msg = $i . ' ' . $res->user_id . ' wallet transition level data not insert';
                    $error[] = $msg;

                    $out = array('msg' => $msg);
                }
            }
        } else {
            $msg = '1 ' . $user_id . ' level Member Not Available';
            $error[] = $msg;
        }

        if (!empty($error)) {
            // print_r($error);
            $myfile = fopen("uploads/logs/Recharge_lvl_cashback-" . $user_id . $transID . '-' . strtotime(date("Y-m-d H:i:s")) . ".txt", "w") or die("Unable to open file!");
            foreach ($error as $err) {
                fwrite($myfile, "\n" . $err);
            }
            fclose($myfile);
        }
        return $out;
    }
    private function check_bp($uid, $amt)
    {
        $res = Wallet::where('userid', $uid)->first();
        if ($res) {
            $amountt = $res->amount + $res->unicash + $res->earning;
            if ($amountt >= $amt) {
                $bpoint = $amt * 0.01;
                if ($res->bp >= $bpoint) {
                    $used_bp = $bpoint;
                    $after_bp = $amt - $used_bp;
                } else {
                    $used_bp = 0;
                    $after_bp = $amt - $used_bp;
                }
                $arr = array('msg' => 'OK', 'bp' => number_format($used_bp, 2), 'amount' => $after_bp);
            } else {
                $arr = array('msg' => 'FAIL', 'status' => 'Insufficient Balance');
            }
        } else {
            $arr = array('msg' => 'FAIL',);
        }

        echo json_encode($arr);
    }
}
