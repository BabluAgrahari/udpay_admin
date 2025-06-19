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

class DashboardController extends Controller
{
    public function index(Request $request)
    {

        $data[] = [];
        return view('CRM.Dashboard', $data);
    }
}
