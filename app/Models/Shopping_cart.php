<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shopping_cart extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'shop_medicine_detail_id',
        'quantity'
    ];

    public function shopMedicineDetails() {
        return $this->belongsTo('App\Models\Shop_medicine_details','shop_medicine_detail_id','id');
    }

    public function format() {
      return [
                'id'=>$this->id,                                        
                'quantity'=>$this->quantity,
                'shop_id'=>$this->shopMedicineDetails->id,
                'mrp_price'=>$this->shopMedicineDetails->mrp_price,
                'offer_price'=>$this->shopMedicineDetails->offer_price,
                'medicine_type'=>$this->shopMedicineDetails->medicine_type,
                'medicine_type_name'=>$this->shopMedicineDetails->medicine_type_name,
                'capsual_quantity'=>$this->shopMedicineDetails->capsual_quantity,
                'shirap_ml'=>$this->shopMedicineDetails->shirap_ml,
                'shipping_price'=>(!empty($this->shopMedicineDetails->user) && !empty($this->shopMedicineDetails->user->userDetails)) ? $this->shopMedicineDetails->user->userDetails->delivery_charge : '',
                'medicine_details'=>(isset($this->shopMedicineDetails->medicineDetails))?
                                [
                                    'id'=>$this->shopMedicineDetails->medicineDetails->id,
                                    'medicine_image'=>$this->shopMedicineDetails->medicineDetails->medicine_image,
                                    'medicine_name'=>$this->shopMedicineDetails->medicineDetails->medicine_name,
                                    'medicine_sku'=>$this->shopMedicineDetails->medicineDetails->medicine_sku,
                                ]:'',
                'status'=>$this->shopMedicineDetails->status,
                'status_name'=>$this->shopMedicineDetails->status_name,
        ];
    }
}
