<?php

namespace App\Http\Controllers\Website\Distributor;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use App\Models\RoyaltyIncome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EarningController extends Controller
{
    public function payoutGeneration(Request $request)
    {
        $query = Payout::where('parent_id', Auth::user()->user_num);

        if (!empty($request->start_date) && !empty($request->end_date)) {
            $start_date = date('Y-m-d 00:00:00', strtotime($request->start_date));
            $end_date = date('Y-m-d 23:59:59', strtotime($request->end_date));
            $query->whereBetween('created_at', [$start_date, $end_date]);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $data['payout_generation'] = $query->get();

        $data['total_payout'] = $query->where('status', 1)->sum('amount');

        return view('Website.Distributor.Wallet.Earning.payout_generation', $data);
    }

    public function royaltyPayout(Request $request)
    {
        $query = RoyaltyIncome::where('user_id', Auth::user()->user_num);

        if (!empty($request->start_date) && !empty($request->end_date)) {
            $start_date = date('Y-m-d 00:00:00', strtotime($request->start_date));
            $end_date = date('Y-m-d 23:59:59', strtotime($request->end_date));
            $query->whereBetween('for_date', [$start_date, $end_date]);
        } else {
            $query->orderBy('for_date', 'desc');
        }

        $data['royalty_payout'] = $query->get();

        $data['total_royalty_payout'] = $query->sum('amount');

        return view('Website.Distributor.Wallet.Earning.royalty_payout', $data);
    }

    public function payoutSlip(Request $request)
    {
        return view('Website.Distributor.Wallet.Earning.payout_slip');
    }
}
