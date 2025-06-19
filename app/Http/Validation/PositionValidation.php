<?php

namespace App\Http\Validation;

use App\Traits\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class PositionValidation extends FormRequest
{
    use Response;

    public function authorize()
    {
        return true;
    }

    public function rules(Request $request)
    {
        return [
            'name'                 => 'required|string',
            'description'          => 'nullable|string|min:2|max:1000',
            'status'               => 'required|numeric|in:0,1'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->validationRes($validator->errors()));
    }
}
