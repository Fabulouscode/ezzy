<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher_code extends Model
{
    use HasFactory, SoftDeletes;
    public $status_value = array(
        '0' => 'Active',
        '1' => 'Inactive',
    );
 
    public $voucher_type_value = array(
        // '0' => 'Common',
        '1' => 'Appointment',
        '2' => 'Order',
        '3' => 'Laboratory Appointment',
        '4' => 'Radiology Appointment',
    );
 
    public $voucher_used_value = array(
        '0' => 'One Time',
        '1' => 'Multiple Time',
    );
 
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
        'status',
        'voucher_used'
    ];

    protected $appends = ['status_name', 'voucher_type_name', 'voucher_used_name'];

    public function getStatusNameAttribute() {
        return array_key_exists($this->status, $this->status_value) ? $this->status_value[$this->status]: '';
    }
 
    public function getVoucherTypeNameAttribute() {
        return array_key_exists($this->voucher_type, $this->voucher_type_value) ? $this->voucher_type_value[$this->voucher_type]: '';
    }
  
    public function getVoucherUsedNameAttribute() {
        return array_key_exists($this->voucher_used, $this->voucher_used_value) ? $this->voucher_used_value[$this->voucher_used]: '';
    }
    
}
