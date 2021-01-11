<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\AppointmentRepository;
use App\Repositories\AppointmentServiceRepository;
use App\Repositories\UserServiceRepository;
use App\Repositories\NotificationRepository;
use App\Http\Requests\Api\AppointmentRequest;
use App\Http\Requests\Api\UrgentAppointmentRequest;
use App\Http\Requests\Api\AppointmentStatusRequest;
use App\Http\Requests\Api\AppointmentRescheduleRequest;
use App\Http\Requests\Api\AppointmentLaboratoryRequest;
use App\Http\Requests\Api\AppointmentCompletedRequest;
use App\Http\Requests\Api\ReviewRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon as Carbon;
use PDF;
use Log;

class AppointmentController extends BaseApiController
{
    private $appointment_repo, $appointment_service_repo, $user_service_repo, $user_repo, $notification_repo;

    public function __construct(
            AppointmentRepository $appointment_repo, 
            AppointmentServiceRepository $appointment_service_repo,
            UserServiceRepository $user_service_repo,
            NotificationRepository $notification_repo,
            UserRepository $user_repo
        )
    {
        parent::__construct();
        $this->appointment_repo = $appointment_repo;
        $this->appointment_service_repo = $appointment_service_repo;
        $this->user_service_repo = $user_service_repo;
        $this->user_repo = $user_repo;
        $this->notification_repo = $notification_repo;
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
        
        if(!empty($request->appointment_type) && $request->appointment_type == '1'){
             //Appointment home care book
            $check_user_location = $this->user_repo->checkUserLocation($request);
            if(empty($check_user_location)){
                return self::sendError([], 'Please Add address.');
            }
        }
        
        //user timing check
        $user_available = $this->user_repo->checkUserAvailable($request);
        if(empty($user_available)){
            return self::sendError([], 'Please Change Appointment Time Provider not available.');
        }

        //user free or not checking
        $check_appointment = $this->appointment_repo->checkUserAvailable($request);
        if(!empty($check_appointment)){
            return self::sendError([], 'Please Change Appointment Time Provider not available.');
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
                        'user_service_id' => !empty($request->user_service_id) ? $request->user_service_id : null,
                        'full_day' => isset($request->full_day) ? $request->full_day : 0,
                        'status' => '0'
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
                                        'message' => 'Appointment Book',
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
            
            $healthcare_provider_assign = 0;
            if(count($healthcare_providers) > 0){
                foreach ($healthcare_providers as $healthcare_provider){
                    $healthcare_providerReq = $this->appointment_repo->getById($data->id);
                    $healthcare_provider_assign = $healthcare_providerReq->user_id;
                    // send notification
                    if($healthcare_provider_assign == 0){
                        $send_notification = [
                                'sender_id' => $request->user()->id,
                                'receiver_id' => $healthcare_provider->id,
                                'title' => 'Urgent Appointment',
                                'message' => 'Urgent Appointment Book',
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
                if($healthcare_provider_assign){
                    Log::info("healthcare provider assign ".date('H:i:s'));
                    return self::sendSuccess($healthcareProvider);
                }else{
                     Log::info("healthcare provider not available ".date('H:i:s'));
                    return self::sendError([],"No healthcare provider available, please try again.");
                }
            }else{
                return Self::sendError([],"No healthcare provider available, please try again.");
            }
            DB::commit();
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
        try {
            DB::beginTransaction();
            $this->appointment_repo->dataCrud($update, $request->id);
            $data = $this->appointment_repo->getById($request->id);
            // '0' => 'Pending','1' => 'Upcoming','2' => 'In progress','3' => 'Paid','4' => 'Unpaid','5' => 'Completed','6' => 'Cancel'
            if (!empty($data)) {
                $send_notification = [
                                        'sender_id' => $request->user()->id,
                                        'receiver_id' => ($request->user()->id == $data->user_id) ? $data->user_id : $data->client_id,
                                        'title' => 'Appointment',
                                        'message' => 'Appointment is '. $data->status_name,
                                        'parameter' => json_encode(['appointment_id'=> $data->id]),
                                        'msg_type' => '1',
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
        $data = array();
        $update = [
                    'appointment_date'=> Carbon::parse($request->appointment_date)->format('Y-m-d'),
                    'appointment_time'=> Carbon::parse($request->appointment_time)->format('H:i:s'),
                    'status' => '0'
                  ];

        try {
            DB::beginTransaction();
            $this->appointment_repo->dataCrud($update, $request->id);
            $data = $this->appointment_repo->getById($request->id);
            if (!empty($data)) {
                $send_notification = [
                                        'sender_id' => $request->user()->id,
                                        'receiver_id' => ($request->user()->id == $data->user_id) ? $data->user_id : $data->client_id,
                                        'title' => 'Appointment',
                                        'message' => 'Appointment is Reschedule',
                                        'parameter' => json_encode(['appointment_id'=> $data->id]),
                                        'msg_type' => '1',
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
                    'status'=> $request->status,
                  ];

        try {
            DB::beginTransaction();
            $this->appointment_repo->dataCrud($update, $request->id);
            $data = $this->appointment_repo->getById($request->id);
            if (!empty($data)) {
                $send_notification = [
                                        'sender_id' => $request->user()->id,
                                        'receiver_id' => $data->client_id,
                                        'title' => 'Appointment',
                                        'message' => 'Appointment is Completed',
                                        'parameter' => json_encode(['appointment_id'=> $data->id]),
                                        'msg_type' => '1',
                                    ];
                $this->notification_repo->sendingNotification($send_notification);
            }
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
                                        'message' => 'Appointment review add',
                                        'parameter' => json_encode(['appointment_id'=> $data->id]),
                                        'msg_type' => '1',
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
                    ];
            $this->appointment_repo->dataCrud($update_user, $request->id);
            if(!empty($data)){
                $send_notification = [
                                        'sender_id' => $request->user()->id,
                                        'receiver_id' => $appointmentRequest->client_id,
                                        'title' => 'Urgent Appointment',
                                        'message' => 'Urgent Appointment Request Accepted',
                                        'parameter' => json_encode(['appointment_id'=> $appointmentRequest->id,'notification_time'=>Carbon::now()->format('Y-m-d H:i:s')]),
                                        'msg_type' => '1',
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
}
