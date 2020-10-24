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


    public function medicineImages() {
        return $this->hasMany('App\Models\Medicine_images','medicine_detail_id','id');
    }

    public function medicineCategory() {
        return $this->belongsTo('App\Models\Medicine_category', 'medicine_category_id');
    }

    public function medicineSubcategory() {
        return $this->belongsTo('App\Models\Medicine_category', 'medicine_subcategoy_id');
    }
}
