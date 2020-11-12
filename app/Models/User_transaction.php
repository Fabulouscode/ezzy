<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_transaction extends Model
{
    use HasFactory;
    
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
