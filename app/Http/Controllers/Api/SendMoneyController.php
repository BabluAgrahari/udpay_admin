<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Validation\SendMoneyValidation;
use App\Models\UserKyc;
use App\Models\WalletTransition;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\User_kyc;
use App\Models\Wallet;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SendMoneyController extends Controller
{
    function sendMoney(SendMoneyValidation $request)
    {
        try {
			return $this->failRes('Please Wait for Sometime...');die;
            if (Auth::user()->isactive != 1)
                return $this->failRes('Please Active Your Account.');

            if (Auth::user()->restricted > 0)
                return $this->failRes('Can not Access send money Service due to Block User.');

            $userId = Auth::user()->user_nm;
            if ($userId == $request->receiver_uid)
                return $this->failRes('Invaliad Receiver UId.');

            $kyc = UserKyc::where('userId', Auth::user()->user_id)->where('kyc_flag', 2)->first();
            if (empty($kyc))
                return $this->failRes('KYC Not Approved.');

            $receiverCount = User::where('user_nm', $request->receiver_uid)->count();
            if ($receiverCount <= 0)
                return $this->failRes('Receiver Not Found.');

            $payload = [
                'upin'         => $request->upin,
                'sender_id'    => $userId,
                'receiver_uid' => $request->receiver_uid,
                'amount'       => $request->amount,
                'remark'       => $request->remark
            ];
			$u = User::where('user_nm', $userId)->first();
			
            $res = $this->transactionHistory($payload);
            if ($res['status']){
				Log::info($res['array']);
                return $this->recordResMsg($res['array']??array(),$res['msg']??'');
				
            } else {
				Log::warning($res['msg']);
                return $this->failRes($res['msg']??'');
				
            }
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }

    private function transactionHistory($request)
    {
        $request = (object)$request;
        if (!verifyUpin($request->sender_id, $request->upin-1))
            return ['status' => false, 'msg' => 'Miss Match Upin.'];

        $senderAmt = 0;
        $earn_amt = $uni1 = $amt = 0;

        $senderWallet = Wallet::where('userid', $request->sender_id)->first();

        $earning = (float)$senderWallet->earning ?? 0;
        $unicash = (float)$senderWallet->unicash ?? 0;
        $amount  = (float)$request->amount ?? 0;

        $updateWallet = Wallet::find($senderWallet->id);
        if ($earning >= $amount) {
            $senderAmt  = $earning;
            $effect_amt = $earning - $amount;
            $balance    = $effect_amt + $unicash + (float)$senderWallet->amount;
            $earn_amt   = $amount;

            $updateWallet->earning =  $effect_amt;
            $description = "Unicash: 0 earning: " . $earning . " amount: 0";
        } else if (($unicash + $earning) >= $amount) {
            $earn_amt   = $earning;
            $remainAmt  = $amount - $earning;
            $uni1       = $remainAmt;
            $uni        = $unicash - $remainAmt;
            $senderAmt  = $earn_amt + $unicash;
            $effect_amt = $unicash + $earn_amt - $amount;
            $balance    = $effect_amt + (float)$senderWallet->amount;

            $updateWallet->earning = 0;
            $updateWallet->unicash = $uni;
            $description = "Unicash: " . $remainAmt . " earning: " . $earn_amt . " amount: 0";
        } else if (($earning + $unicash + (float)$senderWallet->amount) >= $amount) {
            $earn_amt     = $earning;
            $uni1         = $unicash;
            $remainAmt    = $amount - $earning;
            $remainAmt1   = $remainAmt - $unicash;

            $senderAmount = $senderWallet->amount - $remainAmt1;
            $amt          = $remainAmt1;
            $senderAmt    = $earning + $unicash + $amount;
            $effect_amt   = $unicash + $earning + (float)$senderWallet->amount - $amount;
            $balance      = $effect_amt;

            $updateWallet->earning = 0;
            $updateWallet->unicash = 0;
            $updateWallet->amount  = $senderAmount;
            $description = "Unicash: " . $unicash . " earning: " . $earning . " amount: " . $remainAmt1;
        }

        if ($senderAmt < $amount)
            return ['status' => false, 'msg' => 'insufficient Amount.'];

        if (!$updateWallet->save())
            return ['status' => false, 'msg' => 'Something went wrong, Wallet not updated.'];

        $orderId = rand(11111111, 99999999);

        $transPayload = [
            'user_id'     => $request->sender_id ?? '',
            'debit'       => $amount,
            'balance'     => $balance,
            'in_type'     => 'Your Wallet is Debited ' . $amount . ' for Wallet Transfer to UNI' . $request->receiver_uid,
            'description' => $description,
            'remark'      => $request->remark ?? '',
            'unicash'     => $uni1,
            'earning'     => $earn_amt,
            'amount'      => $amt,
            'order_id'    => $orderId
        ];

        if (!walletTransaction($transPayload)) //debit from sender wallet
            return ['status' => false, 'msg' => 'Something went wrong, Not insert in transaction history.'];

        $recieverWallet = Wallet::where('userid', $request->receiver_uid)->first();
        $walletAmt      = $recieverWallet->amount + $amount;
        $recieverWallet->amount = $walletAmt;
        if (!$recieverWallet->save())
            return ['status' => false, 'msg' => 'Something went wrong, Not insert in transaction history.'];

        $senUid = ($request->sender_id <= 11) ? 'UNIPAY' : Auth::user()->alpha_num_uid;
        $transPayload = [
            'user_id'     => $request->receiver_uid ?? "",
            'credit'      => $amount,
            'balance'     => $walletAmt,
            'in_type'     => 'You have Recevied ' . $amount . ' from ' . $senUid,
            'remark'      => $request->remark ?? '',
            'amount'      => $amount,
            'order_id'    => $orderId
        ];
        if (walletTransaction($transPayload)) { //credit amount in receiver account
            return ['status' => true, 'msg' => 'Money Transferred.', 'array'=>['transaction_id' => (int)$orderId, 'date' => date('Y-m-d H:i:s')]];
        } else {
             return ['status' => false, 'msg' => 'Something went wrong.'];
        }
    }
}
