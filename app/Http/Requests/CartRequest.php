<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Product;

class CartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'product_id' => 'required|integer|exists:uni_products,id',
        ];

        // Add quantity validation for add and update operations
        if (in_array($this->route()->getActionMethod(), ['addToCart', 'updateQuantity'])) {
            $rules['quantity'] = 'required|integer|min:1|max:100';
        }

        return $rules;
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'product_id.required' => 'Product ID is required',
            'product_id.exists' => 'Product not found',
            'quantity.required' => 'Quantity is required',
            'quantity.integer' => 'Quantity must be a whole number',
            'quantity.min' => 'Quantity must be at least 1',
            'quantity.max' => 'Quantity cannot exceed 100'
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->has('product_id')) {
                $product = Product::find($this->product_id);
                
                if (!$product) {
                    $validator->errors()->add('product_id', 'Product not found');
                    return;
                }

                // Check if product is active
                if ($product->status != 1) {
                    $validator->errors()->add('product_id', 'Product is not available');
                    return;
                }

                // Check stock availability for add and update operations
                if (in_array($this->route()->getActionMethod(), ['addToCart', 'updateQuantity']) && $this->has('quantity')) {
                    if (isset($product->stock) && $product->stock < $this->quantity) {
                        $validator->errors()->add('quantity', 'Insufficient stock available. Only ' . $product->stock . ' items left');
                    }
                }
            }
        });
    }
} 