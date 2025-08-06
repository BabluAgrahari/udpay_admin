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

        $user = User::where('user_id', $request->user_id)->first();

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

        try {
            DB::beginTransaction();

            $currentUser = Auth::user();
            $recipientUser = User::where('user_id', $request->user_id)->first();

            if (!$recipientUser) {
                return $this->failMsg('Recipient user not found');
            }

            // Check if sender is trying to send to themselves
            if ($currentUser->user_id === $request->user_id) {
                return $this->failMsg('You cannot transfer money to yourself');
            }

            // Get sender's wallet
            $senderWallet = Wallet::where('userid', $currentUser->user_id)->first();

            if (!$senderWallet) {
                return $this->failMsg('Your wallet not found');
            }

            if ($senderWallet->amount < $request->amount) {
                return $this->failMsg('Insufficient balance. Available balance: ₹' . $senderWallet->amount);
            }

            // Get recipient's wallet
            $recipientWallet = Wallet::where('userid', $recipientUser->user_id)->first();

            if (!$recipientWallet) {
                // Create wallet for recipient if doesn't exist
                $recipientWallet = new Wallet();
                $recipientWallet->userid = $recipientUser->user_id;
                $recipientWallet->unm = $recipientUser->name;
                $recipientWallet->amount = $request->amount;
                $recipientWallet->save();
            } else {
                $recipientWallet->amount = $recipientWallet->amount + $request->amount;
                $recipientWallet->save();
            }

            // Update sender's wallet (debit)
            $senderWallet->amount = $senderWallet->amount - $request->amount;
            $senderWallet->save();

            // Create wallet history for sender (debit)
            $senderHistory = new WalletHistory();
            $senderHistory->user_id = $currentUser->user_id;
            $senderHistory->unm = $currentUser->name;
            $senderHistory->credit = 0;
            $senderHistory->debit = $request->amount;
            $senderHistory->balance = $senderWallet->amount;
            $senderHistory->in_type = 'debit';
            $senderHistory->transition_type = 'money_transfer';
            $senderHistory->description = 'Money transferred to ' . $recipientUser->name . ' - ' . $request->reason;
            $senderHistory->amount = $request->amount;
            $senderHistory->earning = 0;
            $senderHistory->unicash = 0;
            $senderHistory->unipoint = 0;
            $senderHistory->remark = $request->reason;
            $senderHistory->from_type = 'wallet';
            $senderHistory->order_id = null;
            $senderHistory->save();

            // Create wallet history for recipient (credit)
            $recipientHistory = new WalletHistory();
            $recipientHistory->user_id = $recipientUser->user_id;
            $recipientHistory->unm = $recipientUser->name;
            $recipientHistory->credit = $request->amount;
            $recipientHistory->debit = 0;
            $recipientHistory->balance = $recipientWallet->amount;
            $recipientHistory->in_type = 'credit';
            $recipientHistory->transition_type = 'money_received';
            $recipientHistory->description = 'Money received from ' . $currentUser->name . ' - ' . $request->reason;
            $recipientHistory->amount = $request->amount;
            $recipientHistory->earning = 0;
            $recipientHistory->unicash = 0;
            $recipientHistory->unipoint = 0;
            $recipientHistory->remark = $request->reason;
            $recipientHistory->from_type = 'wallet';
            $recipientHistory->order_id = null;
            $recipientHistory->save();

            DB::commit();

            return $this->successMsg('Money transferred successfully to ' . $recipientUser->name, [
                'new_balance' => $senderWallet->amount,
                'recipient_name' => $recipientUser->name
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return $this->failMsg('Transfer failed: ' . $e->getMessage());
        }
    }
} 