<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\Auth\UserAuthRequest;
use App\Http\Requests\Api\Auth\UserLoginRequest;
use App\Http\Requests\Api\Auth\UserResendSMSRequest;
use App\Http\Requests\Api\Auth\UserVerifySMSRequest;
use App\Http\Requests\Api\Auth\UserForgetPasswordRequest;
use App\Http\Requests\Api\Auth\UserRecoverPasswordRequest;
use Carbon\Carbon as Carbon;
use Auth;
use DB;

class UserAuthController extends BaseApiController
{

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveRegister(Request $request)
    {
        $user = $this->user_repo->checkbyMobileNo($request);   
        if(!empty($user)){
            $this->user_repo->registerWithRestore($request);
            $user = $this->user_repo->getbyMobileNo($request);   
            if(empty($user->category_id)){
                if(Auth::attempt(['country_code' => $request->country_code, 'mobile_no' => $request->mobile_no, 'password' => $request->password, 'status' => '0'])){
                    if(!empty($user) && $user->status == '0'){
                        return self::sendSuccess([
                            'token' => $user->createToken('EzzyCare')->accessToken,
                            'user' => $user,
                        ]);
                    }else{
                        return self::sendError('', 'User status is pending please wait for Approved');
                    }
                }
            }

            return self::sendSuccess([
                'user' => $user,
            ]);
        } else{
             return self::sendError('', 'User Already Registered.');
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
        if(Auth::attempt(['country_code' => $request->country_code, 'mobile_no' => $request->mobile_no, 'password' => $request->password])){
            $user = $this->user_repo->getById(Auth::user()->id);
            if(isset($user) && $user->status == '0'){
                try{
                    $this->user_repo->removeOauthAccessTokens($user->id);
                    $data = ['device_type' => $request->device_type,'device_token'=> $request->device_token];
                    $this->user_repo->dataCrudUsingData($data, $user->id);
                    return self::sendSuccess([
                        'token' => $user->createToken('EzzyCare')->accessToken,
                        'user' => $user,
                    ]);
                }catch(\Exception $e){
                    return self::sendException($e);
                }
            }else{
                 return self::sendError('', 'User status is pending please wait for Approved');
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
        $user = $this->user_repo->checkbyMobileNo($request);   
        if(!empty($user)){   
            try{
                $user = $this->user_repo->getbyMobileNo($request); 
                $mobile_code = $this->user_rep->generateOTPCode();
                $data = ['otp_code' => $mobile_code];
                $message = 'The OTP is '.$mobile_code.' to verify '.config('app.name').' Account.';
                $this->user_repo->sendMessage($message, $request->country_code.$request->mobile_no);
                $this->user_repo->dataCrudUsingData($data, $user->id);
                $update_user = $this->user_repo->getById($user->id);
                return self::sendSuccess([
                    'user' => $update_user,
                ]);
        
            }catch(\Exception $e){
                return self::sendException($e);
            }
        }else{
            return self::sendError('', 'User Mobile No. Invalid');
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
                $data = ['mobile_verified_at' => Carbon::now(), 'status' => '1'];
                $this->user_repo->dataCrudUsingData($data, $user->id);
                $update_user = $this->user_repo->getById($user->id); 
                return self::sendSuccess([
                    'user' => $update_user,
                ]);
            }catch(\Exception $e){
                return self::sendException($e);
            }
        }else{
            return self::sendError('', 'Verify OTP code is wrong please check');
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
        $user = $this->user_repo->checkbyMobileNo($request);   
        if(!empty($user)){   
            try{
                $user = $this->user_repo->getbyMobileNo($request); 
                $mobile_code = $this->user_rep->generateOTPCode();
                $data = ['otp_code' => $mobile_code];
                $message = 'The OTP is '.$mobile_code.' to forget password '.config('app.name').' Account.';
                $this->user_repo->sendMessage($message, '+'.$request->country_code.$request->mobile_no);
                $this->user_repo->dataCrudUsingData($data, $user->id);
                $update_user = $this->user_repo->getById($user->id);
                return self::sendSuccess([
                    'token' => $user->createToken('EzzyCare')->accessToken,
                    'user' => $update_user,
                    ]);
            }catch(\Exception $e){
                return self::sendException($e);
            }

        }else{
            return self::sendError('', 'User Mobile No. Invalid');
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
        if(!empty($user) && $user->otp_code == $request->otp_code){   
            try{
                $data = ['password' => Hash::make($request->password)];
                $this->user_repo->dataCrudUsingData($data, $user->id);
                $update_user = $this->user_repo->getById($user->id); 
                return self::sendSuccess([
                    'user' => $update_user,
                ]);
            }catch(\Exception $e){
                return self::sendException($e);
            }
        }else{
            return self::sendError('', 'User Mobile No. Invalid');
        }
    }

    /*
     * login user loogut.
    */
    public function userLogout(Request $request) {
        $user = $request->user();
        try{
            $data = ['device_type' => NULL,'device_token'=> NULL];
            $this->user_repo->dataCrudUsingData($data, $user->id);
            $this->user_repo->removeOauthAccessTokens($request->user()->id);
            return Self::sendSuccess('', 'Logout Successfull.');
        }catch(\Exception $e){
            return self::sendException($e);
        }
    }
}
