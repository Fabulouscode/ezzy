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
    ];

    protected $appends = ['status_name','transaction_type_name'];

    public function getStatusNameAttribute() {
        return array_key_exists($this->status, $this->status_value) ? $this->status_value[$this->status]: '';
    }
  
    public function getTransactionTypeNameAttribute() {
        return array_key_exists($this->transaction_type, $this->transaction_type_value) ? $this->transaction_type_value[$this->transaction_type]: '';
    }

    public function users() {
        return $this->belongsTo('App\Models\User', 'user_id','id');
    }

    public function transactionAppointment() {
        return $this->hasOne('App\Models\Appointment', 'transaction_id', 'id');
    }
    
    public function transactionOrder() {
        return $this->hasOne('App\Models\Order', 'transaction_id', 'id');
    }
}
