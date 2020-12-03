<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_details extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'about_us',
        'country',
        'city',
        'address',
        'emergency_contact',
        'dob',
        'marital_status',
        'blood_group',
        'pincode',
        'height',
        'weight',
        'allergies',
        'smoking_habbits',
        'alcohole_consumption',
        'food_preference',
        'occupation',
        'normal_fees',
        'urgent_fees',
        'home_visit_fees',
        'urgent',
        'availability',
        'registration_no',
        'registration_council',
        'registration_year',
        'clinic_name',
        'clinic_city',
        'clinic_locality',
        'total_experiance_year',
        'same_timing',
        'fees_hour',
        'fees_day',
    ];


    public function getQualificationCertificateAttribute($value) {
        return !empty($value) ?  url('storage/'.$value) : asset('/admin/images/avatar.jpg');
    }
    public function getPracticingLicenceAttribute($value) {
        return !empty($value) ?  url('storage/'.$value) : asset('/admin/images/avatar.jpg');
    }
    public function getHealthFacilityCertificateAttribute($value) {
        return !empty($value) ?  url('storage/'.$value) : asset('/admin/images/avatar.jpg');
    }
    public function getRegstrationCertificateAttribute($value) {
        return !empty($value) ?  url('storage/'.$value) : asset('/admin/images/avatar.jpg');
    }
    public function getPharmacistCertificateAttribute($value) {
        return !empty($value) ?  url('storage/'.$value) : asset('/admin/images/avatar.jpg');
    }
}
