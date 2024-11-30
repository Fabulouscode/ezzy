<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserProviderAuthRequest extends FormRequest
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
           'register_type' => 'required|in:1,2',
           'email' => 'required|string|email|max:255|unique:users,email,NOTNULL,password,deleted_at,NULL',
           'mobile_no' => 'required|numeric|unique:users,mobile_no,NOTNULL,password,deleted_at,NULL,country_code,'.$this->country_code,
           'country_code' => 'required_without:email|nullable|numeric',
           'password' => 'required|string|min:8',
           'device_type' => 'required',
           'device_token' => 'required',
           'category_id' => 'required',
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
