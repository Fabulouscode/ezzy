<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medicine_details extends Model
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
        'medicine_name',
        'medicine_sku',
        'description',
        'medicine_image',
        'medicine_type',
        'status',
    ];

    protected $appends = ['medicine_multiple_images'];

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
