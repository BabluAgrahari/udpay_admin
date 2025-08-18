<?php
namespace App\Http\Controllers\Website\Distributor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\LevelCount;
use App\Models\Product;
use App\Models\Royalty;
use App\Models\User;
use App\Models\UserKyc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index($type = 'dashboard')
    {
        $data['type'] = $type;

        if ($type == 'kyc') {
            $data['user'] = User::where('user_id', auth()->user()->user_id)->first();

            $data['kyc'] = UserKyc::where('userId', auth()->user()->user_id)->first();
        }
        if ($type == 'my-direct-referral') {
            $data['referrals'] = Customer::with('rank')->where('ref_id', auth()->user()->user_num)->get();
        }
        if ($type == 'team-generation') {
            $data['team_generation'] = DB::table('level_count')
                ->select(
                    'level_count.level as lvl',
                    DB::raw('count(level_count.child_id) as Tcnt'),
                    DB::raw('sum(level_count.is_active) as Tgreen'),
                    DB::raw('count(level_count.child_id) - sum(level_count.is_active) as Tred')
                )
                ->where('level_count.parent_id', auth()->user()->user_num)
                ->groupBy('level_count.level')
                ->limit(15)
                ->get();

               
        }
        if ($type == 'my-acheivements') {
            $data['achievement'] = Royalty::where('userId', auth()->user()->user_num)->first();
        }

        // Add level count statistics for dashboard
        if ($type == 'dashboard') {
        }

        return view('Website.Distributor.dashboard', $data);
    }


    public function userLeavelList(Request $request,$level){

        $data['records'] = DB::table('level_count')
            ->select('level_count.level as lvl', 
            'level_count.child_id as child', 
            'level_count.is_active', 
            'users_lvl.name', 
            'users_lvl.upgrade_date',
            'users_lvl.user_num',
            'users_lvl.user_id',
            'users_lvl.mobile',
            'users_lvl.email')
            ->join('users_lvl', 'level_count.child_id', '=', 'users_lvl.user_num')
            ->where('level_count.parent_id', auth()->user()->user_num)
            ->where('level_count.level', $level)
            ->orderBy('users_lvl.upgrade_date', 'desc')
            ->get();
        $data['level'] = $level;
        return view('Website.Distributor.user_level_list', $data);
    }
}
