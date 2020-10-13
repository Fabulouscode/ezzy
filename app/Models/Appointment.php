<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    
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
        'reason',
        'appointment_date',
        'appointment_time',
        'appointment_price',
        'otp_code',
        'cancel_reason',
        'cancel_date',
        'status',
    ];
}
