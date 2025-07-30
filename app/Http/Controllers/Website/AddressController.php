<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_add_name' => 'required|string|max:255',
            'user_add_mobile' => 'required|string|max:15',
            'alternate_mob' => 'nullable|string|max:15',
            'user_add_1' => 'required|string',
            'user_add_2' => 'nullable|string',
            'user_zip_code' => 'required|string|max:10',
            'land_mark' => 'nullable|string|max:255',
            'user_state' => 'required|string|max:100',
            'user_city' => 'required|string|max:100',
            'user_country' => 'nullable|string|max:100',
            'address_for' => 'nullable|string|max:50',
            'address_type' => 'required|in:Home,Office,Others',
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
            'user_add_name' => 'required|string|max:255',
            'user_add_mobile' => 'required|string|max:15',
            'alternate_mob' => 'nullable|string|max:15',
            'user_add_1' => 'required|string',
            'user_add_2' => 'nullable|string',
            'user_zip_code' => 'required|string|max:10',
            'land_mark' => 'nullable|string|max:255',
            'user_state' => 'required|string|max:100',
            'user_city' => 'required|string|max:100',
            'user_country' => 'nullable|string|max:100',
            'address_for' => 'nullable|string|max:50',
            'address_type' => 'required|in:Home,Office,Others',
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
