<?php

namespace App\Http\Requests;

use App\Traits\WebResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CategoryRequest extends FormRequest
{
    use WebResponse;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'img' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|boolean',
            'parent_id' => 'nullable|integer|min:0',
            'pro_section' => 'nullable|string|in:primary,deals',
            'meta_title' => 'nullable|string|max:255',
            'meta_keyword' => 'nullable|string|max:500',
            'meta_description' => 'nullable|string|max:1000'
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Category name is required',
            'name.string' => 'Category name must be a string',
            'name.max' => 'Category name must be less than 255 characters',
            
            'img.file' => 'Image must be a file',
            'img.mimes' => 'Image must be a file of type: jpeg, png, jpg, gif, svg',
            'img.max' => 'Image must not be greater than 2MB',
            
            'status.required' => 'Status is required',
            'status.boolean' => 'Status must be true or false',
            
            'parent_id.integer' => 'Parent ID must be an integer',
            'parent_id.min' => 'Parent ID must be 0 or greater',
            
            'pro_section.string' => 'Product section must be a string',
            'pro_section.in' => 'Product section must be either primary or deals',
            
            'meta_title.string' => 'Meta title must be a string',
            'meta_title.max' => 'Meta title must be less than 255 characters',
            
            'meta_keyword.string' => 'Meta keyword must be a string',
            'meta_keyword.max' => 'Meta keyword must be less than 500 characters',
            
            'meta_description.string' => 'Meta description must be a string',
            'meta_description.max' => 'Meta description must be less than 1000 characters'
        ];
    }

     protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->validationMsg($validator->errors()));

    }
}