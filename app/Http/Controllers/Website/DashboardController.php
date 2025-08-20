<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\UserAddress;
use App\Models\Order;
use App\Models\Wishlist;
use App\Models\Address;
use App\Models\ApOrder;

class DashboardController extends Controller
{
    public function myAccount(Request $request) {
        try {
            $data['user'] = User::where('id', Auth::user()->id)->first();
            return view('Website.Dashboard.my_account', $data);
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    }   

    public function saveProfile(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'gender' => 'required|string|max:255',
                'mobile' => 'required|string|max:255',
            ]);
            if ($validator->fails()) {  
                return $this->validationMsg($validator->errors()->first());
            }
          
           $checkMobile = User::where('mobile', $request->mobile)->where('id', '!=', Auth::user()->id)->first();
           if ($checkMobile) {
                return $this->failMsg('Mobile number already exists');
           }

           $checkEmail = User::where('email', $request->email)->where('id', '!=', Auth::user()->id)->first();
           if ($checkEmail) {
                return $this->failMsg('Email already exists');
           }
            $user = User::where('id', Auth::user()->id)->first();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->gender = $request->gender;
            $user->mobile = $request->mobile;
            $user->save();
           return $this->successMsg('Profile updated successfully', ['user' => $user]);
        } catch (\Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }


    public function orderHistory(Request $request) {
        try {
            if(Auth::user()->role == 'distributor' || Auth::user()->role == 'customer'){
                $data['orders'] = ApOrder::where('uid', Auth::user()->user_id)->get();
            }else{
                $data['orders'] = Order::where('uid', Auth::user()->user_id)->get();
            }
            return view('Website.Dashboard.order_history', $data);
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    }

    public function addressBook(Request $request) {
        try {
            $data['addresses'] = UserAddress::where('user_id', Auth::user()->id)->get();
            return view('Website.Dashboard.address_book', $data);
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    }

    public function wishlist(Request $request) {
        try {
            $data['wishlist'] = Wishlist::with('product','product.reviews')->where('user_id', Auth::user()->id)->get();
            return view('Website.Dashboard.wishlist', $data);
        } catch (\Exception $e) {
            abort(500, $e->getMessage());
        }
    }
}