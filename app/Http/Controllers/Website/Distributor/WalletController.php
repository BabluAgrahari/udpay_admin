<?php

namespace App\Http\Controllers\Website\Distributor;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PayoutTransaction;
use App\Models\Wallet;
use App\Models\WalletHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    public function index()
    {
        return view('Website.Distributor.Wallet.index');
    }

    public function myPayout()
    {
        $currentUser = Auth::user();
        
        // Get payout transactions from wallet history
        $payouts = PayoutTransaction::where('uid', $currentUser->user_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('Website.Distributor.Wallet.my_payout', compact('payouts'));
    }

    public function transactionHistory()
    {
        $currentUser = Auth::user();
        
        $transactions = WalletHistory::where('user_id', $currentUser->user_id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('Website.Distributor.Wallet.transaction_history', compact('transactions'));
    }

    public function walletBalance()
    {
        $currentUser = Auth::user();
        
        $wallet = Wallet::where('userid', $currentUser->user_id)->first();
        $recentTransactions = WalletHistory::where('user_id', $currentUser->user_id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('Website.Distributor.Wallet.wallet_balance', compact('wallet', 'recentTransactions'));
    }

    public function myEarning()
    {
        $currentUser = Auth::user();
        
        $earnings = WalletHistory::where('user_id', $currentUser->user_id)
            ->where('earning', '>', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('Website.Distributor.Wallet.my_earning', compact('earnings'));
    }

    public function redeemTransaction()
    {
        $currentUser = Auth::user();
        
        $redeemTransactions = WalletHistory::where('user_id', $currentUser->user_id)
            ->where('transition_type', 'redeem')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('Website.Distributor.Wallet.redeem_transaction', compact('redeemTransactions'));
    }
} 