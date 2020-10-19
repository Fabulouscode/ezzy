<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'subcategory_id',
        'ezzycare_card',
        'mobile_verified_at',
        'first_name',
        'last_name',
        'email',
        'country_code',
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
    
    public function userAvailableTime() {
        return $this->hasMany('App\Models\User_available_time');
    }
    
    public function userReview() {
        return $this->hasMany('App\Models\User_review');
    }
}
