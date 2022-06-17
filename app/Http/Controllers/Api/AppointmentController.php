<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\AppointmentRepository;
use App\Repositories\AppointmentServiceRepository;
use App\Repositories\UserServiceRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\UserTransactionRepository;
use App\Repositories\UserLocationRepository;
use App\Repositories\ManageFeesRepository;
use App\Repositories\VoucherCodeRepository;
use App\Http\Requests\Api\AppointmentRequest;
use App\Http\Requests\Api\UrgentAppointmentRequest;
use App\Http\Requests\Api\AppointmentStatusRequest;
use App\Http\Requests\Api\AppointmentRescheduleRequest;
use App\Http\Requests\Api\AppointmentLaboratoryRequest;
use App\Http\Requests\Api\AppointmentCompletedRequest;
use App\Http\Requests\Api\AppointmentCheckRequest;
use App\Http\Requests\Api\ReviewRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon as Carbon;
use App\Http\Controllers\Api\WalletController;
use PDF;
use Log;
use App\Http\Helpers\Helper;

class AppointmentController extends BaseApiController
{
    private $user_location_repo, $voucher_code_repo, $appointment_repo, $appointment_service_repo, $user_service_repo, $user_repo, $notification_repo, $user_transaction_repo, $manage_fees_repo;

    public function __construct(
            AppointmentRepository $appointment_repo, 
            AppointmentServiceRepository $appointment_service_repo,
            UserServiceRepository $user_service_repo,
            NotificationRepository $notification_repo,
            UserRepository $user_repo,
            UserTransactionRepository $user_transaction_repo,
            ManageFeesRepository $manage_fees_repo,
            UserLocationRepository $user_location_repo,
            VoucherCodeRepository $voucher_code_repo
        )
    {
        parent::__construct();
        $this->appointment_repo = $appointment_repo;
        $this->appointment_service_repo = $appointment_service_repo;
        $this->user_service_repo = $user_service_repo;
        $this->user_repo = $user_repo;
        $this->notification_repo = $notification_repo;
        $this->user_transaction_repo = $user_transaction_repo;
        $this->manage_fees_repo = $manage_fees_repo;
        $this->user_location_repo = $user_location_repo;
        $this->voucher_code_repo = $voucher_code_repo;
    }

    public function walletUpdateBalance($user_id)
    {          
        try {
            $wallet_balance = $this->user_transaction_repo->checkPatientWalletBalance($user_id); 
            $update = ['wallet_balance'=> $wallet_balance];
            $this->user_repo->dataCrudUsingData($update, $user_id);             
            return self::sendSuccess($data, 'Wallet Update');
        } catch (\Exception $e) {
            return self::sendException($e);
        }
        return $total_earning;
    }

    public function getRequestAppointment(Request $request)
    {
        $data = array();
        $data = $this->appointment_repo->getPendingAppointment($request)->map(function ($response){
                                    return [
                                        'id'=>$response->id,
                                        'appointment_type'=>$response->appointment_type,
                                        'appointment_type_name'=>$response->appointment_type_name,
                                        'appointment_date'=>$response->appointment_date,
                                        'appointment_time'=>$response->appointment_time,
                                        'appointment_end_date'=>$response->appointment_end_date,
                                        'appointment_end_time'=>$response->appointment_end_time,
                                        'urgent'=>!empty($response->urgent) ? $response->urgent : 0,
                                        'client'=>(isset($response->client))?
                                                        [
                                                            'id'=>$response->client->id,
                                                            'user_name'=>$response->client->user_name,
                                                            'profile_image'=>$response->client->profile_image
                                                        ]:'',
                                        'user'=>(isset($response->user))?
                                                        [
                                                            'id'=>$response->user->id,
                                                            'user_name'=>$response->user->user_name,
                                                            'profile_image'=>$response->user->profile_image
                                                        ]:'',
                                        'status'=>$response->status,
                                        'status_name'=>$response->status_name,
                                    ];
                                });
        return self::sendSuccess($data, 'User Appointment Request');
    }
   
    public function getAllAppointment(Request $request)
    {
        $data = array();
        $data= $this->appointment_repo->getAllAppointment($request)->map(function ($response){
                                    return [
                                        'id'=>$response->id,
                                        'appointment_type'=>$response->appointment_type,
                                        'appointment_type_name'=>$response->appointment_type_name,
                                        'appointment_date'=>$response->appointment_date,
                                        'appointment_time'=>$response->appointment_time,
                                        'appointment_end_date'=>$response->appointment_end_date,
                                        'appointment_end_time'=>$response->appointment_end_time,
                                        'urgent'=>!empty($response->urgent) ? $response->urgent : 0,
                                        'client'=>(isset($response->client))?
                                                        [
                                                            'id'=>$response->client->id,
                                                            'user_name'=>$response->client->user_name,
                                                            'profile_image'=>$response->client->profile_image
                                                        ]:'',
                                        'user'=>(isset($response->user))?
                                                    [
                                                        'id'=>$response->user->id,
                                                        'user_name'=>$response->user->user_name,
                                                        'profile_image'=>$response->user->profile_image
                                                    ]:'',
                                        'status'=>$response->status,
                                        'status_name'=>$response->status_name,
                                    ];
                                });
        return self::sendSuccess($data);
    }
  
    public function getUpcomingAppointment(Request $request)
    {
        $data = array();
        $data= $this->appointment_repo->getUpcomingAppointment($request)->map(function ($response){
                                    return [
                                        'id'=>$response->id,
                                        'appointment_type'=>$response->appointment_type,
                                        'appointment_type_name'=>$response->appointment_type_name,
                                        'appointment_date'=>$response->appointment_date,
                                        'appointment_time'=>$response->appointment_time,
                                        'appointment_end_date'=>$response->appointment_end_date,
                                        'appointment_end_time'=>$response->appointment_end_time,
                                        'start_datetime'=>$response->start_datetime,
                                        'urgent'=>!empty($response->urgent) ? $response->urgent : 0,
                                        'client'=>(isset($response->client))?
                                                        [
                                                            'id'=>$response->client->id,
                                                            'user_name'=>$response->client->user_name,
                                                            'profile_image'=>$response->client->profile_image
                                                        ]:'',
                                        'user'=>(isset($response->user))?
                                                    [
                                                        'id'=>$response->user->id,
                                                        'user_name'=>$response->user->user_name,
                                                        'profile_image'=>$response->user->profile_image
                                                    ]:'',
                                        'status'=>$response->status,
                                        'status_name'=>$response->status_name,
                                    ];
                                });
        return self::sendSuccess($data);
    }
   
    public function getPendingAppointment(Request $request)
    {
        $data = array();
        $data = $this->appointment_repo->getPendingAppointment($request)->map(function ($response){
                                    return [
                                        'id'=>$response->id,
                                        'appointment_type'=>$response->appointment_type,
                                        'appointment_type_name'=>$response->appointment_type_name,
                                        'appointment_date'=>$response->appointment_date,
                                        'appointment_time'=>$response->appointment_time,
                                        'urgent'=>!empty($response->urgent) ? $response->urgent : 0,
                                        'client'=>(isset($response->client))?
                                                        [
                                                            'id'=>$response->client->id,
                                                            'user_name'=>$response->client->user_name,
                                                            'profile_image'=>$response->client->profile_image
                                                        ]:'',
                                        'user'=>(isset($response->user))?
                                                    [
                                                        'id'=>$response->user->id,
                                                        'user_name'=>$response->user->user_name,
                                                        'profile_image'=>$response->user->profile_image
                                                    ]:'',
                                        'status'=>$response->status,
                                        'status_name'=>$response->status_name,
                                    ];
                                });
        return self::sendSuccess($data);
    }

    public function getCancelledAppointment(Request $request)
    {
        $data = array();
        $data = $this->appointment_repo->getCancelledAppointment($request)->map(function ($response){
                                    return [
                                        'id'=>$response->id,
                                        'appointment_type'=>$response->appointment_type,
                                        'appointment_type_name'=>$response->appointment_type_name,
                                        'appointment_date'=>$response->appointment_date,
                                        'appointment_time'=>$response->appointment_time,
                                        'appointment_end_date'=>$response->appointment_end_date,
                                        'appointment_end_time'=>$response->appointment_end_time,
                                        'cancel_reason'=>$response->cancel_reason,
                                        'cancel_date'=>$response->cancel_date,
                                        'urgent'=>!empty($response->urgent) ? $response->urgent : 0,
                                        'client'=>(isset($response->client))?
                                                        [
                                                            'id'=>$response->client->id,
                                                            'user_name'=>$response->client->user_name,
                                                            'profile_image'=>$response->client->profile_image
                                                        ]:'',
                                        'user'=>(isset($response->user))?
                                                    [
                                                        'id'=>$response->user->id,
                                                        'user_name'=>$response->user->user_name,
                                                        'profile_image'=>$response->user->profile_image
                                                    ]:'',
                                        'status'=>$response->status,
                                        'status_name'=>$response->status_name,
                                    ];
                                });
        return self::sendSuccess($data);
    }

    public function getCompletedAppointment(Request $request)
    {
        $data = array();
        $data = $this->appointment_repo->getCompletedAppointment($request)->map(function ($response){
                                    return [
                                        'id'=>$response->id,
                                        'appointment_type'=>$response->appointment_type,
                                        'appointment_type_name'=>$response->appointment_type_name,
                                        'appointment_date'=>$response->appointment_date,
                                        'appointment_time'=>$response->appointment_time,
                                        'appointment_end_date'=>$response->appointment_end_date,
                                        'appointment_end_time'=>$response->appointment_end_time,
                                        'completed_datetime'=>$response->completed_datetime,
                                        'appointment_price'=>$response->appointment_price,
                                        'urgent'=>!empty($response->urgent) ? $response->urgent : 0,
                                        'client'=>(isset($response->client))?
                                                        [
                                                            'id'=>$response->client->id,
                                                            'user_name'=>$response->client->user_name,
                                                            'profile_image'=>$response->client->profile_image
                                                        ]:'',
                                        'user'=>(isset($response->user))?
                                                    [
                                                        'id'=>$response->user->id,
                                                        'user_name'=>$response->user->user_name,
                                                        'profile_image'=>$response->user->profile_image
                                                    ]:'',
                                        'status'=>$response->status,
                                        'status_name'=>$response->status_name,
                                    ];
                                });
        return self::sendSuccess($data);
    }

    public function getActiveAppointment(Request $request)
    {
        $data = array();
        $data = $this->appointment_repo->getActiveAppointment($request)->map(function ($response){
                                    return [
                                        'id'=>$response->id,
                                        'appointment_type'=>$response->appointment_type,
                                        'appointment_type_name'=>$response->appointment_type_name,
                                        'appointment_date'=>$response->appointment_date,
                                        'appointment_time'=>$response->appointment_time,
                                        'appointment_end_date'=>$response->appointment_end_date,
                                        'appointment_end_time'=>$response->appointment_end_time,
                                        'completed_datetime'=>$response->completed_datetime,
                                        'appointment_price'=>$response->appointment_price,
                                        'urgent'=>!empty($response->urgent) ? $response->urgent : 0,
                                        'client'=>(isset($response->client))?
                                                        [
                                                            'id'=>$response->client->id,
                                                            'user_name'=>$response->client->user_name,
                                                            'profile_image'=>$response->client->profile_image
                                                        ]:'',
                                        'user'=>(isset($response->user))?
                                                    [
                                                        'id'=>$response->user->id,
                                                        'user_name'=>$response->user->user_name,
                                                        'profile_image'=>$response->user->profile_image
                                                    ]:'',
                                        'status'=>$response->status,
                                        'status_name'=>$response->status_name,
                                    ];
                                });
        return self::sendSuccess($data);
    }
    
    public function addAppointment(AppointmentRequest $request)
    {
        $data = array();

        //Appointment book check user wallet balance
        $wallet_balance = $this->user_transaction_repo->checkPatientWalletBalance($request->user()->id);
        $appointment_charges = Self::calculateAppointmentCharges($request);
        $currency_symbol = $this->user_repo->currency_symbol;

        // coupon code check 1-Healthcare, 3-Lab, 4-Radiologies
        if(!empty($request->voucher_code_id) && !empty($request->voucher_code_type)){

            $voucher_amount_apply = 0;
            $voucher_code = $this->voucher_code_repo->getbyIdVoucherType($request->voucher_code_id, $request->voucher_code_type);
            // $voucher_code = $this->voucher_code_repo->getbyIdVoucherType($request->voucher_code_id, '1');

            if(empty($voucher_code)){
                return self::sendError('', 'Voucher code does not apply');
            }

            $voucher_code_used = $this->appointment_repo->checkVoucherCodeUsed($request->user()->id, $request->voucher_code_id); 
            if(!empty($voucher_code_used) && !empty($voucher_code) && !empty($voucher_code->id) && $voucher_code->voucher_used == '0'){
                return self::sendError('', 'Voucher code is already used');
            }

            if(!empty($voucher_code) && !empty($voucher_code->id)){
                if($voucher_code->min_amount > $appointment_charges){
                    return self::sendError('', 'Voucher code does not apply');
                }
            }

            if(!empty($voucher_code) && !empty($voucher_code->id)){
                if(!empty($voucher_code->percentage)){
                    $voucher_amount_apply = (($appointment_charges / 100 ) * $voucher_code->percentage);
                }
                if($voucher_code->fix_amount > $voucher_amount_apply){
                    $voucher_amount_apply = $voucher_amount_apply;
                }else {
                    $voucher_amount_apply = $voucher_code->fix_amount;
                }
            }
            $walletCheck_balance = $appointment_charges - $voucher_amount_apply;
            if(isset($wallet_balance) && !empty($appointment_charges) && ($walletCheck_balance > $wallet_balance)){
                return self::sendError(['data' => 'no_minimum_balance'], 'Please Top up your wallet with a minimum of '.$currency_symbol.$walletCheck_balance.' before booking an appointment.', 402);
            }
        }else if(isset($wallet_balance) && !empty($appointment_charges) && ($appointment_charges > $wallet_balance)){
            return self::sendError(['data' => 'no_minimum_balance'], 'Please Top up your wallet with a minimum of '.$currency_symbol.$appointment_charges.' before booking an appointment.', 402);
        }
        
        //Appointment home care book
        if(!empty($request->appointment_type) && $request->appointment_type == '1'){
            $check_user_location = $this->user_repo->checkUserLocation($request);
            if(empty($check_user_location)){
                return self::sendError([], 'Please Add Location before Book Appointment.');
            }
        }
        
        //user timing check
        $user_available = $this->user_repo->checkUserAvailable($request);
        if(empty($user_available)){
            \Log::info("Provider not available ".json_encode($user_available));     
            return self::sendError([], 'Provider is not available on your selected time.');
        }

        //user free or not checking
        $check_appointment = $this->appointment_repo->checkUserAvailable($request);
        if(!empty($check_appointment)){
            \Log::info("Provider is busy ".json_encode($check_appointment));   
            return self::sendError([], 'Provider is already booked on your selected time.');
        }
        
        $appointment_address = "";
        
        $check_user_location = $this->user_location_repo->getbyUserPrimaryAddress($request->user()->id);
        if(!empty($request->address) && !empty($request->my_appointment)){
            $appointment_address = $request->address;
        }else if(!empty($check_user_location) && !empty($check_user_location->address)){
            $appointment_address = $check_user_location->address;
        }

        $add_data = [
                        'client_id' => $request->user()->id,
                        'user_id' => $request->user_id,
                        'appointment_type' => $request->appointment_type,
                        'name' => $request->name,
                        'email' => $request->email,
                        'mobile_no' => $request->mobile_no,
                        'age' => $request->age,
                        'gender' => $request->gender,
                        'reason' => $request->reason,
                        'urgent' => !empty($request->urgent) ? $request->urgent : 0,
                        'appointment_date' => $request->appointment_date,
                        'appointment_time' => $request->appointment_time,
                        'appointment_end_date' => !empty($request->appointment_end_date) ? $request->appointment_end_date : null,
                        'appointment_end_time' => !empty($request->appointment_end_time) ? $request->appointment_end_time : null,
                        'user_service_id' => !empty($request->user_service_id) ? $request->user_service_id : null,
                        'full_day' => isset($request->full_time) ? $request->full_time : 0,
                        'my_appointment' => isset($request->my_appointment) ? $request->my_appointment : 0,
                        'address' => isset($appointment_address) ? $appointment_address : '',
                        'city' => isset($request->city) ? $request->city : '',
                        'country' => isset($request->country) ? $request->country : '',
                        'voucher_code_type' => isset($request->voucher_code_type) ? $request->voucher_code_type : 1,
                        'status' => '0',
                    ];
             
        try {
            DB::beginTransaction();
            $data = $this->appointment_repo->dataCrud($add_data);
            if (!empty($request->user_services) && !empty($data) && is_array($request->user_services) && count($request->user_services) > 0) {
                foreach ($request->user_services as $key => $value) {
                    $service = $this->user_service_repo->getById($value);                    
                    $service_data=[
                                    'appointment_id'=> $data->id,
                                    'user_service_id'=>$value,
                                    'service_price'=>$service->service_charge,
                                  ];
                    $this->appointment_service_repo->dataCrud($service_data);
                }
            }
            if(!empty($data) && !empty($data->id)){
                $appointment_details = $this->appointment_repo->getById($data->id);
                $home_visit_fees = 0;
                $hcp_fees = 0;
                if(!empty($appointment_details->appointmentServices) && count($appointment_details->appointmentServices) > 0){           
                    foreach ($appointment_details->appointmentServices as $key => $value) {
                        $hcp_fees += $value->service_price;
                    }
                    if($appointment_details->appointment_type == '1'){
                         $home_visit_fees =  $appointment_details->user->userDetails->home_consultation_charge;
                    }
                } else {                 
                    if ($appointment_details->user->category_id == '6' || $appointment_details->user->category_id == '5') {
                        if ($appointment_details->full_day == '1') {
                            if($appointment_details->appointment_type == '1'){
                                $hcp_fees = $appointment_details->user->userDetails->nursing_home_visit_charge_full_day;      
                            }else{
                               $hcp_fees = $appointment_details->user->userDetails->nursing_facility_charge_full_day;   
                            }
                        } else {
                            if($appointment_details->appointment_type == '1'){
                                $hcp_fees = $appointment_details->user->userDetails->home_consultation_charge;      
                            }else {
                                $hcp_fees = $appointment_details->user->userDetails->clinic_consultation_charge;      
                            }                     
                        }
                    } else if ($appointment_details->user->category_id == '4') {
                        if ($appointment_details->urgent == '1') {
                            if($appointment_details->appointment_type == '1'){
                                $hcp_fees = $appointment_details->user->userDetails->home_consultation_charge;      
                                $home_visit_fees = $appointment_details->user->userDetails->urgent_fees;      
                            }else if($appointment_details->appointment_type == '2'){
                                $hcp_fees = $appointment_details->user->userDetails->video_consultation_charge;  
                                $home_visit_fees = $appointment_details->user->userDetails->urgent_fees;    
                            }else {
                                $hcp_fees = $appointment_details->user->userDetails->clinic_consultation_charge;    
                                $home_visit_fees = $appointment_details->user->userDetails->urgent_fees;    
                            }  
                        } else {
                            if($appointment_details->appointment_type == '1'){
                                $hcp_fees = $appointment_details->user->userDetails->home_consultation_charge;      
                            }else if($appointment_details->appointment_type == '2'){
                                $hcp_fees = $appointment_details->user->userDetails->video_consultation_charge;      
                            }else {
                                $hcp_fees = $appointment_details->user->userDetails->clinic_consultation_charge;      
                            }  
                        }
                    } else {
                        if($appointment_details->appointment_type == '1'){
                            $hcp_fees = $appointment_details->user->userDetails->home_consultation_charge;      
                        }else if($appointment_details->appointment_type == '2'){
                            $hcp_fees = $appointment_details->user->userDetails->video_consultation_charge;      
                        }else {
                            $hcp_fees = $appointment_details->user->userDetails->clinic_consultation_charge;      
                        }  
                    }
                } 

                $voucher_amount_apply = 0;
                // coupon code  1-Healthcare, 3-Lab, 4-Radiologies
                if(!empty($request->voucher_code_id) && !empty($request->voucher_code_type)){
                    $voucher_code = $this->voucher_code_repo->getbyIdVoucherType($request->voucher_code_id, $request->voucher_code_type);
                    // $voucher_code = $this->voucher_code_repo->getbyIdVoucherType($request->voucher_code_id, '1'); 
                    if(!empty($voucher_code) && !empty($voucher_code->id)){
                        
                        if(!empty($voucher_code->percentage)){
                            $voucher_amount_apply = (($appointment_charges / 100 ) * $voucher_code->percentage);
                        }

                        if($voucher_code->fix_amount > $voucher_amount_apply){
                            $voucher_amount_apply = $voucher_amount_apply;
                        }else {
                            $voucher_amount_apply = $voucher_code->fix_amount;
                        }

                        $this->voucher_code_repo->dataCrud(['quantity' => ($voucher_code->quantity - 1)], $request->voucher_code_id);    
                        
                        $updateuserVoucher = [
                            'voucher_code_id'=> (!empty($request->voucher_code_id)) ? $request->voucher_code_id : null,
                            'voucher_amount'=> $voucher_amount_apply,
                            'voucher_code_type' => isset($request->voucher_code_type) ? $request->voucher_code_type : 1,
                        ];
                        $this->appointment_repo->dataCrud($updateuserVoucher, $data->id);
                    }

                }

                $updateuser = [
                    'hcp_fees'=> $hcp_fees,
                    'home_visit_fees'=> $home_visit_fees,
                ];
                $this->appointment_repo->dataCrud($updateuser, $data->id);
            }


            if(!empty($data)){
                $transaction_amount = $appointment_charges - $voucher_amount_apply;
                $add_transaction = [
                        'user_id'=> $data->client_id,
                        'amount'=> $transaction_amount,
                        'mode_of_payment'=> '1',
                        'transaction_type'=> '0',
                        'transaction_date'=> $this->appointment_repo->getCurrentDateTime(),
                        'status'=> '3',
                        'payout_status' => '0',
                        'appointment_id' => $data->id,
                        'transaction_msg'=>'Appointment locked amount'
                    ];                    
                $transaction = $this->user_transaction_repo->dataCrud($add_transaction);
                
                $update_appointment = [
                        'transaction_id'=> $transaction->id,
                    ];                    
                $this->appointment_repo->dataCrud($update_appointment, $data->id);
                    
                // update Wallet Balance
                $this->user_repo->userWalletUpdate($request->user()->id);
                $user = $this->user_repo->getById($request->user_id);

                $send_notification = [
                                        'sender_id' => $request->user()->id,
                                        'receiver_id' => $request->user_id,
                                        'title' => 'Appointment',
                                        'message' => 'Appointment booked by '.$request->user()->user_name.' on '.$this->appointment_repo->getConvertLocalTimezoneDateTime($request->appointment_date.''.$request->appointment_time, $user->user_timezone),
                                        'parameter' => json_encode(['appointment_id'=> $data->id]),
                                        'msg_type' => '1',
                                    ];  
                $this->notification_repo->sendingNotification($send_notification);          
            }
            DB::commit();
            return self::sendSuccess($data);
        } catch (\Exception $e) {
            DB::rollBack();
            return self::sendException($e);
        }
    }
   
    public function addUrgentAppointment(UrgentAppointmentRequest $request)
    {
        $data = array();
        
        //Appointment book check user wallet balance
        $wallet_balance = $this->user_transaction_repo->checkPatientWalletBalance($request->user()->id);
        $minimum_balance = $this->manage_fees_repo->getbyFeesKey('minimum_wallet_balance');
        $currency_symbol = $this->user_repo->currency_symbol;
        if(isset($wallet_balance) && !empty($minimum_balance) && !empty($minimum_balance->fees_percentage) && ($minimum_balance->fees_percentage > $wallet_balance)){
            return self::sendError(['data' => 'no_minimum_balance'], 'Please Top up your wallet with a minimum of '.$currency_symbol.$minimum_balance->fees_percentage.' before booking an appointment.', 402);
        }
        
        if(!empty($request->appointment_type) && $request->appointment_type == '1'){
             //Appointment home care book
            $check_user_location = $this->user_repo->checkUserLocation($request);
            if(empty($check_user_location)){
                return self::sendError([], 'Please Add Location before Book Appointment.');
            }
        }
        
        // //user timing check
        // $user_available = $this->user_repo->checkUserAvailable($request);
        // if(empty($user_available)){
        //     return self::sendError([], 'Please Change Appointment Time Provider not available.');
        // }

        // //user free or not checking
        // $check_appointment = $this->appointment_repo->checkUserAvailable($request);
        // if(!empty($check_appointment)){
        //     return self::sendError([], 'Please Change Appointment Time Provider not available.');
        // }
      
        $add_data = [
                        'client_id' => $request->user()->id,
                        'appointment_type' => $request->appointment_type,
                        'name' => $request->name,
                        'email' => $request->email,
                        'mobile_no' => $request->mobile_no,
                        'gender' => $request->gender,
                        'age' => isset($request->age) ? $request->age : '',
                        'urgent' => 1,
                        'appointment_date' => $request->appointment_date,
                        'appointment_time' => $request->appointment_time,
                        'full_day' => isset($request->full_day) ? $request->full_day : 0,
                        'status' => '0'
                    ];
             
        try {
            DB::beginTransaction();
            $data = $this->appointment_repo->dataCrud($add_data);
            $healthcare_providers = $this->user_repo->getHealthcareProvidersUrgent($request);
            
            DB::commit();
            $healthcare_provider_assign = 0;
            if(count($healthcare_providers) > 0){
                foreach ($healthcare_providers as $healthcare_provider){
                    $healthcare_providerReq = $this->appointment_repo->getById($data->id);
                    $healthcare_provider_assign = $healthcare_providerReq->user_id;
                    // send notification
                    if(empty($healthcare_provider_assign) || $healthcare_provider_assign == '0'){
                        $user_timezone = $this->user_repo->getById($healthcare_provider->id);
                        $receiver_user = $this->user_repo->getById($healthcare_provider->id);
                        $sender_user = $this->user_repo->getById($request->user()->id);
                        $notification_user = [
                            'sender_id' => $request->user()->id,
                            'receiver_id' => $healthcare_provider->id,
                            'title' => 'Urgent Appointment',
                            'message' => 'Urgent appointment booked by '.$request->user()->user_name.' on '.$this->appointment_repo->getConvertLocalTimezoneDateTime($request->appointment_date.''.$request->appointment_time, $receiver_user->user_timezone),
                            'parameter' => json_encode(['appointment_id'=> $data->id,'notification_time'=>Carbon::now()->format('Y-m-d H:i:s')]),
                            'msg_type' => '1',
                        ]; 
                        Helper::sendOfflineChatNotification($notification_user, $receiver_user, $sender_user); 
                        Log::info("Notification send ".date('H:i:s'));
                        sleep(40);
                    }else{
                        break;
                    }
                }
                $healthcareProvider = $this->appointment_repo->getById($data->id);
                $healthcare_provider_assign = $healthcareProvider->user_id;
                Log::info($healthcare_provider_assign);
                Log::info("healthcare provider assign time ".date('H:i:s'));
                if(!empty($healthcare_provider_assign)){
                    Log::info("healthcare provider assign ".date('H:i:s'));
                    return self::sendSuccess($healthcareProvider);
                }else{
                    Log::info("healthcare provider not available ".date('H:i:s'));
                    $this->appointment_repo->destroy($data->id);
                    return self::sendError([],"The providers you requested are all currently engaged please expand your search and try again.");
                }
            }else{
                Log::info("healthcare provider not available ".date('H:i:s'));
                $this->appointment_repo->destroy($data->id);
                return Self::sendError([],"The providers you requested are all currently engaged please expand your search and try again.");
            }
            return self::sendSuccess($data);
        } catch (\Exception $e) {
            DB::rollBack();
            return self::sendException($e);
        }
    }

    public function changeAppointmentStatus(AppointmentStatusRequest $request)
    {
        $data = array();
        $update = [
                    'status'=> $request->status,
                    'cancel_reason'=> !empty($request->cancel_reason) && $request->status == '6' ? $request->cancel_reason : null,
                    'cancel_date'=> !empty($request->cancel_date) && $request->status == '6' ? $request->cancel_date : null,
                    'cancel_user_id'=> !empty($request->cancel_date) && $request->status == '6' ? $request->user()->id : null,
                    'consult_notes'=> !empty($request->consult_notes) ? $request->consult_notes : null,
                  ];

        if(!empty($request->user()->category_id) && !empty($request->status) && $request->status == '1'){          
            //user appointment is running
            $appointment_det = $this->appointment_repo->getById($request->id);
            $check_appointment = $this->appointment_repo->checkAcceptTimeUserAvailable($appointment_det, $request->user()->id);
            if(!empty($check_appointment)){
                \Log::info("Provider is busy ".json_encode($check_appointment));   
                return self::sendError([], 'Your appointment is running.');
            }

            $update['accepted_date'] =  $this->appointment_repo->getCurrentDateTime();
        }
        
        $appointment = $this->appointment_repo->getById($request->id);
        $check_appointment_book  = new Carbon($appointment->appointment_date.''.$appointment->appointment_time);
        $accept_appointment  = new Carbon($appointment->accepted_date);
        $current_appointment   = $this->appointment_repo->getCurrentDateTime();
        $current_time   = new Carbon();
        $current_time = $current_time->format('Y-m-d');
        if($request->status == '2' && $check_appointment_book->format('Y-m-d') != $current_time){
                return self::sendError([], 'Please check appointment date');
        }
        
        if($request->status == '2' && !empty($request->start_datetime)){
            $start_appointment  = new Carbon($appointment->appointment_date.''.$appointment->appointment_time);
            $end_appointment   = new Carbon($appointment->appointment_end_date.''.$appointment->appointment_end_time);
            $appointment_timing_slot =  $start_appointment->diffInMinutes($end_appointment);
            if(!empty($appointment_timing_slot)){
                $endAppointmentDateTime = Carbon::parse($request->start_datetime)->addMinute($appointment_timing_slot);
                $endAppointmentDate = $endAppointmentDateTime->format('Y-m-d');
                $endAppointmentTime = $endAppointmentDateTime->format('H:i:s');
                $startappointment = [
                    'start_datetime'=> Carbon::parse($request->start_datetime)->format('Y-m-d H:i:s'),
                    'appointment_end_date'=> $endAppointmentDate,
                    'appointment_end_time'=> $endAppointmentTime,
                ];
            }else{
                $startappointment = [
                    'start_datetime'=> Carbon::parse($request->start_datetime)->format('Y-m-d H:i:s'),
                ];
            }
            $this->appointment_repo->dataCrud($startappointment, $request->id);
        }
      
        if(empty($request->user()->category_id) && $appointment->urgent == '1' && $request->status == '6'){
            $old_transaction_cahrges = 0;
            $cancellation_charge_per = 0;
            $cancellation_charge = $this->manage_fees_repo->getbyFeesKey('urgent_cancellation_charges');
            if(!empty($cancellation_charge->fees_percentage)){                    
                $cancellation_charge_per = $cancellation_charge->fees_percentage;
            } 
            $wallet_balance = $this->user_transaction_repo->checkPatientWalletBalance($request->user()->id);
            $currency_symbol = $this->user_repo->currency_symbol;
            if(isset($wallet_balance) && !empty($cancellation_charge_per) && ($cancellation_charge_per > $wallet_balance)){
                return self::sendError(['data' => 'no_minimum_balance'], 'Please Top up your wallet with a minimum of '.$currency_symbol.$cancellation_charge_per.' before reschedule an appointment.', 402);
            }
            
            if(empty($request->user()->category_id) && $request->status == '6' && $appointment->status == '1'){
                $extra_charges = 0;
                $ezzycare_charge = 0;
                $user_payout = 0;
                $ezzycare_fees = 0;
                $transaction_amount = $cancellation_charge_per;
    
                if(!empty($appointment->user->category_id)){                    
                    $manage_fees = $this->manage_fees_repo->getbyCategoryId($appointment->user->category_id);
                    if(!empty($manage_fees->fees_percentage)){
                        $ezzycare_fees = $manage_fees->fees_percentage;
                    }
                }
                $ezzycare_charge = (($transaction_amount * $ezzycare_fees ) / 100);
                $user_payout = $transaction_amount - $ezzycare_charge;
                $add_transaction = [
                                'user_id'=> $appointment->client_id,
                                'client_id'=> $appointment->user_id,
                                'transaction_date'=> $this->appointment_repo->getCurrentDateTime(),
                                'mode_of_payment'=> '1',
                                'transaction_type'=> '0',
                                'status'=> '0',
                                'payout_status' => '1',
                                'amount' => $transaction_amount,
                                'payout_amount'=> '0',
                                'fees_charge'=> $transaction_amount,
                                'appointment_id' => $appointment->id,
                                'transaction_msg'=>'Urgent Appointment cancellation charges',
                            ];
                    
            }

        }else if($appointment->urgent == '0' && $request->status == '6'){
            if(!empty($appointment->transaction_id)){
                $old_transaction_cahrges = 0;
                $cancellation_charge_per = 0;
                $cancellation_charge = $this->manage_fees_repo->getbyFeesKey('cancellation_charges');
                $old_transaction = $this->user_transaction_repo->getById($appointment->transaction_id);
                if(!empty($cancellation_charge->fees_percentage)){                    
                    $cancellation_charge_per = $cancellation_charge->fees_percentage;
                } 
                if(!empty($old_transaction) && !empty($cancellation_charge_per)){
                    $old_transaction_cahrges = (($old_transaction->amount * $cancellation_charge_per ) / 100);
                }

                $updaappoint = [
                    'transaction_id'=> NULL,
                ];
                $this->appointment_repo->dataCrud($updaappoint, $request->id);      
                if(!empty($appointment->voucher_code_id) && !empty($appointment->voucher_code_type)){
                    // coupon code  1-Healthcare, 3-Lab, 4-Radiologies
                    $voucher_code = $this->voucher_code_repo->getbyIdVoucherTypeget($appointment->voucher_code_id, $appointment->voucher_code_type);
                    // $voucher_code = $this->voucher_code_repo->getbyIdVoucherTypeget($appointment->voucher_code_id, '1'); 
                    if(!empty($voucher_code)){
                        $this->voucher_code_repo->dataCrud(['quantity' => ($voucher_code->quantity + 1)], $appointment->voucher_code_id);   
                        $updateVoucher = [
                            'voucher_code_id'=> NULL,
                            'voucher_amount'=> NULL,
                        ];
                        $this->appointment_repo->dataCrud($updateVoucher, $request->id);   
                    }
                }
         
                $this->user_transaction_repo->destroy($appointment->transaction_id);
                $this->user_repo->userWalletUpdate($appointment->client_id);  
            }    
            $appointment_timing =  $accept_appointment->diffInMinutes($current_appointment);
            if(empty($request->user()->category_id) && !empty($old_transaction_cahrges) && $request->status == '6' && $appointment->status == '1'){
                $extra_charges = 0;
                $ezzycare_charge = 0;
                $user_payout = 0;
                $ezzycare_fees = 0;
                $transaction_amount = $old_transaction_cahrges;
    
                if(!empty($appointment->user->category_id)){                    
                    $manage_fees = $this->manage_fees_repo->getbyCategoryId($appointment->user->category_id);
                    if(!empty($manage_fees->fees_percentage)){
                        $ezzycare_fees = $manage_fees->fees_percentage;
                    }
                }
                $ezzycare_charge = (($transaction_amount * $ezzycare_fees ) / 100);
                $user_payout = $transaction_amount - $ezzycare_charge;
                $add_transaction = [
                                'user_id'=> $appointment->client_id,
                                'client_id'=> $appointment->user_id,
                                'transaction_date'=> $this->appointment_repo->getCurrentDateTime(),
                                'mode_of_payment'=> '1',
                                'transaction_type'=> '0',
                                'status'=> '0',
                                'payout_status' => '1',
                                'amount' => $transaction_amount,
                                'payout_amount'=> 0,
                                'fees_charge'=> $transaction_amount,
                                'appointment_id' => $appointment->id,
                                'transaction_msg'=>'Appointment cancellation charges',
                            ];
                    
            }
        }   
     
        try {
            DB::beginTransaction();
            if(!empty($add_transaction)){
                $transaction = $this->user_transaction_repo->dataCrud($add_transaction);                
                $update_appoint = [
                        'transaction_id'=> $transaction->id,
                    ];
                $this->appointment_repo->dataCrud($update_appoint, $request->id);        
                // update Wallet Balance
                $this->user_repo->userWalletUpdate($appointment->client_id);  
            }        
            $this->appointment_repo->dataCrud($update, $request->id);
            $data = $this->appointment_repo->getById($request->id);
            // '0' => 'Pending','1' => 'Upcoming','2' => 'In progress','3' => 'Paid','4' => 'Unpaid','5' => 'Completed','6' => 'Cancel'
            $notification_message = '';
            if($request->status == '1'){
                $notification_message = 'Appointment accepted by '. $request->user()->user_name;
            }else if($request->status == '2'){
                $notification_message = 'Appointment started by '. $request->user()->user_name;
            }else if($request->status == '5'){
                $notification_message = 'Appointment completed by '. $request->user()->user_name;
            }else if($request->status == '6'){
                $notification_message = 'Appointment cancelled by '. $request->user()->user_name;
            }else{
                $notification_message = 'Appointment '.strtolower($data->status_name).' by '. $request->user()->user_name;
            }            

            if (!empty($data)) {
                $send_notification = [
                                        'sender_id' => $request->user()->id,
                                        'receiver_id' => ($request->user()->id == $data->user_id) ? $data->client_id : $data->user_id,
                                        'title' => 'Appointment',
                                        'message' => $notification_message,
                                        'parameter' => json_encode(['appointment_id'=> $data->id, 'status'=>$data->status]),
                                        'msg_type' => '2',
                                    ];
                $this->notification_repo->sendingNotification($send_notification);
            }
            DB::commit();
            if(!empty($data)){
                return self::sendSuccess($data->format(), 'Appointment status change');
            }
            $this->user_repo->userWalletUpdate($appointment->client_id); 
            return self::sendSuccess($data, 'Appointment status change');
        } catch (\Exception $e) {
            DB::rollBack();
            return self::sendException($e);
        }
    }
  
    public function rescheduleAppointment(AppointmentRescheduleRequest $request)
    {
        $appointment = $this->appointment_repo->getById($request->id);
        //user timing check
        $user_available = $this->user_repo->checkRescheduleAppointmentUserAvailable($request, $appointment);
        if(empty($user_available)){
            \Log::info("Provider not available ".json_encode($user_available));     
            return self::sendError([], 'Please Change Appointment Time Provider not available.');
        }

        //user free or not checking
        $check_appointment = $this->appointment_repo->checkRescheduleAppointmentUserAvailable($request, $appointment);
        if(!empty($check_appointment)){
            \Log::info("Provider is busy ".json_encode($check_appointment));   
            return self::sendError([], 'Please Change Appointment Time Provider is busy.');
        }

        $data = array();
        $start_appointment  = new Carbon($appointment->appointment_time);
        $end_appointment   = new Carbon($appointment->appointment_end_time);
        $appointment_timing_slot =  $start_appointment->diffInMinutes($end_appointment);
          
      
        $update = [
                    'appointment_date'=> Carbon::parse($request->appointment_date)->format('Y-m-d'),
                    'appointment_time'=> Carbon::parse($request->appointment_time)->format('H:i:s'),
                    'appointment_end_date'=> !empty($request->appointment_end_date) ?  Carbon::parse($request->appointment_end_date)->format('Y-m-d') : Carbon::parse($request->appointment_date)->addMinute($appointment_timing_slot)->format('Y-m-d'),
                    'appointment_end_time'=> !empty($request->appointment_end_time) ?  Carbon::parse($request->appointment_end_time)->format('H:i:s') : Carbon::parse($request->appointment_time)->addMinute($appointment_timing_slot)->format('H:i:s'),
                    'accepted_date' => Carbon::parse($request->appointment_date.' '.$request->appointment_time)->format('Y-m-d H:i:s'),
                    'status' => '1'
                  ];
                  
        $appointment = $this->appointment_repo->getById($request->id);
        $accepted_date  = new Carbon($appointment->accepted_date);
        $current_appointment   = $this->appointment_repo->getCurrentDateTime();
        $appointment_timing =  $accepted_date->diffInMinutes($current_appointment);
        $reschedule_charges = 0;
        $reschedule_charge_per = 0;
        $reschedule_charge = $this->manage_fees_repo->getbyFeesKey('reschedule_charges');
        $old_transaction = $this->user_transaction_repo->getById($appointment->transaction_id);
        if(!empty($reschedule_charge->fees_percentage)){                    
            $reschedule_charge_per = $reschedule_charge->fees_percentage;
        } 
        if(!empty($old_transaction) && !empty($reschedule_charge_per)){
            $reschedule_charges = (($old_transaction->amount * $reschedule_charge_per ) / 100);
        }
        if(empty($request->user()->category_id) && !empty($reschedule_charges) && $appointment->status == '1'){
            $wallet_balance = $this->user_transaction_repo->checkPatientWalletBalance($request->user()->id);
            $currency_symbol = $this->user_repo->currency_symbol;
            if(isset($wallet_balance) && !empty($reschedule_charges) && ($reschedule_charges > $wallet_balance)){
                return self::sendError(['data' => 'no_minimum_balance'], 'Please Top up your wallet with a minimum of '.$currency_symbol.$reschedule_charges.' before reschedule an appointment.', 402);
            }
            $ezzycare_charge = 0;
            $user_payout = 0;
            $ezzycare_fees = 0;
            if(!empty($appointment->user->category_id)){                    
                $manage_fees = $this->manage_fees_repo->getbyCategoryId($appointment->user->category_id);
                if(!empty($manage_fees->fees_percentage)){
                    $ezzycare_fees = $manage_fees->fees_percentage;
                }
            }
            $ezzycare_charge = (($reschedule_charges * $ezzycare_fees ) / 100);
            $user_payout = $reschedule_charges - $ezzycare_charge;
            $add_transaction = [
                        'user_id'=> $request->user()->id,
                        'client_id'=> $appointment->user_id,
                        'transaction_date'=> $this->appointment_repo->getCurrentDateTime(),
                        'amount'=> !empty($reschedule_charges) ? $reschedule_charges : '',                        
                        'mode_of_payment'=> '1',
                        'transaction_type'=> '0',
                        'status'=> '0',
                        'payout_status' => '1',
                        'payout_amount'=> '0',
                        'fees_charge'=> !empty($reschedule_charges) ? $reschedule_charges : '',                          
                        'appointment_id' => $appointment->id,
                        'transaction_msg'=>'Appointment reschedule charges',
                    ];
        }
        try {
            DB::beginTransaction();
            if(!empty($add_transaction)){
                $transaction = $this->user_transaction_repo->dataCrud($add_transaction);
                // update Wallet Balance
                $this->user_repo->userWalletUpdate($appointment->client_id);                  
            } 
            $this->appointment_repo->dataCrud($update, $request->id);
            $data = $this->appointment_repo->getById($request->id);
            if (!empty($data)) {
                $user_timezone = $this->user_repo->getById($data->client_id);
                $send_notification = [
                                        'sender_id' => $request->user()->id,
                                        'receiver_id' => ($request->user()->id == $data->user_id) ? $data->client_id : $data->user_id,
                                        'title' => 'Appointment',
                                        'message' => 'Appointment rescheduled by '.$request->user()->user_name.' on '.$this->appointment_repo->getConvertLocalTimezoneDateTime($request->appointment_date.''.$request->appointment_time, $user_timezone->user_timezone),                                        
                                        'parameter' => json_encode(['appointment_id'=> $data->id, 'status'=>$data->status]),
                                        'msg_type' => '2',
                                    ];
                $this->notification_repo->sendingNotification($send_notification);
            }
            DB::commit();
            if(!empty($data)){
                return self::sendSuccess($data->format(), 'Reschedule Appointment');
            }
            return self::sendSuccess($data, 'Reschedule Appointment');
        } catch (\Exception $e) {
            DB::rollBack();
            return self::sendException($e);
        }
    }

    public function completedAppointment(AppointmentCompletedRequest $request)
    {
        $appointmentCheck = $this->appointment_repo->getById($request->id);
        if($appointmentCheck->status == '1'){
            return self::sendError([], 'Appointment is Pending.');
        }else if($appointmentCheck->status == '5'){
            return self::sendError([], 'Appointment already Completed.');
        }else if($appointmentCheck->status == '6'){
            return self::sendError([], 'Appointment is Cancelled.');
        }
        
        $data = array();
        $update = [
                    'completed_datetime'=> Carbon::parse($request->completed_datetime)->format('Y-m-d H:i:s'),
                    'consult_notes'=> (!empty($request->consult_notes)) ? $request->consult_notes : "",
                    'status'=> $request->status,
                  ];

        try {
            DB::beginTransaction();
            $this->appointment_repo->dataCrud($update, $request->id);
            $appointment_details = $this->appointment_repo->getById($request->id);
            $transaction_amount = 0;
            $totalTransaction_amount = 0;
            $voucher_amount = 0;
            $hcp_fees = 0;
            $home_visit_fees = 0;
            $full_day = 0;
           
            $appointment_days = 0;
            $appointment_timing = 0;
            if(!empty($appointment_details->full_day) && $appointment_details->full_day == '1'){
                $start_appointment  = new Carbon($appointment_details->start_datetime);
                $end_appointment   = new Carbon($appointment_details->completed_datetime);
                $appointment_days =  $start_appointment->diffInDays($end_appointment);
                $appointment_days =  $appointment_days + 1;
            }else{              
                $start_appointment  = new Carbon($appointment_details->start_datetime);
                $end_appointment   = new Carbon($appointment_details->completed_datetime);
                $appointment_timing =  $start_appointment->diffInSeconds($end_appointment);
                $appointment_timing = $appointment_timing / 60;
            }
            
            if(!empty($appointment_details->appointmentServices) && count($appointment_details->appointmentServices) > 0){           
                foreach ($appointment_details->appointmentServices as $key => $value) {
                    $transaction_amount += $value->service_price;
                }
                if($appointment_details->appointment_type == '1'){
                    $transaction_amount +=  $appointment_details->home_visit_fees;
                }
            } else {                 
                if ($appointment_details->user->category_id == '6' || $appointment_details->user->category_id == '5') {
                    if ($appointment_details->full_day == '1') {
                        if($appointment_details->appointment_type == '1'){
                            $transaction_amount = $appointment_details->hcp_fees * $appointment_days;   
                        }else{
                            $transaction_amount = $appointment_details->hcp_fees * $appointment_days;
                        }
                        $full_day = 1;
                    } else {
                        if($appointment_details->appointment_type == '1'){
                            $transaction_amount = $appointment_details->hcp_fees * ($appointment_timing/60);
                        }else {
                            $transaction_amount = $appointment_details->hcp_fees * ($appointment_timing/60);
                        }                     
                    }
                } else if ($appointment_details->user->category_id == '4') {
                    if ($appointment_details->urgent == '1') {
                        if($appointment_details->appointment_type == '1'){
                            $transaction_amount = $appointment_details->hcp_fees * $appointment_timing;
                            $transaction_amount += $appointment_details->home_visit_fees;        
                        }else if($appointment_details->appointment_type == '2'){
                            $transaction_amount = $appointment_details->hcp_fees * $appointment_timing;
                            $transaction_amount += $appointment_details->home_visit_fees;     
                        }else {
                            $transaction_amount = $appointment_details->hcp_fees * $appointment_timing; 
                            $transaction_amount += $appointment_details->home_visit_fees;    
                        }  
                    } else {
                        if($appointment_details->appointment_type == '1'){
                            $transaction_amount = $appointment_details->hcp_fees * $appointment_timing;   
                        }else if($appointment_details->appointment_type == '2'){
                            $transaction_amount = $appointment_details->hcp_fees * $appointment_timing;
                        }else {
                            $transaction_amount = $appointment_details->hcp_fees * $appointment_timing; 
                        }  
                    }
                } else {
                    if($appointment_details->appointment_type == '1'){
                        $transaction_amount = $appointment_details->hcp_fees * ($appointment_timing/60);    
                    }else if($appointment_details->appointment_type == '2'){
                        $transaction_amount = $appointment_details->hcp_fees * ($appointment_timing/60);      
                    }else {
                        $transaction_amount = $appointment_details->hcp_fees * ($appointment_timing/60);  
                    }  
                }
            }

            $voucher_amount_apply = 0;
            $totalTransaction_amount = $transaction_amount;
            $voucher_amount_apply = $appointment_details->voucher_amount;
            if($voucher_amount_apply > $transaction_amount){
                if(!empty($appointment_details->voucher_code_id) && !empty($appointment_details->voucher_code_type)){
                    // coupon code  1-Healthcare, 3-Lab, 4-Radiologies
                    $voucher_amount_apply = 0;
                    $voucher_code = $this->voucher_code_repo->getbyIdVoucherTypeget($appointment_details->voucher_code_id, $appointment_details->voucher_code_type);
                    // $voucher_code = $this->voucher_code_repo->getbyIdVoucherTypeget($appointment_details->voucher_code_id, '1'); 
                    if(!empty($voucher_code) && !empty($voucher_code->id)){
                        if(!empty($voucher_code->percentage)){
                            $voucher_amount_apply = (($transaction_amount / 100 ) * $voucher_code->percentage);
                        }
                        if($voucher_code->fix_amount > $voucher_amount_apply){
                            $voucher_amount_apply = $voucher_amount_apply;
                        }else {
                            $voucher_amount_apply = $voucher_code->fix_amount;
                        }
                    }
                }
            }

            $transaction_amount = $transaction_amount - $voucher_amount_apply;
            $update = [
                    'status'=> '5',
                    'full_day'=> $full_day,
                    'appointment_price'=> $transaction_amount,
                    'voucher_amount'=> $voucher_amount_apply,
                ];
            $this->appointment_repo->dataCrud($update, $request->id);
            
            if (!empty($appointment_details)) {
                if(!empty($appointment_details->transaction_id)){
                    $old_transaction = $this->user_transaction_repo->getById($appointment_details->transaction_id);
                }
                $extra_charges = 0;
                $ezzycare_charge = 0;
                $user_payout = 0;
                $ezzycare_fees = 0;
                if(!empty($appointment_details->user->category_id)){                    
                    $manage_fees = $this->manage_fees_repo->getbyCategoryId($appointment_details->user->category_id);
                    if(!empty($manage_fees->fees_percentage)){
                        $ezzycare_fees = $manage_fees->fees_percentage;
                    }
                }
                $ezzycare_charge = (($totalTransaction_amount * $ezzycare_fees ) / 100);
                $user_payout = $totalTransaction_amount - $ezzycare_charge;
                $add_transaction = [
                            'user_id'=> $appointment_details->client_id,
                            'client_id'=> $appointment_details->user_id,
                            'transaction_date'=> $this->appointment_repo->getCurrentDateTime(),
                            'mode_of_payment'=> '1',
                            'transaction_type'=> '0',
                            'status'=> '0',
                            'payout_status' => '1',
                            'amount' => $transaction_amount,
                            'payout_amount'=> $user_payout,
                            'fees_charge'=> $ezzycare_charge,
                            'appointment_id' => $appointment_details->id,
                            'transaction_msg'=> ($appointment_details->urgent == '1') ? 'Urgent Appointment' : 'Appointment',
                        ];
                
                $transaction = $this->user_transaction_repo->dataCrud($add_transaction);                
                $update_appoint = [
                        'transaction_id'=> $transaction->id,
                    ];
                $this->appointment_repo->dataCrud($update_appoint, $request->id);    
                if(!empty($old_transaction) && !empty($transaction) && $transaction_amount > $old_transaction->amount){
                        $send_notification = [
                            'sender_id' => $request->user()->id,
                            'receiver_id' => ($request->user()->id == $appointment_details->client_id) ? $appointment_details->user_id : $appointment_details->client_id,
                            'title' => 'Appointment',
                            'message' => 'Appointment charges is exceeded',
                            'parameter' => json_encode(['appointment_id'=> $appointment_details->id, 'status'=>$appointment_details->status]),
                            'msg_type' => '2',
                        ];
                        $this->notification_repo->sendingNotification($send_notification);
                }     
                if(!empty($old_transaction->id)){                    
                    $this->user_transaction_repo->destroy($old_transaction->id);
                }   

                 // update Wallet Balance
                $this->user_repo->userWalletUpdate($appointment_details->client_id);

                $send_notification = [
                                        'sender_id' => $request->user()->id,
                                        'receiver_id' => ($request->user()->id == $appointment_details->client_id) ? $appointment_details->user_id : $appointment_details->client_id,
                                        'title' => 'Appointment',
                                        'message' => 'Appointment completed by '. $request->user()->user_name,
                                        'parameter' => json_encode(['appointment_id'=> $appointment_details->id, 'status'=>$appointment_details->status]),
                                        'msg_type' => '2',
                                    ];
                $this->notification_repo->sendingNotification($send_notification);

                // if(!empty($extra_charges) && $extra_charges != '0'){
                //         $send_notification = [
                //             'sender_id' => $request->user()->id,
                //             'receiver_id' => $appointment_details->client_id,
                //             'title' => 'Appointment',
                //             'message' => 'Appointment completed by '. $request->user()->user_name,
                //             'parameter' => json_encode(['appointment_id'=> $appointment_details->id]),
                //             'msg_type' => '2',
                //         ];
                //     $this->notification_repo->sendingNotification($send_notification);
                // }
            }

            $data = $this->appointment_repo->getById($request->id);
            DB::commit();
            if(!empty($data)){
                return self::sendSuccess($data->format(), 'Appointment Completed');
            }
            return self::sendSuccess($data, 'Appointment Completed');
        } catch (\Exception $e) {
            DB::rollBack();
            return self::sendException($e);
        }
    }

    public function addAppointmentReview(ReviewRequest $request)
    {
    
        $data = array();
        $update = [
                    'user_rating'=> $request->rating,
                    'user_review'=> isset($request->comment) ? $request->comment :'',
                  ];

        try{
            DB::beginTransaction();
            $this->appointment_repo->dataCrud($update, $request->id);
            $data = $this->appointment_repo->getById($request->id);
            if (!empty($data)) {
                $send_notification = [
                                        'sender_id' => $request->user()->id,
                                        'receiver_id' => $data->user_id,
                                        'title' => 'Appointment',
                                        'message' => 'Appointment review added by '.$request->user()->user_name,
                                        'parameter' => json_encode(['appointment_id'=> $data->id, 'status'=>$data->status]),
                                        'msg_type' => '2',
                                    ];
                $this->notification_repo->sendingNotification($send_notification);
            }
            DB::commit();
            return self::sendSuccess($data, 'Appointment Add Review');
        }catch(\Exception $e){
            DB::rollBack();
            return self::sendException($e);
        }
        
    }

    public function getAppointmentById($appointment_id)
    {
        $data = $this->appointment_repo->getbyIdedit($appointment_id); 
        if(!empty($data)){
            return self::sendSuccess($data->format(), 'Appointment get');
        }
        return self::sendError($data, 'Appointment Timeout!');
    }

    public function generateInvoice($appointment_id)
    {
        $currency_symbol  = $this->appointment_repo->currency_symbol;
        $status = $this->appointment_repo->getStatusValue();
        $data = $this->appointment_repo->getbyIdedit($appointment_id); 
        view()->share(['data' => $data, 'status' => $status,'currency_symbol' => $currency_symbol]);
        //  return view('invoice.appointment');
        $pdf = PDF::loadView('invoice.appointment', [$data, $status, $currency_symbol]);
        $pdf_file = $this->appointment_repo->uploadPDFFile($pdf->output(), 'pdf/appointment_invoice','appointment_invoice'); 
        $file_url = url('storage/'.$pdf_file);
        return self::sendSuccess($file_url, 'Appointment Invoice get');
    }

    /** Accept Appointment */
    public function acceptAppointment(AppointmentStatusRequest $request){
        
        $appointment_det = $this->appointment_repo->checkUrgentAppointmentAccepted($request->id); 
        if(empty($appointment_det)){
            return self::sendError('', 'Request time out');
        }
        
        //user appointment is running
        $check_appointment = $this->appointment_repo->checkAcceptTimeUserAvailable($appointment_det, $request->user()->id);
        if(!empty($check_appointment)){
            \Log::info("Provider is busy ".json_encode($check_appointment));   
            return self::sendError([], 'Your appointment is running.');
        }

        DB::beginTransaction();
        try{
            $appointmentRequest= $this->appointment_repo->getById($request->id);
            $update_user = [
                        'user_id' => $request->user()->id,
                        'status' => '1',
                        'accepted_date' => $this->appointment_repo->getCurrentDateTime(),
                    ];
            $this->appointment_repo->dataCrud($update_user, $request->id);

            $appointment_details = $this->appointment_repo->getById($request->id);
           
            if($appointment_details->appointment_type == '1'){
                $hcp_fees = $appointment_details->user->userDetails->home_consultation_charge;       
            }else if($appointment_details->appointment_type == '2'){
                $hcp_fees = $appointment_details->user->userDetails->video_consultation_charge;     
            }else {
                $hcp_fees = $appointment_details->user->userDetails->clinic_consultation_charge;    
            }   
            
            $home_visit_fees = 0;
            $urgent_booking_charges = $this->manage_fees_repo->getbyFeesKey('urgent_booking_charges');
            if(!empty($urgent_booking_charges->fees_percentage)){                    
                $home_visit_fees = $urgent_booking_charges->fees_percentage;
            }

            if(!empty($appointment_details->appointment_date) && !empty($appointment_details->appointment_time)){
                $wallet_balance = $this->user_transaction_repo->checkPatientWalletBalance($appointment_details->client_id);
                $currency_symbol = $this->user_repo->currency_symbol;
                $walletBalanceCalculate = $wallet_balance - $home_visit_fees;
                $hcp_fees = (!empty($hcp_fees)) ? $hcp_fees : 1;
                $appointmentTimeCalculate = round($walletBalanceCalculate / $hcp_fees);
                if($appointmentTimeCalculate <= 60){
                    $start_appointment  = new Carbon($appointment_details->appointment_date.' '.$appointment_details->appointment_time);
                    $end_appointment   = new Carbon($appointment_details->appointment_date.' '.$appointment_details->appointment_time);
                    $end_appointment   = $end_appointment->addMinute($appointmentTimeCalculate);
                    $end_appointment_date = $end_appointment->format('Y-m-d');  
                    $end_appointment_time = $end_appointment->format('H:i:s');  
                }else{
                    $start_appointment  = new Carbon($appointment_details->appointment_date.' '.$appointment_details->appointment_time);
                    $end_appointment   = new Carbon($appointment_details->appointment_date.' '.$appointment_details->appointment_time);
                    $end_appointment   = $end_appointment->addMinute(60);
                    $end_appointment_date = $end_appointment->format('Y-m-d');  
                    $end_appointment_time = $end_appointment->format('H:i:s');  
                }
            }

            $updateuser = [
                'hcp_fees'=> $hcp_fees,
                'home_visit_fees'=> $home_visit_fees,
                'appointment_end_date'=> !empty($end_appointment_date) ?  $end_appointment_date : null,
                'appointment_end_time'=> !empty($end_appointment_time) ?  $end_appointment_time : null,
            ];
            $this->appointment_repo->dataCrud($updateuser, $request->id);

            if(!empty($data)){
                $send_notification = [
                                        'sender_id' => $request->user()->id,
                                        'receiver_id' => $appointmentRequest->client_id,
                                        'title' => 'Urgent Appointment',
                                        'message' => 'Urgent appointment request accepted by '.$request->user()->user_name,
                                        'parameter' => json_encode(['appointment_id'=> $appointmentRequest->id, 'status'=>$appointmentRequest->status,'notification_time'=>Carbon::now()->format('Y-m-d H:i:s')]),
                                        'msg_type' => '2',
                                    ];  
                $this->notification_repo->sendingNotification($send_notification);          
            }
            Log::info("Appointment Request Accepted".date('H:i:s'));
            DB::commit();
            $data = $this->appointment_repo->getById($request->id);
            if(!empty($data)){
                return self::sendSuccess($data->format(), 'Appointment Request Accepted Successfully');
            }
            return self::sendSuccess($data, 'Appointment Request Accepted Successfully');
        }catch(\Exception $e){
            DB::rollBack();
            return Self::sendException($e);
        }
    }

    public function getAppointmentProgressByUserId(Request $request, $client_id){
        $data = $this->appointment_repo->getbyClientIdToCheckAppointment($client_id, $request); 
        return self::sendSuccess($data, 'Appointment get data');
    }

    public function checkAppointmentClientWallet($client_id){
        $wallet_balance = $this->user_transaction_repo->checkPatientWalletBalance($client_id);
        $minimum_balance = $this->manage_fees_repo->getbyFeesKey('minimum_wallet_balance');
        if(isset($wallet_balance) && !empty($minimum_balance) && !empty($minimum_balance->fees_percentage) && ($minimum_balance->fees_percentage > $wallet_balance)){
            return self::sendError(['data' => 'no_minimum_balance'], 'Please Top up your wallet before start an appointment.', 402);
        }
        return self::sendSuccess([], 'wallet');
    }

    public function calculateAppointmentCharges($appointment_details){
            $transaction_amount = 0;
            $appointment_days = 0;
            $appointment_timing = 0;
            $user = $this->user_repo->getById($appointment_details->user_id);   
            if(!empty($appointment_details->full_time) && $appointment_details->full_time == '1'){
                $start_appointment  = new Carbon($appointment_details->appointment_date.' 00:00:01');
                $end_appointment   = new Carbon($appointment_details->appointment_end_date.' 23:59:00');
                $appointment_days =  $start_appointment->diffInDays($end_appointment);
                $appointment_days =  $appointment_days + 1;
            }else{              
                $start_appointment  = new Carbon($appointment_details->appointment_date.' '.$appointment_details->appointment_time);
                $end_appointment   = new Carbon($appointment_details->appointment_end_date.' '.$appointment_details->appointment_end_time);
                $appointment_timing =  $start_appointment->diffInSeconds($end_appointment);
                $appointment_timing = $appointment_timing/60;
            }
            if(!empty($appointment_details->user_services) && count($appointment_details->user_services) > 0){           
                foreach ($appointment_details->user_services as $key => $value) {
                    $appointment_service = $this->user_service_repo->getById($value);
                    $transaction_amount += $appointment_service->service_charge;
                }
                if($appointment_details->appointment_type == '1'){
                    $transaction_amount +=  $user->userDetails->home_consultation_charge;
                }
            } else {                 
                if ($user->category_id == '6' || $user->category_id == '5') {
                    if ($appointment_details->full_time == '1') {
                        if($appointment_details->appointment_type == '1'){
                            $transaction_amount = $user->userDetails->nursing_home_visit_charge_full_day * $appointment_days;
                        }else{
                            $transaction_amount = $user->userDetails->nursing_facility_charge_full_day * $appointment_days;
                        }
                    } else {
                        if($appointment_details->appointment_type == '1'){
                            $transaction_amount = $user->userDetails->home_consultation_charge * ($appointment_timing/60);
                        }else {
                            $transaction_amount = $user->userDetails->clinic_consultation_charge * ($appointment_timing/60);
                        }                     
                    }
                } else if ($user->category_id == '4') {
                    if ($appointment_details->urgent == '1') {
                        if($appointment_details->appointment_type == '1'){
                            $transaction_amount = $user->userDetails->home_consultation_charge * $appointment_timing;
                            // $transaction_amount += $user->userDetails->urgent_fees;      
                        }else if($appointment_details->appointment_type == '2'){
                            $transaction_amount = $user->userDetails->video_consultation_charge * $appointment_timing; 
                            // $transaction_amount += $user->userDetails->urgent_fees;   
                        }else {
                            $transaction_amount = $user->userDetails->clinic_consultation_charge * $appointment_timing; 
                            // $transaction_amount += $user->userDetails->urgent_fees;   
                        }  
                    } else {
                        if($appointment_details->appointment_type == '1'){
                            $transaction_amount = $user->userDetails->home_consultation_charge * $appointment_timing;
                        }else if($appointment_details->appointment_type == '2'){
                            $transaction_amount = $user->userDetails->video_consultation_charge * $appointment_timing;
                        }else {
                            $transaction_amount = $user->userDetails->clinic_consultation_charge * $appointment_timing;
                        }  
                    }
                } else {
                    if($appointment_details->appointment_type == '1'){
                        $transaction_amount = $user->userDetails->home_consultation_charge * ($appointment_timing/60);
                    }else if($appointment_details->appointment_type == '2'){
                        $transaction_amount = $user->userDetails->video_consultation_charge * ($appointment_timing/60);
                    }else {
                        $transaction_amount = $user->userDetails->clinic_consultation_charge * ($appointment_timing/60);
                    }  
                }
            }

        return $transaction_amount;
    }

    public function addAppointmentTimeExtend(Request $request){

        $wallet_balance = $this->user_transaction_repo->checkPatientWalletBalance($request->user()->id);
        $appointment_charges = Self::calculateAppointmentCharges($request);
        $currency_symbol = $this->user_repo->currency_symbol;
        if(isset($wallet_balance) && !empty($appointment_charges) && ($appointment_charges > $wallet_balance)){
            return self::sendError(['data' => 'no_minimum_balance'], 'Please Top up your wallet with a minimum of '.$currency_symbol.$appointment_charges.' before booking an appointment.', 402);
        }
                
        //user timing check
        $user_available = $this->user_repo->checkUserAvailable($request);
        if(empty($user_available)){
            \Log::info("Provider not available ".json_encode($user_available));     
            return self::sendError([], 'Provider is not available on your selected time.');
        }

        //user free or not checking
        $check_appointment = $this->appointment_repo->checkUserAvailableExtendtime($request);
        if(!empty($check_appointment)){
            \Log::info("Provider is busy ".json_encode($check_appointment));   
            return self::sendError([], 'Provider is already booked on your selected time.');
        }
        DB::beginTransaction();
        try{
            $data= $this->appointment_repo->getById($request->appointment_id);
            $update_user = [
                        'appointment_end_date' => !empty($request->appointment_end_date) ? $request->appointment_end_date : null,
                        'appointment_end_time' => !empty($request->appointment_end_time) ? $request->appointment_end_time : null,
                    ];
            $this->appointment_repo->dataCrud($update_user, $request->appointment_id);
            $old_transaction = $this->user_transaction_repo->getById($data->transaction_id);

            if(!empty($old_transaction)){
                $update_transaction = [
                    'amount'=> $old_transaction->amount + $appointment_charges,
                ];                    
                $this->user_transaction_repo->dataCrud($update_transaction, $data->transaction_id);
            }

            // update Wallet Balance
            $this->user_repo->userWalletUpdate($request->user()->id);
            $user = $this->user_repo->getById($request->user_id);

            $send_notification = [
                                'sender_id' => $request->user()->id,
                                'receiver_id' => $request->user_id,
                                'title' => 'Appointment',
                                'message' => 'Appointment extend by '.$request->user()->user_name.' on '.$this->appointment_repo->getConvertLocalTimezoneDateTime($request->appointment_date.''.$request->appointment_time, $user->user_timezone),
                                'parameter' => json_encode(['appointment_id'=> $data->id]),
                                'msg_type' => '1',
                            ];  
            $this->notification_repo->sendingNotification($send_notification);     
 
            $data = $this->appointment_repo->getById($request->appointment_id);
            DB::commit();
            return self::sendSuccess($data, 'Appointment get data');
        }catch(\Exception $e){
            DB::rollBack();
            return Self::sendException($e);
        }
    }

    public function getAllTrackingAppointment(Request $request){
        $appointment_details = $this->appointment_repo->getAllTrackingAppointment($request);
        return self::sendSuccess($appointment_details, 'TrackingAppointment');
    }

    
}
