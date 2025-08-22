<?php

namespace App\Http\Controllers\Website\Distributor;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserKyc;
use App\Models\Wallet;
use App\Models\UniCashDetail;
use App\Models\WalletHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\PaymentGatway\CashFree;
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

        $currentUser = Auth::user();
        // $payload = [
        //     'order_id' => 'MT-' . time() . '-' . $currentUser->user_id,
        //     'customer_id' => $currentUser->user_num,
        //     'customer_name' => $currentUser->name,
        //     'customer_email' => $currentUser->email,
        //     'customer_phone' => $currentUser->mobile,
        //     'return_url' => url('add-money/payment-response/cashfree/?order_id={order_id}'),
        //     'webhook_url' => url('add-money/payment-webhook/cashfree')
        // ];
        // $payment = new CashFree();
        // $paymentResponse = $payment->createOrder($request->amount, 'INR', $payload);

        // if (empty($paymentResponse['status']) || $paymentResponse['status'] == false) {
        //     return $this->failMsg('Payment Failed');
        // }
        
        // if(empty($paymentResponse['payment_session_id'])){
        //     return $this->failMsg('Payment Session ID not found');
        // }
        // $payment_session_id =  $paymentResponse['payment_session_id'];

        // $unicash = new UniCashDetail();
        // $unicash->user_id = $currentUser->user_id;
        // $unicash->unm = $currentUser->user_num;
        // $unicash->amount = $request->amount;
        // $unicash->order_id = $paymentResponse['order_id'];
        // $unicash->status = 'initiated';
        // $unicash->payout_type = 'cash_free';
        // $unicash->created_on = date('Y-m-d H:i:s');
        // $unicash->transition_id = $paymentResponse['cashfree_order_id'];
        // $unicash->bank_txn_id = '';
        // $unicash->bank_res_code = '';
        // $unicash->description = 'Payment initiated';
        // $unicash->payment_response = json_encode($paymentResponse);
        // if($unicash->save()){
        //     return $this->successMsg('Payment initiated successfully',['payment_session_id'=>$payment_session_id]);
        // }
        

        try {
            DB::beginTransaction();
            $userWallet = Wallet::where('userid', $currentUser->user_id)->first();
            if (!$userWallet) {
                $userWallet = new Wallet();
                $userWallet->userid = $currentUser->user_id;
                $userWallet->unm = $currentUser->user_num;
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