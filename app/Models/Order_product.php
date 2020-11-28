<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order_product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'shop_medicine_detail_id',
        'quantity',
        'medicine_price'
    ];

    public function shopMedicineDetails() {
        return $this->belongsTo('App\Models\Shop_medicine_details', 'shop_medicine_detail_id','id');
    }

}
