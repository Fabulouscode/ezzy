<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payout_amount extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'user_bank_account_id',
        'amount',
        'deduction_amount',
        'payable_amount',
        'notes',
        'bank_transaction_id',
        'approved_by',
        'approved_date',
        'admin_id'

    ];

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    
    public function userBankAccount() {
        return $this->belongsTo('App\Models\User_bank_account', 'user_bank_account_id');
    }

    public function admin() {
        return $this->belongsTo('App\Models\Admin', 'admin_id');
    }
}
