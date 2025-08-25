<?php

namespace App\Http\Controllers\Website\Distributor;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MoneyTransferController extends Controller
{
    public function index()
    {
        return view('Website.Distributor.Wallet.money_transfer');
    }

    public function getUserName(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->validationMsg($validator->errors());
        }

        $user = User::where('user_num', $request->user_id)->first();

        if (!$user) {
            return $this->failMsg('User not found');
        }

        return $this->successMsg('User found', [
            'name' => $user->name,
            'user_id' => $user->user_id
        ]);
    }

    public function transferMoney(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|string|max:255',
            'confirm_user_id' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
            'reason' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return $this->validationMsg($validator->errors());
        }

        // Validate that both user IDs match
        if ($request->user_id !== $request->confirm_user_id) {
            return $this->failMsg('User ID and Confirm User ID do not match');
        }

        // try {
        DB::beginTransaction();

        $currentUser = Auth::user();
        $recipientUser = User::where('user_num', $request->user_id)->first();

        if (!$recipientUser) {
            return $this->failMsg('Recipient user not found');
        }

        // Check if sender is trying to send to themselves
        if ($currentUser->user_id === $request->user_id) {
            return $this->failMsg('You cannot transfer money to yourself');
        }

        // Get sender's wallet
        $senderWallet = Wallet::where('unm', $currentUser->user_num)->first();

        if (!$senderWallet) {
            return $this->failMsg('Your wallet not found');
        }

        if ($senderWallet->amount < $request->amount) {
            return $this->failMsg('Insufficient balance. Available balance: ₹' . $senderWallet->amount);
        }

        $payload = [
            'upin'         => $request->upin,
            'sender_id'    => $currentUser->user_num,
            'receiver_uid' => $request->user_id,
            'amount'       => $request->amount,
            'remark'       => $request->reason
        ];

        $res = $this->transactionHistory($payload);
        if (empty($res['status']) || !$res['status']) {
            DB::rollBack();
            return $this->failMsg($res['msg'] ?? 'Something went wrong');
        }

        DB::commit();

        return $this->successMsg('Money transferred successfully to ' . $recipientUser->name, [
            'new_balance' => $senderWallet->amount,
            'recipient_name' => $recipientUser->name
        ]);

        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return $this->failMsg('Transfer failed: ' . $e->getMessage());
        // }
    }

    private function transactionHistory($request)
    {
        $request = (object)$request;
        // if (!verifyUpin($request->sender_id, $request->upin-1))
        //     return ['status' => false, 'msg' => 'Miss Match Upin.'];

        $senderAmt = 0;
        $earn_amt = $uni1 = $amt = 0;

        $senderWallet = Wallet::where('unm', $request->sender_id)->first();

        $earning = (float)$senderWallet->earning ?? 0;
        $unicash = (float)$senderWallet->unicash ?? 0;
        $amount  = (float)$request->amount ?? 0;
        $bp      = (float)$senderWallet->bp ?? 0;

        $updateWallet = Wallet::find($senderWallet->id);
        if ($earning >= $amount) {
            $senderAmt  = $earning;
            $effect_amt = $earning - $amount;
            $balance    = $effect_amt + $unicash + (float)$senderWallet->amount;
            $earn_amt   = $amount;

            $updateWallet->earning =  $effect_amt;
            $description = "Unicash: 0 earning: " . $earning . " amount: 0" . " bp:" . $bp;
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
            $description = "Unicash: " . $remainAmt . " earning: " . $earn_amt . " amount: 0" . " bp:" . $bp;
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
            $description = "Unicash: " . $unicash . " earning: " . $earning . " amount: " . $remainAmt1 . " bp:" . $bp;
        }

        if ($senderAmt < $amount)
            return ['status' => false, 'msg' => 'insufficient Amount.'];

        if (!$updateWallet->save())
            return ['status' => false, 'msg' => 'Something went wrong, Wallet not updated.'];

        $orderId = rand(11111111, 99999999);

        $transPayload = [
            'unm'         => $senderWallet->unm ?? '',
            'user_id'     => $senderWallet->user_id ?? '',
            'debit'       => $amount,
            'balance'     => $balance,
            'transition_type' => 'money_transfer',
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

        $recieverWallet = Wallet::where('unm', $request->receiver_uid)->first();
        $walletAmt      = $recieverWallet->amount + $amount;
        $recieverWallet->amount = $walletAmt;
        if (!$recieverWallet->save())
            return ['status' => false, 'msg' => 'Something went wrong, Not insert in transaction history.'];

        $transPayload = [
            'unm'         => $recieverWallet->unm ?? '',
            'user_id'     => $recieverWallet->user_id ?? "",
            'credit'      => $amount,
            'balance'     => ($recieverWallet->amount ?? 0) + ($recieverWallet->earning ?? 0) + ($recieverWallet->unicash ?? 0),
            'transition_type' => 'money_transfer',
            'in_type'     => 'You have Recevied ' . $amount . ' from UNI' . Auth::user()->user_num,
            'remark'      => $request->remark ?? '',
            'amount'      => $amount,
            'order_id'    => $orderId
        ];
        if (walletTransaction($transPayload)) { //credit amount in receiver account
            return ['status' => true, 'msg' => 'Money Transferred.', 'array' => ['transaction_id' => (int)$orderId, 'date' => date('Y-m-d H:i:s')]];
        } else {
            return ['status' => false, 'msg' => 'Something went wrong.'];
        }
    }
}
