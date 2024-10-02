<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medicine_details extends Model
{
    use HasFactory, SoftDeletes;
    
    public $status_value = array(
        '0' => 'Active',
        '1' => 'Inactive',
    );

    public $medicine_type_value = array(
        '0' => 'Capsules',
        '1' => 'Bottle',
        '2' => 'Tablets',
        '3' => 'Syrup',
        '4' => 'Suspension',
        '5' => 'Injection',
        '6' => 'Cream/lotion',
        '7' => 'Drops',
        '8' => 'Spray',
        '9' => 'Suppository',
        '11' => 'Sachets',
        '10' => 'Others'
    );

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'medicine_category_id',
        'medicine_subcategoy_id',
        'medicine_name',
        'medicine_sku',
        'description',
        'medicine_image',
        'medicine_type',
        'status',
        'size_dosage',
        'mrp_price',
        'quantity'
    ];

    protected $appends = ['medicine_multiple_images','status_name','medicine_type_name'];
    
    public function getStatusNameAttribute() {
        return array_key_exists($this->status, $this->status_value) ? $this->status_value[$this->status]: '';
    }

    public function getMedicineTypeNameAttribute() {
        return array_key_exists($this->medicine_type, $this->medicine_type_value) ? $this->medicine_type_value[$this->medicine_type]: '';
    }

    public function medicineImages() {
        return $this->hasMany('App\Models\Medicine_images','medicine_detail_id','id')->orderBy('sequence_no');
    }

    public function medicineCategory() {
        return $this->belongsTo('App\Models\Medicine_category', 'medicine_category_id');
    }

    public function medicineSubcategory() {
        return $this->belongsTo('App\Models\Medicine_subcategory', 'medicine_subcategoy_id');
    }
    
    public function getMedicineImageAttribute($value) {
        $value = $this->hasOne('App\Models\Medicine_images','medicine_detail_id','id')->orderBy('sequence_no')->select('product_image')->first();
        return !empty($value) ?  url('storage/'.$value->product_image) : asset('/admin/images/medicine_image.jpg');
    }

    public function getMedicineMultipleImagesAttribute(){
        $images =  $this->hasMany('App\Models\Medicine_images','medicine_detail_id','id')->orderBy('sequence_no')->pluck('product_image');
        $image_url = array();
        if(!empty($images)){
            foreach ($images as $key => $value) {
                $image_url[] = !empty($value) ?  url('storage/'.$value) : asset('/admin/images/medicine_image.jpg');
            }
        }
        return $image_url;
    }
}
