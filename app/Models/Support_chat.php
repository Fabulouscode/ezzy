<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Support_chat extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'support_request_id',
        'user_id',
        'admin_id',
        'message',
    ];
    
    public function supportTicket() {
        return $this->hasOne('App\Models\Support_request', 'id', 'support_request_id');
    }
    
    public function user() {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
    
    public function admin() {
        return $this->hasOne('App\Models\Admin', 'id', 'admin_id');
    }

    public function getMessageAttribute($value) {
        return !empty($value) ?  json_decode($value) : '';
    }

     public function format(){
        return [
            'id'=>$this->id,
            'message'=>$this->message,
            'is_admin'=> (!empty($this->admin_id)) ? 1 : 0,
            'created_at'=>$this->created_at,
        ];
     }
}
