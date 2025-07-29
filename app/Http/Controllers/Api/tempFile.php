<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use app\Services\Recharge\PayMyRecharge;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\RechargeModel;
use App\Models\User;
use App\Models\Level_count;
use App\Models\UserKyc;
use App\Models\WalletTransition;
use App\Models\Operator_code;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;

class RechargeController extends Controller
{

    public function findOperators(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_phone' => 'required|numeric|digits:10|not_in:0',
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
            'img'      => $res['img'] ?? ''
        ];

        return $this->recordRes($response);
    }

    public function findAllPlansTitle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'operator'   => 'required|string|min:2|max:100',
            'circle' => 'required|string|min:2|max:100',
        ]);
        if ($validator->fails())
            return validationRes($validator->messages());

        $recharge = new PayMyRecharge();
        $res = $recharge->findPlanTitle($request->operator, $request->circle);

        if (!empty($res) && $res['status'] == 'error')
            return $this->failRes($res['msg'] ?? '');

        return $this->recordRes($res['plan_title'] ?? array());
    }


    public function singlePlans(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile'   => 'required|numeric|digits:10|not_in:0',
            'plan'     => 'required|string|min:2|max:100',
            'operator' => 'required|string|min:2|max:100',
            'circle'   => 'required|string|min:2|max:100',
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
    }

    public function completePlansNoffer(Request $request)
    {
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
            'my_offer' => $res['my_offers'] ?? array(),
            'plans' => $res1['plans'] ?? array()
        ]);
    }

    public function rechargeMob(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId'     => 'required',
            'mobile'     => 'required|numeric|digits:10|not_in:0',
            'circle'     => 'required|string|',
            'operator'   => 'required|string|',
            'plan'       => 'required|numeric',
            'amount'     => 'required|gt:0'
        ]);

        if ($validator->fails())
            return validationRes($validator->messages());

        if (verifyUpin(Auth::user()->user_id, $request->plan))
            return ['status' => false, 'msg' => 'Miss Match Upin.'];

        $userRest = User::where('user_id', Auth::user()->user_id)->where('restricted', 1)->count();
        if ($userRest > 0)
            return $this->failRes('User Blocked.');

        $uid = $request->userId;
        $mob = $request->user_phone;
        $circleName = $request->circleName;
        $operator = $request->operator;
        $amt = $request->amt;
        $otp = $request->plan;
        $type = 'mob';

        if ($request->plan) {
            return self::recharge($uid, $mob, $operator, $amt, $type, $circleName, $otp, $request);
        } else {
            return $this->failRes('Can not Access Recharge Service');
        }
    }

    public function rechargeList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required|numeric',
            'limit' => 'required'
        ]);

        if ($validator->fails())
            return validationRes($validator->messages());

        return self::get_recharge_list($request->userId, $request->limit);
    }
    private function find_operators($mob)
    {
        $circle = $opt1 = '';
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://paymyrecharge.in/api/V5/apimaster.asmx/Rechargeoperatorfinder',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'mobile=' . $mob,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'Token: ' . rcToken
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $a = json_decode($response);
        if ($a->Data->records->status == 0) {
            $record = array(
                'error' => $a->Error,
                'responseMsg' => $a->Message,
                'desc' => $a->Data->records->desc,
                'operator' => 'Null'
            );
            return $this->failRes($record);
        } else {
            $opts =  Operator_code::where('operator_name', 'like', '%' . $a->Data->records->Operator . '%')->first();
            if (!empty($opts)) {
                $opt1 = $opts->img;
            }
            if ($a->Data->records->Operator == 'Vodafone' || $a->Data->records->Operator == 'VODAFONE' || $a->Data->records->Operator == 'AIRTEL') {
                if ($a->Data->records->circle == '17') {
                    $circle = 'Delhi NCR';
                } else if ($a->Data->records->circle == 'Delhi') {
                    $circle = 'Delhi NCR';
                } else if ($a->Data->records->circle == '8') {
                    $circle = 'Rajasthan';
                } else if ($a->Data->records->circle == '9') {
                    $circle = 'Maharashtra';
                } else {
                    $circle = $a->Data->records->circle;
                    if ($circle == 'null' || $circle == 'NULL' || $circle == null || $circle == '') {
                        $circle = $a->Data->records->comcircle;
                    }
                }
            } else if ($a->Data->records->Operator == 'Jio') {
                if ($a->Data->records->circle == 'Maharashtra') {
                    $circle = 'Maharashtra';
                } else if ($a->Data->records->circle == 'Delhi') {
                    $circle = 'Delhi NCR';
                } else {
                    $circle = $a->Data->records->circle;
                    if ($circle == 'null' || $circle == 'NULL' || $circle == null || $circle == '') {
                        $circle = $a->Data->records->comcircle;
                    }
                }
            } else {
                $circle = $a->Data->records->circle;
                if ($circle == 'null' || $circle == 'NULL' || $circle == null || $circle == '') {
                    $circle = $a->Data->records->comcircle;
                }
            }

            $record = array(
                'error' => $a->Error,
                'msg' => 'Success',
                'responseMsg' => $a->Message,
                'mob' => $a->Data->tel,
                'operator' => $a->Data->records->Operator,
                'TPstatus' => $a->Data->records->status,
                'circle' => $circle,
                'comcircle' => $a->Data->records->comcircle,
                'img' => 'https://uni-pay.in/assets/operator/' . $opt1
            );
            return $this->successRes($record);
        }
    }

    private function find_plans($circleName, $operator, $key = '')
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://paymyrecharge.in/api/V5/apimaster.asmx/RechargePlanfinder',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'oparetorName=' . $operator . '&circleName=' . $circleName,
            CURLOPT_HTTPHEADER => array(
                'Token: ' . rcToken,
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $a = json_decode($response);
        if ($a->Data->status == 0) {
            $record = array(
                'error' => $a->Error,
                'responseMsg' => $a->Message
            );
            return $this->failRes($record);
        } else {
            $record = array('error' => $a->Error, 'responseMsg' => $a->Message);
            if ($key) {
                foreach ($a->Data->records as $k => $value) {
                    if ($k == $key) {
                        $record['data'] = $a->Data->records->$k;
                    }
                }
            } else {
                $category = array('My Offer');
                foreach ($a->Data->records as $k => $value) {
                    $category[]  = $k;
                }
                $record['data'] = $category;
            }

            return $this->successRes($record);
        }
    }

    private function myOffer($mobile, $operator)
    {
        //echo "hi";
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://paymyrecharge.in/api/V5/apimaster.asmx/RechargeRoffer',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'Mobile=' . $mobile . '&operatorName=' . $operator,
            CURLOPT_HTTPHEADER => array(
                'Token: ' . rcToken,
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $a = json_decode($response);

        if ($a->Data->status == 0) {
            $record = array('error' => $a->Error, 'msg' => 'FAIL', 'responseMsg' => $a->Message);
            return $this->failRes($record);
        } else {
            $record = array(
                'error' => $a->Error,
                'msg' => 'Success',
                'responseMsg' => $a->Message,
                'data' => $a->Data
            );
            return $this->successRes($record);
        }

        //echo json_encode($arr);
    }

    private function complete_find_offer($mobile, $operator, $circle)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://paymyrecharge.in/api/V5/apimaster.asmx/RechargePlanfinder',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'oparetorName=' . $operator . '&circleName=' . $circle,
            CURLOPT_HTTPHEADER => array(
                'Token: ' . rcToken,
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $a = json_decode($response);
        if ($a->Data->status == 0) {
            $arr = array('error' => $a->Error, 'msg' => 'FAIL', 'responseMsg' => $a->Message);
        } else {

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://paymyrecharge.in/api/V5/apimaster.asmx/RechargeRoffer',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => 'Mobile=' . $mobile . '&operatorName=' . $operator,
                CURLOPT_HTTPHEADER => array(
                    'Token: ' . rcToken,
                    'Content-Type: application/x-www-form-urlencoded'
                ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            $a1 = json_decode($response);
            if ($a1->Data->status == 0) {
                $record['data']['records']['My Offer'] = 'NULL';
                return $this->failRes($record);
            } else {
                $record = array(
                    'error' => $a->Error,
                    'responseMsg' => $a->Message,
                    'Records' => $a->Data->records,
                    'My Offer' => $a1->Data->records
                );
                return $this->successRes($record);
            }
        }
        //echo json_encode($arr);
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

    private function recharge($userId, $mob, $operator, $amt1, $type, $circleName, $otp, $request)
    {
        // $epin = $otp - 1; //die;


        $transID = rand(6, 9999999);
        $get_num = RechargeModel::where('transId', $transID)->first();
        if ($get_num)
            $transID = rand(6, 9999999);

        $check_isactive = User::where('user_id', $userId)->where('isactive', 1)->first();
        if (!empty($check_isactive)) {
            $check_kyc = UserKyc::where('userId', $userId)->where('kyc_flag', 2)->first();
            if (empty($check_kyc))
                return $this->failRes('Kyc Not Completed....');

            $qryAmt = RechargeModel::where('user_id', $userId)->where('status', 'Success')->where('cur_date', 'LIKE', date('Y-m') . '%')->sum('amount');

            $conditionAmt = $amt1 + $qryAmt;
            if ($conditionAmt > 3500)
                return $this->failRes('Recharge Limit Completed....');

            return self::rechargecpy($userId, $mob, $operator, $amt1, $type, $circleName, $transID, 1);
        } else {
            $check_kyc = UserKyc::where('userId', $userId, 'kyc_flag', 2)->first();
            if (empty($check_kyc))
                return $this->failRes('Kyc Not Completed....');
            $qryAmt = RechargeModel::where('user_id', $userId)->where('status', 'Success')->where('cur_date', 'LIKE', date('Y-m') . '%')->sum('amount');

            $conditionAmt = $amt1 + $qryAmt;
            if ($conditionAmt > 3500)
                return $this->failRes('Recharge Limit Completed.....');

            return $reach = self::rechargecpy($userId, $mob, $operator, $amt1, $type, $circleName, $transID, 0);
        }
    }

    private function rechargecpy($userId, $mob, $operator, $amt1, $type, $circleName, $transID, $active)
    {
        $useBp = $cbs = $cc = 0;
        $type = 'recharge_cashback';

        $code = Operator_code::where('operator_name', 'like', '%' . $operator . '%')->first();

        //echo $this->db->last_query();
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
        $qry->recharge_type = 'mob';
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
                            $check_isactive = User::where('isactive', 0)->where('user_id', $userId)->first();
                            if ($check_isactive) {
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
                                                return $this->successRes($arr);
                                            } else {
                                                //$arr = array('msg'=>'Remain not added...');
                                                $arr = array('msg' => $a->status, 'response' => $a);
                                                return $this->successRes($arr);
                                            }
                                        }
                                    }
                                }
                            } else {
                                $check_availability1 = RechargeModel::where('cur_date', date('Y-m-d'))->where('user_id', $userId)->where('status', 'Success')->count();
                                if ($check_availability1 > 5) {

                                    $arr = array('msg' => $a->status, 'response' => $a, 'op_code' => $code->operator_code, 'cb' => 0);
                                    return $this->successRes($arr);
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
                                                return $this->successRes($arr);
                                            } else {

                                                //$arr = array('msg'=>'Remain not added...');
                                                $cc = $amt * 0.09;
                                                $cbs = (float)$bp + $cc;
                                                $arr = array('msg' => 'Success', 'response' => $a, 'op_code' => $code->operator_code, 'cb' => $cbs);
                                                return $this->successRes($arr);
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
                                if ($active == 1) {
                                    if ($check_availability->num_rows() > 5) {
                                        $cbs = 0;
                                    } else {
                                        $cc = $amt * 0.09;
                                        $cbs = number_format($bp + $cc, 2);
                                    }
                                } else {
                                    if ($check_availability->num_rows() > 1) {
                                        $cbs = 0;
                                    } else {
                                        $cc = $amt * 0.01;
                                        $cbs = number_format($cc, 2);
                                    }
                                }
                                $arr = array('msg' => $a->status, 'response' => $a, 'op_code' => $code->operator_code, 'cb' => $cbs);
                                return $this->successRes($arr);
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
        /*if(!empty($array)){
			$file = 'recharge'.date("Y-m-d").'.txt';
			$filepath = "uploads/logs/recharge/";
			if(!file_exists($filepath.$file)){
				$myfile = fopen($filepath.$file, "w") or die("Unable to open file!"); 
			}else{
				$myfile = fopen($filepath.$file, "a") or die("Unable to open file!"); 
			}
			foreach ($array as $key =>  $err) {
				fwrite($myfile, "\n".$key.' - '.$err);
			}    
			fclose($myfile);
		}	
		return $arr;*/
    }

    public function check_wallet($amount, $bp, $user_id, $order_id)
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
                $qq->transition_type = 'Recharge Mobile';
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
        $arr = array('amount' => $amount, 'bp' => $bp, 'uid' => $user_id, 'ord_id' => $order_id, 'earning' => $sender_wallet->earning, 'amount' => $sender_wallet->amount, 'unicash' => $sender_wallet->unicash, 'senderAmt' => $senderAmt, 'out' => json_encode($out));
        if (!empty($arr)) {
            $file = 'check_wallet' . date("Y-m-d") . '.txt';
            $filepath = "uploads/logs/recharge/";
            if (!file_exists($filepath . $file)) {
                $myfile = fopen($filepath . $file, "w") or die("Unable to open file!");
            } else {
                $myfile = fopen($filepath . $file, "a") or die("Unable to open file!");
            }

            $arr['break'] = "-------------------------------" . date('Y-m-d  H:i:s');

            foreach ($arr as $key =>  $err) {
                fwrite($myfile, "\n" . $key . ' - ' . $err);
            }
            fclose($myfile);
        }
        return $out;
    }

    public function get_recharge_list($uid, $limit)
    {
        if ($limit == 'limit')
            $res = RechargeModel::where('user_id', $uid)->orderBy('id', 'desc')->limit('15')->get();

        $res = RechargeModel::where('user_id', $uid)->orderBy('id', 'desc')->get();

        if ($res) {
            foreach ($res as $key => $r) {
                $arr['data'][] = array('mobile' => $r->mobile, 'uniTransId' => $r->transId, 'operator_code' => $r->operator_code, 'operator' => $r->operator, 'amount' => $r->amount, 'bp' => $r->bp, 'after_bp_amt' => $r->after_bp_amt, 'status' => $r->status, 'dateNtime' => $r->create_on, 'transaction_id' => $r->transaction_id, 'operatorref' => $r->operatorref);
            }
            return $this->successRes($arr);
        } else {
            return $this->successRes('no record found.....');
        }
    }
    function userLvl($user_id = 2218)
    {
        $get_num = Level_count::select('users_lvl.user_id')
            ->join('users_lvl', 'users_lvl.user_id', '=', 'level_count.parent_id')
            ->where('level_count.child_id', $user_id)
            ->where('level_count.level', '>', 1)
            ->where('level_count.level', '<=', 15)
            ->where('users_lvl.isactive', 1)
            ->get();
        foreach ($get_num as $res) {
            echo $res->user_id;
        }
    }
}
