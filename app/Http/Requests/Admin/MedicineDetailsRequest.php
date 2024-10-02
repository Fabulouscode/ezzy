<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MedicineDetailsRequest extends FormRequest
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
           'medicine_category_id'=>'required',
           'medicine_name'=>'required',
           'medicine_sku'=>'required|unique:medicine_details,medicine_sku,'.$this->id.',id,medicine_subcategoy_id,'.$this->medicine_subcategoy_id,
           'medicine_type'=>'required',
           'status'=>'required',
           'mrp_price'=>'required|numeric|min:0',
           'quantity'=>'required|integer|min:0',
        ];
    }
}
