<?php

namespace App\Http\Requests;

use App\Traits\WebResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CouponRequest extends FormRequest
{
    use WebResponse;
    
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'code' => 'required|string|max:50|unique:uni_coupon,code,' . ($this->coupon ?? ''),
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'minimum_amount' => 'nullable|numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_to' => 'nullable|date|after_or_equal:valid_from',
            'status' => 'required|boolean'
        ];

        // Additional validation for percentage discount
        if ($this->discount_type === 'percentage') {
            $rules['discount_value'] = 'required|numeric|min:0|max:100';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'code.unique' => 'This coupon code already exists.',
            'discount_value.max' => 'Percentage discount cannot exceed 100%.',
            'valid_to.after_or_equal' => 'End date must be after or equal to start date.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->validationMsg($validator->errors()));
    }
} 