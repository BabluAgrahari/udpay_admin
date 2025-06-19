<?php

namespace App\Http\Requests;

use App\Traits\WebResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductRequest extends FormRequest
{
    use WebResponse;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'category_id' => 'required|exists:categories,_id',
            'brand_id' => 'required|exists:brands,_id',
            'product_name' => 'required|string|max:255',
            'sku' => 'required|string|max:50',
            'hsn_code' => 'required|string|max:20',
            'mrp' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'cgst' => 'required|numeric|min:0|max:100',
            'up' => 'nullable|numeric|min:0',
            'sv' => 'nullable|numeric|min:0',
            'offer' => 'nullable|boolean',
            'offer_date' => 'nullable|date',
            'mart_status' => 'nullable|boolean',
            'product_type' => 'nullable|string|in:primary1,rp',
            'product_section' => 'nullable|string|in:primary,deals',
            'short_description' => 'required|string|max:1000',
            'description' => 'required|string|max:5000',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_keyword' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'status' => 'required|boolean'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->validationMsg($validator->errors()));

    }
} 