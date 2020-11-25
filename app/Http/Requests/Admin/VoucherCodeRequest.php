<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class VoucherCodeRequest extends FormRequest
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
                'voucher_name' => 'required',
                'voucher_code' => 'required|unique:voucher_codes,voucher_code,'.$this->id,
                'quantity' => 'required|numeric',
                'expiry_date' => 'required',
                'voucher_type' => 'required',
                'status' =>'required',
        ];
    }
}
