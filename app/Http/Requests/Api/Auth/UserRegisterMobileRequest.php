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
           'mobile_no' => 'required|numeric|starts_with: 1,2,3,4,5,6,7,8,9|unique:users,mobile_no,NOTNULL,password,deleted_at,NULL,country_code,'.$this->country_code,
           'country_code'=> 'required',
        ];
    }

    protected function failedValidation(Validator $validator) {
        $transformed=[];       
        foreach ($validator->errors()->toArray() as $field => $message) {
            if(!empty($message) && count($message) > 0 && $field == 'mobile_no' && $message[0] == 'The mobile no must start with one of the following:  1, 2, 3, 4, 5, 6, 7, 8, 9.'){
                $transformed[$field] = 'The mobile no not start to 0.';
            }else{
                $transformed[$field] = $message[0];
            }
        }
       
        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors' => $transformed,
            'message' => 'The given data was invalid.',
        ], 422));
    }
}
