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

        $data['payout_generation'] = $query->paginate(20);

        $data['total_payout'] =  Payout::where('parent_id', Auth::user()->user_num)->where('status', '1')->sum('amount');

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

        $data['royalty_payout'] = $query->paginate(20);

        $data['total_royalty_payout'] = RoyaltyIncome::where('user_id', Auth::user()->user_num)->sum('amount');

        return view('Website.Distributor.Wallet.Earning.royalty_payout', $data);
    }

    public function payoutSlip(Request $request)
    {
        $array = [];
        // $array = [];
        $monthCount = date('m');
        for ($i = 5; $i <= $monthCount; $i++) {
            $query = RoyaltyIncome::where('user_id', Auth::user()->user_num);
            $month = date('Y-m-d', strtotime(date('Y-' . $i . '-01')));
            $start_date = date('Y-m-01 00:00:00', strtotime($month));
            $end_date = date('Y-m-t 23:59:59', strtotime($month));

            $array['royalty'][$month] = $query->whereBetween('for_date', [$start_date, $end_date])->sum('amount');


            $payoutquery = Payout::where('parent_id', Auth::user()->user_num);
            $payoutquery->whereBetween('created_at', [$start_date, $end_date]);
            $array['payout'][$month] = $payoutquery->where('status', '1')->sum('amount');
        }

        $data = [];

       
        for ($i = 5; $i <= $monthCount; $i++) {
            $month = date('Y-m-d', strtotime(date('Y-' . $i . '-01')));
            $royalty = !empty($array['royalty'][$month]) ? $array['royalty'][$month] : 0;
            $payout = !empty($array['payout'][$month]) ? $array['payout'][$month] : 0;
            $total = $royalty + $payout;
            $data[$month] = $total;
        }
        $data['payout_slip'] = $data;
        return view('Website.Distributor.Wallet.Earning.payout_slip', $data);
    }
}
