<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Repositories\UserRepository;
use App\Repositories\UserDetailsRepository;
use App\Repositories\NotificationRepository;
use App\Http\Requests\Api\Auth\UserAuthRequest;
use App\Http\Requests\Api\Auth\UserLoginRequest;
use App\Http\Requests\Api\Auth\UserResendSMSRequest;
use App\Http\Requests\Api\Auth\UserVerifySMSRequest;
use App\Http\Requests\Api\Auth\UserForgetPasswordRequest;
use App\Http\Requests\Api\Auth\UserRecoverPasswordRequest;
use App\Http\Requests\Api\Auth\UserProviderAuthRequest;
use App\Http\Requests\Api\Auth\PasswordChangeRequest;
use App\Http\Requests\Api\Auth\SocialLoginRequest;
use App\Http\Requests\Api\Auth\UserRegisterMobileRequest;
use Carbon\Carbon as Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\AppSetting;
use App\Models\OtpDetails;
use Helper;

class UserAuthController extends BaseApiController
{

    private $user_repo, $notification_repo, $user_details_repo;

    public function __construct(
        UserRepository $user_repo,
        UserDetailsRepository $user_details_repo, 
        NotificationRepository $notification_repo
        )
    {
        parent::__construct();
        $this->user_repo = $user_repo;
        $this->notification_repo = $notification_repo;
        $this->user_details_repo = $user_details_repo;
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveRegisterwithMobile(UserRegisterMobileRequest $request)
    {

        // if (!empty($request->g_recaptcha_response)) {
        //     //your site secret key
        //     $secret = config('app.GOOGLE_RECAPTCH_SECRET_KEY');
        //     //get verify response data
        //     $verifyResponse = file_get_contents(config('app.GOOGLE_RECAPTCH_URL') . '?secret=' . $secret . '&response=' . $request->g_recaptcha_response);
        //     $responseData = json_decode($verifyResponse);
        //     \Log::info('google verify data');
        //     \Log::info(json_encode($responseData));
        //     if ($responseData->success) {
        //         //contact form submission code goes here
        //     } else {
        //         return self::sendError('', 'Robot verification failed, please try again.');
        //     }
        // }else{
        //     return self::sendError('', 'Currently, the registration feature is temporarily disabled. Please wait for a few days.'); 
        // }
       
        if (!empty($request->header('device_type')) && !empty($request->header('device_id'))) {
            $otpDetails = OtpDetails::where('device_type',$request->header('device_type'))->where('device_id',$request->header('device_id'))->whereDate('start_date_time',Carbon::now()->format('Y-m-d'))->count();
            if(!empty($otpDetails) && $otpDetails >= 3){
                return self::sendError('', 'I am sorry, it appears you have exceeded the OTP request limit for today, please try again after 24 hrs.');
            }
        }else{
            return self::sendError('', 'Currently, the registration feature is temporarily disabled. Please wait for a few days.'); 
        }

        $registerStop = AppSetting::where('key_name', 'registration_start')->first();
        if(isset($registerStop) && isset($registerStop->value_txt) && $registerStop->value_txt == 0){             
            return self::sendError('', 'For the time being registration is closed. Please try later.');
        }

        $user = $this->user_repo->checkbyMobileNo($request);
        if (!empty($request->email)) {
            $user_email = $this->user_repo->checkbyEmailId($request);
            if (!empty($user_email)) {
                return self::sendError('', 'Email ID Already Registered.');
            }
        }

        if (!empty($request->country_code)) {
            $restracted = Helper::countryCodeRestriction($request->country_code);
            if(isset($restracted) && $restracted == true){
                return self::sendError('', 'You have not access.');
            }
            if(!empty($request->ip())){
                $request->merge(['user_ip' => $request->ip()]); 
                $userIpCheck = $this->user_repo->getIpRegisterdAndLoginIp($request->ip());
                if(!empty($userIpCheck) && $userIpCheck > 3){
                    return self::sendError('', 'You have not access.');
                }
            }
        }



        if(empty($user)){
            try{
                DB::beginTransaction();
                $mobile_code = $this->user_repo->generateOTPCode();
                $data = ['otp_code' => $mobile_code];
                $request->otp_code = $mobile_code;
                $request->status = '3';
                $message = 'Your OTP for ['.config('app.name').'] is: '.$mobile_code;

                try{
                    $currentDateTime = Carbon::now();
                    $TwoMinutesAfter = $currentDateTime->addMinutes(2)->format('Y-m-d H:i:s');
                    OtpDetails::create([
                        'device_type' => !empty($request->header('device_type')) ? $request->header('device_type') : null,
                        'device_id' => !empty($request->header('device_id')) ? $request->header('device_id') : null,
                        'country_code' => !empty($request->country_code) ? $request->country_code : null,
                        'mobile' => !empty($request->mobile_no) ? $request->mobile_no : null,
                        'email' => !empty($request->email) ? $request->email : null,
                        'otp' => $mobile_code,
                        'start_date_time' => Carbon::now()->format('Y-m-d H:i:s'),
                        'expiry_date_time' => $TwoMinutesAfter,
                    ]);
                }catch(\Exception $e){
                    return self::sendError('', 'SMS Sending Failed');
                } 

                try{
                    $sent_msg = $this->user_repo->sendMessage($message, $request->country_code.$request->mobile_no); 
                }catch(\Exception $e){
                    return self::sendError('', 'SMS Sending Failed');
                }                
                if(!empty($sent_msg)){
                    return self::sendError('', 'SMS Sending Failed');
                }
                $user = $this->user_repo->registerWithMobileno($request);
                if(!empty($user) && !empty($user->id)){
                    $this->user_details_repo->dataCrudByArray(['user_id' => $user->id, 'urgent'=>'1', 'urgent_criteria'=>'0,1,2'], $user->id);
                }
                DB::commit();
                return self::sendSuccess([
                    'token' => $user->createToken('EzzyCare')->accessToken,
                    'data' => $data,
                ]);
            }catch(\Exception $e){
                DB::rollBack();
                return self::sendException($e);
            }
        } else{
             return self::sendError('', 'Mobile No. Already Registered.');
        }

    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveRegisterPatient(UserAuthRequest $request)
    {
        $registerStop = AppSetting::where('key_name', 'registration_start')->first();
        if(isset($registerStop) && isset($registerStop->value_txt) && $registerStop->value_txt == 0){             
            return self::sendError('', 'For the time being registration is closed. Please try later.');
        }

        $user = $this->user_repo->checkbyMobileNoVerify($request);   

        if (!empty($request->country_code)) {
            $restracted = Helper::countryCodeRestriction($request->country_code);
            if(isset($restracted) && $restracted == true){
                return self::sendError('', 'You have not access.');
            }
            if(!empty($request->ip())){
                $request->merge(['user_ip' => $request->ip()]); 
                $userIpCheck = $this->user_repo->getIpRegisterdAndLoginIp($request->ip());
                if(!empty($userIpCheck) && $userIpCheck > 3){
                    return self::sendError('', 'You have not access.');
                }
            }
        }

        if(!empty($user)){      
            try{
                DB::beginTransaction();
                $this->user_repo->registerWithRestore($request);
                $user = $this->user_repo->getbyMobileNo($request);           
                $this->user_repo->removeOauthAccessTokens($user->id); 
                if(!empty($user) && !empty($user->id)){
                    $this->user_details_repo->dataCrudByArray(['user_id' => $user->id], $user->id);
                }
               
                if(!empty($request->env)){

                }else{
                    $notification_topic = $this->notification_repo->getNotificationTopic();
                    if(!empty($user->device_token)){                    
                        $this->notification_repo->subscribeNotificationTopic($user->device_token, 'Ezzycare');
                        $this->notification_repo->subscribeNotificationTopic($user->device_token, !empty($user->category_id) ? $notification_topic[$user->category_id] : $notification_topic['1']);
                    }
                }

                DB::commit();  
                return self::sendSuccess([
                        'token' => $user->createToken('EzzyCare')->accessToken,
                        'user' => $user,
                        ]);
            }catch(\Exception $e){
                DB::rollBack();
                return self::sendException($e);
            }
        } else{

             return self::sendError('', 'Mobile No. Already Registered.');
        }

    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveRegister(UserProviderAuthRequest $request)
    {
        $registerStop = AppSetting::where('key_name', 'registration_start')->first();
        if(isset($registerStop) && isset($registerStop->value_txt) && $registerStop->value_txt == 0){             
            return self::sendError('', 'For the time being registration is closed. Please try later.');
        }

        $user = $this->user_repo->checkbyMobileNoVerify($request);   

        if (!empty($request->country_code)) {
            $restracted = Helper::countryCodeRestriction($request->country_code);
            if(isset($restracted) && $restracted == true){
                return self::sendError('', 'You have not access.');
            }
            if(!empty($request->ip())){
                $request->merge(['user_ip' => $request->ip()]); 
                $userIpCheck = $this->user_repo->getIpRegisterdAndLoginIp($request->ip());
                if(!empty($userIpCheck) && $userIpCheck > 3){
                    return self::sendError('', 'You have not access.');
                }
            }
        }

        if(!empty($user)){
            try{
                DB::beginTransaction();
                $this->user_repo->registerWithRestore($request);
                $user = $this->user_repo->getbyMobileNo($request); 
                $this->user_repo->removeOauthAccessTokens($user->id);
                if(!empty($user) && !empty($user->id)){
                    $this->user_details_repo->dataCrudByArray(['user_id' => $user->id, 'urgent'=>'1', 'urgent_criteria'=>'0,1,2'], $user->id);
                }
                
                if(!empty($request->env)){

                }else{
                    $notification_topic = $this->notification_repo->getNotificationTopic();
                    if(!empty($user->device_token)){                    
                        $this->notification_repo->subscribeNotificationTopic($user->device_token, 'Ezzycare');
                        $this->notification_repo->subscribeNotificationTopic($user->device_token, !empty($user->category_id) ? $notification_topic[$user->category_id] : $notification_topic['1']);
                    }
                }

                DB::commit(); 
                return self::sendSuccess([
                        'token' => $user->createToken('EzzyCare')->accessToken,
                        'user' => $user,
                        ]);
            }catch(\Exception $e){
                DB::rollBack();
                return self::sendException($e);
            }
        } else{
             return self::sendError('', 'Mobile No. Already Registered.');
        }

    }

    /**
     * Login a registered users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function socialLogin(SocialLoginRequest $request)
    {
        $user = $this->user_repo->checkbyMobileNoAndEmail($request);
        if(!empty($request->ip())){
            $request->merge(['user_ip' => $request->ip()]); 
            $userIpCheck = $this->user_repo->getIpRegisterdAndLoginIp($request->ip());
            if(!empty($userIpCheck) && $userIpCheck > 3){
                return self::sendError('', 'You have not access.');
            }
        }

        if(!empty($user)){
            if($request->hcp_type == '0'){
                if(!empty($user->category_id)){
                    return self::sendError('', 'User Mobile No. and Password Invalid');
                } 
            }
            if($request->hcp_type == '1'){
                if(empty($user->category_id)){
                    return self::sendError('', 'User Mobile No. and Password Invalid');
                } 
            }
            if(isset($user) && in_array($user->status, ['0','1'])){
                try{
                    $this->user_repo->removeOauthAccessTokens($user->id);
                    $data = [
                                'device_type' => $request->device_type,
                                'device_token'=> $request->device_token,
                                'social_type'=> $request->social_type,
                                'facebook_id'=> $request->facebook_id,
                                'google_id'=> $request->google_id,
                                'apple_id'=> $request->apple_id,
                                'user_ip' => !empty($request->user_ip) ? $request->user_ip : null,
                            ];
                    $this->user_repo->dataCrudUsingData($data, $user->id);

                    if(!empty($request->env)){

                    }else{
                        $notification_topic = $this->notification_repo->getNotificationTopic();
                        if(!empty($user->device_token) && $user->notification_status == '0'){                    
                            $this->notification_repo->unsubscribeNotificationTopic($user->device_token, 'Ezzycare');
                            $this->notification_repo->unsubscribeNotificationTopic($user->device_token, !empty($user->category_id) ? $notification_topic[$user->category_id] : $notification_topic['1']);
                            $this->notification_repo->subscribeNotificationTopic($user->device_token, 'Ezzycare');
                            $this->notification_repo->subscribeNotificationTopic($user->device_token, !empty($user->category_id) ? $notification_topic[$user->category_id] : $notification_topic['1']);
                        }else if(!empty($user->device_token) && $user->notification_status == '1'){
                            $this->notification_repo->unsubscribeNotificationTopic($user->device_token, 'Ezzycare');
                            $this->notification_repo->unsubscribeNotificationTopic($user->device_token, !empty($user->category_id) ? $notification_topic[$user->category_id] : $notification_topic['1']);
                        }
                    }

                    return self::sendSuccess([
                        'token' => $user->createToken('EzzyCare')->accessToken,
                        'user' => $user,
                    ]);
                }catch(\Exception $e){
                    return self::sendException($e);
                }
            }else if(isset($user) && $user->status == '2'){
                return self::sendError('', 'You have been deactivated please wait to be activated');
            }else {
                return self::sendError('', 'Please Fill up register details');
            }
        
        }else{
               return self::sendError('', 'User Mobile No. and Email Invalid');
        }
        
    }

    /**
     * Login a registered users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(UserLoginRequest $request)
    {

        if (!empty($request->country_code)) {
            $restracted = Helper::countryCodeRestriction($request->country_code);
            if(isset($restracted) && $restracted == true){
                return self::sendError('', 'You have not access.');
            }
            if(!empty($request->ip())){
                $request->merge(['user_ip' => $request->ip()]); 
            }
        }

        if(Auth::attempt(['country_code' => $request->country_code, 'mobile_no' => $request->mobile_no, 'password' => $request->password])){
            $user = $this->user_repo->getById(Auth::user()->id);
            if($request->hcp_type == '0'){
                if(!empty($user->category_id)){
                    return self::sendError('', 'This number is registered as a Health Care Provider, please login as a Health Care Provider.');
                } 
            }
            if($request->hcp_type == '1'){
                if(empty($user->category_id)){
                    return self::sendError('', 'This number is registered as a patient, please login as a patient.');
                } 
            }
            if(isset($user) && in_array($user->status, ['0','1'])){
                try{
                    $this->user_repo->removeOauthAccessTokens($user->id);
                    $data = ['device_type' => $request->device_type,'device_token'=> $request->device_token,'user_timezone' => !empty(request()->header('X-TimeZone')) ? request()->header('X-TimeZone') : '','user_ip' => !empty($request->user_ip) ? $request->user_ip : null];
                    $this->user_repo->dataCrudUsingData($data, $user->id);
                    $user = $this->user_repo->getById(Auth::user()->id);
                    if(!empty($request->env)){

                    }else{
                        $notification_topic = $this->notification_repo->getNotificationTopic();
                        if(!empty($user->device_token) && $user->notification_status == '0'){       
                            $this->notification_repo->unsubscribeNotificationTopic($user->device_token, 'Ezzycare');
                            $this->notification_repo->unsubscribeNotificationTopic($user->device_token, !empty($user->category_id) ? $notification_topic[$user->category_id] : $notification_topic['1']);
                            $this->notification_repo->subscribeNotificationTopic($user->device_token, 'Ezzycare');
                            $this->notification_repo->subscribeNotificationTopic($user->device_token, !empty($user->category_id) ? $notification_topic[$user->category_id] : $notification_topic['1']);
                        }else if(!empty($user->device_token) && $user->notification_status == '1'){
                            $this->notification_repo->unsubscribeNotificationTopic($user->device_token, 'Ezzycare');
                            $this->notification_repo->unsubscribeNotificationTopic($user->device_token, !empty($user->category_id) ? $notification_topic[$user->category_id] : $notification_topic['1']);
                        }
                    }


                    return self::sendSuccess([
                        'token' => $user->createToken('EzzyCare')->accessToken,
                        'user' => $user,
                    ]);
                }catch(\Exception $e){
                    return self::sendException($e);
                }
            }else if(isset($user) && $user->status == '2'){
                 return self::sendError('', 'You have been deactivated please wait to be activated');
            }else if(isset($user) && $user->status == '3'){
                 return self::sendError('', 'Please Verify mobile number');
            }else {
                 return self::sendError('', 'Please Fill up register details');
            }
        }else{
            return self::sendError('', 'User Mobile No. and Password Invalid');
        }
    }

    
    /**
     * resend otp code a registered users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resendSMS(UserResendSMSRequest $request)
    {
        if (!empty($request->header('device_type')) && !empty($request->header('device_id'))) {
            $otpDetails = OtpDetails::where('device_type',$request->header('device_type'))->where('device_id',$request->header('device_id'))->whereDate('start_date_time',Carbon::now()->format('Y-m-d'))->count();
            if(!empty($otpDetails) && $otpDetails >= 3){
                return self::sendError('', 'I am sorry, it appears you have exceeded the OTP request limit for today, please try again after 24 hrs.');
            }
        }else{
            return self::sendError('', 'Currently, the registration feature is temporarily disabled. Please wait for a few days.'); 
        }

        if (!empty($request->country_code)) {
            $restracted = Helper::countryCodeRestriction($request->country_code);
            if(isset($restracted) && $restracted == true){
                return self::sendError('', 'You have not access.');
            }
            if(!empty($request->ip())){
                $request->merge(['user_ip' => $request->ip()]); 
            }
        }

        try{
            $user = $this->user_repo->getbyMobileNo($request); 
            $mobile_code = $this->user_repo->generateOTPCode();
            $data = ['otp_code' => $mobile_code, 'user_ip' => !empty($request->user_ip) ? $request->user_ip : null];
            $message = 'Your OTP for ['.config('app.name').'] is: '.$mobile_code;

            try{
                $currentDateTime = Carbon::now();
                $TwoMinutesAfter = $currentDateTime->addMinutes(2)->format('Y-m-d H:i:s');
                OtpDetails::create([
                    'device_type' => !empty($request->header('device_type')) ? $request->header('device_type') : null,
                    'device_id' => !empty($request->header('device_id')) ? $request->header('device_id') : null,
                    'country_code' => !empty($request->country_code) ? $request->country_code : null,
                    'mobile' => !empty($request->mobile_no) ? $request->mobile_no : null,
                    'email' => !empty($request->email) ? $request->email : null,
                    'otp' => $mobile_code,
                    'start_date_time' => Carbon::now()->format('Y-m-d H:i:s'),
                    'expiry_date_time' => $TwoMinutesAfter,
                ]);
            }catch(\Exception $e){
                return self::sendError('', 'SMS Sending Failed');
            } 

            try{
                $sent_msg = $this->user_repo->sendMessage($message, $request->country_code.$request->mobile_no);
            }catch(\Exception $e){
                return self::sendError('', 'SMS Sending Failed');
            }               
            if(!empty($sent_msg)){
               return self::sendError('', 'SMS Sending Failed');
            }
            $this->user_repo->dataCrudUsingData($data, $user->id);
            $update_user = $this->user_repo->getById($user->id);
            return self::sendSuccess([
                'data' => $data,
            ]);
    
        }catch(\Exception $e){
            return self::sendException($e);
        }
    }

    /**
     * resend otp code a registered users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function verifyOTP(UserVerifySMSRequest $request)
    {
        $user = $this->user_repo->getbyMobileNo($request);   
        if(!empty($user) && $user->otp_code == $request->otp_code){   
            try{
                $data = ['mobile_verified_at' => Carbon::now()];
                $this->user_repo->dataCrudUsingData($data, $user->id);
                $update_user = $this->user_repo->getById($user->id); 
                return self::sendSuccess([],'Mobile no verify');
            }catch(\Exception $e){
                return self::sendException($e);
            }
        }else{
            return self::sendError('', 'Wrong OTP code. Please check');
        }
    }

    /**
     * forget password otp code a registered users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function forgetPassword(UserForgetPasswordRequest $request)
    {
        // if (!empty($request->g_recaptcha_response)) {
        //     //your site secret key
        //     $secret = config('app.GOOGLE_RECAPTCH_SECRET_KEY');
        //     //get verify response data
        //     $verifyResponse = file_get_contents(config('app.GOOGLE_RECAPTCH_URL') . '?secret=' . $secret . '&response=' . $request->g_recaptcha_response);
        //     $responseData = json_decode($verifyResponse);
        //     \Log::info('google verify data');
        //     \Log::info(json_encode($responseData));
        //     if ($responseData->success) {
        //         //contact form submission code goes here
        //     } else {
        //         return self::sendError('', 'Robot verification failed, please try again.');
        //     }
        // }else{
        //     return self::sendError('', 'Currently, the forget password feature is temporarily disabled. Please wait for a few days.'); 
        // }

        if (!empty($request->header('device_type')) && !empty($request->header('device_id'))) {
            $otpDetails = OtpDetails::where('device_type',$request->header('device_type'))->where('device_id',$request->header('device_id'))->whereDate('start_date_time',Carbon::now()->format('Y-m-d'))->count();
            if(!empty($otpDetails) && $otpDetails >= 3){
                return self::sendError('', 'I am sorry, it appears you have exceeded the OTP request limit for today, please try again after 24 hrs.');
            }
        }else{
            return self::sendError('', 'Currently, the registration feature is temporarily disabled. Please wait for a few days.'); 
        }
        
        $user = $this->user_repo->checkbyMobileNo($request);   
        if (!empty($request->country_code)) {
            $restracted = Helper::countryCodeRestriction($request->country_code);
            if(isset($restracted) && $restracted == true){
                return self::sendError('', 'You have not access.');
            }
            if(!empty($request->ip())){
                $request->merge(['user_ip' => $request->ip()]); 
            }
        }
        if(!empty($user)){   
            try{
                $user = $this->user_repo->getbyMobileNo($request); 
                $mobile_code = $this->user_repo->generateOTPCode();
                $data = ['otp_code' => $mobile_code,'user_ip' => !empty($request->user_ip) ? $request->user_ip : null];
                $message = 'Your OTP for ['.config('app.name').'] is: '.$mobile_code;

                try{
                    $currentDateTime = Carbon::now();
                    $TwoMinutesAfter = $currentDateTime->addMinutes(2)->format('Y-m-d H:i:s');
                    OtpDetails::create([
                        'device_type' => !empty($request->header('device_type')) ? $request->header('device_type') : null,
                        'device_id' => !empty($request->header('device_id')) ? $request->header('device_id') : null,
                        'country_code' => !empty($request->country_code) ? $request->country_code : null,
                        'mobile' => !empty($request->mobile_no) ? $request->mobile_no : null,
                        'email' => !empty($request->email) ? $request->email : null,
                        'otp' => $mobile_code,
                        'start_date_time' => Carbon::now()->format('Y-m-d H:i:s'),
                        'expiry_date_time' => $TwoMinutesAfter,
                    ]);
                }catch(\Exception $e){
                    return self::sendError('', 'SMS Sending Failed');
                } 

                try{
                    $sent_msg = $this->user_repo->sendMessage($message, $request->country_code.$request->mobile_no);
                }catch(\Exception $e){
                    return self::sendError('', 'SMS Sending Failed');
                }    
                if(!empty($sent_msg)){
                    return self::sendError('', 'SMS Sending Failed');
                }
                $this->user_repo->dataCrudUsingData($data, $user->id);
                $update_user = $this->user_repo->getById($user->id);
                return self::sendSuccess([
                    'token' => $user->createToken('EzzyCare')->accessToken,
                    'data' => $data,
                    ]);
            }catch(\Exception $e){
                return self::sendException($e);
            }

        }else{
            return self::sendError('', 'User Mobile No. Not Registered');
        }
    }

    /**
     * recover password otp code a registered users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function recoverPassword(UserRecoverPasswordRequest $request)
    {
        $user = $this->user_repo->getbyMobileNo($request);   
        if(!empty($user)){   
            try{
                $data = ['password' => Hash::make($request->password)];
                $this->user_repo->dataCrudUsingData($data, $user->id);
                $update_user = $this->user_repo->getById($user->id); 
                return self::sendSuccess([], 'Password Reset');
            }catch(\Exception $e){
                return self::sendException($e);
            }
        }else{
            return self::sendError('', 'User Mobile No. Not Registered');
        }
    }
    
    /**
     * Change password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function userChangePassword(PasswordChangeRequest $request)
    {
        $user = $this->user_repo->getById($request->user()->id); 
        if(!empty($user)){   
            try{
                if(Hash::check($request->password , $request->user()->password)){
                    $data = ['password' => Hash::make($request->new_password)];
                    $this->user_repo->dataCrudUsingData($data, $request->user()->id);
                    $update_user = $this->user_repo->getById($request->user()->id); 
                    return self::sendSuccess('', 'Password Reset');
                }
            return self::sendError('', 'Current Password Not Match');
            }catch(\Exception $e){
                return self::sendException($e);
            }
        }else{
            return self::sendError('', 'User Mobile No. Not Registered');
        }
    }

    /*
     * login user loogut.
    */
    public function userLogout(Request $request) 
    {
        try{
            $user = $this->user_repo->getById($request->user()->id); 
            $notification_topic = $this->notification_repo->getNotificationTopic();
            $this->notification_repo->unsubscribeNotificationTopic($user->device_token, 'Ezzycare');
            $this->notification_repo->unsubscribeNotificationTopic($user->device_token, !empty($user->category_id) ? $notification_topic[$user->category_id] : $notification_topic['1']);
            $data = ['device_type' => NULL,'device_token'=> NULL];
            $this->user_repo->dataCrudUsingData($data, $request->user()->id);
            $this->user_repo->removeOauthAccessTokens($request->user()->id);
            return Self::sendSuccess('', 'Logout Successfull.');
        }catch(\Exception $e){
            return self::sendException($e);
        }
    }
}
