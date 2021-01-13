<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory, SoftDeletes;

    public $notification_type = array(
        '0' => 'General Notification',
        '1' => 'Appointment Request',
        '2' => 'Appointmnent Status',
        '3' => 'Appointmnent Payment Completed',
        '4' => 'Order Placed',
        '5' => 'Order Status',
        '6' => 'Order Payment Completed'
    );
 
    public $notification_topic = array(
        '1' => 'Patient',
        '4' => 'Doctor',
        '5' => 'Nurses',
        '6' => 'Massage-therapist',
        '7' => 'Pharmacist',
        '8' => 'Scientists',
        '9' => 'Pathologist',
        '10' => 'Radiologist',
    );

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'title',
        'message',
        'parameter',
        'msg_type',
        'read',
    ];

    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'parameter',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = ['msg_type_name'];

    public function getMsgTypeNameAttribute() {
        return array_key_exists($this->msg_type, $this->notification_type) ? $this->notification_type[$this->msg_type]: '';
    }

    public function getSender() {
        return $this->belongsTo('App\Models\User', 'sender_id','id');
    }

    public function getReceiver() {
        return $this->belongsTo('App\Models\User', 'receiver_id','id');
    }
}
