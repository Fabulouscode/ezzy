<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Support_request extends Model
{
    use HasFactory, SoftDeletes;
    
    public $status_value = array(
        '0' => 'Pending',
        '1' => 'Success',
        '2' => 'Cancel',
        '3' => 'Close',
        '4' => 'User waiting for reply',
        '5' => 'User reply',
    );

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'attachment',
        'comment',
        'closed_date',
        'closed_by',
        'admin_id',
        'status',
    ];

    protected $appends = ['status_name','support_request_no_generate'];

    public function getStatusNameAttribute() {
        return array_key_exists($this->status, $this->status_value) ? $this->status_value[$this->status]: '';
    }

    public function userDetails() {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function getAttachmentAttribute($value) {
        return !empty($value) ?  url('storage/'.$value) : asset('/admin/images/avatar.jpg');
    }

    public function getSupportRequestNoGenerateAttribute(){
       return str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }
}
