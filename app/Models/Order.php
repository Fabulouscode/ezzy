<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'client_id',
        'user_location_id',
        'total_price',
        'shipping_price',
        'payment_res',
        'otp_code',
        'cancel_reason',
        'cancel_date',
        'cancel_user_id',
        'status',
    ];

    public function userDetails() {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function clientDetails() {
        return $this->hasOne('App\Models\User', 'id', 'client_id');
    }
    
    public function cancelUserDetails() {
        return $this->hasOne('App\Models\User', 'id', 'cancel_user_id');
    }
  
    public function userLocationDetails() {
        return $this->hasOne('App\Models\User_location', 'id', 'user_location_id');
    }
   
    public function orderProductDetails() {
        return $this->hasMany('App\Models\Order_product', 'order_id', 'id');
    }
}
