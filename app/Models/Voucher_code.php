<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher_code extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'voucher_name',
        'voucher_code',
        'description',
        'quantity',
        'expiry_date',
        'percentage',
        'fix_amount',
        'min_amount',
        'voucher_type',
        'status'
    ];
}
