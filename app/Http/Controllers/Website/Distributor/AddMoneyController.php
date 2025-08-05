<?php

namespace App\Http\Controllers\Website\Distributor;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserKyc;
use App\Models\Wallet;
use App\Models\WalletHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AddMoneyController extends Controller
{

    public function addMoney(Request $request)
    {
        return view('Website.Distributor.Wallet.add_money');
    }

    public function previewAddMoney(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:50',
        ]);

        if ($validator->fails()) {
            return $this->validationMsg($validator->errors());
        }

        $currentUser = Auth::user();
        $userWallet = Wallet::where('userid', $currentUser->user_id)->first();
        $currentBalance = $userWallet ? $userWallet->amount : 0;
        $newBalance = $currentBalance + $request->amount;

        $previewData = [
            'name' => $currentUser->name,
            'email' => $currentUser->email,
            'mobile' => $currentUser->mobile,
            'amount' => $request->amount,
            'current_balance' => $currentBalance,
            'new_balance' => $newBalance
        ];

        return $this->successMsg('Preview data generated successfully', $previewData);
    }

    public function addMoneySave(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:50',
        ]);

        if ($validator->fails()) {
          return $this->validationMsg($validator->errors());
        }

        try {
            DB::beginTransaction();

            $currentUser = Auth::user();
            $userWallet = Wallet::where('userid', $currentUser->user_id)->first();

            if (!$userWallet) {
                $userWallet = new Wallet();
                $userWallet->userid = $currentUser->user_id;
                $userWallet->unm = $currentUser->name;
                $userWallet->amount = $request->amount;
                $userWallet->save();
            } else {
                $userWallet->amount = $userWallet->amount + $request->amount;
                $userWallet->save();
            }

            // Create wallet history for adding money (credit)
            $walletHistory = new WalletHistory();
            $walletHistory->user_id = $currentUser->user_id;
            $walletHistory->unm = $currentUser->user_num;
            $walletHistory->credit = $request->amount;
            $walletHistory->debit = 0;
            $walletHistory->balance = $userWallet->amount;
            $walletHistory->in_type = 'credit';
            $walletHistory->transition_type = 'add_money';
            $walletHistory->description = 'Money added to wallet';
            $walletHistory->amount = $request->amount;
            $walletHistory->earning = 0;
            $walletHistory->unicash = 0;
            $walletHistory->unipoint = 0;
            $walletHistory->remark = 'Money added via form';
            $walletHistory->from_type = 'wallet';
            $walletHistory->order_id = null;
            if($walletHistory->save()){
                DB::commit();
                return $this->successMsg('Money added successfully');
            }else{
                DB::rollBack();
                return $this->failMsg('Failed to add money');
            }

           
        } catch (\Exception $e) {
            DB::rollback();
            return $this->failMsg($e->getMessage());
        }
    }
}