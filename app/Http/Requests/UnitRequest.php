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
        $uniqueRule = 'unique:units,unit';
        
        if ($unitId) {
            $uniqueRule .= ',' . $unitId . ',_id';
        }

        return [
            'unit' => 'required|string|max:50|' . $uniqueRule,
            'status' => 'nullable|boolean'
        ];
    }

    public function messages()
    {
        return [
            'unit.required' => 'Unit name is required',
            'unit.string' => 'Unit name must be a string',
            'unit.max' => 'Unit name cannot exceed 50 characters',
            'unit.unique' => 'This unit name already exists',
            'status.nullable' => 'Status is required',
            'status.boolean' => 'Status must be true or false'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->validationMsg($validator->errors()));
    }
} 