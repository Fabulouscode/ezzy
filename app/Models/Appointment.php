<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon as Carbon;
use App\Models\Manage_fees;

class Appointment extends Model
{
    use HasFactory,SoftDeletes;

    public $status_value = array(
        '0' => 'Pending',
        '1' => 'Upcoming',
        '2' => 'In progress',
        '3' => 'Paid',
        '4' => 'Unpaid',
        '5' => 'Completed',
        '6' => 'Cancelled'
    );

    public $appointment_type_value = array(
        '0' => 'Clinic Consultation',
        '1' => 'Home Consultation',
        '2' => 'Video Consultation'
    );

    public $nurse_appointment_type_value = array(
        '0' => 'Nursing Facility',
        '1' => 'Home Visit'
    );
  
    public $laboratory_appointment_type_value = array(
        '0' => 'Visit Laboratory',
        '1' => 'Home Visit'
    );

    public $massage_appointment_type_value = array(
        '0' => 'Care Facility',
        '1' => 'Home Visit'
    );
   
    public $gender_value = array(
        '0' => 'Male',
        '1' => 'Female',
    );
    


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'client_id',
        'appointment_type',
        'urgent',
        'name',
        'email',
        'mobile_no',
        'age',
        'gender',
        'reason',
        'appointment_date',
        'appointment_time',
        'appointment_price',
        'otp_code',
        'cancel_reason',
        'cancel_date',
        'cancel_user_id',
        'status',
        'transaction_id',
        'consult_notes',
        'user_rating',
        'user_review',
        'user_service_id',
        'completed_datetime',
        'voucher_code_id',
        'voucher_amount',
        'hcp_fees',
        'home_visit_fees',
        'total_charge',
        'full_day',
        'address',
        'city',
        'country',
        'my_appointment',
        'video_start_time',
        'video_end_time',
        'longitude',
        'latitude',
        'accepted_date',
        'appointment_end_date',
        'appointment_end_time',
        'start_datetime'
    ];

    protected $appends = ['invoice_no_generate','start_to_end_time_diff','start_to_end_time_diff_format','status_name','gender_name','appointment_type_name','urgent_appointment_book_charges'];
   
    protected $hidden = ['start_to_end_time_diff_format'];

    public function getStatusNameAttribute() {
        if($this->status == '1'){
            $current_time  =  Carbon::now();
            // $current_time = $current_time->addMinute(1);
            if(!empty($this->appointment_end_date) && !empty($this->appointment_end_time) && $current_time > $this->appointment_end_date.' '.$this->appointment_end_time){
                return array_key_exists($this->status, $this->status_value) ? 'Elapsed': '';
            }else{
                return array_key_exists($this->status, $this->status_value) ? $this->status_value[$this->status]: '';
            }
        }else{
             return array_key_exists($this->status, $this->status_value) ? $this->status_value[$this->status]: '';
        }
       
    }
   
    public function getGenderNameAttribute() {
        return array_key_exists($this->gender, $this->gender_value) ? $this->gender_value[$this->gender]: '';
    }

    public function getAppointmentTypeNameAttribute() {
        if(!empty($this->user->categoryParent) && $this->user->categoryParent->id == '5'){
            return array_key_exists($this->appointment_type, $this->nurse_appointment_type_value) ? $this->nurse_appointment_type_value[$this->appointment_type]: '';
        }else if(!empty($this->user->categoryParent) && $this->user->categoryParent->id == '6'){
            return array_key_exists($this->appointment_type, $this->massage_appointment_type_value) ? $this->massage_appointment_type_value[$this->appointment_type]: '';
        }else if(!empty($this->user->categoryParent) && $this->user->categoryParent->parent_id == '3'){
            return array_key_exists($this->appointment_type, $this->laboratory_appointment_type_value) ? $this->laboratory_appointment_type_value[$this->appointment_type]: '';
        }else{
            return array_key_exists($this->appointment_type, $this->appointment_type_value) ? $this->appointment_type_value[$this->appointment_type]: '';
        }
    }

    public function user() {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function client() {
        return $this->hasOne('App\Models\User', 'id', 'client_id');
    }
    
    public function cancelUser() {
        return $this->hasOne('App\Models\User', 'id', 'cancel_user_id');
    }

    public function userService() {
        return $this->hasOne('App\Models\User_services', 'id','user_service_id');
    }
  
    public function appointmentServices() {
        return $this->hasMany('App\Models\Appointment_services', 'appointment_id','id');
    }

    public function getTransaction() {
        return $this->hasOne('App\Models\User_transaction', 'id', 'transaction_id');
    }

    public function getInvoiceNoGenerateAttribute(){
       return 'INV-'.str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    public function voucherDetails() {
        return $this->hasOne('App\Models\Voucher_code', 'id', 'voucher_code_id');
    }
  
    public function getUrgentAppointmentBookChargesAttribute() {
        if($this->urgent == '1') {
            return Manage_fees::where('fees_key','urgent_booking_charges')->pluck('fees_percentage')->first();
        }else{
            return 0;
        }
    }
  
    public function getStartToEndTimeDiffAttribute(){
        $appointment_timing = '0';
        if(!empty($this->start_datetime) && !empty($this->completed_datetime)){
            $start_appointment  = new Carbon($this->start_datetime);
            $end_appointment   = new Carbon($this->completed_datetime);
            $appointment_timing =  $start_appointment->diffInSeconds($end_appointment);
            $appointment_timing = $appointment_timing / 60;
        }
       return $appointment_timing;
    }
  
    public function getStartToEndTimeDiffFormatAttribute(){
        $appointment_timing = '0';
        if(!empty($this->start_datetime) && !empty($this->completed_datetime)){
            $start_appointment  = new Carbon($this->start_datetime);
            $end_appointment   = new Carbon($this->completed_datetime);
            $appointment_timing =  $start_appointment->diffInSeconds($end_appointment);
            $appointment_timing = gmdate('H:i:s', $appointment_timing);
        }
       return $appointment_timing;
    }

    public function format(){
        return [
            'id'=>$this->id,
            'urgent'=> !empty($this->urgent) ? $this->urgent : 0,
            'appointment_type'=>$this->appointment_type,
            'my_appointment'=>$this->my_appointment,
            'appointment_type_name'=>$this->appointment_type_name,
            'appointment_date'=>$this->appointment_date,
            'appointment_time'=>$this->appointment_time,            
            'appointment_end_date'=>$this->appointment_end_date,
            'appointment_end_time'=>$this->appointment_end_time,
            'start_datetime'=>$this->start_datetime,
            'completed_datetime'=>$this->completed_datetime,
            'appointment_price'=>$this->appointment_price,
            'home_visit_fees'=>$this->home_visit_fees,
            'urgent_appointment_book_charges'=>$this->urgent_appointment_book_charges,
            'total_charge'=>$this->total_charge,
            'invoice_no_generate'=>$this->invoice_no_generate,
            'user_rating'=>$this->user_rating,
            'user_review'=>$this->user_review,
            'name'=>$this->name,
            'email'=>$this->email,
            'mobile_no'=>$this->mobile_no,
            'gender'=>$this->gender,
            'gender_name'=>$this->gender_name,
            'age'=>$this->age,
            'reason'=>$this->reason,
            'full_day'=>$this->full_day,
            'address'=>$this->address,
            'latitude'=>(!empty($this->client)) ? $this->client->current_latitude : '',
            'longitude'=>(!empty($this->client)) ? $this->client->current_longitude : '',
            'urgent_fees'=>  (!empty($this->home_visit_fees)) ? strval($this->home_visit_fees) : '0',
            'clinic_consultation_charge'=> (!empty($this->hcp_fees)) ? $this->hcp_fees : 0.0,
            'home_consultation_charge'=> (!empty($this->hcp_fees)) ? $this->hcp_fees : 0.0,
            'video_consultation_charge'=> (!empty($this->hcp_fees)) ? $this->hcp_fees : 0.0,
            'appointment_services'=>(!empty($this->appointmentServices))? $this->serviceDetailsformat($this->appointmentServices) :'',
            'client'=>(isset($this->client))?
                            [
                                'id'=>$this->client->id,
                                'user_name'=>$this->client->user_name,
                                'profile_image'=>$this->client->profile_image,
                                'address'=>(!empty($this->client->userPrimaryAddress)) ? $this->client->userPrimaryAddress->address : '',
                                'latitude'=>(!empty($this->client)) ? $this->client->current_latitude : '',
                                'longitude'=>(!empty($this->client)) ? $this->client->current_longitude : '',
                            ]:'',
            'user'=>(isset($this->user))?
                            [
                                'id'=>$this->user->id,
                                'user_name'=>$this->user->user_name,
                                'profile_image'=>$this->user->profile_image,
                                'category_id' =>$this->user->category_id,
                                'category_name' => (!empty($this->user->categoryParent)) ? $this->user->categoryParent->name : '',
                                'subcategory_name' => (!empty($this->user->categoryChild)) ? $this->user->categoryChild->name : '',
                                'address'=>(!empty($this->user->userDetails)) ? $this->user->userDetails->clinic_locality : '',
                                'eduction_details'=>(!empty($this->user->user_eduction_details)) ? $this->user->user_eduction_details : '',
                                'latitude'=>(!empty($this->user)) ? $this->user->latitude : '',
                                'longitude'=>(!empty($this->user)) ? $this->user->longitude : '',
                            ]:'',
            'status'=>$this->status,
            'status_name'=>$this->status_name,
        ];
    }

    public function serviceDetailsformat($appointmentServices){
        $data = [];
        if(!empty($appointmentServices)){
            foreach ($appointmentServices as $key => $value) {
                $data[]=[
                    "id"=> !empty($value->userService) ? $value->id : '',
                    "service_id"=> !empty($value->userService) ? $value->userService->id : '',
                    "service_charge"=> !empty($value->userService) ? $value->userService->service_charge : '',
                    "service_name"=>!empty($value->userService) && !empty($value->userService->service) ? $value->userService->service->service_name : '',
               ];
            }
        }
        return $data;
    }
}
