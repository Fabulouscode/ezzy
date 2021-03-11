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
use App\Repositories\ManageFeesRepository;
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
use PDF;
use Log;

class AppointmentController extends BaseApiController
{
    private $appointment_repo, $appointment_service_repo, $user_service_repo, $user_repo, $notification_repo, $user_transaction_repo, $manage_fees_repo;

    public function __construct(
            AppointmentRepository $appointment_repo, 
            AppointmentServiceRepository $appointment_service_repo,
            UserServiceRepository $user_service_repo,
            NotificationRepository $notification_repo,
            UserRepository $user_repo,
            UserTransactionRepository $user_transaction_repo,
            ManageFeesRepository $manage_fees_repo
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
        $minimum_balance = $this->manage_fees_repo->getbyFeesKey('minimum_wallet_balance');
        if(isset($wallet_balance) && !empty($minimum_balance) && !empty($minimum_balance->fees_percentage) && ($minimum_balance->fees_percentage > $wallet_balance)){
            return self::sendError(['data' => 'no_minimum_balance'], 'Please fund wallet.', 402);
        }
        
        //Appointment home care book
        if(!empty($request->appointment_type) && $request->appointment_type == '1'){
            $check_user_location = $this->user_repo->checkUserLocation($request);
            if(empty($check_user_location)){
                return self::sendError([], 'Please Add Location after Book Appointment.');
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
        if(!empty($request->address) && !empty($request->my_appointment)){
            $appointment_address = $request->address;
        }else if(!empty($check_user_location) && !empty($check_user_location->userLocation)){
            $appointment_address = $check_user_location->userLocation->address;
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
                        'full_day' => isset($request->full_day) ? $request->full_day : 0,
                        'my_appointment' => isset($request->my_appointment) ? $request->my_appointment : 0,
                        'address' => isset($appointment_address) ? $appointment_address : '',
                        'city' => isset($request->city) ? $request->city : '',
                        'country' => isset($request->country) ? $request->country : '',
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
            if(!empty($data)){
                $send_notification = [
                                        'sender_id' => $request->user()->id,
                                        'receiver_id' => $request->user_id,
                                        'title' => 'Appointment',
                                        'message' => 'Appointment booked by '.$request->user()->user_name.' on '.$request->appointment_time,
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
        if(isset($wallet_balance) && !empty($minimum_balance) && !empty($minimum_balance->fees_percentage) && ($minimum_balance->fees_percentage > $wallet_balance)){
            return self::sendError(['data' => 'no_minimum_balance'], 'Please Add Wallet Balance before Booking Appointment.', 402);
        }
        
        if(!empty($request->appointment_type) && $request->appointment_type == '1'){
             //Appointment home care book
            $check_user_location = $this->user_repo->checkUserLocation($request);
            if(empty($check_user_location)){
                return self::sendError([], 'Please Add address.');
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
                        $send_notification = [
                                'sender_id' => $request->user()->id,
                                'receiver_id' => $healthcare_provider->id,
                                'title' => 'Urgent Appointment',
                                'message' => 'Urgent appointment booked by '.$request->user()->user_name.' on '.$request->appointment_time,
                                'parameter' => json_encode(['appointment_id'=> $data->id,'notification_time'=>Carbon::now()->format('Y-m-d H:i:s')]),
                                'msg_type' => '1',
                            ];  
                        $this->notification_repo->sendingNotification($send_notification);  
                        Log::info("Notification send ".date('H:i:s'));
                        sleep(30);
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
                    return self::sendError([],"No healthcare provider available, please try again.");
                }
            }else{
                Log::info("healthcare provider not available ".date('H:i:s'));
                $this->appointment_repo->destroy($data->id);
                return Self::sendError([],"No healthcare provider available, please try again.");
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
        if(!empty($request->status) && $request->status == '1'){
            $update['accepted_date'] =  $this->appointment_repo->getCurrentDateTime();
        }
        
        $appointment = $this->appointment_repo->getById($request->id);
        $check_appointment_book  = new Carbon($appointment->appointment_date.''.$appointment->appointment_time);
        $start_appointment  = new Carbon($appointment->accepted_date);
        $end_appointment   = $this->appointment_repo->getCurrentDateTime();
        $current_time   = new Carbon();
        $current_time = $current_time->format('Y-m-d');
        if($request->status == '2' && $check_appointment_book->format('Y-m-d') != $current_time){
                return self::sendError([], 'Please check appointment date');
        }
        $appointment_timing =  $start_appointment->diffInMinutes($end_appointment);
        if(empty($request->user()->category_id) && !empty($request->status) && !empty($appointment_timing) && $request->status == '6' && ($appointment_timing > $this->appointment_repo->cancel_timing_no_charge)){
            $minimum_balance = $this->manage_fees_repo->getbyFeesKey('minimum_wallet_balance');
            $add_transaction = [
                        'user_id'=> $request->user()->id,
                        'transaction_date'=> $this->appointment_repo->getCurrentDateTime(),
                        'amount'=> !empty($minimum_balance) ? $minimum_balance->fees_percentage : '',                        
                        'payment_gateway_response'=> '',
                        'mode_of_payment'=> '1',
                        'transaction_type'=> '1',
                        'wallet_transaction'=> '1',
                        'payout_status'=> '0',
                        'status'=> '0',
                    ];
        }
             
     
        try {
            DB::beginTransaction();
            if(!empty($add_transaction)){
                $transaction = $this->user_transaction_repo->dataCrud($add_transaction);
                $update['transaction_id'] = $transaction->id;
                self::walletUpdateBalance($request->user()->id);   
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
                                        'parameter' => json_encode(['appointment_id'=> $data->id]),
                                        'msg_type' => '2',
                                    ];
                $this->notification_repo->sendingNotification($send_notification);
            }
            DB::commit();
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
            \Log::info("Provider is busy ".json_encode($user_available));   
            return self::sendError([], 'Please Change Appointment Time Provider is busy.');
        }

        $data = array();
        $update = [
                    'appointment_date'=> Carbon::parse($request->appointment_date)->format('Y-m-d'),
                    'appointment_time'=> Carbon::parse($request->appointment_time)->format('H:i:s'),
                    'status' => '1'
                  ];
       
        $appointment = $this->appointment_repo->getById($request->id);
        $start_appointment  = new Carbon($appointment->accepted_date);
        $end_appointment   = $this->appointment_repo->getCurrentDateTime();
        $appointment_timing =  $start_appointment->diffInMinutes($end_appointment);
        if(empty($request->user()->category_id) && !empty($appointment_timing) && ($appointment_timing > $this->appointment_repo->cancel_timing_no_charge)){
            $minimum_balance = $this->manage_fees_repo->getbyFeesKey('minimum_wallet_balance');
            $add_transaction = [
                        'user_id'=> $request->user()->id,
                        'transaction_date'=> $this->appointment_repo->getCurrentDateTime(),
                        'amount'=> !empty($minimum_balance) ? $minimum_balance->fees_percentage : '',                        
                        'payment_gateway_response'=> '',
                        'mode_of_payment'=> '1',
                        'transaction_type'=> '1',
                        'wallet_transaction'=> '1',
                        'payout_status'=> '0',
                        'status'=> '0',
                    ];
        }

        try {
            DB::beginTransaction();
            if(!empty($add_transaction)){
                $transaction = $this->user_transaction_repo->dataCrud($add_transaction);
                self::walletUpdateBalance($request->user()->id);                   
            } 
            $this->appointment_repo->dataCrud($update, $request->id);
            $data = $this->appointment_repo->getById($request->id);
            if (!empty($data)) {
                $send_notification = [
                                        'sender_id' => $request->user()->id,
                                        'receiver_id' => ($request->user()->id == $data->user_id) ? $data->client_id : $data->user_id,
                                        'title' => 'Appointment',
                                        'message' => 'Appointment rescheduled by '. $request->user()->user_name,
                                        'parameter' => json_encode(['appointment_id'=> $data->id]),
                                        'msg_type' => '2',
                                    ];
                $this->notification_repo->sendingNotification($send_notification);
            }
            DB::commit();
            return self::sendSuccess($data, 'Reschedule Appointment');
        } catch (\Exception $e) {
            DB::rollBack();
            return self::sendException($e);
        }
    }

    public function completedAppointment(AppointmentCompletedRequest $request)
    {
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
            $voucher_amount = 0;
            $hcp_fees = 0;
            $home_visit_fees = 0;
            $full_day = 0;
            $start_appointment  = new Carbon($appointment_details->appointment_date.''.$appointment_details->appointment_time);
            $end_appointment  = new Carbon($appointment_details->appointment_end_date.''.$appointment_details->appointment_end_time);
            // $end_appointment   = new Carbon($appointment_details->completed_datetime);
            $appointment_timing =  $start_appointment->diffInMinutes($end_appointment);
            
            if(!empty($appointment_details->appointmentServices) && count($appointment_details->appointmentServices) > 0){           
                foreach ($appointment_details->appointmentServices as $key => $value) {
                    $transaction_amount += $value->service_price;
                    $hcp_fees += $value->service_price;
                }
                if($appointment_details->appointment_type == '1'){
                    $transaction_amount +=  $appointment_details->user->userDetails->home_consultation_charge;
                    $home_visit_fees =  $appointment_details->user->userDetails->home_consultation_charge;
                }
            } else {                 
                if ($appointment_details->user->category_id == '6' || $appointment_details->user->category_id == '5') {
                    $appointment_hour = $appointment_timing/60;
                    if ($appointment_hour > '24') {
                        if($appointment_details->appointment_type == '1'){
                            $appointment_timing = $appointment_timing - 1440;
                            $transaction_amount = $appointment_details->user->userDetails->nursing_home_visit_charge_full_day;
                            $hcp_fees = $appointment_details->user->userDetails->nursing_home_visit_charge_full_day;      
                        }
                        if($appointment_timing > '0') {
                            $transaction_amount = $appointment_details->user->userDetails->nursing_facility_charge_full_day;
                            $hcp_fees = $appointment_details->user->userDetails->nursing_facility_charge_full_day;      
                        }  
                        $full_day = 1;
                    } else {
                        if($appointment_details->appointment_type == '1'){
                            $transaction_amount = $appointment_details->user->userDetails->home_consultation_charge * ($appointment_timing/60);
                            $hcp_fees = $appointment_details->user->userDetails->home_consultation_charge;      
                        }else {
                            $transaction_amount = $appointment_details->user->userDetails->clinic_consultation_charge * ($appointment_timing/60);
                            $hcp_fees = $appointment_details->user->userDetails->clinic_consultation_charge;      
                        }                     
                    }
                } else if ($appointment_details->user->category_id == '4') {
                    if ($appointment_details->urgent == '1') {
                        if($appointment_details->appointment_type == '1'){
                            $transaction_amount = $appointment_details->user->userDetails->home_consultation_charge * $appointment_timing;
                            $hcp_fees = $appointment_details->user->userDetails->home_consultation_charge;      
                        }else if($appointment_details->appointment_type == '2'){
                            $transaction_amount = $appointment_details->user->userDetails->video_consultation_charge * $appointment_timing;
                            $hcp_fees = $appointment_details->user->userDetails->video_consultation_charge;      
                        }else {
                            $transaction_amount = $appointment_details->user->userDetails->clinic_consultation_charge * $appointment_timing;
                            $hcp_fees = $appointment_details->user->userDetails->clinic_consultation_charge;      
                        }  
                    } else {
                        if($appointment_details->appointment_type == '1'){
                            $transaction_amount = $appointment_details->user->userDetails->home_consultation_charge * $appointment_timing;
                            $hcp_fees = $appointment_details->user->userDetails->home_consultation_charge;      
                        }else if($appointment_details->appointment_type == '2'){
                            $transaction_amount = $appointment_details->user->userDetails->video_consultation_charge * $appointment_timing;
                            $hcp_fees = $appointment_details->user->userDetails->video_consultation_charge;      
                        }else {
                            $transaction_amount = $appointment_details->user->userDetails->clinic_consultation_charge * $appointment_timing;
                            $hcp_fees = $appointment_details->user->userDetails->clinic_consultation_charge;      
                        }  
                    }
                } else {
                    if($appointment_details->appointment_type == '1'){
                        $transaction_amount = $appointment_details->user->userDetails->home_consultation_charge * ($appointment_timing/60);
                        $hcp_fees = $appointment_details->user->userDetails->home_consultation_charge;      
                    }else if($appointment_details->appointment_type == '2'){
                        $transaction_amount = $appointment_details->user->userDetails->video_consultation_charge * ($appointment_timing/60);
                        $hcp_fees = $appointment_details->user->userDetails->video_consultation_charge;      
                    }else {
                        $transaction_amount = $appointment_details->user->userDetails->clinic_consultation_charge * ($appointment_timing/60);
                        $hcp_fees = $appointment_details->user->userDetails->clinic_consultation_charge;      
                    }  
                }
            }
     

            $update = [
                    'status'=> $request->status,
                    'full_day'=> $full_day,
                    'appointment_price'=> $transaction_amount,
                    'hcp_fees'=> $hcp_fees,
                    'home_visit_fees'=> $home_visit_fees,
                ];
            $this->appointment_repo->dataCrud($update, $request->id);
            
            if (!empty($appointment_details)) {
                $send_notification = [
                                        'sender_id' => $request->user()->id,
                                        'receiver_id' => $appointment_details->client_id,
                                        'title' => 'Appointment',
                                        'message' => 'Appointment completed by '. $request->user()->user_name,
                                        'parameter' => json_encode(['appointment_id'=> $appointment_details->id]),
                                        'msg_type' => '2',
                                    ];
                $this->notification_repo->sendingNotification($send_notification);
            }

            $data = $this->appointment_repo->getById($request->id);
            DB::commit();
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
                                        'parameter' => json_encode(['appointment_id'=> $data->id]),
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
        $data = $this->appointment_repo->getbyIdedit($appointment_id)->format(); 
        return self::sendSuccess($data, 'Appointment get');
    }

    public function generateInvoice($appointment_id)
    {
        $currency_symbol  = $this->appointment_repo->currency_symbol;
        $status = $this->appointment_repo->getStatusValue();
        $data = $this->appointment_repo->getbyIdedit($appointment_id); 
        view()->share(['data' => $data, 'status' => $status,'currency_symbol' => $currency_symbol]);
        //  return view('invoice.appointment');
        $pdf = PDF::loadView('invoice.appointment', [$data, $status, $currency_symbol]);
        $pdf_file = $this->appointment_repo->uploadPDFFile($pdf->output(), 'pdf/appointment_invoice'); 
        $file_url = url('storage/'.$pdf_file);
        return self::sendSuccess($file_url, 'Appointment Invoice get');
    }

        /** Accept Appointment */
    public function acceptAppointment(AppointmentStatusRequest $request){
        DB::beginTransaction();
        try{
            $appointmentRequest= $this->appointment_repo->getById($request->id);
            $update_user = [
                        'user_id' => $request->user()->id,
                        'status' => '1',
                        'accepted_date' => $this->appointment_repo->getCurrentDateTime(),
                    ];
            $this->appointment_repo->dataCrud($update_user, $request->id);
            if(!empty($data)){
                $send_notification = [
                                        'sender_id' => $request->user()->id,
                                        'receiver_id' => $appointmentRequest->client_id,
                                        'title' => 'Urgent Appointment',
                                        'message' => 'Urgent appointment request accepted by '.$request->user()->user_name,
                                        'parameter' => json_encode(['appointment_id'=> $appointmentRequest->id,'notification_time'=>Carbon::now()->format('Y-m-d H:i:s')]),
                                        'msg_type' => '2',
                                    ];  
                $this->notification_repo->sendingNotification($send_notification);          
            }
            Log::info("Appointment Request Accepted".date('H:i:s'));
            DB::commit();
            $data = $this->appointment_repo->getById($request->id);
            return self::sendSuccess($data, 'Appointment Request Accepted Successfully');
        }catch(\Exception $e){
            DB::rollBack();
            return Self::sendException($e);
        }
    }


    public function getAppointmentProgressByUserId($client_id){
        $data = $this->appointment_repo->getbyClientIdToCheckAppointment($client_id); 
        return self::sendSuccess($data, 'Appointment get data');
    }

    public function checkAppointmentClientWallet($client_id){
        $wallet_balance = $this->user_transaction_repo->checkPatientWalletBalance($client_id);
        $minimum_balance = $this->manage_fees_repo->getbyFeesKey('minimum_wallet_balance');
        if(isset($wallet_balance) && !empty($minimum_balance) && !empty($minimum_balance->fees_percentage) && ($minimum_balance->fees_percentage > $wallet_balance)){
            return self::sendError(['data' => 'no_minimum_balance'], 'Please fund wallet.', 402);
        }
        return self::sendSuccess([], 'wallet');
    }
}
