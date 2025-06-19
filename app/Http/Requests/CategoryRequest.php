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
            'description' => 'nullable|string',
            'icon' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|boolean',
            'parent_id' => 'nullable|exists:categories,_id',
            'labels*' => 'nullable|string|max:50'
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Category name is required',
            'name.string' => 'Category name must be a string',
            'name.max' => 'Category name must be less than 255 characters',
            
            'description.string' => 'Description must be a string',
            
            'icon.file' => 'Icon must be a file',
            'icon.mimes' => 'Icon must be a file of type: jpeg, png, jpg, gif, svg',
            'icon.max' => 'Icon must not be greater than 2MB',
            
            'status.required' => 'Status is required',
            'status.boolean' => 'Status must be true or false',
            
            'parent_id.exists' => 'Selected parent category does not exist',
            
            'short.required' => 'Short code is required',
            'short.string' => 'Short code must be a string',
            'short.max' => 'Short code must be less than 50 characters',
            'short.unique' => 'This short code is already taken',
            
            'labels.array' => 'Labels must be an array',
            'labels.*.string' => 'Each label must be a string',
            'labels.*.max' => 'Each label must be less than 50 characters'
        ];
    }

     protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->validationMsg($validator->errors()));

    }
}