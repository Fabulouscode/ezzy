<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRegisterMobileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
           'mobile_no' => 'required|string|max:255|unique:users,mobile_no,NOTNULL,password,deleted_at,NULL',
           'country_code'=> 'required',
        ];
    }

    protected function failedValidation(Validator $validator) {
        $transformed=[];
        foreach ($validator->errors()->toArray() as $field => $message) {
            $transformed[$field] = $message[0];
        }

        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors' => $transformed,
            'message' => 'The given data was invalid.',
        ], 422));
    }
}
