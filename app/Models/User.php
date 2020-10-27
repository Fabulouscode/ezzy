<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use Carbon\Carbon;

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

    protected $appends = ['user_completed_appointment', 'user_cancelled_appointment', 'user_pending_appointment',
                          'client_completed_appointment', 'client_cancelled_appointment', 'client_pending_appointment',
                          'monthly_wallet_balance','total_wallet_balance'
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

    public function getMonthlyWalletBalanceAttribute(){
        $total_earning =  $credit_balance = $debit_balance  = 0;
        $credit_balance = $this->hasOne('App\Models\Credit_transaction','user_id','id')
                               ->where('status', '0')->whereMonth('transaction_date', Carbon::now()->format('m'))->sum('credit'); 
        $debit_balance = $this->hasOne('App\Models\Debit_transaction','user_id','id')
                               ->where('status', '0')->whereMonth('transaction_date', Carbon::now()->format('m'))->sum('debit');  
        $total_earning = $debit_balance - $credit_balance;      
        return $total_earning;

    }
   
    public function getTotalWalletBalanceAttribute(){
        $total_earning =  $credit_balance = $debit_balance  = 0;
        $credit_balance = $this->hasOne('App\Models\Credit_transaction','user_id','id')
                               ->where('status', '0')->sum('credit'); 
        $debit_balance = $this->hasOne('App\Models\Debit_transaction','user_id','id')
                               ->where('status', '0')->sum('debit');  
        $total_earning = $debit_balance - $credit_balance;      
        return $total_earning;

    }

    public function getUserCompletedAppointmentAttribute(){
        return $this->hasOne('App\Models\Appointment','user_id','id')
                    ->where('status', '5')->count('*');        
    }

    public function getUserCancelledAppointmentAttribute(){
        return $this->hasOne('App\Models\Appointment','user_id','id')
                    ->where('status', '6')->count('*');     
    }
    
    public function getUserPendingAppointmentAttribute(){
        return $this->hasOne('App\Models\Appointment','user_id','id')
                    ->whereIn('status', ['1','2'])->count('*');     
    }

    public function getClientCompletedAppointmentAttribute(){
        return $this->hasOne('App\Models\Appointment','client_id','id')
                    ->where('status', '5')->count('*');    
    }

    public function getClientCancelledAppointmentAttribute(){
        return $this->hasOne('App\Models\Appointment','client_id','id')
                    ->where('status', '6')->count('*');    
    }
    
    public function getClientPendingAppointmentAttribute(){
        return $this->hasOne('App\Models\Appointment','client_id','id')
                    ->whereIn('status', ['1','2'])->count('*');
    }
}
