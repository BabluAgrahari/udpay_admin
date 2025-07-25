<?php

namespace App\Http\Requests;

use App\Traits\WebResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UnitRequest extends FormRequest
{
    use WebResponse;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $unitId = $this->route('unit');
        $uniqueRule = 'unique:uni_unit,unit';
        
        if ($unitId) {
            $uniqueRule .= ',' . $unitId . ',id';
        }

        return [
            'unit' => [
                'required',
                'string',
                'max:50',
                'min:1',
                $uniqueRule,
                'regex:/^[a-zA-Z0-9\s\-_]+$/'
            ],
            'status' => 'nullable|boolean'
        ];
    }

    public function messages()
    {
        return [
            'unit.required' => 'Unit name is required',
            'unit.string' => 'Unit name must be a string',
            'unit.max' => 'Unit name cannot exceed 50 characters',
            'unit.min' => 'Unit name must be at least 1 character',
            'unit.unique' => 'This unit name already exists',
            'unit.regex' => 'Unit name can only contain letters, numbers, spaces, hyphens, and underscores',
            'status.boolean' => 'Status must be true or false'
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('unit')) {
            $this->merge([
                'unit' => trim($this->unit)
            ]);
        }
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->validationMsg($validator->errors()));
    }
} 