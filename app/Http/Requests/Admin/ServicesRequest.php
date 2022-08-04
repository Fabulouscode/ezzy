<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ServicesRequest extends FormRequest
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
        $service_rule = !empty($this->id) ? 'unique:services,service_name,'.$this->id.',id,service_type,'.$this->service_type : 'unique:services,service_name,'.$this->id.',id';
        return [
            'service_name'=>'required|string|'.$service_rule,
            'service_type'=>'required',
            'status'=>'required',
        ];
    }
}
