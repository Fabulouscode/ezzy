<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shop_medicine_details extends Model
{
    use HasFactory, SoftDeletes;
    
    public $status_value = array(
        '0' => 'Active',
        '1' => 'Inactive',
    );
    
    public $medicine_type_value = array(
        '0' => 'Capsules',
        '1' => 'Bottle',
    );
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
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

    protected $appends = ['status_name','medicine_type_name'];

    public function getStatusNameAttribute() {
        return array_key_exists($this->status, $this->status_value) ? $this->status_value[$this->status]: '';
    }
    
    public function getMedicineTypeNameAttribute() {
        return array_key_exists($this->medicine_type, $this->medicine_type_value) ? $this->medicine_type_value[$this->medicine_type]: '';
    }

    public function medicineDetails() {
        return $this->belongsTo('App\Models\Medicine_details','medicine_detail_id','id');
    }

    public function medicineCategory() {
        return $this->belongsTo('App\Models\Medicine_category', 'medicine_category_id');
    }

    public function medicineSubcategory() {
        return $this->belongsTo('App\Models\Medicine_category', 'medicine_subcategoy_id');
    }
}
