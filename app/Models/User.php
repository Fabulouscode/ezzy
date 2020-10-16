<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'subcategory_id',
        'eazzycare_card',
        'first_name',
        'last_name',
        'email',
        'mobile_no',
        'gender',
        'password',
        'profile_image',
        'otp_code',
        'device_type',
        'device_uuid',
        'device_token',
        'social_type',
        'facebook_id',
        'google_id',
        'apple_id',
        'latitude',
        'longitude',
        'wallet_balance',
        'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'mobile_verified_at' => 'datetime',
    ];


    public function categoryParent() {
        return $this->hasOne('App\Models\Category', 'id', 'category_id');
    }

    public function categoryChild() {
        return $this->hasOne('App\Models\Category', 'id', 'subcategory_id');
    }
    
    public function userDetails() {
        return $this->hasOne('App\Models\User_details');
    }
    
    public function userEduction() {
        return $this->hasMany('App\Models\User_education');
    }

    public function userExperiance() {
        return $this->hasMany('App\Models\User_experiance');
    }

    public function userBankAccount() {
        return $this->hasMany('App\Models\User_bank_account');
    }
}
