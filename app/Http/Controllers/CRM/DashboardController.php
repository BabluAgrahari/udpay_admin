<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Jobs\RequestUpdateJob;
use App\Models\VirtualAccount;
use App\Models\Transaction;
use App\Models\Merchant;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;
use App\Models\User;
use App\Models\WalletHistory;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        try {
            // $totalOrders = Order::count();
            // $activeUsers = User::where('isactive', 1)->count();
            // $inactiveUsers = User::where('isactive', 0)->count();
            // $totalRecharges = WalletHistory::where('type', 'credit')->sum('amount');
            // $latestOrders = Order::orderBy('created', 'desc')->take(10)->get();

            return view('CRM.Dashboard', [
                'totalOrders' => $totalOrders??0,
                'activeUsers' => $activeUsers??0,
                'inactiveUsers' => $inactiveUsers??0,
                'totalRecharges' => $totalRecharges??0,
                'latestOrders' => $latestOrders??0,
            ]);
        } catch (\Exception $e) {
           abort(500, 'Internal Server Error', [
                'message' => $e->getMessage(),
            ]);
        }
    }
}
