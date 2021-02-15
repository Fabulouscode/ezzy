<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_transaction extends Model
{
    use HasFactory;
    
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
        '1' => 'Net Banking',
        '2' => 'Debit/Credit Card',
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
        'wallet_transaction'
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
}
