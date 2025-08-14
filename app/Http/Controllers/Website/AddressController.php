<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_add_name' => 'required|string|min:2|max:255',
            'user_add_mobile' => 'required|numeric|digits:10|not_in:0|regex:/^[6-9]\d{9}$/',
            'alternate_mob' => 'nullable|numeric|digits:10|not_in:0|regex:/^[6-9]\d{9}$/',
            'user_add_1' => 'required|string|min:10|max:500',
            'user_add_2' => 'nullable|string',
            'user_zip_code' => 'required|numeric|digits:6|not_in:0',
            'land_mark' => 'nullable|string|max:255',
            'user_state' => 'required|string|max:100',
            'user_city' => 'required|string|max:100',
            'user_country' => 'nullable|string|max:100',
            'address_for' => 'nullable|string',
            'address_type' => 'required|in:Home,Office,Others',
        ], [
            'user_add_mobile.regex' => 'Please enter a valid mobile number starting with 6, 7, 8, or 9.',
            'user_add_mobile.not_in' => 'Please enter a valid mobile number starting with 6, 7, 8, or 9.',
            'alternate_mob.regex' => 'Please enter a valid mobile number starting with 6, 7, 8, or 9.',
            'alternate_mob.not_in' => 'Please enter a valid mobile number starting with 6, 7, 8, or 9.',
        ]);

        if ($validator->fails()) {
            return $this->validationMsg($validator->errors());
        }

        try {
            $user = Auth::user();

            $address = new UserAddress();
            $address->user_id = $user->id;
            $address->user_add_name = $request->user_add_name;
            $address->user_add_mobile = $request->user_add_mobile;
            $address->alternate_mob = $request->alternate_mob;
            $address->user_add_1 = $request->user_add_1;
            $address->user_add_2 = $request->user_add_2;
            $address->user_zip_code = $request->user_zip_code;
            $address->land_mark = $request->land_mark;
            $address->user_state = $request->user_state;
            $address->user_city = $request->user_city;
            $address->user_country = $request->user_country ?? 'India';
            $address->address_for = $request->address_type;
            $address->address_type = 'shipping';
            $address->add_status = 1;
            if ($address->save())
                return $this->successMsg('Address saved successfully');

            return $this->failMsg('Failed to save address');
        } catch (\Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_add_name' => 'required|string|min:2|max:255',
            'user_add_mobile' => 'required|numeric|digits:10|not_in:0|regex:/^[6-9]\d{9}$/',
            'alternate_mob' => 'nullable|numeric|digits:10|not_in:0|regex:/^[6-9]\d{9}$/',
            'user_add_1' => 'required|string|min:10|max:500',
            'user_add_2' => 'nullable|string',
            'user_zip_code' => 'required|numeric|digits:6|not_in:0',
            'land_mark' => 'nullable|string|max:255',
            'user_state' => 'required|string|max:100',
            'user_city' => 'required|string|max:100',
            'user_country' => 'nullable|string|max:100',
            'address_for' => 'nullable|string',
            'address_type' => 'required|in:Home,Office,Others',
        ], [
            'user_add_mobile.regex' => 'Please enter a valid mobile number starting with 6, 7, 8, or 9.',
            'alternate_mob.regex' => 'Please enter a valid mobile number starting with 6, 7, 8, or 9.',
            'alternate_mob.not_in' => 'Please enter a valid mobile number starting with 6, 7, 8, or 9.',
        ]);

        if ($validator->fails()) {
            return $this->validationMsg($validator->errors());
        }

        try {
            $user = Auth::user();
            $address = UserAddress::where('id', $id)
                ->where('user_id', $user->id)
                ->where('add_status', 1)
                ->first();

            if (!$address) {
                return $this->failMsg('Address not found');
            }

            $address->user_add_name = $request->user_add_name;
            $address->user_add_mobile = $request->user_add_mobile;
            $address->alternate_mob = $request->alternate_mob;
            $address->user_add_1 = $request->user_add_1;
            $address->user_add_2 = $request->user_add_2;
            $address->user_zip_code = $request->user_zip_code;
            $address->land_mark = $request->land_mark;
            $address->user_state = $request->user_state;
            $address->user_city = $request->user_city;
            $address->user_country = $request->user_country ?? 'India';
            $address->address_for = $request->address_type;
            $address->address_type = 'shipping';
            $address->save();

            return $this->successMsg('Address updated successfully');
        } catch (\Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function remove($id)
    {
        try {
            $user = Auth::user();
            $address = UserAddress::where('id', $id)
                ->where('user_id', $user->id)
                ->where('add_status', 1)
                ->first();
            if (!$address) {
                return $this->failMsg('Address not found');
            }

            $address->add_status = '0';
            if ($address->save())
                return $this->successMsg('Address removed successfully');

            return $this->failMsg('Failed to remove address');
        } catch (\Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function setDefault($id)
    {
        try {
            $user = Auth::user();

            $address = UserAddress::where('id', $id)
                ->where('user_id', $user->id)
                ->where('add_status', 1)
                ->first();
            if (!$address) {
                return $this->failMsg('Address not found');
            }

            $address->is_default = 1;
            $address->save();
            return $this->successMsg('Default address set successfully');
        } catch (\Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }
}
