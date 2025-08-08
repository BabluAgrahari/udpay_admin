<?php

namespace App\Http\Validation;

use App\Traits\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UserAddressValidation extends FormRequest
{
    use Response;
    public function authorize()
    {
        return true;
    }

    public function rules(Request $request)
    {
        $rules = [
            'mobile'         => 'required|numeric|digits:10|not_in:0',
            'name'           => 'required|string|min:2|max:200',
            'pincode'        => 'required|numeric|digits:6|not_in:0',
            'city'           => 'required|string|min:2|max:100',
            'landmark'       => 'nullable|string|min:2|max:100',
            'state'          => 'required|string|min:2|max:100',
            'address'        => 'required|string|min:2|max:1000',
            'alternate_mobile'  => 'nullable|numeric|digits:10|not_in:0',
            'add_type'       => 'required|in:home,Work',
        ];
        return $rules;
    }

    public function messages()
    {
        return [
            'userId.required'           => 'User id is required.',
            'userId.numeric'            => 'Please enter numeric value for User id.',
            'mobile.required'           => 'Mobile is required',
            'mobile.numeric'            => 'Please enter numeric value for mobile.',
            'mobile.digits'             => 'Please enter 10 digits for mobile.',
            'mobile.not_in'             => 'Please enter Valid digits for mobile.',
            'name.required'             => 'Name is required',
            'pincode.required'          => 'pincode is required.',
            'pincode.numeric'           => 'Please Enter numeric value for pincode.',
            'city.required'             => 'City is required.',
            'state.required'            => 'State is required.',
            'locality.required'         => 'locality is required.',
            'landmark.required'         => 'landmark is required.',
            'address.required'          => 'Address is required.',
            'alternate_mob.numeric'     => 'Please enter numeric value for mobile.',
            'alternate_mob.digits'      => 'Please enter 10 digits for mobile.',
            'same.required'             => 'Same is required.',
            'add_type.required'         => 'Address Type is Required.',
            'add_type.in'               => 'Address Type value should be home/office.',
            'operate_type.required'     => 'Operate type is Required.',
            'add_id.numeric'            => "Address id Should be in Numeric Value"
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        // throw new HttpResponseException();
        throw new HttpResponseException($this->validationRes($validator->errors()));
    }
}
