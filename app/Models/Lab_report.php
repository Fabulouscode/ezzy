<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lab_report extends Model
{
   use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id',
        'report_name',
        'user_id',
        'doctor_name',
        'report_date',
        'report_time',
        'description',
        'report_images'
    ];

    public function client() {
        return $this->belongsTo('App\Models\User','client_id','id');
    }

    public function user() {
        return $this->belongsTo('App\Models\User','user_id','id');
    }
    
    public function getReportImagesAttribute($value) {
       $image_url = array();
        if(!empty($value)){
        $data = json_decode($value);
            if(!empty($data) && count($data) > 0){
                foreach ($data as $key => $value) {
                    $image_url[] = !empty($value) ?  url('storage/'.$value) : asset('/admin/images/avatar.jpg');
                }
            }
        }
        return $image_url;
    }
}
