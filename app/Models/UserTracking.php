<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTracking extends Model
{
    use HasFactory;
    protected $guard = 'admin';

    protected $fillable = [
        'user_type','admin_id' ,'user_id', 'field_name', 'field_value'
    ];

    public function user() {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function admin() {
        return $this->hasOne('App\Models\Admin', 'id', 'admin_id');
    }
}
