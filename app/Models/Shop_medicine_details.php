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

    public function user() {
        return $this->belongsTo('App\Models\User','user_id','id');
    }

    public function medicineCategory() {
        return $this->belongsTo('App\Models\Medicine_category', 'medicine_category_id');
    }

    public function medicineSubcategory() {
        return $this->belongsTo('App\Models\Medicine_category', 'medicine_subcategoy_id');
    }
    
    public function favoriteProduct() {
        return $this->hasOne('App\Models\Favorite_product','shop_medicine_detail_id','id');
    }

    public function format(){
        return [
            'id'=>$this->id,
            'name'=>(!empty($this->user)) ? $this->user->user_name : '',
            'medicine_category_id'=>$this->medicine_category_id,
            'medicine_subcategoy_id'=>$this->medicine_subcategoy_id,
            'medicine_detail_id'=>$this->medicine_detail_id,
            'capsual_quantity'=>$this->capsual_quantity,
            'shirap_ml'=>$this->shirap_ml,
            'offer_price'=>$this->offer_price,
            'mrp_price'=>$this->mrp_price,
            'medicine_type'=>$this->medicine_type,
            'medicine_type_name'=>$this->medicine_type_name,
            'medicine_details'=>(isset($this->medicineDetails))?
                            [
                                'id'=>$this->medicineDetails->id,
                                'medicine_image'=>$this->medicineDetails->medicine_image,
                                'medicine_multiple_images'=>$this->medicineDetails->medicine_multiple_images,
                                'medicine_name'=>$this->medicineDetails->medicine_name,
                                'medicine_sku'=>$this->medicineDetails->medicine_sku,
                                'description'=>$this->medicineDetails->description,
                            ]:'',
            'favorite_product'=>(isset($this->favoriteProduct))? 1 : 0,
            'status'=>$this->status,
            'status_name'=>$this->status_name,
        ];
    }
}
