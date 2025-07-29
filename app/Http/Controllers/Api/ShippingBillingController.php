<?php
//use Log;
namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Validator;
use App\Http\Validation\UserAddressValidation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Product\Products;
use App\Models\Product\Cart;
use App\Models\Product\UserAddress;
use DB;
use Exception;

class ShippingBillingController extends Controller
{
    public function index(Request $request)
    {
        try {
            $records = UserAddress::where('user_id', Auth::user()->user_id)->where('add_status', '1')->where('address_type', 'shipping')->get()->map(function ($record) {
                return [
                    'add_id'      => $record->add_id,
                    'address'     => $record->user_add_1,
                    'landmark'    => $record->landmark,
                    'city'        => $record->user_city,
                    'state'       => $record->user_state,
                    'zipcode'     => $record->user_zip_code,
                    'user_name'   => $record->user_add_name,
                    'mobile'      => $record->user_add_mobile,
                    'address_type'    => $record->address_type,
                    'address_for' => $record->address_for
                ];
            });

            if ($records->isEmpty())
                return $this->notFoundRes();

            return $this->recordsRes($records);
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }

    public function store(UserAddressValidation $request)
    {
        try {
            $existBilling = UserAddress::where('user_id', Auth::user()->user_id)->where('address_type', 'billing')->count();
            if ($existBilling <= 0) {
                $billing = new UserAddress();
                $billing->user_id         = Auth::user()->user_id;
                $billing->user_add_name   = $request->name;
                $billing->user_add_mobile = $request->mobile;
                $billing->user_add_1      = $request->address;
                $billing->alternate_mob   = $request->alternate_mob;
                $billing->user_zip_code   = $request->pincode;
                $billing->user_city       = $request->city;
                $billing->user_state      = $request->state;
                $billing->user_country    = 'india';
                $billing->address_for     = $request->add_type;
                $billing->user_add_2        = $request->landmark;
                $billing->add_from        = 1;
                $billing->add_status      = 1;
                $billing->address_type    = 'billing';
                $billing->save();
            }

            $shipping = new UserAddress();
            $shipping->user_id         =  Auth::user()->user_id;
            $shipping->user_add_name   = $request->name;
            $shipping->user_add_mobile = $request->mobile;
            $shipping->user_add_1      = $request->address;
            $shipping->alternate_mob   = $request->alternate_mob;
            $shipping->user_zip_code   = $request->pincode;
            $shipping->user_city       = $request->city;
            $shipping->user_state      = $request->state;
            $shipping->address_for     = $request->add_type;
            $shipping->user_country   = 'india';
            $shipping->user_add_2      = $request->landmark;
            $shipping->add_from        = 1;
            $shipping->add_status      = 1;
            $shipping->address_type    = 'shipping';
            if ($shipping->save())
                return $this->successRes('Address Added Successfully');

            return $this->failRes('Something Went Wrong, Address not added!');
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }



    public function update(UserAddressValidation $request, $id)
    {
        try {
            $shipping = UserAddress::where('add_id', $id)->first();
            $shipping->user_add_name   = $request->name;
            $shipping->user_add_mobile = $request->mobile;
            $shipping->user_add_1      = $request->address;
            $shipping->alternate_mob   = $request->alternate_mob;
            $shipping->user_zip_code   = $request->pincode;
            $shipping->user_city       = $request->city;
            $shipping->user_state      = $request->state;
            $shipping->address_for     = $request->add_type;
            $shipping->user_add_2      = $request->landmark;
            if ($shipping->save())
                return $this->successRes('Address Updated Successfully');

            return $this->failRes('Something Went Wrong, Address not Updated!');
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }


    public function remove($id)
    {
        try {
            $res = UserAddress::where('add_id', $id)->where('user_id', Auth::user()->user_id)->forceDelete();
            if ($res)
                return $this->successRes('Address Removed Successfully.');

            return $this->failRes('Something Went Wrong.');
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }





    public function buy_now($uid, $pid, $qty)  //hold
    {
        $q = User::where('user_id', $uid[1])->first();
        if ($q) {
            if ($q->isactive == '0') {
                $cart_type = 'ap_shopping';
                $rp = 'ap';
            } else if ($q->isactive == '1') {
                $cart_type = 'shopping';
                $rp = 'rp';
            }

            $proInfo = Products::where('product_id', $pid)->first();
            if ($qty < 1) {
                $qty = 1;
            } else {
                $qty = $qty;
            }
            $cart = Cart::where('pid', $pid)->where('uid', $uid)->where('status', 0)->where('cart_type', $cart_type)->first();
            if ($cart) {
                $qtys = $cart->qty + $qty;
                $rp = $cart->rp + $proInfo->rp;
                $totAmt = $cart->amt * $qtys;
                $disAmt = $totAmt * $proInfo->up / 100;
                $grossAmt = $totAmt - $disAmt;
                $gstAmt = $grossAmt * $proInfo->igst / 100;
                $netAmt = $grossAmt + $gstAmt;

                $qry->qty = $qtys;
                $qry->rp = $rp;
                $qry->totAmt = $totAmt;
                $qry->disAmt = $disAmt;
                $qry->grossAmt = $grossAmt;
                $qry->netAmt = $netAmt;
                $qry->gstAmt = $gstAmt;
                $qry = Cart::find($cart->id);
            } else {
                $qtys = $qty;
                $rp = $proInfo->rp;
                $totAmt = $proInfo->product_price * $qtys;
                $disAmt = $totAmt * $proInfo->up / 100;
                $grossAmt = $totAmt - $disAmt;
                $gstAmt = $grossAmt * $proInfo->igst / 100;
                $netAmt = $grossAmt + $gstAmt;

                $qry = new Cart();
                $qry->qty = $qtys;
                $qry->rp = $rp;
                $qry->totAmt = $totAmt;
                $qry->disAmt = $disAmt;
                $qry->grossAmt = $grossAmt;
                $qry->netAmt = $netAmt;
                $qry->gstAmt = $gstAmt;
                $qry->cart_type = $cart_type;
                $qry->status = 0;
                $qry->added_on = date('Y-m-d H:i:s');
                $qry->pid = $pid;
                $qry->amt = $proInfo->product_price;
                $qry->uid = $uid;
                $qry->up = $proInfo->up;
            }
            if ($qry->save()) {
                return self::get_cart_products($pid, $uid);
            } else {
                $arrayName = array('status' => false, 'code' => 400, 'msg' => 'FAIL', 'txt' => 'Product not added.....');
                return json_encode($arrayName);
            }
        } else {
            return response()->json([
                'status' => false, 'code' => 404, 'msg' => 'FAIL', 'txt' => 'Record Not Found.....'
            ]);
        }
    }
}
