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
        'clinic_hospital_name',
        'about_us',
        'country',
        'city',
        'address',
        'pincode',
        'emergency_contact',
        'dob',
        'marital_status',
        'blood_group',
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
        'delivery_charge',
        'urgent',
        'availability',
        'registration_no',
        'registration_council',
        'registration_year',
        'clinic_name',
        'clinic_city',
        'clinic_locality',
        'qualification_certificate',
        'practicing_licence',
        'health_facility_certificate',
        'regstration_certificate',
        'pharmacist_certificate',
        'qualification_certificate_status',
        'practicing_licence_status',
        'health_facility_certificate_status',
        'regstration_certificate_status',
        'pharmacist_certificate_status',
        'total_experiance_year',
        'same_timing',
        'fees_hour',
        'fees_day',
        'activity_level',
        'current_medications',
        'past_medications',
        'chronic_disease',
        'injuries',
        'surgeries',
        'emergency_contact_name',
        'fees_minute',
        'urgent_criteria',
        'clinic_consultation_charge',
        'home_consultation_charge',
        'video_consultation_charge',
        'nursing_facility_charge_full_day',
        'nursing_home_visit_charge_full_day',
        'clinic_country',
        'clinic_state',
        'address_type',
        'practicing_licence_date'
    ];


    public function getQualificationCertificateAttribute($value) {
      $data = json_decode($value);
      $temp_arr = [];
      if(!empty($data) && is_array($data)){        
        foreach ($data as $k => $v) {
          $temp_arr[] = url('storage/'.$v);
        }        
      }else if(!empty($value) && $value != '[]'){
        $temp_arr[] = !empty($value) ? url('storage/'.$value) : '';
      }
      return json_encode($temp_arr);
    }
    public function getPracticingLicenceAttribute($value) {
        return !empty($value) ?  url('storage/'.$value) : '';
    }
    public function getHealthFacilityCertificateAttribute($value) {
        return !empty($value) ?  url('storage/'.$value) : '';
    }
    public function getRegstrationCertificateAttribute($value) {
        return !empty($value) ?  url('storage/'.$value) : '';
    }
    public function getPharmacistCertificateAttribute($value) {
        return !empty($value) ?  url('storage/'.$value) : '';
    }

    public function getAllergiesAttribute($value) {
      // return !empty($value) ?  json_decode($value) : '';
      return $value;
    }
    public function getCurrentMedicationsAttribute($value) {
      // return !empty($value) ?  json_decode($value) : '';
      return $value;
    }
    public function getPastMedicationsAttribute($value) {
      // return !empty($value) ?  json_decode($value) : '';
      return $value;
    }
    public function getChronicDiseaseAttribute($value) {
      // return !empty($value) ?  json_decode($value) : '';
      return $value;
    }
    public function getInjuriesAttribute($value) {
      // return !empty($value) ?  json_decode($value) : '';
      return $value;
    }
    public function getSurgeriesAttribute($value) {
      // return !empty($value) ?  json_decode($value) : '';
      return $value;
    }
    public function getTotalExperianceYearAttribute($value) {
      $currentYear = date('Y');
      if(!empty($this->registration_year)){
        $totalYears = $currentYear - $this->registration_year;
      }else{
        $totalYears = $value;
      }
      return strval($totalYears);
    }
    public function getUrgentCriteriaAttribute($value) {
      $data = [];
      if(isset($value) && $value != '' && $value != 'NULL') {
        $tmp_data = explode(',', $value);
        foreach ($tmp_data as $k => $v) {
          $data[] = (int)$v;
        }
      }else{
        $data = [];
      }
      return $data;
    }
    

}
