<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserForgetPasswordRequest extends FormRequest
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
            'mobile_no' => 'required_without:email|nullable|numeric',
            'email' => 'required_without:mobile_no|nullable|email:rfc,dns',
            'country_code' => 'required_without:email|nullable|numeric'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $transformed = [];
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
