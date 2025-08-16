<?php
// use Log;
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Validation\UserAddressValidation;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;

class ShippingBillingController extends Controller
{
    public function index(Request $request)
    {
        try {
            $records = UserAddress::where('user_id', Auth::user()->user_id)->where('add_status', '1')->where('address_type', 'shipping')->get()->map(function ($record) {
                return $this->field($record);
            });

            if ($records->isEmpty())
                return $this->notFoundRes();

            return $this->recordsRes($records);
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }

    private function field($record)
    {
        return [
            'id' => $record->id,
            'user_name' => $record->user_add_name,
            'mobile' => $record->user_add_mobile,
            'alternate_mobile' => $record->alternate_mob,
            'address' => $record->user_add_1,
            'landmark' => $record->land_mark,
            'city' => $record->user_city,
            'state' => $record->user_state,
            'country' => $record->user_country,
            'zipcode' => $record->user_zip_code,
            'address_type' => $record->address_type,
            'address_for' => $record->address_for,
            'is_default' => $record->is_default ? 1 : 0,
            'created_at' => $record->created_at,
        ];
    }

    public function show($id)
    {
        try {
            $record = UserAddress::where('id', $id)->where('user_id', Auth::user()->user_id)->first();
            if (!$record)
                return $this->notFoundRes();

            return $this->recordRes($this->field($record));
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }

    public function store(UserAddressValidation $request)
    {
        try {
            // check if billing address is already exists then remove from record

            $address = UserAddress::where('user_id', Auth::user()->user_id)->get();
            foreach ($address as $item) {
                $updateAddress = UserAddress::where('id', $item->id)->first();
                $updateAddress->is_default = 0;
                $updateAddress->save();
            }

            $existBilling = UserAddress::where('user_id', Auth::user()->user_id)->where('address_type', 'billing')->count();
            if ($existBilling <= 0) {
                $billing = new UserAddress();
                $billing->user_id = Auth::user()->user_id;
                $billing->user_add_name = $request->name;
                $billing->user_add_mobile = $request->mobile;
                $billing->user_add_1 = $request->address;
                $billing->alternate_mob = $request->alternate_mobile;
                $billing->user_zip_code = $request->pincode;
                $billing->user_city = $request->city;
                $billing->user_state = $request->state;
                $billing->user_country = 'india';
                $billing->address_for = $request->add_type;
                $billing->land_mark = $request->landmark;
                $billing->add_status = '1';
                $billing->address_type = 'billing';
                $billing->is_default = $request->is_default ? '1' : '0';
                $billing->save();
            }

            $shipping = new UserAddress();
            $shipping->user_id = Auth::user()->user_id;
            $shipping->user_add_name = $request->name;
            $shipping->user_add_mobile = $request->mobile;
            $shipping->user_add_1 = $request->address;
            $shipping->alternate_mob = $request->alternate_mobile;
            $shipping->user_zip_code = $request->pincode;
            $shipping->user_city = $request->city;
            $shipping->user_state = $request->state;
            $shipping->address_for = $request->add_type;
            $shipping->user_country = 'india';
            $shipping->land_mark = $request->landmark;
            $shipping->add_status = '1';
            $shipping->address_type = 'shipping';
            $shipping->is_default = $request->is_default ? '1' : '0';
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
            $address = UserAddress::where('user_id', Auth::user()->user_id)->get();
            foreach ($address as $item) {
                $updateAddress = UserAddress::where('id', $item->id)->first();
                $updateAddress->is_default = 0;
                $updateAddress->save();
            }

            $shipping = UserAddress::where('id', $id)->first();
            $shipping->user_add_name = $request->name;
            $shipping->user_add_mobile = $request->mobile;
            $shipping->user_add_1 = $request->address;
            $shipping->alternate_mob = $request->alternate_mobile;
            $shipping->user_zip_code = $request->pincode;
            $shipping->user_city = $request->city;
            $shipping->user_state = $request->state;
            $shipping->address_for = $request->add_type;
            $shipping->land_mark = $request->landmark;
            $shipping->user_country = 'india';
            $shipping->is_default = $request->is_default ? '1' : '0';
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
            $res = UserAddress::where('id', $id)->where('user_id', Auth::user()->user_id)->first();
            $res->add_status = '0';
            if ($res->save())
                return $this->successRes('Address Removed Successfully.');

            return $this->failRes('Something Went Wrong.');
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }

    public function setDefaultAddress(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'is_default' => 'required|in:0,1',
                'address_id' => 'required|exists:user_address,id',
            ],[
                'is_default.required' => 'Is default is required.',
                'is_default.in' => 'Is default value should be 0/1.',
                'address_id.required' => 'Address id is required.',
                'address_id.exists' => 'Address id is not valid.',
            ]);
            if ($validator->fails()) {
                return $this->validationRes($validator->errors());
            }
            $address = UserAddress::where('user_id', Auth::user()->user_id)->get();

            foreach ($address as $item) {
                $updateAddress = UserAddress::where('id', $item->id)->first();
                $updateAddress->is_default = '0';
                $updateAddress->save();
            }

            $shipping = UserAddress::where('id', $request->address_id)->first();
            $shipping->is_default = $request->is_default ? '1' : '0';
            if ($shipping->save())
                return $this->successRes('Address set as default successfully');

            return $this->successRes('Address set as default successfully');
        } catch (Exception $e) {
            return $this->failRes($e->getMessage());
        }
    }

    // public function buy_now($uid, $pid, $qty)  //hold
    // {
    //     $q = User::where('user_id', $uid[1])->first();
    //     if ($q) {
    //         if ($q->isactive == '0') {
    //             $cart_type = 'ap_shopping';
    //             $rp = 'ap';
    //         } else if ($q->isactive == '1') {
    //             $cart_type = 'shopping';
    //             $rp = 'rp';
    //         }

    //         $proInfo = Products::where('product_id', $pid)->first();
    //         if ($qty < 1) {
    //             $qty = 1;
    //         } else {
    //             $qty = $qty;
    //         }
    //         $cart = Cart::where('pid', $pid)->where('uid', $uid)->where('status', 0)->where('cart_type', $cart_type)->first();
    //         if ($cart) {
    //             $qtys = $cart->qty + $qty;
    //             $rp = $cart->rp + $proInfo->rp;
    //             $totAmt = $cart->amt * $qtys;
    //             $disAmt = $totAmt * $proInfo->up / 100;
    //             $grossAmt = $totAmt - $disAmt;
    //             $gstAmt = $grossAmt * $proInfo->igst / 100;
    //             $netAmt = $grossAmt + $gstAmt;

    //             $qry->qty = $qtys;
    //             $qry->rp = $rp;
    //             $qry->totAmt = $totAmt;
    //             $qry->disAmt = $disAmt;
    //             $qry->grossAmt = $grossAmt;
    //             $qry->netAmt = $netAmt;
    //             $qry->gstAmt = $gstAmt;
    //             $qry = Cart::find($cart->id);
    //         } else {
    //             $qtys = $qty;
    //             $rp = $proInfo->rp;
    //             $totAmt = $proInfo->product_price * $qtys;
    //             $disAmt = $totAmt * $proInfo->up / 100;
    //             $grossAmt = $totAmt - $disAmt;
    //             $gstAmt = $grossAmt * $proInfo->igst / 100;
    //             $netAmt = $grossAmt + $gstAmt;

    //             $qry = new Cart();
    //             $qry->qty = $qtys;
    //             $qry->rp = $rp;
    //             $qry->totAmt = $totAmt;
    //             $qry->disAmt = $disAmt;
    //             $qry->grossAmt = $grossAmt;
    //             $qry->netAmt = $netAmt;
    //             $qry->gstAmt = $gstAmt;
    //             $qry->cart_type = $cart_type;
    //             $qry->status = 0;
    //             $qry->added_on = date('Y-m-d H:i:s');
    //             $qry->pid = $pid;
    //             $qry->amt = $proInfo->product_price;
    //             $qry->uid = $uid;
    //             $qry->up = $proInfo->up;
    //         }
    //         if ($qry->save()) {
    //             return self::get_cart_products($pid, $uid);
    //         } else {
    //             $arrayName = array('status' => false, 'code' => 400, 'msg' => 'FAIL', 'txt' => 'Product not added.....');
    //             return json_encode($arrayName);
    //         }
    //     } else {
    //         return response()->json([
    //             'status' => false, 'code' => 404, 'msg' => 'FAIL', 'txt' => 'Record Not Found.....'
    //         ]);
    //     }
    // }
}
