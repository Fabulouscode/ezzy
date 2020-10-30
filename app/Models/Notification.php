<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory, SoftDeletes;

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
