<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shop_medicine_details extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'medicine_category_id',
        'medicine_subcategoy_id',
        'medicine_detail_id',
        'capsual_quantity',
        'shirap_ml',
        'mrp_price',
        'offer_price',
        'medicine_type',
        'status',
    ];
}
