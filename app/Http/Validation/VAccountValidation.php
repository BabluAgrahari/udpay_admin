<?php

namespace App\Http\Validation;

use App\Traits\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class VAccountValidation extends FormRequest
{
    use Response;

    public function authorize()
    {
        return true;
    }

    public function rules(Request $request)
    {
        $rules = [
            'name' => 'required|string',
        ];

        if ($request->method() == 'post') {
            $rules['phone_no'] = 'required|numeric|digits:10|unique:virtual_accounts,phone_no';
            $rules['email'] = 'required|string|email|unique:virtual_accounts,email';
        } else {
            $rules['email'] = 'required|string|email';
        }

        return $rules;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->validationRes($validator->errors()));
    }
}
