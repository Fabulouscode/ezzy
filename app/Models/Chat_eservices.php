<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat_eservices extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'chat_history_id',
        'shop_medicine_detail_id',
        'medicine_name',
        'quanity',
        'price',
        'effective_date',
        'patient_direction',
        'dispense',
        'dispense_unit',
        'refills',
        'days_supply',
        'user_service_id',
    ];
}
