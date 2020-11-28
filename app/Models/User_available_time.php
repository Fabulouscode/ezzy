<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_available_time extends Model
{
    use HasFactory;

    public $day_value = array(
        '0'=>'Sunday', 
        '1'=>'Monday', 
        '2'=>'Tuesday', 
        '3'=>'Wednesday', 
        '4'=>'Thursday', 
        '5'=>'Friday', 
        '6'=>'Saturday'
    );
    
    public $appointment_type_value = array(
        '0' => 'In Clinic',
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
        'day',
        'appointment_type',
        'start_time',
        'end_time',
    ];
    
    protected $appends = ['day_name','appointment_type_name'];

    public function getDayNameAttribute() {
        return array_key_exists($this->day, $this->day_value) ? $this->day_value[$this->day]: '';
    }
    
    public function getAppointmentTypeNameAttribute() {
        return array_key_exists($this->appointment_type, $this->appointment_type_value) ? $this->appointment_type_value[$this->appointment_type]: '';
    }

}
