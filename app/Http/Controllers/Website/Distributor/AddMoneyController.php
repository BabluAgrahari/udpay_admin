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
use Illuminate\Support\Facades\Log;

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
        try {
            $validator = Validator::make($request->all(), [
                'amount' => 'required|numeric|min:50',
            ]);

            if ($validator->fails()) {
                return $this->validationMsg($validator->errors());
            }


            $currentUser = Auth::user();
            $order_id = 'CF-' . time() . '-' . $currentUser->user_id;
            $payload = [
                'order_id' => $order_id,
                'customer_id' => $currentUser->user_num,
                'customer_name' => $currentUser->name,
                'customer_email' => $currentUser->email,
                'customer_phone' => $currentUser->mobile,
                'return_url' => url('add-money/payment-response/cashfree/?order_id={order_id}'),
                'webhook_url' => url('add-money/payment-webhook/cashfree')
            ];
            $payment = new CashFree();
            $paymentResponse = $payment->createOrder($request->amount, 'INR', $payload);

            if (empty($paymentResponse['status']) || $paymentResponse['status'] == false) {
                return $this->failMsg('Payment Failed');
            }

            if (empty($paymentResponse['payment_session_id'])) {
                return $this->failMsg('Payment Session ID not found');
            }
            $payment_session_id =  $paymentResponse['payment_session_id'];

            $unicash = new UniCashDetail();
            $unicash->user_id = $currentUser->user_id;
            $unicash->unm = $currentUser->user_num;
            $unicash->amount = $request->amount;
            $unicash->order_id = $order_id;
            $unicash->status = 'initiated';
            $unicash->payout_type = 'cash_free';
            $unicash->created_on = date('Y-m-d H:i:s');
            $unicash->transition_id = $paymentResponse['cashfree_order_id'] ?? '';
            // $unicash->bank_txn_id = '';
            // $unicash->bank_res_code ='';
            $unicash->description = 'Payment initiated';
            $unicash->payment_response = json_encode($paymentResponse);
            if ($unicash->save()) {
                return $this->successMsg('Payment initiated successfully', [
                    'payment_session_id' => $payment_session_id,
                    'payment_gateway' => 'cashfree'
                ]);
            }
            return $this->failMsg('Payment initiated failed');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->failMsg($e->getMessage());
        }
    }


    public function paymentResponse(Request $request)
    {
        $unicash = UniCashDetail::where('order_id', $request->order_id)->first();
        if (!$unicash) {
            Log::info('No unicash found for order id: ' . $request->order_id);
            return redirect()->to('distributor/wallet/transaction-history')->with('error', 'No unicash found for order id');
        }

        $payment = new CashFree();
        $res = $payment->getPayment($request->order_id);
        if (empty($res['status']) || $res['status'] == false) {
            Log::info('Add Money Payment Redirect cashfree Response Failed -' . $request->order_id, [$res]);
            return redirect()->to('distributor/wallet/transaction-history')->with('error', 'Add Money Payment Redirect cashfree Response Failed');
        }

        $paymentStatus = ((!empty($res['payment_status']) && $res['payment_status'] == 'SUCCESS') && !empty($res['is_captured'])) ? 'success' : (strtolower($res['payment_status']) ?? 'failed');
        $unicash->status = $paymentStatus;
        $unicash->bank_txn_id = $res['bank_reference'] ?? '';
        $unicash->payment_response = json_encode($res);
        if (!$unicash->save()) {
            return redirect()->to('distributor/wallet/transaction-history')->with('error', 'Money added to wallet failed');
        }

        if ($unicash->status == 'success') {
            $user_id = $unicash->user_id;
            $userWallet = Wallet::where('userid', $user_id)->first();
            if (!$userWallet) {
                $userWallet = new Wallet();
                $userWallet->userid = $user_id;
                $userWallet->unm = $unicash->unm;
                $userWallet->amount = $unicash->amount;
                $userWallet->save();
            } else {
                $userWallet->amount = $userWallet->amount + $unicash->amount;
                $userWallet->save();
            }
            // Create wallet history for adding money (credit)

            $closing_amount = $userWallet->amount ?? 0;
            $closing_earning = $userWallet->earning ?? 0;
            $closing_unicash = $userWallet->unicash ?? 0;
            $closing_bp = $userWallet->bp ?? 0;

            $description = "Unicash: " . $closing_unicash . " earning: " . $closing_earning . " amount: " . $closing_amount . " bp:" . $closing_bp;
            $in_type = ' Your Wallet is Creditd ' . $unicash->amount . ' for ' . $unicash->bank_txn_id . ' Using UPI-PG';

            $walletHistory = new WalletHistory();
            $walletHistory->user_id = $user_id;
            $walletHistory->unm = $unicash->unm;
            $walletHistory->credit = $unicash->amount;
            $walletHistory->debit = 0;
            $walletHistory->balance = $userWallet->amount;
            $walletHistory->in_type = $in_type;
            $walletHistory->transition_type = 'money_transfer';
            $walletHistory->description = $description;
            $walletHistory->amount = $unicash->amount;
            $walletHistory->earning = 0;
            $walletHistory->unicash = 0;
            $walletHistory->unipoint = 0;
            $walletHistory->closing_amount = $closing_amount;
            $walletHistory->closing_earning = $closing_earning;
            $walletHistory->closing_unicash = $closing_unicash;
            $walletHistory->closing_bp = $closing_bp;
            $walletHistory->remark = 'Money added via cashfree';
            $walletHistory->from_type = 'wallet';
            $walletHistory->order_id = null;
            $walletHistory->save();

            return redirect()->to('distributor/wallet/transaction-history')->with('success', 'Money added to wallet successfully');
        } else {
            return redirect()->to('distributor/wallet/transaction-history')->with('error', 'Some went wrong Payment failed.');
        }
    }
}
