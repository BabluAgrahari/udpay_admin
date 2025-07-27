<?php

namespace App\Http\Requests;

use App\Traits\WebResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PickupAddressRequest extends FormRequest
{
    use WebResponse;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $pickupAddressId = $this->route('pickup_address');
        // $uniqueRule = 'unique:pickup_addresses,email';
        
        // if ($pickupAddressId) {
        //     $uniqueRule .= ',' . $pickupAddressId . ',_id';
        // }

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|integer|digits:10',
            'type' => 'required|in:pickup_address,rto_address,return_address',
            'location' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|integer|digits:6',
            'status' => 'nullable|boolean'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'name.string' => 'Name must be a string',
            'name.max' => 'Name cannot exceed 255 characters',
            'email.required' => 'Email is required',
            'email.email' => 'Email must be a valid email address',
            'email.max' => 'Email cannot exceed 255 characters',
            // 'email.unique' => 'This email already exists',
            'phone.required' => 'Phone number is required',
            'phone.string' => 'Phone number must be a string',
            'phone.max' => 'Phone number cannot exceed 20 characters',
            'type.required' => 'Address type is required',
            'type.in' => 'Address type must be pickup address, RTO address, or return address',
            'location.required' => 'Location is required',
            'location.string' => 'Location must be a string',
            'location.max' => 'Location cannot exceed 255 characters',
            'address.required' => 'Address is required',
            'address.string' => 'Address must be a string',
            'address.max' => 'Address cannot exceed 500 characters',
            'city.required' => 'City is required',
            'city.string' => 'City must be a string',
            'city.max' => 'City cannot exceed 100 characters',
            'state.required' => 'State is required',
            'state.string' => 'State must be a string',
            'state.max' => 'State cannot exceed 100 characters',
            'pincode.required' => 'Pincode is required',
            'pincode.integer' => 'Pincode must be a number',
            'pincode.digits' => 'Pincode must be exactly 6 digits',
            'status.nullable' => 'Status is required',
            'status.boolean' => 'Status must be true or false'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->validationMsg($validator->errors()));
    }
} 