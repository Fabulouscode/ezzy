<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite_product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'medicine_detail_id',
        'shop_medicine_detail_id',
    ];

    public function medicineDetails() {
        return $this->belongsTo('App\Models\Medicine_details','medicine_detail_id','id');
    }

    public function shopMedicineDetails() {
        return $this->belongsTo('App\Models\Shop_medicine_details','shop_medicine_detail_id','id');
    }
}
