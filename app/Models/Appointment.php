<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'client_id',
        'appointment_type',
        'name',
        'email',
        'mobile_no',
        'age',
        'gender',
        'reason',
        'appointment_date',
        'appointment_time',
        'appointment_price',
        'otp_code',
        'cancel_reason',
        'cancel_date',
        'status',
        'credit_transaction_id',
        'debit_transaction_id',
        'consult_notes',
        'user_rating',
        'user_review'
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

}
