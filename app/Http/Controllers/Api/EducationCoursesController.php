<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\KicoCourse;
use App\Models\KicoDetails;
use App\Models\Wallet;
use App\Models\WalletTransition;
use App\Models\User;
use App\Models\LevelCount;
use DB;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EducationCoursesController extends Controller
{
    public function index()
    {
        try {
            $records = KicoCourse::where('status', 1)->get()->map(function ($record) {
                return [
                    'course_id' => $record->id,
                    'title'     => $record->title,
                    'amount'    => (float)$record->amount,
                    'unipoint'  => (float)$record->bp
                ];
            });
            if ($records->isEmpty())
                $this->notFoundRes();

            $result  = [];
            foreach ($records as $record) {
                $tp =  ($record == 'kicko') ? 'unilearn' : $record->type;
                $result[$tp][] = $record;
            }

            return $this->recordRes($result);
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }

    public function purchaseCourse(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name'      => 'required|string|min:2|max:100',
                'email'     => 'required|string|email',
                'mobile'    => 'required|numeric|digits:10',
                'language'  => 'required|string|in:english,hindi',
                'class'     => 'required|string',
                'course'    => 'required|numeric',
                'edu_type'  => 'required',
                'upin'      => 'required|numeric|digits:4'

            ]);
            if ($validator->fails())
                return $this->validationRes($validator->messages());

            $uid = Auth::user()->user_id;

            if ($this->crossCheck($uid, $request->upin - 1))
                return $this->failRes('Missmatch U-PIN.');

            $res = $this->getKico($uid, $request->class, $request->mobile, $request->email, $request->course, $request->name, $request->edu_type);

            if (!$res['status']) {
                return $this->failRes($res['msg']);
            } else {
                return $this->recordResMsg($res['array'] ?? array(), $res['msg'] ?? '');
            }
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }

    private function crossCheck($uid, $epin)
    {
        $count = User::where('user_id', $uid)->where('epin', md5($epin))->count();
        if ($count > 0)
            return true;

        return false;
    }

    public function getKico($uid, $class, $mobile, $email, $course, $name, $edu_type)
    {
        $userUniqueId = date('dsi') . rand(1111, 9999);

        $courseDtl    = KicoCourse::where('id', $course)->first();

        if (empty($courseDtl))
            return ['status' => false, 'msg' => 'Course not Available'];

        $unipoint = $courseDtl->bp ?? 0;
        $amount   = $courseDtl->amount ?? 0;

        $tp = ($edu_type == 'unilearn') ? 'UDES Training' : 'Kicko Class';

        $WalletRes = Wallet::where('userid', $uid)->first();
        $bp = 0;
        if (!empty($WalletRes)) {
            $bp = $amount * $unipoint / 100;
            $bp = ($WalletRes->bp >= $bp) ? $bp : $WalletRes->bp;
            $amt = $amount - $bp;
        }
        $type = 'kico_cashback';

        $check_isactive = User::where('user_id', $uid)->first();
        if (empty($check_isactive))
            return ['status' => false, 'msg' => 'Kico Offer is only available for UNIPAY Distributor'];
        
        $checkWallet = $this->debitUserWallet($amt, $bp, $uid, $userUniqueId, $tp); // debit user wallet
        if (!$checkWallet['status'])
            return ['status' => false, 'msg' => $checkWallet['msg'] ?? ''];

        $insQry       = new KicoDetails();
        $insQry->uid       = $uid;
        $insQry->class     = $class;
        $insQry->edu_type  = $edu_type;
        $insQry->member_id = $userUniqueId;
        $insQry->email     = $email;
        $insQry->mobile    = $mobile;
        $insQry->course    = $course;
        $insQry->name      = $name;
        $insQry->cur_date  = date('Y-m-d');
        $insQry->status    = '1';
        $insQry->amount    = $amt;
        $insQry->order_id  = $userUniqueId;
        if (!$insQry->save()) {
            Log::warning(" user data not inserted in kico details [$uid]");
            return ['status' => false, 'msg' => 'Something Went Wrong, Not Saved.'];
        }

        $cashbackAmt = $amt * 0.08;
        $lvlCash     = $cashbackAmt / 15;
        $addWallet   = add_wallet(4, $uid, $cashbackAmt);
        if (!$addWallet) {
            Log::warning("user cashback not inserted in wallet transition [$uid]");
            return ['status' => true, 'array' => ['member_id' => $userUniqueId], 'msg' => 'Successfull.'];
        }

        $balance = $this->userWallet($uid);
        $transPayload = [
            'user_id'         => $uid,
            'transition_type' => 'kico_cashback',
            'credit'          => (float)$cashbackAmt,
            'balance'         => (float)$balance,
            'in_type'         => "You Earn " . (float)$cashbackAmt . " Unicash",
            'order_id'        => $userUniqueId
        ];
        walletTransaction($transPayload);

        $first_lvl_user = LevelCount::where('child_id', $uid)->where('level', '<=', 1)->first();
        $add_wallet = add_wallet($key = 4, $first_lvl_user->parent_id, $lvlCash);
        if (!$add_wallet) {
            Log::warning("first lvl user  cashback cashback not inserted in wallet transition [$first_lvl_user->parent_id]");
            return ['status' => true, 'array' => ['member_id' => $userUniqueId], 'msg' => 'Successfull.'];
        }
        $balance1 = $this->userWallet($first_lvl_user->parent_id);
        $transPayload = [
            'user_id'         => $first_lvl_user->parent_id,
            'transition_type' => 'kico_cashback',
            'credit'          => (float)$lvlCash,
            'balance'         => (float)$balance1,
            'in_type'         => "You Earn " . (float)$lvlCash . " Unicash",
            'order_id'        => $userUniqueId
        ];
        walletTransaction($transPayload);

        $this->cash_back_distribution($lvlCash, $uid, $userUniqueId, $type);
        return ['status' => true, 'array' => ['member_id' => $userUniqueId], 'msg' => 'Successfull.'];
    }

    private function userWallet($user)
    {
        $qry = Wallet::where('userid', $user)->first();
        return $qry->unicash + $qry->earning + $qry->amount;
    }

    private function debitUserWallet($amount, $bp, $user_id, $order_id, $tp)
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
            $userWalletData        = array('earning' => $effectAmt, 'bp' => $userWallet->bp - $bp);
        } else if ($userWallet->unicash + $userWallet->earning >= $amount) {

            $remainAmt     = $amount - $userWallet->earning;
            $userWalletAmt = $userWallet->earning + $userWallet->unicash;
            $effect_amt    = $userWallet->unicash + $userWallet->earning - $amount;
            $earningAmt    = $userWallet->earning;
            $unicashAmt    = $remainAmt;
            $toatlBalance  = $effect_amt + $userWallet->amount;
            $userWalletData        = array('earning' => 0, 'unicash' => ($userWallet->unicash - $remainAmt), 'bp' => $userWallet->bp - $bp);
        } else if ($userWallet->earning + $userWallet->unicash + $userWallet->amount >= $amount) {

            $remainAmt     = $amount - $userWallet->earning;
            $amtAdd        = $remainAmt - $userWallet->unicash;
            $senderAmount  = $userWallet->amount - $amtAdd;
            $userWalletAmt = $userWallet->earning + $userWallet->unicash + $userWallet->amount;
            $earningAmt    = $userWallet->earning;
            $unicashAmt    = $userWallet->unicash;
            $toatlBalance  = $userWallet->unicash + $userWallet->earning + $userWallet->amount - $amount;
            $userWalletData = array('earning' => 0, 'unicash' => 0, 'amount' => $senderAmount, 'bp' => $userWallet->bp - $bp);
        }

        if ($userWalletAmt >= $amount) {
            Wallet::where('userid', $user_id)->update($userWalletData);
            $transPayload = [
                'user_id'         => $user_id,
                'transition_type' => 'purchase_kico',
                'debit'           => $amount,
                'balance'         => (float)$toatlBalance,
                'in_type'         => 'Your Wallet is Debited ' . $amount . ' for ' . $tp . ' to UNI' . $order_id,
                'description'     => 'amount : ' . $amtAdd . ' unicash: ' . $unicashAmt . ' earning : ' . $earningAmt . ' uniPoint: ' . $userWallet->bp,
                'amount'          => $amtAdd,
                'unicash'         => $unicashAmt,
                'earning'         => $earningAmt,
                'unipoint'        => $bp,
                'order_id'        => $order_id
            ];

            if (walletTransaction($transPayload)) {
                return ['status' => true, 'msg' => 'insufficent Amount.'];
            } else {
                return ['status' => false, 'msg' => 'Amount not debit.'];
            }
        } else {
            return ['status' => false, 'msg' => 'insufficent Amount.'];
        }
    }

    private function cash_back_distribution($lvlCash, $user_id, $transID, $type)
    {
        $i = 0;
        $totalCash = 0;
        $records = DB::table('level_count as a')
            ->select('b.user_id')
            ->leftJoin('users_lvl as b', 'b.user_id', '=', 'a.parent_id')
            ->where('a.child_id', $user_id)
            ->where('a.level', '>', 1)
            ->where('a.level', '<=', 15)
            ->where('b.isactive', 1)
            ->get();
        if ($records->isNotEmpty()) {
            $i = 0;
            foreach ($records as $res) {
                $i++;
                $userWallet = $this->userWallet($res->user_id);
                $walletBal = $userWallet + $lvlCash;

                $addReceiver = add_wallet($key = 4, $res->user_id, $lvlCash);
                if ($addReceiver) {

                    $transPayload = [
                        'user_id'         => $res->user_id,
                        'transition_type' => $type,
                        'credit'          => (float)$lvlCash,
                        'balance'         => (float)$walletBal,
                        'in_type'         => "You Earn " . (float)$lvlCash . " Unicash",
                        'order_id'        => $transID
                    ];
                    walletTransaction($transPayload); //credit amount in wallet
                    $totalCash += $lvlCash;
                    Log::info($lvlCash . ' Level cashback Added to UserID' . $res->user_id);
                } else {

                    Log::warning($i . ' ' . $res->user_id . ' wallet transition level data not insert');
                }
            }
        } else {
            Log::warning($i . ' ' . $user_id . ' level Member Not Available');
        }

        return 0;
    }
}
