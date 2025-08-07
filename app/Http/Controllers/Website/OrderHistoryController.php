<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderToProduct;
use App\Models\UserAddress;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Services\PaymentGatway\CashFree;

class OrderHistoryController extends Controller
{

    public function index(Request $request)
    {
        try {
            $data['orders'] = Order::with([ 'orderToProducts', 'orderToProducts.product', 'orderToProducts.variant']   )->where('uid', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(10);
            return view('Website.order_history', $data);
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    }

    public function orderDetail($id)
    {
        // try {
            $data['order'] = Order::with([  'orderToProducts', 'orderToProducts.product', 'orderToProducts.variant'])->where('id', $id)->first();
            return view('Website.order_detail', $data);
        // } catch (\Exception $e) {
        //     abort(500, $e->getMessage());
        // }
    }


}