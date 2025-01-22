<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\CustomEmailController;
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
use App\Models\UserTempMobileRegister;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationOtp;
use App\Mail\ForgetPasswordOtp;
use App\Http\Helpers\Helper;

class UserAuthController extends BaseApiController
{

    private $user_repo, $notification_repo, $user_details_repo, $otpSendLimit = 100;

    public function __construct(
        UserRepository $user_repo,
        UserDetailsRepository $user_details_repo,
        NotificationRepository $notification_repo
    ) {
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
        \Log::info('saveRegisterwithMobile');
        \Log::info(json_encode($request->all()));
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
            $otpDetails = OtpDetails::where('device_type', $request->header('device_type'))->where('device_id', $request->header('device_id'))->whereDate('start_date_time', Carbon::now()->format('Y-m-d'))->count();
            if (!empty($otpDetails) && $otpDetails >= $this->otpSendLimit) {
                return self::sendError('', 'I am sorry, it appears you have exceeded the OTP request limit for today, please try again after 24 hrs.');
            }
            if (!empty($request->country_code) && $request->country_code == "+234") {
            } else {
                // $check_valid_number = Helper::mobileNoVerify($request->mobile_no, $request->country_code);
                // \Log::info($check_valid_number);
                // if (!empty($check_valid_number) && !empty($check_valid_number['valid']) && !empty($check_valid_number['line_type']) && $check_valid_number['valid'] == true && $check_valid_number['line_type'] == 'mobile') {

                // }else{
                //     return self::sendError('', 'Please try again, as the provided phone number is not valid.');
                // }
            }
        } else {
            return self::sendError('', 'Currently, the registration feature is temporarily disabled. Please wait for a few days.');
        }

        $registerStop = AppSetting::where('key_name', 'registration_start')->first();
        if (isset($registerStop) && isset($registerStop->value_txt) && $registerStop->value_txt == 0) {
            return self::sendError('', 'For the time being registration is closed. Please try later.');
        }

        $registerEmailStop = AppSetting::where('key_name', 'registration_email_start')->first();
        if (!empty($request->email) && isset($registerEmailStop) && isset($registerEmailStop->value_txt) && $registerEmailStop->value_txt == 0) {
            return self::sendError('', 'For the time being email registration is closed. Please try later.');
        }

        $registerPhoneStop = AppSetting::where('key_name', 'registration_phone_start')->first();
        if (!empty($request->mobile_no) && isset($registerPhoneStop) && isset($registerPhoneStop->value_txt) && $registerPhoneStop->value_txt == 0) {
            return self::sendError('', 'For the time being phone no registration is closed. Please try later.');
        }

        if (!empty($request->register_type) && $request->register_type == '2') {
            $emailVerification = Helper::getEmailVerification($request->email);
            if (!empty($emailVerification) && !empty($emailVerification['status']) && $emailVerification['status'] == 'true') {
            } else  if (!empty($emailVerification) && isset($emailVerification['status']) && !empty($emailVerification['msg'])) {
                return self::sendError('', $emailVerification['msg']);
            } else {
                return self::sendError('', 'This email ID is not valid. Please use a different email ID.');
            }

            $user = $this->user_repo->checkbyEmailId($request);
            if (!empty($user)) {
                return self::sendError('', 'Email ID Already Registered.');
            }
        } else {
            $user = $this->user_repo->checkbyMobileNo($request);
            if (!empty($user)) {
                return self::sendError('', 'Mobile No. Already Registered.');
            }
        }

        if (!empty($request->country_code)) {
            // $restracted = Helper::countryCodeRestriction($request->country_code);
            // if(isset($restracted) && $restracted == true){
            //     return self::sendError('', 'You have not access.');
            // }
            if (!empty($request->ip())) {
                $request->merge(['user_ip' => $request->ip()]);
                $userIpCheck = $this->user_repo->getIpRegisterdAndLoginIp($request->ip());
                // if(!empty($userIpCheck) && $userIpCheck > 3){
                //     return self::sendError('', 'You ip limit is our.');
                // }
            }
        }



        if (empty($user)) {
            try {
                DB::beginTransaction();
                $mobile_code = $this->user_repo->generateOTPCode();
                $data = ['otp_code' => $mobile_code];
                $request->otp_code = $mobile_code;
                $request->status = '3';
                $message = 'Your OTP for [' . config('app.name') . '] is: ' . $mobile_code;

                try {
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
                } catch (\Exception $e) {
                    DB::rollBack();
                    UserTempMobileRegister::create([
                        'device_type' => !empty($request->header('device_type')) ? $request->header('device_type') : null,
                        'device_id' => !empty($request->header('device_id')) ? $request->header('device_id') : null,
                        'country_code' => !empty($request->country_code) ? $request->country_code : null,
                        'mobile_no' => !empty($request->mobile_no) ? $request->mobile_no : null,
                        'email' => !empty($request->email) ? $request->email : null,
                    ]);
                    DB::commit();
                    return self::sendError('', 'SMS Sending Failed');
                }

                try {
                    if (!empty($request->register_type) && $request->register_type == '2') {
                        $subject = 'Registration OTP Verification' . ' | ' . config('app.name');
                        $toMail = $request->email;
                        $toName = '';
                        $templateName = 'register_otp';
                        $templateData = $mobile_code;
                        $controller = new CustomEmailController();
                        // Call the sendEmail method with the necessary data
                        $controller->sendEmail($subject, $toMail, $toName, $templateName, $templateData);
                        // Mail::to($request->email)->send(new RegistrationOtp($mobile_code));
                    } else {
                        $sent_msg = $this->user_repo->sendMessage($message, $request->country_code . $request->mobile_no);
                    }
                } catch (\Exception $e) {
                    DB::rollBack();
                    UserTempMobileRegister::create([
                        'device_type' => !empty($request->header('device_type')) ? $request->header('device_type') : null,
                        'device_id' => !empty($request->header('device_id')) ? $request->header('device_id') : null,
                        'country_code' => !empty($request->country_code) ? $request->country_code : null,
                        'mobile_no' => !empty($request->mobile_no) ? $request->mobile_no : null,
                        'email' => !empty($request->email) ? $request->email : null,
                    ]);
                    DB::commit();
                    return self::sendError('', 'SMS Sending Failed');
                }

                if (!empty($sent_msg) && $sent_msg === "SMS Sending Failed") {
                    DB::rollBack();
                    UserTempMobileRegister::create([
                        'device_type' => !empty($request->header('device_type')) ? $request->header('device_type') : null,
                        'device_id' => !empty($request->header('device_id')) ? $request->header('device_id') : null,
                        'country_code' => !empty($request->country_code) ? $request->country_code : null,
                        'mobile_no' => !empty($request->mobile_no) ? $request->mobile_no : null,
                        'email' => !empty($request->email) ? $request->email : null,
                    ]);
                    DB::commit();
                    return self::sendError('', 'SMS Sending Failed');
                }
                $user = $this->user_repo->registerWithMobileno($request);
                if (!empty($user) && !empty($user->id)) {
                    $this->user_details_repo->dataCrudByArray(['user_id' => $user->id, 'urgent' => '1', 'urgent_criteria' => '0,1,2'], $user->id);
                }
                DB::commit();
                return self::sendSuccess([
                    'token' => $user->createToken('EzzyCare')->accessToken,
                    'data' => $data,
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::info('UserTempMobileRegister4');
                return self::sendException($e);
            }
        } else {
            if (!empty($request->register_type) && $request->register_type == '2') {
                return self::sendError('', 'Email Id Already Registered.');
            } else {
                return self::sendError('', 'Mobile No. Already Registered.');
            }
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
        \Log::info('saveRegisterPatient');
        \Log::info(json_encode($request->all()));
        $registerStop = AppSetting::where('key_name', 'registration_start')->first();
        if (isset($registerStop) && isset($registerStop->value_txt) && $registerStop->value_txt == 0) {
            return self::sendError('', 'For the time being registration is closed. Please try later.');
        }

        if (!empty($request->register_type) && $request->register_type == '2') {
            $emailVerification = Helper::getEmailVerification($request->email);
            if (!empty($emailVerification) && !empty($emailVerification['status']) && $emailVerification['status'] == 'true') {
            } else  if (!empty($emailVerification) && isset($emailVerification['status']) && !empty($emailVerification['msg'])) {
                return self::sendError('', $emailVerification['msg']);
            } else {
                return self::sendError('', 'This email ID is not valid. Please use a different email ID.');
            }

            $user = $this->user_repo->checkbyEmailVerify($request);
            if (!empty($request->country_code) && !empty($request->mobile_no)) {
                $userMobile = $this->user_repo->getbyMobileNo($request);
                if (!empty($userMobile)) {
                    return self::sendError('', 'Mobile No. Already Registered.', 500);
                }
            }
        } else {
            $user = $this->user_repo->checkbyMobileNoVerify($request);            
            if (!empty($request->email)) {
                $userEmail = $this->user_repo->getbyEmail($request);
                if (!empty($userEmail)) {
                    return self::sendError('', 'Email Id Already Registered.', 500);
                }
            }
        }


        if (!empty($request->country_code)) {
            // $restracted = Helper::countryCodeRestriction($request->country_code);
            // if(isset($restracted) && $restracted == true){
            //     return self::sendError('', 'You have not access.');
            // }
            if (!empty($request->ip())) {
                $request->merge(['user_ip' => $request->ip()]);
                $userIpCheck = $this->user_repo->getIpRegisterdAndLoginIp($request->ip());
                // if(!empty($userIpCheck) && $userIpCheck > 3){
                //     return self::sendError('', 'You have not access.');
                // }
            }
        }

        if (!empty($user)) {
            try {
                DB::beginTransaction();
                $this->user_repo->registerWithRestore($request);
                $user = $this->user_repo->getbyMobileNo($request);
                $this->user_repo->removeOauthAccessTokens($user->id);
                if (!empty($user) && !empty($user->id)) {
                    $this->user_details_repo->dataCrudByArray(['user_id' => $user->id], $user->id);
                }

                if (!empty($request->env)) {
                } else {
                    $notification_topic = $this->notification_repo->getNotificationTopic();
                    if (!empty($user->device_token)) {
                        $this->notification_repo->subscribeNotificationTopic($user->device_token, 'Ezzycare');
                        $this->notification_repo->subscribeNotificationTopic($user->device_token, !empty($user->category_id) ? $notification_topic[$user->category_id] : $notification_topic['1']);
                    }
                }

                DB::commit();
                return self::sendSuccess([
                    'token' => $user->createToken('EzzyCare')->accessToken,
                    'user' => $user,
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return self::sendException($e);
            }
        } else {
            if (!empty($request->register_type) && $request->register_type == '2') {
                return self::sendError('', 'Email Id Already Registered.');
            } else {
                return self::sendError('', 'Mobile No. Already Registered.');
            }
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
        \Log::info('saveRegister');
        \Log::info(json_encode($request->all()));
        $registerStop = AppSetting::where('key_name', 'registration_start')->first();
        if (isset($registerStop) && isset($registerStop->value_txt) && $registerStop->value_txt == 0) {
            return self::sendError('', 'For the time being registration is closed. Please try later.');
        }

        if (!empty($request->register_type) && $request->register_type == '2') {
            $user = $this->user_repo->checkbyEmailVerify($request);
            if (!empty($request->country_code) && !empty($request->mobile_no)) {
                $userMobile = $this->user_repo->getbyMobileNo($request);
                if (!empty($userMobile)) {
                    return self::sendError('', 'Mobile No. Already Registered.', 500);
                }
            }
        } else {
            $user = $this->user_repo->checkbyMobileNoVerify($request);
            if (!empty($request->email)) {
                $userEmail = $this->user_repo->getbyEmail($request);
                if (!empty($userEmail)) {
                    return self::sendError('', 'Email Id Already Registered.', 500);
                }
            }
        }


        if (!empty($request->country_code)) {
            // $restracted = Helper::countryCodeRestriction($request->country_code);
            // if(isset($restracted) && $restracted == true){
            //     return self::sendError('', 'You have not access.');
            // }
            if (!empty($request->ip())) {
                $request->merge(['user_ip' => $request->ip()]);
                $userIpCheck = $this->user_repo->getIpRegisterdAndLoginIp($request->ip());
                // if(!empty($userIpCheck) && $userIpCheck > 3){
                //     return self::sendError('', 'You have not access.');
                // }
            }
        }

        if (!empty($user)) {
            try {
                DB::beginTransaction();
                $this->user_repo->registerWithRestore($request);
                if (!empty($request->register_type) && $request->register_type == '2') {
                    $emailVerification = Helper::getEmailVerification($request->email);
                    if (!empty($emailVerification) && !empty($emailVerification['status']) && $emailVerification['status'] == 'true') {
                    } else  if (!empty($emailVerification) && isset($emailVerification['status']) && !empty($emailVerification['msg'])) {
                        return self::sendError('', $emailVerification['msg']);
                    } else {
                        return self::sendError('', 'This email ID is not valid. Please use a different email ID.');
                    }

                    $user = $this->user_repo->getbyEmail($request);
                } else {
                    $user = $this->user_repo->getbyMobileNo($request);
                }

                $this->user_repo->removeOauthAccessTokens($user->id);
                if (!empty($user) && !empty($user->id)) {
                    $this->user_details_repo->dataCrudByArray(['user_id' => $user->id, 'urgent' => '1', 'urgent_criteria' => '0,1,2'], $user->id);
                }

                if (!empty($request->env)) {
                } else {
                    $notification_topic = $this->notification_repo->getNotificationTopic();
                    if (!empty($user->device_token)) {
                        $this->notification_repo->subscribeNotificationTopic($user->device_token, 'Ezzycare');
                        $this->notification_repo->subscribeNotificationTopic($user->device_token, !empty($user->category_id) ? $notification_topic[$user->category_id] : $notification_topic['1']);
                    }
                }

                DB::commit();
                return self::sendSuccess([
                    'token' => $user->createToken('EzzyCare')->accessToken,
                    'user' => $user,
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return self::sendException($e);
            }
        } else {
            if (!empty($request->register_type) && $request->register_type == '2') {
                return self::sendError('', 'Email Id Already Registered.');
            } else {
                return self::sendError('', 'Mobile No. Already Registered.');
            }
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
        \Log::info('socialLogin');
        \Log::info(json_encode($request->all()));
        $user = $this->user_repo->checkbyMobileNoAndEmail($request);
        if (!empty($request->ip())) {
            $request->merge(['user_ip' => $request->ip()]);
            $userIpCheck = $this->user_repo->getIpRegisterdAndLoginIp($request->ip());
            // if(!empty($userIpCheck) && $userIpCheck > 3){
            //     return self::sendError('', 'You have not access.');
            // }
        }

        if (!empty($user)) {
            if ($request->hcp_type == '0') {
                if (!empty($user->category_id)) {
                    return self::sendError('', 'User Mobile No. and Password Invalid');
                }
            }
            if ($request->hcp_type == '1') {
                if (empty($user->category_id)) {
                    return self::sendError('', 'User Mobile No. and Password Invalid');
                }
            }
            if (isset($user) && in_array($user->status, ['0', '1'])) {
                try {
                    $this->user_repo->removeOauthAccessTokens($user->id);
                    $data = [
                        'device_type' => $request->device_type,
                        'device_token' => $request->device_token,
                        'social_type' => $request->social_type,
                        'facebook_id' => $request->facebook_id,
                        'google_id' => $request->google_id,
                        'apple_id' => $request->apple_id,
                        'user_ip' => !empty($request->user_ip) ? $request->user_ip : null,
                    ];
                    $this->user_repo->dataCrudUsingData($data, $user->id);

                    if (!empty($request->env)) {
                    } else {
                        $notification_topic = $this->notification_repo->getNotificationTopic();
                        if (!empty($user->device_token) && $user->notification_status == '0') {
                            $this->notification_repo->unsubscribeNotificationTopic($user->device_token, 'Ezzycare');
                            $this->notification_repo->unsubscribeNotificationTopic($user->device_token, !empty($user->category_id) ? $notification_topic[$user->category_id] : $notification_topic['1']);
                            $this->notification_repo->subscribeNotificationTopic($user->device_token, 'Ezzycare');
                            $this->notification_repo->subscribeNotificationTopic($user->device_token, !empty($user->category_id) ? $notification_topic[$user->category_id] : $notification_topic['1']);
                        } else if (!empty($user->device_token) && $user->notification_status == '1') {
                            $this->notification_repo->unsubscribeNotificationTopic($user->device_token, 'Ezzycare');
                            $this->notification_repo->unsubscribeNotificationTopic($user->device_token, !empty($user->category_id) ? $notification_topic[$user->category_id] : $notification_topic['1']);
                        }
                    }

                    return self::sendSuccess([
                        'token' => $user->createToken('EzzyCare')->accessToken,
                        'user' => $user,
                    ]);
                } catch (\Exception $e) {
                    return self::sendException($e);
                }
            } else if (isset($user) && $user->status == '2') {
                return self::sendError('', 'You have been deactivated please wait to be activated');
            } else {
                return self::sendError('', 'Please Fill up register details');
            }
        } else {
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
            // $restracted = Helper::countryCodeRestriction($request->country_code);
            // if(isset($restracted) && $restracted == true){
            //     return self::sendError('', 'You have not access.');
            // }
            if (!empty($request->ip())) {
                $request->merge(['user_ip' => $request->ip()]);
            }
        }
        if (!empty($request->register_type) && $request->register_type == '2') {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            } else {
                return self::sendError('', 'User Mobile No. and Password Invalid');
            }
        } else {
            if (Auth::attempt(['country_code' => $request->country_code, 'mobile_no' => $request->mobile_no, 'password' => $request->password])) {
            } else {
                return self::sendError('', 'User Mobile No. and Password Invalid');
            }
        }

        if (!empty(Auth::user()) && !empty(Auth::user()->id)) {
            $user = $this->user_repo->getById(Auth::user()->id);
            if ($request->hcp_type == '0') {
                if (!empty($user->category_id)) {
                    if (!empty($request->register_type) && $request->register_type == '2') {
                        return self::sendError('', 'This email is registered as a Care Provider, so please log in as a Care Provider.');
                    } else {
                        return self::sendError('', 'This number is registered as a Care Provider, so please log in as a Care Provider.');
                    }
                }
            }
            if ($request->hcp_type == '1') {
                if (empty($user->category_id)) {
                    if (!empty($request->register_type) && $request->register_type == '2') {
                        return self::sendError('', 'This email is registered as a Care Seeker, so please log in as a Care Seeker.');
                    } else {
                        return self::sendError('', 'This number is registered as a Care Seeker, so please log in as a Care Seeker.');
                    }
                }
            }
            if (isset($user) && in_array($user->status, ['0', '1'])) {
                try {
                    $this->user_repo->removeOauthAccessTokens($user->id);
                    $data = ['device_type' => $request->device_type, 'device_token' => $request->device_token, 'user_timezone' => !empty(request()->header('X-TimeZone')) ? request()->header('X-TimeZone') : '', 'user_ip' => !empty($request->user_ip) ? $request->user_ip : null];
                    $this->user_repo->dataCrudUsingData($data, $user->id);
                    $user = $this->user_repo->getById(Auth::user()->id);
                    if (!empty($request->env)) {
                    } else {
                        $notification_topic = $this->notification_repo->getNotificationTopic();
                        if (!empty($user->device_token) && $user->notification_status == '0') {
                            $this->notification_repo->unsubscribeNotificationTopic($user->device_token, 'Ezzycare');
                            $this->notification_repo->unsubscribeNotificationTopic($user->device_token, !empty($user->category_id) ? $notification_topic[$user->category_id] : $notification_topic['1']);
                            $this->notification_repo->subscribeNotificationTopic($user->device_token, 'Ezzycare');
                            $this->notification_repo->subscribeNotificationTopic($user->device_token, !empty($user->category_id) ? $notification_topic[$user->category_id] : $notification_topic['1']);
                        } else if (!empty($user->device_token) && $user->notification_status == '1') {
                            $this->notification_repo->unsubscribeNotificationTopic($user->device_token, 'Ezzycare');
                            $this->notification_repo->unsubscribeNotificationTopic($user->device_token, !empty($user->category_id) ? $notification_topic[$user->category_id] : $notification_topic['1']);
                        }
                    }


                    return self::sendSuccess([
                        'token' => $user->createToken('EzzyCare')->accessToken,
                        'user' => $user,
                    ]);
                } catch (\Exception $e) {
                    return self::sendException($e);
                }
            } else if (isset($user) && $user->status == '2') {
                return self::sendError('', 'You have been deactivated please wait to be activated');
            } else if (isset($user) && $user->status == '3') {
                if (!empty($request->register_type) && $request->register_type == '2') {
                    return self::sendError('', 'Please Verify email id');
                } else {
                    return self::sendError('', 'Please Verify mobile number');
                }
            } else {
                return self::sendError('', 'Please Fill up register details');
            }
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
            $otpDetails = OtpDetails::where('device_type', $request->header('device_type'))->where('device_id', $request->header('device_id'))->whereDate('start_date_time', Carbon::now()->format('Y-m-d'))->count();
            if (!empty($otpDetails) && $otpDetails >= $this->otpSendLimit) {
                return self::sendError('', 'I am sorry, it appears you have exceeded the OTP request limit for today, please try again after 24 hrs.');
            }
        } else {
            return self::sendError('', 'Currently, the registration feature is temporarily disabled. Please wait for a few days.');
        }

        if (!empty($request->country_code)) {
            // $restracted = Helper::countryCodeRestriction($request->country_code);
            // if(isset($restracted) && $restracted == true){
            //     return self::sendError('', 'You have not access.');
            // }
            if (!empty($request->ip())) {
                $request->merge(['user_ip' => $request->ip()]);
            }
        }

        try {
            if (!empty($request->register_type) && $request->register_type == '2') {
                $user = $this->user_repo->getbyEmail($request);
            } else {
                $user = $this->user_repo->getbyMobileNo($request);
            }

            if (empty($user)) {
                return self::sendError('', 'The user is not registered. Kindly verify your details.');
            }

            $mobile_code = $this->user_repo->generateOTPCode();
            $data = ['otp_code' => $mobile_code, 'user_ip' => !empty($request->user_ip) ? $request->user_ip : null];
            $message = 'Your OTP for [' . config('app.name') . '] is: ' . $mobile_code;

            try {
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
            } catch (\Exception $e) {
                return self::sendError('', 'SMS Sending Failed');
            }

            try {
                if (!empty($request->register_type) && $request->register_type == '2') {
                    $subject = 'Registration OTP Verification' . ' | ' . config('app.name');
                    $toMail = $request->email;
                    $toName = '';
                    $templateName = 'register_otp';
                    $templateData = $mobile_code;
                    $controller = new CustomEmailController();
                    // Call the sendEmail method with the necessary data
                    $controller->sendEmail($subject, $toMail, $toName, $templateName, $templateData);
                    // Mail::to($request->email)->send(new RegistrationOtp($mobile_code));
                } else {
                    $sent_msg = $this->user_repo->sendMessage($message, $request->country_code . $request->mobile_no);
                }
            } catch (\Exception $e) {
                return self::sendError('', 'SMS Sending Failed');
            }
            if (!empty($sent_msg)) {
                return self::sendError('', 'SMS Sending Failed');
            }
            $this->user_repo->dataCrudUsingData($data, $user->id);
            $update_user = $this->user_repo->getById($user->id);
            return self::sendSuccess([
                'data' => $data,
            ]);
        } catch (\Exception $e) {
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
        \Log::info('verifyOTP');
        \Log::info(json_encode($request->all()));
        if (!empty($request->register_type) && $request->register_type == '2') {
            $user = $this->user_repo->getbyEmail($request);
        } else {
            $user = $this->user_repo->getbyMobileNo($request);
        }

        if (!empty($user) && $user->otp_code == $request->otp_code) {
            try {
                $data = ['mobile_verified_at' => Carbon::now(), 'email_verified_at' => Carbon::now()];
                $this->user_repo->dataCrudUsingData($data, $user->id);
                $update_user = $this->user_repo->getById($user->id);
                return self::sendSuccess([], 'Mobile no verify');
            } catch (\Exception $e) {
                return self::sendException($e);
            }
        } else {
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
            $otpDetails = OtpDetails::where('device_type', $request->header('device_type'))->where('device_id', $request->header('device_id'))->whereDate('start_date_time', Carbon::now()->format('Y-m-d'))->count();
            if (!empty($otpDetails) && $otpDetails >= $this->otpSendLimit) {
                return self::sendError('', 'I am sorry, it appears you have exceeded the OTP request limit for today, please try again after 24 hrs.');
            }
        } else {
            return self::sendError('', 'Currently, the registration feature is temporarily disabled. Please wait for a few days.');
        }


        if (!empty($request->register_type) && $request->register_type == '2') {
            $user = $this->user_repo->checkbyEmailId($request);
        } else {
            $user = $this->user_repo->checkbyMobileNo($request);
        }
        if (!empty($request->country_code)) {
            // $restracted = Helper::countryCodeRestriction($request->country_code);
            // if(isset($restracted) && $restracted == true){
            //     return self::sendError('', 'You have not access.');
            // }
            if (!empty($request->ip())) {
                $request->merge(['user_ip' => $request->ip()]);
            }
        }
        if (!empty($user)) {
            try {
                if (!empty($request->register_type) && $request->register_type == '2') {
                    $user = $this->user_repo->getbyEmail($request);
                } else {
                    $user = $this->user_repo->getbyMobileNo($request);
                }

                $mobile_code = $this->user_repo->generateOTPCode();
                $data = ['otp_code' => $mobile_code, 'user_ip' => !empty($request->user_ip) ? $request->user_ip : null];
                $message = 'Your OTP for [' . config('app.name') . '] is: ' . $mobile_code;

                try {
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
                } catch (\Exception $e) {
                    return self::sendError('', 'SMS Sending Failed');
                }

                try {
                    if (!empty($request->register_type) && $request->register_type == '2') {
                        $subject = 'Forgot Password OTP Verification' . ' | ' . config('app.name');
                        $toMail = $request->email;
                        $toName = '';
                        $templateName = 'forget_otp';
                        $templateData = $mobile_code;
                        $controller = new CustomEmailController();
                        // Call the sendEmail method with the necessary data
                        $controller->sendEmail($subject, $toMail, $toName, $templateName, $templateData);
                        // Mail::to($request->email)->send(new ForgetPasswordOtp($mobile_code));
                    } else {
                        $sent_msg = $this->user_repo->sendMessage($message, $request->country_code . $request->mobile_no);
                    }
                } catch (\Exception $e) {
                    return self::sendError('', 'SMS Sending Failed');
                }
                if (!empty($sent_msg)) {
                    return self::sendError('', 'SMS Sending Failed');
                }
                $this->user_repo->dataCrudUsingData($data, $user->id);
                $update_user = $this->user_repo->getById($user->id);
                return self::sendSuccess([
                    'token' => $user->createToken('EzzyCare')->accessToken,
                    'data' => $data,
                ]);
            } catch (\Exception $e) {
                return self::sendException($e);
            }
        } else {
            if (!empty($request->register_type) && $request->register_type == '2') {
                return self::sendError('', 'User Email Id Not Registered.');
            } else {
                return self::sendError('', 'User Mobile No. Not Registered');
            }
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
        if (!empty($request->register_type) && $request->register_type == '2') {
            $user = $this->user_repo->getbyEmail($request);
        } else {
            $user = $this->user_repo->getbyMobileNo($request);
        }

        if (!empty($user)) {
            try {
                $data = ['password' => Hash::make($request->password)];
                $this->user_repo->dataCrudUsingData($data, $user->id);
                $update_user = $this->user_repo->getById($user->id);
                return self::sendSuccess([], 'Password Reset');
            } catch (\Exception $e) {
                return self::sendException($e);
            }
        } else {
            if (!empty($request->register_type) && $request->register_type == '2') {
                return self::sendError('', 'User Email Id Not Registered.');
            } else {
                return self::sendError('', 'User Mobile No. Not Registered');
            }
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
        if (!empty($user)) {
            try {
                if (Hash::check($request->password, $request->user()->password)) {
                    $data = ['password' => Hash::make($request->new_password)];
                    $this->user_repo->dataCrudUsingData($data, $request->user()->id);
                    $update_user = $this->user_repo->getById($request->user()->id);
                    return self::sendSuccess('', 'Password Reset');
                }
                return self::sendError('', 'Current Password Not Match');
            } catch (\Exception $e) {
                return self::sendException($e);
            }
        } else {
            if (!empty($request->register_type) && $request->register_type == '2') {
                return self::sendError('', 'User Email Id Not Registered.');
            } else {
                return self::sendError('', 'User Mobile No. Not Registered');
            }
        }
    }

    /*
     * login user loogut.
    */
    public function userLogout(Request $request)
    {
        try {
            $user = $this->user_repo->getById($request->user()->id);
            $notification_topic = $this->notification_repo->getNotificationTopic();
            $this->notification_repo->unsubscribeNotificationTopic($user->device_token, 'Ezzycare');
            $this->notification_repo->unsubscribeNotificationTopic($user->device_token, !empty($user->category_id) ? $notification_topic[$user->category_id] : $notification_topic['1']);
            $data = ['device_type' => NULL, 'device_token' => NULL];
            $this->user_repo->dataCrudUsingData($data, $request->user()->id);
            $this->user_repo->removeOauthAccessTokens($request->user()->id);
            return Self::sendSuccess('', 'Logout Successfull.');
        } catch (\Exception $e) {
            return self::sendException($e);
        }
    }

    public function testingFunction(Request $request)
    {
        try {
            $country_code = '+91';
            $mobile_no = '80008655549';
            $mobile_code = 111111;
            $message = 'Your OTP for [' . config('app.name') . '] is: ' . $mobile_code;
            // dd($message);
            // $sent_msg = $this->user_repo->sendMessage($message, $country_code . $mobile_no);
            $subject = 'Registration OTP Verification' . ' | ' . config('app.name');
            $toMail = 'parth.cears@gmail.com';
            $toName = '';
            $templateName = 'register_otp';
            $templateData = $mobile_code;
            $controller = new CustomEmailController();
            // Call the sendEmail method with the necessary data
            $re = $controller->sendEmail($subject, $toMail, $toName, $templateName, $templateData);
            dd($re);
            // Mail::to('parth.cears@gmail.com')->send(new RegistrationOtp($mobile_code));
            // dd($sent_msg);
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
