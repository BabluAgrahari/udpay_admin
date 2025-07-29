<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\UniPostPlan;
use App\Models\UniPost;
use App\Models\UniPostPo;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransition;
use Exception;
use Illuminate\Support\Facades\Auth;

class UnipostController extends Controller
{
    public function getPostList()
    {
        try {
            $uniPost = UniPost::where('status', '1')->where('postType', 'Posts')->get();
            if ($uniPost->isEmpty())
                return $this->notFoundRes();

            foreach ($uniPost as $r) {
                $record[$r->category][] = array('img' => 'https://uni-pay.digital/aspx/uploads/posts/' . $r->img);
            }
            return $this->recordRes(
                [
                    'user_id'     => Auth::User()->user_id,
                    'user_mobile' => Auth::User()->mobile,
                    'data'        => $record
                ]
            );
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }

    public function uniPostPlan()
    {
        try {
            $res = UniPostPlan::where('status', 1)->get();
            if ($res->isEmpty())
                return $this->notFoundRes();

            $record = [];
            foreach ($res as $r) {
                $record[] = array(
                    'id'     => $r->id,
                    'title'  => $r->title,
                    'amount' => $r->amount
                );
            }
            return $this->recordRes($record);
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }

    public function planPo(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'plan_id'      => 'required|numeric',
            ]);
            if ($validator->fails())
                return $this->validationRes($validator->messages());

            $planId = $request->plan_id;
            $uid    =  Auth::User()->user_id;

            $plan       = UniPostPlan::where('id', $planId)->first();
            $userWallet = Wallet::where('userid', $uid)->first();

            if ($userWallet->bp <= $plan->amount)
                return $this->failRes('Insufficient Bonus Point.');

            if ($planId == 1) {
                $expDt = date('Y-m-d', strtotime('+1 month', strtotime(date('Y-m-d'))));
            } else if ($planId == 2) {
                $expDt = date('Y-m-d', strtotime('+1 year', strtotime(date('Y-m-d'))));
            } else {
                $expDt = "lifetime";
            }

            $userPlanCheck = User::where('user_id', $uid)->first();
            if ($userPlanCheck->planMem > 0) {
                $checkMemPlan = UniPostPo::where('uid', $uid)->first();
                if ($checkMemPlan->expiry_date == 'lifetime') {
                    return $this->failRes('You have Already Purchased lifetime Membership.');
                } else {
                    return $this->failRes('Your Post Plan is Already Purchased.');
                }
            } else {
                $orderId = date('ymdis') . rand(1111, 9999);
                $wallet = $this->checkWallet($uid, $plan->amount, $orderId);
                if (!$wallet['status'])
                    return $this->failRes($wallet['msg'] ?? '');

                $insQry = new UniPostPo();
                $insQry->uid         = $uid;
                $insQry->plan_id     = $planId;
                $insQry->amount      = $plan->amount;
                $insQry->pur_date    = date('Y-m-d H:i:s');
                $insQry->expiry_date = $expDt;
                $insQry->order_id    = $orderId;
                $insQry->updated_on  = '';
                $insQry->status      = 1;
                if ($insQry->save()) {
                    User::where('user_id', $uid)->update(['planMem' => 1]);
                    return $this->successRes('Your Post Plan is Activate Now...Your Order Id' . $orderId);
                } else {
                    return $this->failRes('Something Went Wrong, UniPost not updated.');
                }
            }
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }



    public function checkWallet($uid, $amount, $orderId)
    {
        try {
            $userWallet = Wallet::where('userid', $uid)->first();
            if ($userWallet->bp <= $amount)
                return ['status' => false, 'msg' => 'Insufficient Bonus Point.'];

            $remainBp = $userWallet->bp - $amount;
            $update = Wallet::where('userid', $uid)->update(['bp' => $remainBp]);
            if ($update) {
                $balance = $userWallet->earning + $userWallet->amount + $userWallet->unicash;
                $des = 'Order Amount Deducted from amount : 0 unicash: 0 earning 0 bp :' . $remainBp;
                $insQry = new WalletTransition();
                $insQry->user_id         = $uid;
                $insQry->debit           = $amount;
                $insQry->credit          = 0;
                $insQry->balance         = $balance;
                $insQry->transition_type = 'post_po';
                $insQry->amount          = 0;
                $insQry->unipoint        = $amount;
                $insQry->unicash         = 0;
                $insQry->earning         = 0;
                $insQry->in_type         = 'Your Bonus Wallet is Debited ' . $amount . ' for Post Membership to ' . $orderId;
                $insQry->description     = $des;
                $insQry->ord_id          = $orderId;
                $insQry->remark          = '';
                if ($insQry->save()) {
                    return ['status' => true, 'msg' => 'done'];
                } else {
                    return  ['status' => false, 'msg' => 'Something Went Wrong.'];
                }
            } else {
                return  ['status' => false, 'msg' => 'Something Went Wrong.'];
            }
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }
}
