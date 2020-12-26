<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Services extends Model
{
     use HasFactory, SoftDeletes;

    public $status_value = array(
        '0' => 'Active',
        '1' => 'Inactive',
    );

    public $service_type_value = array(
        '2' => 'Scientist',
        '3' => 'Pathologist',
        '4' => 'Radiologist',
    );

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'service_name',
        'service_type',
        'sevice_usages',
        'status',
    ];

    	
    protected $appends = ['status_name','service_type_name'];

    public function getStatusNameAttribute() {
        return array_key_exists($this->status, $this->status_value) ? $this->status_value[$this->status]: '';
    }
    
    public function getServiceTypeNameAttribute() {
        return array_key_exists($this->service_type, $this->service_type_value) ? $this->service_type_value[$this->service_type]: '';
    }
  
    public function getSeviceUsagesAttribute($value) {
        return !empty($value) ? explode(',', $value) :'';
    }

}
