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
}
