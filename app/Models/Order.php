<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;
    
    public $status_value = array(
        '0' => 'Pending',
        '1' => 'Success',
        '2' => 'Cancel',
    );

    public $delivery_type_value = array(
        '0' => 'Home Delievry',
        '1' => 'pick-up from store',
    );

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
        'completed_datetime',
        'user_rating',
        'user_review',
        'transaction_id',
        'voucher_code_id',
        'voucher_amount',
        'delivery_type'
    ];

    protected $appends = ['invoice_no_generate','order_no_generate','status_name','delivery_type_name'];

    public function getStatusNameAttribute() {
        return array_key_exists($this->status, $this->status_value) ? $this->status_value[$this->status]: '';
    }

    public function getDeliveryTypeNameAttribute() {
        return array_key_exists($this->delivery_type, $this->delivery_type_value) ? $this->delivery_type_value[$this->delivery_type]: '';
    }

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

    public function getTransaction() {
        return $this->hasOne('App\Models\User_transaction', 'id', 'transaction_id');
    }

    public function getInvoiceNoGenerateAttribute(){
       return 'INV-ORDER-'.str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }
   
    public function getOrderNoGenerateAttribute(){
       return '#'.str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }
}
