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
        'cancel_user_id',
        'status',
        'transaction_id',
        'consult_notes',
        'user_rating',
        'user_review',
        'user_service_id',
        'completed_datetime'
    ];


    public function user() {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function client() {
        return $this->hasOne('App\Models\User', 'id', 'client_id');
    }
    
    public function cancelUser() {
        return $this->hasOne('App\Models\User', 'id', 'cancel_user_id');
    }

    public function userService() {
        return $this->hasOne('App\Models\User_services', 'id','user_service_id');
    }
  
    public function appointmentServices() {
        return $this->hasMany('App\Models\Appointment_services', 'appointment_id','id');
    }

    public function getTransaction() {
        return $this->hasOne('App\Models\User_transaction', 'id', 'transaction_id');
    }
}
