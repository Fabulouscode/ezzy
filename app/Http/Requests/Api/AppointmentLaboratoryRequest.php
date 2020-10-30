<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AppointmentLaboratoryRequest extends FormRequest
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
            'user_id' => 'required',
            'appointment_type' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'mobile_no' => 'required',
            'age' => 'required',
            'gender' => 'required',
            'reason' => 'required',
            'user_services' => 'required|array|min:1',
            'appointment_date' => 'required|date_format:Y-m-d',
            'appointment_time' => 'required|date_format:H:i:s',
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
