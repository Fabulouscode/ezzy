<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User_transaction extends Model
{
    use HasFactory, SoftDeletes;
    
    public $status_value = array(
        '0' => 'Success',
        '1' => 'Unsuccess',
        '2' => 'Pending',
    );
    
    public $payout_status_value = array(
        '0' => 'Paid',
        '1' => 'Pending',
        '2' => 'Cancel',
        '3' => 'In-progress',
    );
        
    public $transaction_type_value = array(
        '0' => 'Wallet',
        '1' => 'Paystack',
        '2' => 'InterSwitch',
        '3' => 'Paypal',
    );

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',        
        'transaction_date',
        'amount',
        'mode_of_payment',
        'transaction_type',
        'status',        
        'payment_gateway_response',
        'client_id',
        'payout_status',
        'payout_amount',
        'fees_charge',
        'payout_date',
        'wallet_transaction',
        'appointment_id',
        'order_id',
        'transaction_msg',
        'payment_gateway_full_response'
    ];

    protected $appends = ['status_name','payout_status_name','transaction_type_name'];

    public function getStatusNameAttribute() {
        return array_key_exists($this->status, $this->status_value) ? $this->status_value[$this->status]: '';
    }

    public function getPayoutStatusNameAttribute() {
        return array_key_exists($this->payout_status, $this->payout_status_value) ? $this->payout_status_value[$this->payout_status]: '';
    }
  
    public function getTransactionTypeNameAttribute() {
        return array_key_exists($this->transaction_type, $this->transaction_type_value) ? $this->transaction_type_value[$this->transaction_type]: '';
    }

    public function users() {
        return $this->belongsTo('App\Models\User', 'user_id','id');
    }
   
    public function client() {
        return $this->belongsTo('App\Models\User', 'client_id','id');
    }

    public function transactionAppointment() {
        return $this->hasOne('App\Models\Appointment', 'transaction_id', 'id');
    }
    
    public function transactionOrder() {
        return $this->hasOne('App\Models\Order', 'transaction_id', 'id');
    }
 
    public function transactionTreatmentPlan() {
        return $this->hasOne('App\Models\Chat_history', 'transaction_id', 'id');
    }

    public function tranAppointment() {
        return $this->belongsTo('App\Models\Appointment', 'appointment_id','id');
    }
   
    public function tranOrder() {
        return $this->belongsTo('App\Models\Order', 'order_id','id');
    }

    public function userFormat(){
        return [
            'id'=>$this->id,
            'amount'=>$this->amount,
            'transaction_type'=>$this->transaction_type,
            'transaction_type_name'=>$this->transaction_type_name,
            'transaction_date'=>$this->transaction_date,
            'wallet_transaction'=>$this->wallet_transaction,
            'mode_of_payment'=>$this->mode_of_payment,
            'transaction_msg'=>$this->transaction_msg,
            'payout_amount'=>$this->payout_amount,
            'fees_charge'=>$this->fees_charge,
            'user'=>(isset($this->client))?
                        [
                            'id'=>$this->client->id,
                            'user_name'=>$this->client->user_name,
                            'profile_image'=>$this->client->profile_image,
                            'category'=> (!empty($this->client->categoryParent) && !empty($this->client->categoryParent->name))? $this->client->categoryParent->name : NULL,
                            'subcategory'=> (!empty($this->client->categoryChild) && !empty($this->client->categoryChild->name))? $this->client->categoryChild->name : NULL,
                        ]:NULL,
            'client'=>(isset($this->users))?
                        [
                            'id'=>$this->users->id,
                            'user_name'=>$this->users->user_name,
                            'profile_image'=>$this->users->profile_image,
                         ]:NULL,
            'appointment'=>(isset($this->tranAppointment))?
                    [
                        'id'=>$this->tranAppointment->id,
                        'appointment_type_name'=>$this->tranAppointment->appointment_type_name,
                        'appointment_date'=>$this->tranAppointment->appointment_date .' '.$this->tranAppointment->appointment_time,
                    ]:NULL,
            'order'=>(isset($this->tranOrder))?
                    [
                        'id'=>$this->tranOrder->id,
                        'products' => $this->tranOrder->order_medicine_name,
                        'order_date'=>$this->tranOrder->created_at,
                    ]:NULL,
            'treatment_plan'=>(isset($this->transactionTreatmentPlan))?
                    [
                        'id'=>$this->transactionTreatmentPlan->id,
                        'plan_name'=>$this->transactionTreatmentPlan->plan_name,
                        'plan_date'=>$this->transactionTreatmentPlan->created_at,
                    ]:NULL,
            'status'=> $this->status,
            'status_name'=> $this->status_name,
        ];
    }
}
