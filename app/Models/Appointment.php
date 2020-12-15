<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon as Carbon;

class Appointment extends Model
{
    use HasFactory,SoftDeletes;

    public $status_value = array(
        '0' => 'Pending',
        '1' => 'Upcoming',
        '2' => 'in_progress',
        '3' => 'Paid',
        '4' => 'Unpaid',
        '5' => 'Completed',
        '6' => 'Cancel'
    );

    public $appointment_type_value = array(
        '0' => 'Clinic Care',
        '1' => 'Home Care',
        '2' => 'Video Call'
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
        'full_day'
    ];

    protected $appends = ['invoice_no_generate','start_to_end_time_diff','status_name','appointment_type_name'];

    public function getStatusNameAttribute() {
        return array_key_exists($this->status, $this->status_value) ? $this->status_value[$this->status]: '';
    }

    public function getAppointmentTypeNameAttribute() {
        return array_key_exists($this->appointment_type, $this->appointment_type_value) ? $this->appointment_type_value[$this->appointment_type]: '';
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
  
    public function getStartToEndTimeDiffAttribute(){
        $appointment_timing = '0';
        if(!empty($this->appointment_date) && !empty($this->appointment_date) && $this->appointment_date){
            $start_appointment  = new Carbon($this->appointment_date.''.$this->appointment_time);
            $end_appointment   = new Carbon($this->completed_datetime);
            $appointment_timing =  $start_appointment->diffInMinutes($end_appointment);
        }
       return $appointment_timing;
    }

    public function format(){
        return [
            'id'=>$this->id,
            'urgent'=> !empty($this->urgent) ? $this->urgent : 0,
            'appointment_type'=>$this->appointment_type,
            'appointment_type_name'=>$this->appointment_type_name,
            'appointment_date'=>$this->appointment_date,
            'appointment_time'=>$this->appointment_time,
            'completed_datetime'=>$this->completed_datetime,
            'appointment_price'=>$this->appointment_price,
            'home_visit_fees'=>$this->home_visit_fees,
            'invoice_no_generate'=>$this->invoice_no_generate,
            'user_rating'=>$this->user_rating,
            'user_review'=>$this->user_review,
            'mobile_no'=>$this->mobile_no,
            'email'=>$this->email,
            'reason'=>$this->reason,
            'client'=>(isset($this->client))?
                            [
                                'id'=>$this->client->id,
                                'user_name'=>$this->client->user_name,
                                'profile_image'=>$this->client->profile_image
                            ]:'',
            'user'=>(isset($this->user))?
                            [
                                'id'=>$this->user->id,
                                'user_name'=>$this->user->user_name,
                                'profile_image'=>$this->user->profile_image
                            ]:'',
            'status'=>$this->status,
            'status_name'=>$this->status_name,
        ];
    }
}
