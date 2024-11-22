<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    public $status_value = array(
        '0'=>'Active', 
        '1'=>'Wait for Approval', 
        '2'=>'Inactive', 
        '3'=>'Pending Verify',
        '4'=>'Profile Not Complete',
    );
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'subcategory_id',
        'ezzycare_card',
        'mobile_verified_at',
        'first_name',
        'last_name',
        'email',
        'country_code',
        'mobile_no',
        'gender',
        'password',
        'profile_image',
        'otp_code',
        'device_type',
        'device_uuid',
        'device_token',
        'social_type',
        'facebook_id',
        'google_id',
        'apple_id',
        'latitude',
        'longitude',
        'wallet_balance',
        'status',
        'notification_status',
        'approved_date',
        'current_latitude',
        'current_longitude',
        'lock_wallet_balance',
        'user_timezone',
        'user_ip',
        'completed_percentage'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'approved_date'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['user_appointment_review','user_appointment_rating','user_completed_appointment', 'user_cancelled_appointment', 'user_pending_appointment',
                          'client_completed_appointment', 'client_cancelled_appointment', 'client_pending_appointment',
                          'user_order_review','user_order_rating', 'user_completed_order', 'user_cancelled_order', 'user_active_order',
                          'monthly_wallet_balance', 'total_wallet_balance', 'user_name', 'status_name', 'mobile_no_country_code', 'profile_completed_progress',
                          'user_eduction_details','user_active_product', 'profile_required_fields'
                        ];

    public function getStatusNameAttribute() {
        return array_key_exists($this->status, $this->status_value) ? $this->status_value[$this->status]: '';
    }

    public function getProfileImageAttribute($value) {
        return !empty($value) ?  url('storage/'.$value) : asset('/admin/images/avatar.jpg');
    }
   
    public function getUserNameAttribute($value) {
        $last_name = !empty($this->last_name)? ' '.$this->last_name : '' ;
        return $this->first_name.$last_name;
    }
      
    public function getMobileNoCountryCodeAttribute($value) {
        return $this->country_code .' '.$this->mobile_no;
    }
    
    public function categoryParent() {
        return $this->hasOne('App\Models\Category', 'id', 'category_id');
    }

    public function categoryChild() {
        return $this->hasOne('App\Models\Category', 'id', 'subcategory_id');
    }
    
    public function userDetails() {
        return $this->hasOne('App\Models\User_details');
    }
    
    public function userEduction() {
        return $this->hasMany('App\Models\User_education');
    }

    public function userExperiance() {
        return $this->hasMany('App\Models\User_experiance');
    }

    public function userBankAccount() {
        return $this->hasMany('App\Models\User_bank_account');
    }
    
    public function userPrimaryBankAccount() {
        return $this->hasOne('App\Models\User_bank_account')->where('primary_account','1');
    }

    public function userPrimaryAddress() {
        return $this->hasOne('App\Models\User_location')->where('primary_address','1');
    }
    
    public function userAvailableTime() {
        return $this->hasMany('App\Models\User_available_time')->orderBy('day','asc');
    }

    public function userLocation() {
        return $this->hasMany('App\Models\User_location');
    }
    
    public function userReview() {
        return $this->hasMany('App\Models\User_review');
    }

    public function urgenAppointmentDetails() {
        return $this->hasMany('App\Models\Appointment')->where('urgent','1')->whereNotIn('status',['0','1','5','6']);
    }
    
    public function nonUrgentAppointmentDetails() {
        return $this->hasMany('App\Models\Appointment')->where('urgent','0')->whereNotIn('status',['0','1','5','6']);
    }

 
    public function userLabReport() {
        return $this->hasMany('App\Models\Lab_report','client_id','id');
    }
  
    public function userservices() {
        return $this->hasMany('App\Models\User_services','user_id','id');
    }

    public function userOwnServices() {
        $user_services = $this->hasMany('App\Models\User_services','user_id','id')
                        ->with(['service' => function($query){
                            $query->addSelect('id','service_name');
                        }])->select('id','service_id','user_id','service_charge','status');

        return $user_services;
    }
  

    public function getUserEductionDetailsAttribute(){
        return $this->hasMany('App\Models\User_education')->orderBy('end_year','desc')->pluck('degree_name')->implode(', ');        
    }

    public function getMonthlyWalletBalanceAttribute(){
        $total_earning = 0;
        $total_earning = $this->hasOne('App\Models\User_transaction','client_id','id')
                               ->where([['status', '=', '0']])->whereMonth('transaction_date', Carbon::now()->format('m'))->whereYear('transaction_date', Carbon::now()->format('Y'))->sum('payout_amount'); 
         return $total_earning;

    }
   
    public function getTotalWalletBalanceAttribute(){
        $total_earning = 0;
        $total_earning = $this->hasOne('App\Models\User_transaction','client_id','id')
                               ->where([['status', '=', '0']])->sum('payout_amount'); 
        return $total_earning;

    }

    public function userAppointmentRating(){
        return $this->hasMany('App\Models\Appointment','user_id','id')->where('status', '5');        
    }
    
    
    public function getUserAppointmentRatingAttribute(){
        return $this->hasOne('App\Models\Appointment','user_id','id')
                    ->where('status', '5')->avg('user_rating');        
    }
   
    public function getUserAppointmentReviewAttribute(){
        return $this->hasOne('App\Models\Appointment','user_id','id')
                    ->where('status', '5')
                    ->where(function($query){
                                $query->orWhereNotNull('user_review');
                                $query->orWhereNotNull('user_rating');
                            })->count('*');        
    }

    public function getUserCompletedAppointmentAttribute(){
        return $this->hasOne('App\Models\Appointment','user_id','id')
                    ->where('status', '5')->count('*');        
    }

    public function getUserCancelledAppointmentAttribute(){
        return $this->hasOne('App\Models\Appointment','user_id','id')
                    ->where('status', '6')->count('*');     
    }
    
    public function getUserPendingAppointmentAttribute(){
        return $this->hasOne('App\Models\Appointment','user_id','id')
                    ->whereIn('status', ['1','2','3','4'])->count('*');     
    }

    public function getClientCompletedAppointmentAttribute(){
        return $this->hasOne('App\Models\Appointment','client_id','id')
                    ->where('status', '5')->count('*');    
    }

    public function getClientCancelledAppointmentAttribute(){
        return $this->hasOne('App\Models\Appointment','client_id','id')
                    ->where('status', '6')->count('*');    
    }
    
    public function getClientPendingAppointmentAttribute(){
        return $this->hasOne('App\Models\Appointment','client_id','id')
                    ->whereIn('status', ['1','2','3','4'])->count('*');
    }

    public function getUserActiveProductAttribute(){
        return $this->hasMany('App\Models\Shop_medicine_details','user_id','id')
                    ->where('status', '0')->count('id');      
    }

    public function getUserOrderRatingAttribute(){
        return $this->hasOne('App\Models\Order','user_id','id')
                    ->where('status', '3')->avg('user_rating');      
    }

    public function getUserOrderReviewAttribute(){
        return $this->hasOne('App\Models\Order','user_id','id')
                    ->where('status', '3')                    
                    ->where(function($query){
                                $query->orWhereNotNull('user_review');
                                $query->orWhereNotNull('user_rating');
                            })->count('*');      
    }

    public function getUserCompletedOrderAttribute(){
        return $this->hasOne('App\Models\Order','user_id','id')
                    ->where('status', '3')->count('*');        
    }

    public function getUserCancelledOrderAttribute(){
        return $this->hasOne('App\Models\Order','user_id','id')
                    ->where('status', '4')->count('*');     
    }
    
    public function getUserActiveOrderAttribute(){
        return $this->hasOne('App\Models\Order','user_id','id')
                    ->whereIn('status', ['1','2'])->count('*');     
    }

    public function format(){
        return [
            'id'=>$this->id,
            'ezzycare_card'=>$this->ezzycare_card,
            'first_name'=>$this->first_name,
            'last_name'=>$this->last_name,
            'email'=>$this->email,
            'country_code'=>$this->country_code,
            'mobile_no'=>$this->mobile_no,
            'gender'=>$this->gender,
            'profile_image'=>$this->profile_image,
            'user_name'=>$this->user_name,
            'mobile_no_country_code'=>$this->mobile_no_country_code,
            'status'=>$this->status,
            'status_name'=>$this->status_name,
            'lab_report'=>(!empty($this->userLabReport))? $this->userLabReportformat($this->userLabReport) : '',
            'user_details'=>(isset($this->userDetails))?
                            [
                                'emergency_contact'=>$this->userDetails->emergency_contact,
                                'dob'=>$this->userDetails->dob,
                                'marital_status'=>$this->userDetails->marital_status,
                                'blood_group'=>$this->userDetails->blood_group,
                                'height'=>$this->userDetails->height,
                                'weight'=>$this->userDetails->weight,
                                'allergies'=>$this->userDetails->allergies,
                                'smoking_habbits'=>$this->userDetails->smoking_habbits,
                                'alcohole_consumption'=>$this->userDetails->alcohole_consumption,
                                'food_preference'=>$this->userDetails->food_preference,
                                'occupation'=>$this->userDetails->occupation,
                                'current_medications'=>$this->userDetails->current_medications,
                                'past_medications'=>$this->userDetails->past_medications,
                                'chronic_disease'=>$this->userDetails->chronic_disease,
                                'injuries'=>$this->userDetails->injuries,
                                'surgeries'=>$this->userDetails->surgeries,
                                'activity_level'=>$this->userDetails->activity_level,
                            ]:'',
        ];
    }

    public function userLabReportformat($userLabReport){
        $data = [];
        if(!empty($userLabReport)){
            foreach ($userLabReport as $key => $value) {
                $data[]=[
                    "report_name"=>$value->report_name,
                    "doctor_name"=>$value->doctor_name,
                    "report_date"=>$value->report_date,
                    "report_time"=>$value->report_time,
                    "report_images"=>$value->report_images,
                    "created_at"=>$value->updated_at,
                 ];
            }
        }
        return $data;
    }


    public function patientWalletFormat() {
        return [
            'id'=>$this->id,
            'wallet_balance'=>$this->wallet_balance,
            'lock_wallet_balance'=>$this->lock_wallet_balance,
        ];
    }

    public function userLocationFormat() {
        return [
            'id' =>$this->id,
            'user_name' =>$this->user_name,
            'rating' => (!empty($this->category_id))  ? ($this->categoryParent->id == '7') ? round($this->user_order_rating, 1) : round($this->user_appointment_rating, 1) : 0,
            'reviews' => (!empty($this->category_id)) ? ($this->categoryParent->id == '7') ? $this->user_order_review : $this->user_appointment_review : 0,
            'category_id' =>$this->category_id,
            'category_name' => (!empty($this->categoryParent)) ? $this->categoryParent->name : '',
            'clinic_locality' => (!empty($this->userDetails)) ? ($this->userDetails->address_type == '0') ? $this->userDetails->clinic_locality : $this->userDetails->clinic_locality.', '.$this->userDetails->clinic_city.', '.$this->userDetails->clinic_state.', '.$this->userDetails->clinic_country : NULL,
            'patient_address' => (!empty($this->userPrimaryAddress)) ? $this->userPrimaryAddress->address : NULL,
            'latitude' =>$this->latitude,
            'longitude' =>$this->longitude,
            'profile_image' =>$this->profile_image,
        ];
    }

    public function getProfileCompletedProgressAttribute() {
        $imageStaticValue = "/admin/images/avatar.jpg";
        $userProfileImage = ((strpos($this->profile_image, $imageStaticValue) !== false)) ? '' : $this->profile_image;
        $total_progress_point = 0;
        $required_progress = 0;
        $required_progress_array = [];
        $required_user = [];
        $required_userDetails = [];
        $required_userDetails_count = 0;
        $required_userCounts = [];
        if(!empty($this->categoryParent) && $this->categoryParent->parent_id == '1'){
            //Heathcare Provider
            $required_userCounts = [$this->userAvailableTime, $this->userEduction];
            if ($this->categoryParent->id == '5') {     
                // nurses
                $required_user = [$userProfileImage, $this->first_name, $this->mobile_no, $this->email, $this->subcategory_id, $this->gender];
                if (!empty($this->userDetails)) {
                $required_userDetails = [$this->userDetails->registration_no, $this->userDetails->registration_council,
                                    $this->userDetails->registration_year, $this->userDetails->clinic_name, 
                                    $this->userDetails->clinic_locality, $this->userDetails->total_experiance_year, $this->userDetails->dob,
                                    $this->userDetails->country, $this->userDetails->city, $this->userDetails->address,
                                    !empty($this->userDetails->qualification_certificate) && (count(json_decode($this->userDetails->qualification_certificate)) > 0) ? $this->userDetails->qualification_certificate : '', 
                                    $this->userDetails->practicing_licence,
                                    $this->userDetails->about_us, $this->userDetails->clinic_consultation_charge, 
                                    $this->userDetails->home_consultation_charge, $this->userDetails->nursing_facility_charge_full_day,
                                    $this->userDetails->nursing_home_visit_charge_full_day];
                    $required_userDetails_count = count($required_userDetails);
                }else{
                    $required_userDetails_count = 17;
                }
            }else if ($this->categoryParent->id == '6') {     
                // Massage Therapist
                $required_user = [$userProfileImage, $this->first_name, $this->mobile_no, $this->email, $this->gender];
                if (!empty($this->userDetails)) {
                $required_userDetails = [$this->userDetails->registration_no, $this->userDetails->registration_council,
                                    $this->userDetails->registration_year, $this->userDetails->clinic_name, 
                                    $this->userDetails->clinic_locality, $this->userDetails->total_experiance_year, $this->userDetails->dob,
                                    $this->userDetails->country, $this->userDetails->city, $this->userDetails->address, 
                                    !empty($this->userDetails->qualification_certificate) && (count(json_decode($this->userDetails->qualification_certificate)) > 0) ? $this->userDetails->qualification_certificate : '', 
                                    $this->userDetails->practicing_licence,
                                    $this->userDetails->about_us, $this->userDetails->clinic_consultation_charge, 
                                    $this->userDetails->home_consultation_charge];
                    $required_userDetails_count = count($required_userDetails);
                }else{
                    $required_userDetails_count = 15;
                }

            }else if ($this->categoryParent->id == '4') {             
                // Doctor
                $required_user = [$userProfileImage, $this->first_name, $this->mobile_no, $this->email, $this->subcategory_id, $this->gender];
                if (!empty($this->userDetails)) {
                $required_userDetails = [$this->userDetails->registration_no, $this->userDetails->registration_council,
                                    $this->userDetails->registration_year, $this->userDetails->clinic_name,
                                    $this->userDetails->clinic_locality, $this->userDetails->total_experiance_year, $this->userDetails->dob,
                                    $this->userDetails->country, $this->userDetails->city, $this->userDetails->address,
                                    !empty($this->userDetails->qualification_certificate) && (count(json_decode($this->userDetails->qualification_certificate)) > 0) ? $this->userDetails->qualification_certificate : '', 
                                    $this->userDetails->practicing_licence, 
                                    $this->userDetails->about_us, $this->userDetails->clinic_consultation_charge, 
                                    $this->userDetails->home_consultation_charge, $this->userDetails->video_consultation_charge];
                    $required_userDetails_count = count($required_userDetails);
                }else{
                    $required_userDetails_count = 16;
                }
            }else {               
                // Physiotherapy
                $required_user = [$userProfileImage, $this->first_name, $this->mobile_no, $this->email, $this->gender];
                if (!empty($this->userDetails)) {
                $required_userDetails = [$this->userDetails->registration_no, $this->userDetails->registration_council,
                                    $this->userDetails->registration_year, $this->userDetails->clinic_name, 
                                    $this->userDetails->clinic_locality, $this->userDetails->total_experiance_year, $this->userDetails->dob,
                                    $this->userDetails->country, $this->userDetails->city, $this->userDetails->address,
                                    !empty($this->userDetails->qualification_certificate) && (count(json_decode($this->userDetails->qualification_certificate)) > 0) ? $this->userDetails->qualification_certificate : '', 
                                    $this->userDetails->practicing_licence, 
                                    $this->userDetails->about_us, $this->userDetails->clinic_consultation_charge, 
                                    $this->userDetails->home_consultation_charge];
                    $required_userDetails_count = count($required_userDetails);
                }else{
                    $required_userDetails_count = 15;
                }
            }
        
        }else if(!empty($this->categoryParent) && $this->categoryParent->parent_id == '2'){
            //Medicine 
            $required_user = [$userProfileImage, $this->first_name, $this->mobile_no, $this->email];
            $required_userCounts = [$this->userAvailableTime];
            if (!empty($this->userDetails)) {
                $required_userDetails = [ $this->userDetails->registration_no, $this->userDetails->registration_council,
                                 $this->userDetails->registration_year, $this->userDetails->clinic_name, 
                                 $this->userDetails->clinic_locality, $this->userDetails->country, $this->userDetails->city,
                                 $this->userDetails->address, $this->userDetails->delivery_charge, $this->userDetails->practicing_licence,
                                 !empty($this->userDetails->qualification_certificate) && (count(json_decode($this->userDetails->qualification_certificate)) > 0) ? $this->userDetails->qualification_certificate : '', 
                                 $this->userDetails->about_us];
                $required_userDetails_count = count($required_userDetails);
            }else{
                $required_userDetails_count = 12;
            }
            
     
        }else if(!empty($this->categoryParent) && $this->categoryParent->parent_id == '3'){
            //Laboratories 
            $required_user = [$userProfileImage, $this->first_name, $this->mobile_no, $this->email];
            $required_userCounts = [$this->userAvailableTime, $this->userEduction];
            if (!empty($this->userDetails)) {
                $required_userDetails = [ $this->userDetails->registration_no, $this->userDetails->registration_council,
                                 $this->userDetails->registration_year, $this->userDetails->clinic_name, 
                                 $this->userDetails->clinic_locality, $this->userDetails->total_experiance_year, $this->userDetails->dob,
                                 $this->userDetails->country, $this->userDetails->city, $this->userDetails->address,
                                 !empty($this->userDetails->qualification_certificate) && (count(json_decode($this->userDetails->qualification_certificate)) > 0) ? $this->userDetails->qualification_certificate : '', 
                                 $this->userDetails->practicing_licence,
                                 $this->userDetails->about_us, $this->userDetails->home_consultation_charge];
               $required_userDetails_count = count($required_userDetails);
            }else{
                $required_userDetails_count = 14;
            }
           
      
        }else{
            //client 
            $required_userCounts = [$this->userLocation];
            $required_user = [$userProfileImage, $this->first_name, $this->mobile_no, $this->email, $this->gender];
            if (!empty($this->userDetails)) {
                 $required_userDetails = [$this->userDetails->dob, $this->userDetails->blood_group, $this->userDetails->marital_status,
                                 $this->userDetails->height, $this->userDetails->weight, $this->userDetails->emergency_contact,
                                 $this->userDetails->emergency_contact_name];
                $required_userDetails_count = count($required_userDetails);
            }else{
                $required_userDetails_count = 7;
            }
      
        }
        if(!empty($required_user) && count($required_user) > 0){
            foreach ($required_user as $key => $value) {
                if($key == '0') {
                    // $required_progress_array[] = $key;
                    if((strpos($this->profile_image, $imageStaticValue) !== false)){

                    }else{
                        if(!empty($value)){
                            $required_progress ++;
                        }
                    }
                }else if(isset($value) && ($value != '' || $value == '0')){
                    // $required_progress_array[] = $key;
                    $required_progress ++;
                }    
            }
        }

        if(!empty($this->userDetails) && !empty($required_userDetails) && count($required_userDetails) > 0){
            foreach ($required_userDetails as $key => $value) {
                if(!empty($this->userDetails) && isset($value) && ($value != '' || $value == '0')){
                    // $required_progress_array[] = $key;
                    $required_progress ++;
                }    
            }
        }

        if(!empty($required_userCounts) && count($required_userCounts) > 0){
            foreach ($required_userCounts as $key => $value) {
                if(!empty($value) && count($value) > 0){
                    // $required_progress_array[] = $key;
                    $required_progress ++;
                }    
            }
        }

        // dd($required_progress_array);
        $total_fields_count = (count($required_user) + count($required_userCounts) + $required_userDetails_count);
        // dd($required_progress.' '.(count($required_user) + count($required_userDetails) + count($required_userCounts)));
        if($total_fields_count > 0){
            $total_progress_point = ($required_progress * 100) / $total_fields_count;
        }else{
            $total_progress_point = 0;
        }
        // dd($required_progress.' '.(count($required_user) + count($required_userDetails)));
        $total_progress_point = round($total_progress_point);
        return $total_progress_point;
    }
    
    public function getProfileRequiredFieldsAttribute() {
        $imageStaticValue = "/admin/images/avatar.jpg";
        $userProfileImage = ((strpos($this->profile_image, $imageStaticValue) !== false)) ? '' : $this->profile_image;
        $total_progress_point = 0;
        $required_progress = 0;
        $required_progress_array = [];
        $required_progress_pending = [];
        $required_user = [];
        $required_userDetails = [];
        $required_userDetails_count = 0;
        $required_userCounts = [];
        if(!empty($this->categoryParent) && $this->categoryParent->parent_id == '1'){
            //Heathcare Provider
            $required_userCounts = [
                                    'Available Time'=>$this->userAvailableTime, 
                                    'Education Details'=>$this->userEduction
                                ];
            if ($this->categoryParent->id == '5') {     
                // nurses
                $required_user = [
                                  'Profile Image'=>$userProfileImage, 
                                  'User Name'=>$this->first_name, 
                                  'Mobile No.'=>$this->mobile_no, 
                                  'Email'=>$this->email, 
                                  'Subcategory Name'=>$this->subcategory_id, 
                                  'Gender'=>$this->gender
                                ];
                $required_userDetails = [
                                            'Registration Number'=> !empty($this->userDetails) ? $this->userDetails->registration_no : '', 
                                            'Registration Council'=> !empty($this->userDetails) ? $this->userDetails->registration_council : '', 
                                            'Registration Year'=> !empty($this->userDetails) ? $this->userDetails->registration_year : '', 
                                            'Clinic Name'=> !empty($this->userDetails) ? $this->userDetails->clinic_name : '', 
                                            'Clinic Locality'=> !empty($this->userDetails) ? $this->userDetails->clinic_locality : '', 
                                            'Years of Experience'=> !empty($this->userDetails) ? $this->userDetails->total_experiance_year : '', 
                                            'DOB'=>  !empty($this->userDetails) ? $this->userDetails->dob : '',
                                            'Country'=> !empty($this->userDetails) ? $this->userDetails->country : '', 
                                            'City'=> !empty($this->userDetails) ? $this->userDetails->city : '',
                                            'Address'=> !empty($this->userDetails) ? $this->userDetails->address : '',
                                            'Qualification Certificate'=> !empty($this->userDetails) && (count(json_decode($this->userDetails->qualification_certificate)) > 0) ? $this->userDetails->qualification_certificate : '',
                                            'Practicing Licence'=> !empty($this->userDetails) ? $this->userDetails->practicing_licence : '',
                                            'About Us'=>!empty($this->userDetails) ? $this->userDetails->about_us : '',
                                            'Clinic Consultation Fees (per Minute)'=> !empty($this->userDetails) ? $this->userDetails->clinic_consultation_charge : '', 
                                            'Home Consultation Fees (per Minute)'=> !empty($this->userDetails) ? $this->userDetails->home_consultation_charge : '',
                                            'Clinic Consultation Fees (per Day)'=> !empty($this->userDetails) ? $this->userDetails->nursing_facility_charge_full_day : '',
                                            'Home Consultation Fees (per Day)'=> !empty($this->userDetails) ? $this->userDetails->nursing_home_visit_charge_full_day : '',
                                        ];
                $required_userDetails_count = count($required_userDetails);
           
            }else if ($this->categoryParent->id == '6') {     
                // Massage Therapist
                $required_user = [
                                    'Profile Image'=>$userProfileImage, 
                                    'User Name'=>$this->first_name, 
                                    'Mobile No.'=>$this->mobile_no, 
                                    'Email'=>$this->email, 
                                    'Gender'=>$this->gender
                                ];
                $required_userDetails = [
                                            'Registration Number'=> !empty($this->userDetails) ? $this->userDetails->registration_no : '', 
                                            'Registration Council'=> !empty($this->userDetails) ? $this->userDetails->registration_council : '', 
                                            'Registration Year'=> !empty($this->userDetails) ? $this->userDetails->registration_year : '', 
                                            'Clinic Name'=> !empty($this->userDetails) ? $this->userDetails->clinic_name : '', 
                                            'Clinic Locality'=> !empty($this->userDetails) ? $this->userDetails->clinic_locality : '', 
                                            'Years of Experience'=> !empty($this->userDetails) ? $this->userDetails->total_experiance_year : '', 
                                            'DOB'=>  !empty($this->userDetails) ? $this->userDetails->dob : '',
                                            'Country'=> !empty($this->userDetails) ? $this->userDetails->country : '', 
                                            'City'=> !empty($this->userDetails) ? $this->userDetails->city : '',
                                            'Address'=> !empty($this->userDetails) ? $this->userDetails->address : '',
                                            'Qualification Certificate'=> !empty($this->userDetails) && (count(json_decode($this->userDetails->qualification_certificate)) > 0) ? $this->userDetails->qualification_certificate : '',
                                            'Practicing Licence'=> !empty($this->userDetails) ? $this->userDetails->practicing_licence : '',
                                            'About Us'=>!empty($this->userDetails) ? $this->userDetails->about_us : '',
                                            'Clinic Consultation Fees (per Minute)'=> !empty($this->userDetails) ? $this->userDetails->clinic_consultation_charge : '', 
                                            'Home Consultation Fees (per Minute)'=> !empty($this->userDetails) ? $this->userDetails->home_consultation_charge : '',
                                        ];
                $required_userDetails_count = count($required_userDetails);

            }else if ($this->categoryParent->id == '4') {             
                // Doctor
                $required_user = [
                                    'Profile Image'=>$userProfileImage, 
                                    'User Name'=>$this->first_name, 
                                    'Mobile No.'=>$this->mobile_no, 
                                    'Email'=>$this->email, 
                                    'Subcategory Name'=>$this->subcategory_id, 
                                    'Gender'=>$this->gender
                                ];
                    $required_userDetails = [
                                                'Registration Number'=> !empty($this->userDetails) ? $this->userDetails->registration_no : '', 
                                                'Registration Council'=> !empty($this->userDetails) ? $this->userDetails->registration_council : '', 
                                                'Registration Year'=> !empty($this->userDetails) ? $this->userDetails->registration_year : '', 
                                                'Clinic Name'=> !empty($this->userDetails) ? $this->userDetails->clinic_name : '', 
                                                'Clinic Locality'=> !empty($this->userDetails) ? $this->userDetails->clinic_locality : '', 
                                                'Years of Experience'=> !empty($this->userDetails) ? $this->userDetails->total_experiance_year : '', 
                                                'DOB'=>  !empty($this->userDetails) ? $this->userDetails->dob : '',
                                                'Country'=> !empty($this->userDetails) ? $this->userDetails->country : '', 
                                                'City'=> !empty($this->userDetails) ? $this->userDetails->city : '',
                                                'Address'=> !empty($this->userDetails) ? $this->userDetails->address : '',
                                                'Qualification Certificate'=> !empty($this->userDetails) && (count(json_decode($this->userDetails->qualification_certificate)) > 0) ? $this->userDetails->qualification_certificate : '',
                                                'Practicing Licence'=> !empty($this->userDetails) ? $this->userDetails->practicing_licence : '',
                                                'About Us'=>!empty($this->userDetails) ? $this->userDetails->about_us : '',
                                                'Clinic Consultation Fees (per Minute)'=> !empty($this->userDetails) ? $this->userDetails->clinic_consultation_charge : '', 
                                                'Home Consultation Fees (per Minute)'=> !empty($this->userDetails) ? $this->userDetails->home_consultation_charge : '',
                                                'Video Consultation Fees (per Minute)'=> !empty($this->userDetails) ? $this->userDetails->video_consultation_charge : '',
                                            ];
                    $required_userDetails_count = count($required_userDetails);

            }else {               
                // Physiotherapy
                $required_user =  [
                                    'Profile Image'=>$userProfileImage, 
                                    'User Name'=>$this->first_name, 
                                    'Mobile No.'=>$this->mobile_no, 
                                    'Email'=>$this->email, 
                                    'Gender'=>$this->gender
                                ];
                    $required_userDetails = [
                                                'Registration Number'=> !empty($this->userDetails) ? $this->userDetails->registration_no : '', 
                                                'Registration Council'=> !empty($this->userDetails) ? $this->userDetails->registration_council : '', 
                                                'Registration Year'=> !empty($this->userDetails) ? $this->userDetails->registration_year : '', 
                                                'Clinic Name'=> !empty($this->userDetails) ? $this->userDetails->clinic_name : '', 
                                                'Clinic Locality'=> !empty($this->userDetails) ? $this->userDetails->clinic_locality : '', 
                                                'Years of Experience'=> !empty($this->userDetails) ? $this->userDetails->total_experiance_year : '', 
                                                'DOB'=>  !empty($this->userDetails) ? $this->userDetails->dob : '',
                                                'Country'=> !empty($this->userDetails) ? $this->userDetails->country : '', 
                                                'City'=> !empty($this->userDetails) ? $this->userDetails->city : '',
                                                'Address'=> !empty($this->userDetails) ? $this->userDetails->address : '',
                                                'Qualification Certificate'=> !empty($this->userDetails) && (count(json_decode($this->userDetails->qualification_certificate)) > 0) ? $this->userDetails->qualification_certificate : '',
                                                'Practicing Licence'=> !empty($this->userDetails) ? $this->userDetails->practicing_licence : '',
                                                'About Us'=>!empty($this->userDetails) ? $this->userDetails->about_us : '',
                                                'Clinic Consultation Fees (per Minute)'=> !empty($this->userDetails) ? $this->userDetails->clinic_consultation_charge : '', 
                                                'Home Consultation Fees (per Minute)'=> !empty($this->userDetails) ? $this->userDetails->home_consultation_charge : '',
                                            ];
                    
                    $required_userDetails_count = count($required_userDetails);
            }
        
        }else if(!empty($this->categoryParent) && $this->categoryParent->parent_id == '2'){
            //Medicine 
            $required_user = [
                                'Profile Image'=>$userProfileImage, 
                                'User Name'=>$this->first_name, 
                                'Mobile No.'=>$this->mobile_no, 
                                'Email'=>$this->email, 
                            ];

            $required_userCounts = ['Available Time' => $this->userAvailableTime];
            $required_userDetails = [
                    'Registration Number'=> !empty($this->userDetails) ? $this->userDetails->registration_no : '', 
                    'Registration Council'=> !empty($this->userDetails) ? $this->userDetails->registration_council : '', 
                    'Registration Year'=> !empty($this->userDetails) ? $this->userDetails->registration_year : '', 
                    'Pharmacy Name'=> !empty($this->userDetails) ? $this->userDetails->clinic_name : '', 
                    'Pharmacy Locality'=> !empty($this->userDetails) ? $this->userDetails->clinic_locality : '', 
                    'Country'=> !empty($this->userDetails) ? $this->userDetails->country : '', 
                    'City'=> !empty($this->userDetails) ? $this->userDetails->city : '',
                    'Address'=> !empty($this->userDetails) ? $this->userDetails->address : '',
                    'Delivery Charge'=> !empty($this->userDetails) ? $this->userDetails->delivery_charge : '',
                    'Qualification Certificate'=> !empty($this->userDetails) && (count(json_decode($this->userDetails->qualification_certificate)) > 0) ? $this->userDetails->qualification_certificate : '',
                    'Practicing Licence'=> !empty($this->userDetails) ? $this->userDetails->practicing_licence : '',
                    'About Us'=>!empty($this->userDetails) ? $this->userDetails->about_us : '',
                ];
                $required_userDetails_count = count($required_userDetails);
     
        }else if(!empty($this->categoryParent) && $this->categoryParent->parent_id == '3'){
            //Laboratories 
            $required_user = [
                                'Profile Image'=>$userProfileImage, 
                                'User Name'=>$this->first_name, 
                                'Mobile No.'=>$this->mobile_no, 
                                'Email'=>$this->email, 
                            ];
            $required_userCounts = [
                                        'Available Time'=>$this->userAvailableTime, 
                                        'Education Details'=>$this->userEduction
                                    ];

            $required_userDetails = [
                                        'Registration Number'=> !empty($this->userDetails) ? $this->userDetails->registration_no : '', 
                                        'Registration Council'=> !empty($this->userDetails) ? $this->userDetails->registration_council : '', 
                                        'Registration Year'=> !empty($this->userDetails) ? $this->userDetails->registration_year : '', 
                                        'Laboratory Name'=> !empty($this->userDetails) ? $this->userDetails->clinic_name : '', 
                                        'Laboratory Locality'=> !empty($this->userDetails) ? $this->userDetails->clinic_locality : '',
                                        'Years of Experience'=>!empty($this->userDetails) ? $this->userDetails->total_experiance_year : '',
                                        'DOB'=> !empty($this->userDetails) ? $this->userDetails->dob : '',
                                        'Country'=> !empty($this->userDetails) ? $this->userDetails->country : '', 
                                        'City'=> !empty($this->userDetails) ? $this->userDetails->city : '',
                                        'Address'=> !empty($this->userDetails) ? $this->userDetails->address : '',
                                        'Home Visit Charge'=> !empty($this->userDetails) ? $this->userDetails->home_consultation_charge : '',
                                        'Qualification Certificate'=> !empty($this->userDetails) && (count(json_decode($this->userDetails->qualification_certificate)) > 0) ? $this->userDetails->qualification_certificate : '',
                                        'Practicing Licence'=> !empty($this->userDetails) ? $this->userDetails->practicing_licence : '',
                                        'About Us'=>!empty($this->userDetails) ? $this->userDetails->about_us : '',
                                    ];
               $required_userDetails_count = count($required_userDetails);           
      
        }else{
            //client 
            $required_userCounts = ['User Location'=>$this->userLocation];
            $required_user = [
                                'Profile Image'=>$userProfileImage, 
                                'User Name'=>$this->first_name, 
                                'Mobile No.'=>$this->mobile_no, 
                                'Email'=>$this->email, 
                                'Gender'=>$this->gender
                            ];
            $required_userDetails = [
                                        'Date of Birth'=> !empty($this->userDetails) ? $this->userDetails->dob : '',
                                        'Blood Group'=> !empty($this->userDetails) ? $this->userDetails->blood_group : '',
                                        'Marital Status'=> !empty($this->userDetails) ? $this->userDetails->marital_status : '',
                                        'Height'=>!empty($this->userDetails) ? $this->userDetails->height : '',
                                        'Weight'=>!empty($this->userDetails) ? $this->userDetails->weight : '',
                                        'Emergency Contact Name'=>!empty($this->userDetails) ? $this->userDetails->emergency_contact_name : '',
                                        'Emergency Contact'=>!empty($this->userDetails) ? $this->userDetails->emergency_contact : '',
                                    ];
            $required_userDetails_count = count($required_userDetails);
            
      
        }
        if(!empty($required_user) && count($required_user) > 0){
            foreach ($required_user as $key => $value) {   
                $required_progress_pending[] = $key;
                if($key == 'Profile Image') {
                    $required_progress_array[] = $key;
                    if((strpos($this->profile_image, $imageStaticValue) !== false)){

                    }else{
                        if(!empty($value)){
                            $required_progress ++;
                        }
                    }
   
                }else if(isset($value) && ($value != '' || $value == '0')){
                    $required_progress_array[] = $key;
                    $required_progress ++;
                }    
            }
        }

        if(!empty($required_userDetails) && count($required_userDetails) > 0){
            foreach ($required_userDetails as $key => $value) {
                $required_progress_pending[] = $key;
                if(!empty($this->userDetails) && isset($value) && ($value != '' || $value == '0')){
                    $required_progress_array[] = $key;
                    $required_progress ++;
                }    
            }
        }

        if(!empty($required_userCounts) && count($required_userCounts) > 0){
            foreach ($required_userCounts as $key => $value) {                
                $required_progress_pending[] = $key;
                if(!empty($value) && count($value) > 0){
                    $required_progress_array[] = $key;
                    $required_progress ++;
                }    
            }
        }
        // dd($required_progress_array, $required_progress_pending);
        $required_fields_array = [];
        foreach ($required_progress_pending as $key => $value) {
            if(!in_array($value, $required_progress_array)){
                $required_fields_array[] = $value;
            }
        }
        $total_fields_count = (count($required_user) + count($required_userCounts) + $required_userDetails_count);
        // dd($required_progress.' '.(count($required_user) + count($required_userDetails) + count($required_userCounts)));
        if($total_fields_count > 0){
            $total_progress_point = ($required_progress * 100) / $total_fields_count;
        }else{
            $total_progress_point = 0;
        }
        // dd($required_progress.' '.(count($required_user) + count($required_userDetails)));
        $total_progress_point = round($total_progress_point);
        return $required_fields_array;
    }



}
