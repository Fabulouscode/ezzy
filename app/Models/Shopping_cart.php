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
}
