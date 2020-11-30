<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory, SoftDeletes;

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


    public function getSender() {
        return $this->belongsTo('App\Models\User', 'sender_id','id');
    }

    public function getReceiver() {
        return $this->belongsTo('App\Models\User', 'receiver_id','id');
    }
}
