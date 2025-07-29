<?php

namespace App\Http\Controllers\Api;

use DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\WalletTransition;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        try {
            $wallet = Wallet::with(['Transaction'])->where('userid', Auth::user()->user_id ?? '')->first();
            if (empty($wallet))
                return $this->failRes('User Wallet not found.');

            $total = $wallet->amount + $wallet->earning + $wallet->unicash;
            $record = [
                'id'      => $wallet->id,
                'amount'  => (float)round($wallet->amount ?? 0,2),
                'earning' => (float)round($wallet->earning ?? 0,2),
                'sp'      => (float)round($wallet->sp ?? 0,2),
                'bp'      => (float)round($wallet->bp ?? 0,2),
                'unicash' => (float)round($wallet->unicash ?? 0,2),
                'total'   => (float)round($total ?? 0,2),
            ];
            if ($wallet->Transaction->isNotEmpty()) {
                $record['transaction'] = $wallet->Transaction->map(function ($recordVal) {
                    return [
                        'id'              => $recordVal->id ?? '',
                        'user_id'         => $recordVal->user_id ?? '',
                        'credit'          => (float)$recordVal->credit ?? 0,
                        'debit'           => (float)$recordVal->debit ?? 0,
                        'in_type'         => $recordVal->in_type ?? '',
                        'transition_type' => $recordVal->transition_type ?? '',
                        'ord_id'          => $recordVal->ord_id ?? '',
                        'description'     => $recordVal->description ?? '',
                        'crType'          => ($recordVal->credit == 0) ? 'd_type' : 'c_type',
                        'created_on'      => $recordVal->created_on ?? '',
                    ];
                });
            }
            return $this->recordRes($record);
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }


    
	
	public function allPassbook(Request $request)
    {
		//$types = ['gen_payout', 'tob_payout', 'offer_payout_ap', 'repurchase_payout', 'Offer_payout'];
            
        try {
			
            $query = WalletTransition::where('user_id', Auth::user()->user_id);

            if ($request->filter_type == 'credit') {
                $query->where('transition_type', 'add_money');
            } elseif ($request->filter_type == 'debit') {
                $query->where('debit', '>', 0);
            } elseif ($request->filter_type == 'earning') {
                $types = ['gen_payout', 'tob_payout', 'offer_payout_ap', 'repurchase_payout', 'Offer_payout'];
                $query->whereIn('transition_type', $types);
            }
            if ($request->fromDate && $request->toDate) {
                $fromDate = date('Y-m-d 00:00:01', strtotime($request->fromDate));
                $toDate = date('Y-m-d 23:59:59', strtotime($request->toDate));
                $query->whereBetween('created_on', [$fromDate, $toDate]);
            }

            //$perPage = $request->perPage ?? config('global.perPage');
            //$page    = $request->page ?? 1;
            $records = $query->orderBy('id', 'desc')->get()->map(function ($record) {

                return [
                    'id'               => $record->id,
                    'user_id'          => $record->user_id,
                    'amount'           => ($record->credit > 0) ? (float)$record->credit : (float)$record->debit,
                    'closing_amount'   => (float)$record->balance ?? 0,
                    'type'             => ($record->credit > 0) ? 'credit' : 'debit',
                    'transaction_type' => $record->transition_type ?? '',
                    'description'      => $record->in_type,
                    'filter_type'      => in_array($record->transition_type ?? '', ['gen_payout', 'tob_payout', 'offer_payout_ap', 'repurchase_payout', 'Offer_payout']) ? 'earning' : ($record->credit > 0 ? 'credit' : 'debit'),
                    'date'             => $record->created_on,
                ];
            });

            if ($records->isEmpty())
                return $this->notFoundRes();

            return $this->recordsRes($records);
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }
	
	public function passbookAmount(Request $request, $type)
    {
        try {

            $query = WalletTransition::where('user_id', Auth::user()->user_id);

            if ($type == 'debit') {
                $query->where('debit', '>', 0);
            } else if ($type == 'credit') {
                $query->where('transition_type', 'add_money');
            } elseif ($type == 'both') {
                $query->where(function ($query) {
                    $query->where('transition_type', 'add_money')
                        ->orWhere('debit', '>', 0);
                });
            }

            if ($request->fromDate && $request->toDate) {
                $fromDate = date('Y-m-d 00:00:01', strtotime($request->fromDate));
                $toDate = date('Y-m-d 11:59:59', strtotime($request->toDate));
                $query->whereBetween('created_on', [$fromDate, $toDate]);
            }

            $perPage = $request->perPage ?? config('global.perPage');
            $page    = $request->page ?? 1;
            $records = $query->limit($perPage)->offset($page)->orderBy('id', 'desc')->get()->map(function ($record) {

                return [
                    'id'               => $record->id,
                    'user_id'          => $record->user_id,
                    'amount'           => ($record->credit > 0) ? (float)$record->credit : (float)$record->debit,
                    'closing_amount'   => (float)$record->balance ?? 0,
                    'type'             => ($record->credit > 0) ? 'credit' : 'debit',
                    'transaction_type' => $record->transition_type ?? '',
                    'description'      => $record->in_type,
                    'date'             => $record->created_on,
                ];
            });

            if ($records->isEmpty())
                return $this->notFoundRes();

            return $this->recordsRes($records);
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }

    function earningPassbook(Request $request, $type)
    {
        $types = ['gen_payout', 'tob_payout', 'offer_payout_ap', 'repurchase_payout', 'Offer_payout'];

        try {
			
            $query = WalletTransition::where('user_id', Auth::user()->user_id);
            if ($type == 'debit') {
                $query->where('debit', '>', 0);
            } elseif ($type == 'credit') {
                $query->whereIn('transition_type', $types);
            } elseif ($type == 'both') {
                $query->where(function ($query) use($types) {
                    $query->whereIn('transition_type', $types)
                        ->orWhere('debit', '>', 0);
                });
            }


            if ($request->fromDate && $request->toDate) {
                $fromDate = date('Y-m-d 00:00:01', strtotime($request->fromDate));
                $toDate = date('Y-m-d 11:59:59', strtotime($request->toDate));
                $query->whereBetween('created_on', [$fromDate, $toDate]);
            }

            $perPage = $request->perPage ?? config('global.perPage');
            $page    = $request->page ?? 1;
            $records = $query->limit($perPage)->offset($page)->orderBy('id', 'desc')->get()->map(function ($record) {

                return [
                    'id'               => $record->id,
                    'user_id'          => $record->user_id,
                    'amount'           => ($record->credit > 0) ? (float)$record->credit : (float)$record->debit,
                    'closing_amount'   => (float)$record->balance ?? 0,
                    'type'             => ($record->credit > 0) ? 'credit' : 'debit',
                    'transaction_type' => $record->transition_type ?? '',
                    'description'      => $record->in_type,
                    'date'             => $record->created_on,
                ];
            });

            if ($records->isEmpty())
                return $this->notFoundRes();

            return $this->recordsRes($records);
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }

    function unicashPassbook(Request $request, $type)
    {
        $types = ['ins_cashback', 'kico_cashback', 'ubazar_cashback', 'recharge_cashback', 'inr_cashback', 'bus_cashback'];

        try {
           
            $query = WalletTransition::where('user_id', Auth::user()->user_id);
            if (!empty($request->fromDate) && !empty($request->toDate)) {
                $fromDate = date('Y-m-d 00:00:01', strtotime($request->fromDate));
                $toDate = date('Y-m-d 11:59:59', strtotime($request->toDate));
                $query->whereBetween('created_on', [$fromDate, $toDate]);
            }

            if ($type == 'debit') {
                $query->where('debit', '>', 0);
            } elseif ($type == 'credit') {
                $query->whereIn('transition_type', $types);
            } elseif ($type == 'both') {
                $query->where(function ($query) use($types) {
                    $query->whereIn('transition_type', $types)
                        ->orWhere('debit', '>', 0);
                });
            }

            $perPage = $request->perPage ?? config('global.perPage');
            $page    = $request->page ?? 1;
            $records = $query->limit($perPage)->offset($page)->orderBy('id', 'desc')->get()->map(function ($record) {

                return [
                    'id'               => $record->id,
                    'user_id'          => $record->user_id,
                    'amount'           => ($record->credit > 0) ? (float)$record->credit : (float)$record->debit,
                    'closing_amount'   => (float)$record->balance ?? 0,
                    'type'             => ($record->credit > 0) ? 'credit' : 'debit',
                    'transaction_type' => $record->transition_type ?? '',
                    'description'      => $record->in_type,
                    'date'             => $record->created_on,
                ];
            });

            //$records=DB::getQueryLog();
            //print_r($records);
            if ($records->isEmpty())
                return $this->notFoundRes();

            return $this->recordsRes($records);
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }




    // public function getBothRes($request, $transsactionFor)
    // {
    //     $uid = $request->userId;
    //     $types = ['gen_payout', 'tob_payout', 'offer_payout_ap', 'repurchase_payout', 'Offer_payout'];
    //     $types = implode(',', $types);
    //     //$query->whereIn('transition_type', $types);
    //     //$query->where('debit', '>', 0);
    //     if (!empty($request->from_date) && !empty($request->to_date)) {
    //         $fromDate = date('Y-m-d 00:00:01', strtotime($request->from_date));
    //         $toDate = date('Y-m-d 11:59:59', strtotime($request->to_date));
    //         // if ($transsactionFor == 'e')
    //         //     $query = DB::select("
    //         //     SELECT *
    //         //     FROM `wallet_transition`
    //         //     WHERE `user_id` = $uid
    //         //     AND (`transition_type` IN ($types) OR `debit` > 0) and created_on between $fromDate and $toDate ORDER BY `created_on` DESC ");
    //         // if ($transsactionFor == 'u')
    //         //     $query = DB::select("
    //         //     SELECT *
    //         //     FROM `wallet_transition`
    //         //     WHERE `user_id` = $uid
    //         //     AND (`transition_type` IN ('ins_cashback','kico_cashback','ubazar_cashback','recharge_cashback','inr_cashback','bus_cashback') OR `debit` > 0) and created_on between $fromDate and $toDate ORDER BY `created_on` DESC ");

    //         // if ($transsactionFor == 'a')
    //         //     $query = DB::select("
    //         //     SELECT *
    //         //     FROM `wallet_transition`
    //         //     WHERE `user_id` = $uid
    //         //     AND (`transition_type` = 'add_money' OR `debit` > 0) and created_on between $fromDate and $toDate ORDER BY `created_on` DESC ");
    //     } else {
    //         // if ($transsactionFor == 'e')
    //         //     $query = DB::select("
    //         //     SELECT * FROM `wallet_transition` WHERE `user_id` = $uid 
    //         //     AND (`transition_type` IN ('gen_payout','tob_payout','offer_payout_ap','repurchase_payout','Offer_payout') OR `debit` > 0)  ORDER BY `created_on` DESC ");

    //         // if ($transsactionFor == 'u')
    //         //     $query = DB::select("
    //         //         SELECT *
    //         //         FROM `wallet_transition`
    //         //         WHERE `user_id` = $uid
    //         //         AND (`transition_type` IN ('ins_cashback','kico_cashback','ubazar_cashback','recharge_cashback','inr_cashback','bus_cashback') OR `debit` > 0)  ORDER BY `created_on` DESC ");

    //         // if ($transsactionFor == 'a')
    //         //     $query = DB::select("
    //         //         SELECT *
    //         //         FROM `wallet_transition`
    //         //         WHERE `user_id` = $uid
    //         //         AND (`transition_type` = 'add_money' OR `debit` > 0)
    //         //     ORDER BY `created_on` DESC ");
    //     }

    //     //$perPage = $request->perPage ?? config('global.perPage');
    //     //$page    = $request->page ?? 1;
    //     //$records = $query->limit($perPage)->skip($page)->orderBy('id', 'desc')->get()->map(function ($record) {
    //     foreach ($query as $record) {
    //         //$arr[] = array();
    //         $records[] = [
    //             'id'               => $record->id,
    //             'user_id'          => $record->user_id,
    //             'amount'           => ($record->credit > 0) ? (float)$record->credit : (float)$record->debit,
    //             'closing_amount'   => (float)$record->balance ?? 0,
    //             'type'             => ($record->credit > 0) ? 'credit' : 'debit',
    //             'transaction_type' => $record->transition_type ?? '',
    //             'description'      => $record->in_type,
    //             'date'             => $record->created_on,
    //         ];
    //     };
    //     print_r($records);
    //     die;

    //     if ($records->isEmpty())
    //         return $this->notFoundRes();

    //     return $this->recordsRes($records);
    // }
}
