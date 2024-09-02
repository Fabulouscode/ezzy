<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Traits\HasPermissionsTrait;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable, HasPermissionsTrait;

    protected $guard = 'admin';

    protected $fillable = [
        'name', 'email', 'password', 'role_id', 'timezone', 'status'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getRole(){
        return $this->belongsTo('App\Models\Role');
    }
    
    public function getAvatarAttribute(){
       return asset('/admin/images/avatar.jpg');
    }


}
