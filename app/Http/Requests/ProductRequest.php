<?php

namespace App\Http\Requests;

use App\Traits\WebResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
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
        $productId = $this->route('product');
        $uniqueSkuRule = 'unique:uni_products,sku_code';
        $uniqueSlugRule = 'unique:uni_products,slug_url';
        
        if ($productId) {
            $uniqueSkuRule .= ',' . $productId . ',id';
            $uniqueSlugRule .= ',' . $productId . ',id';
        }

        return [
            'is_combo' => 'nullable|boolean',
            'combo_id' => 'nullable|string',
            'product_name' => 'required|string|max:255',
            'product_category_id' => 'required|exists:uni_category,id',
            'brand_id' => 'required|exists:uni_brand,id',
            'product_price' => 'required|numeric|min:0',
            'product_sale_price' => 'required|numeric|min:0',
            'mrp' => 'required|numeric|min:0',
            'product_stock' => 'nullable|integer|min:0',
            'unit_id' => 'nullable|exists:uni_unit,id',
            'product_description' => 'nullable|string|max:5000',
            'product_short_description' => 'nullable|string|max:1000',
            'product_meta_title' => 'nullable|string|max:255',
            'product_meta_keywords' => 'nullable|string|max:500',
            'meta_description' => 'nullable|string|max:1000',
            'product_min_qty' => 'nullable|integer|min:1',
            'igst' => 'nullable|integer|min:0|max:100',
            'is_featured' => 'nullable|boolean',
            'on_slider' => 'nullable|boolean',
            'on_banner' => 'nullable|boolean',
            'up' => 'nullable|integer|min:0',
            'sv' => 'nullable|numeric|min:0',
            'offer' => 'nullable|boolean',
            'offer_date' => 'nullable|date|after:today',
            'hsn_code' => 'required|string|max:55',
            'sku_code' => 'required|string|max:55|' . $uniqueSkuRule,
            'status' => 'nullable|boolean',
            'mart_status' => 'nullable|boolean',
            'pro_type' => 'nullable|in:primary1,rp',
            'pro_section' => 'nullable|in:primary,deals',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            // Variant validation
            'variants' => 'nullable|array',
            'variants.*.sku' => 'nullable|string|max:100',
            'variants.*.stock' => 'nullable|integer|min:0',
            'variants.*.varient_name' => 'nullable|string|max:255',
            'variants.*.price' => 'nullable|numeric|min:0',
            'variants.*.status' => 'nullable|boolean',
        ];
    }

    public function messages()
    {
        return [
            'product_name.required' => 'Product name is required',
            'product_name.max' => 'Product name cannot exceed 255 characters',
            'product_category_id.required' => 'Category is required',
            'product_category_id.exists' => 'Selected category is invalid',
            'brand_id.required' => 'Brand is required',
            'brand_id.exists' => 'Selected brand is invalid',
            'product_price.required' => 'Product price is required',
            'product_price.numeric' => 'Product price must be a number',
            'product_price.min' => 'Product price cannot be negative',
            'product_sale_price.required' => 'Sale price is required',
            'product_sale_price.numeric' => 'Sale price must be a number',
            'product_sale_price.min' => 'Sale price cannot be negative',
            'mrp.required' => 'MRP is required',
            'mrp.numeric' => 'MRP must be a number',
            'mrp.min' => 'MRP cannot be negative',
            'product_stock.nullable' => 'Product stock is required',
            'product_stock.integer' => 'Product stock must be a whole number',
            'product_stock.min' => 'Product stock cannot be negative',
            'unit_id.nullable' => 'Unit is required',
            'unit_id.exists' => 'Selected unit is invalid',
            'product_description.max' => 'Product description cannot exceed 5000 characters',
            'product_short_description.max' => 'Short description cannot exceed 1000 characters',
            'product_meta_title.max' => 'Meta title cannot exceed 255 characters',
            'product_meta_keywords.max' => 'Meta keywords cannot exceed 500 characters',
            'meta_description.max' => 'Meta description cannot exceed 1000 characters',
            'product_min_qty.integer' => 'Minimum quantity must be a whole number',
            'product_min_qty.min' => 'Minimum quantity must be at least 1',
            'igst.integer' => 'IGST must be a whole number',
            'igst.min' => 'IGST cannot be negative',
            'igst.max' => 'IGST cannot exceed 100%',
            'up.integer' => 'UP must be a whole number',
            'up.min' => 'UP cannot be negative',
            'sv.numeric' => 'SV must be a number',
            'sv.min' => 'SV cannot be negative',
            'offer_date.date' => 'Offer date must be a valid date',
            'offer_date.after' => 'Offer date must be in the future',
            'hsn_code.required' => 'HSN code is required',
            'hsn_code.max' => 'HSN code cannot exceed 55 characters',
            'sku_code.required' => 'SKU code is required',
            'sku_code.max' => 'SKU code cannot exceed 55 characters',
            'sku_code.unique' => 'This SKU code already exists',
            'pro_type.in' => 'Product type must be either primary1 or rp',
            'pro_section.in' => 'Product section must be either primary or deals',
            'product_image.image' => 'Product image must be an image file',
            'product_image.mimes' => 'Product image must be a JPEG, PNG, JPG, GIF, or WebP file',
            'product_image.max' => 'Product image cannot exceed 2MB',
            'images.*.image' => 'Each image must be an image file',
            'images.*.mimes' => 'Each image must be a JPEG, PNG, JPG, GIF, or WebP file',
            'images.*.max' => 'Each image cannot exceed 2MB',
            // Variant validation messages
            'variants.array' => 'Variants must be an array',
            'variants.*.sku.max' => 'Variant SKU cannot exceed 100 characters',
            'variants.*.stock.integer' => 'Variant stock must be a whole number',
            'variants.*.stock.min' => 'Variant stock cannot be negative',
            'variants.*.varient_name.max' => 'Variant name cannot exceed 255 characters',
            'variants.*.price.numeric' => 'Variant price must be a number',
            'variants.*.price.min' => 'Variant price cannot be negative',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('product_name')) {
            $this->merge([
                'product_name' => trim($this->product_name)
            ]);
        }
        
        if ($this->has('sku_code')) {
            $this->merge([
                'sku_code' => trim($this->sku_code)
            ]);
        }
        
        if ($this->has('hsn_code')) {
            $this->merge([
                'hsn_code' => trim($this->hsn_code)
            ]);
        }
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->validationMsg($validator->errors()));
    }
}
