<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();

        $data['addresses'] = UserAddress::limit(5)->get();
        // ->where('user_id', $user->id)
        $data['user'] = $user;

        $cartItems =  Cart::with('product')->get();

        $subtotal = $cartItems->sum(function ($item) {
            return $item->product ? $item->product->product_sale_price * $item->quantity : 0;
        });
        $total_mrp = $cartItems->sum(function ($item) {
            return $item->product && isset($item->product->mrp) ? $item->product->mrp * $item->quantity : 0;
        });
        $total_saving = $total_mrp - $subtotal;
        $total_items = $cartItems->sum('quantity');

        $data['subtotal'] = $subtotal;
        $data['total_mrp'] = $total_mrp;
        $data['total_saving'] = $total_saving;
        $data['total_items'] = $total_items;

        return view('Website.checkout', $data);
    }


    public function saveAddress(Request $request)
    {
        try {
            $user = Auth::user();
            $address = new UserAddress();
            $address->user_id = $user->id;
            $address->user_add_name = $request->user_add_name;
            $address->user_add_mobile = $request->user_add_mobile;
            $address->user_add_1 = $request->user_add_1;
            $address->user_city = $request->user_city;
            $address->user_state = $request->user_state;
            $address->user_zip_code = $request->user_zip_code;
            $address->address_type = $request->address_type;
            $address->save();
            return $this->successMsg('Address saved successfully',);
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    }
}
