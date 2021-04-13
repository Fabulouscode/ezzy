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
        '1' => 'Accepted',
        '2' => 'Order Dispatch',        
        '3' => 'Success',
        '4' => 'Cancel',
    );

    public $delivery_type_value = array(
        '0' => 'Home Delivery',
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
        'delivery_type',
    ];

    protected $appends = ['invoice_no_generate','order_no_generate','status_name','delivery_type_name','order_medicine_name'];

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
 
    public function voucherDetails() {
        return $this->hasOne('App\Models\Voucher_code', 'id', 'voucher_code_id');
    }
   
    public function orderProductDetails() {
        return $this->hasMany('App\Models\Order_product', 'order_id', 'id');
    }

    public function orderProductTrackingDetails() {
        return $this->hasOne('App\Models\Order_tracking', 'order_id', 'id')->orderby('id','desc');
    }

    public function orderTrackingDetails() {
        return $this->hasMany('App\Models\Order_tracking', 'order_id', 'id')->orderby('id','desc');
    }

    public function getOrderMedicineNameAttribute(){
        return $this->orderProductNamesformat($this->hasMany('App\Models\Order_product', 'order_id', 'id')->with(['shopMedicineDetails','shopMedicineDetails.medicineDetails'])->get());        
        // return $this->hasMany('App\Models\Order_product', 'order_id', 'id')->with(['shopMedicineDetails','shopMedicineDetails.medicineDetails'])->get();    
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


    public function format(){
        return [
            'id'=>$this->id,
            'total_price'=>$this->total_price,
            'transaction'=>(!empty($this->getTransaction))? 1 : 0,
            'shipping_price'=>$this->shipping_price,
            'user_rating'=>$this->user_rating,
            'user_review'=>$this->user_review,
            'delivery_type'=>$this->delivery_type,
            'delivery_type_name'=>$this->delivery_type_name,
            'order_no_generate'=>$this->order_no_generate,
            'completed_datetime'=>$this->completed_datetime,
            'created_at'=>$this->created_at,
            'order_tracking_status'=>(!empty($this->orderProductTrackingDetails)) ? $this->orderProductTrackingDetails->status : 0,
            'order_product_details'=>(!empty($this->orderProductDetails))? $this->orderProductDetailsformat($this->orderProductDetails) : '',
            'client'=>(isset($this->clientDetails))?
                            [
                                'id'=>$this->clientDetails->id,
                                'user_name'=>$this->clientDetails->user_name,
                                'profile_image'=>$this->clientDetails->profile_image
                            ]:'',
            'user'=>(isset($this->userDetails))?
                            [
                                'id'=>$this->userDetails->id,
                                'user_name'=>$this->userDetails->user_name,
                                'profile_image'=>$this->userDetails->profile_image,
                                'address'=>(isset($this->userDetails->userDetails))? $this->userDetails->userDetails->address : ''
                            ]:'',
            'status'=>$this->status,
            'status_name'=>$this->status_name,
        ];
    }

    public function orderProductDetailsformat($orderProductDetails){
        $data = [];
        if(!empty($orderProductDetails)){
            foreach ($orderProductDetails as $key => $value) {
                $data[]=[
                    "id"=>$value->id,
                    "quantity"=>$value->quantity,
                    "medicine_price"=>$value->medicine_price,
                    'shirap_ml'=> !empty($value->shopMedicineDetails)  && !empty($value->shopMedicineDetails->medicineDetails)  ? $value->shopMedicineDetails->medicineDetails->size_dosage : '',
                    'medicine_type'=> !empty($value->shopMedicineDetails)  && !empty($value->shopMedicineDetails->medicineDetails)  ? $value->shopMedicineDetails->medicineDetails->medicine_type : '',
                    'medicine_type_name'=> !empty($value->shopMedicineDetails) && !empty($value->shopMedicineDetails->medicineDetails)  ? $value->shopMedicineDetails->medicineDetails->medicine_type_name : '',
                    'medicine_image'=> !empty($value->shopMedicineDetails) && !empty($value->shopMedicineDetails->medicineDetails)  ? $value->shopMedicineDetails->medicineDetails->medicine_image : '',
                    'medicine_name'=> !empty($value->shopMedicineDetails) && !empty($value->shopMedicineDetails->medicineDetails) ? $value->shopMedicineDetails->medicineDetails->medicine_name : '',
                    'medicine_sku'=> !empty($value->shopMedicineDetails) && !empty($value->shopMedicineDetails->medicineDetails) ? $value->shopMedicineDetails->medicineDetails->medicine_sku : '',
                ];
            }
        }
        return $data;
    }
 
    public function orderProductNamesformat($orderProductDetails){
        $data = [];
        if(!empty($orderProductDetails)){
            foreach ($orderProductDetails as $key => $value) {
                $data[]=!empty($value->shopMedicineDetails) && !empty($value->shopMedicineDetails->medicineDetails) ? $value->shopMedicineDetails->medicineDetails->medicine_name : '';
            }
        }
        return $data;
    }
}
