<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User_services extends Model
{
    use HasFactory, SoftDeletes;
    
    public $status_value = array(
        '0' => 'Active',
        '1' => 'Inactive',
    );

    public $service_charge_type_value = array(
        '1' => 'per Minute',
        '2' => 'per Hours',
        '3' => 'per Day'
    );
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'service_id',
        'service_charge',
        'service_charge_type',
        'status',
    ];
 
    protected $appends = ['status_name','service_charge_type_name'];

    public function getStatusNameAttribute() {
        return array_key_exists($this->status, $this->status_value) ? $this->status_value[$this->status]: '';
    }
    
    public function getServiceChargeTypeNameAttribute() {
        return array_key_exists($this->service_charge_type, $this->service_charge_type_value) ? $this->service_charge_type_value[$this->service_charge_type]: '';
    }

    public function service() {
        return $this->belongsTo('App\Models\Services', 'service_id','id');
    }
    
}
