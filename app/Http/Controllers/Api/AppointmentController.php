<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\AppointmentRepository;
use App\Repositories\AppointmentServiceRepository;
use App\Repositories\UserServiceRepository;
use App\Http\Requests\Api\AppointmentRequest;
use App\Http\Requests\Api\AppointmentStatusRequest;
use App\Http\Requests\Api\AppointmentRescheduleRequest;
use App\Http\Requests\Api\AppointmentLaboratoryRequest;
use App\Http\Requests\Api\AppointmentCompletedRequest;
use App\Http\Requests\Api\ReviewRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon as Carbon;
use PDF;

class AppointmentController extends BaseApiController
{
    private $appointment_repo, $appointment_service_repo, $user_service_repo, $user_repo;

    public function __construct(
            AppointmentRepository $appointment_repo, 
            AppointmentServiceRepository $appointment_service_repo,
            UserServiceRepository $user_service_repo,
            UserRepository $user_repo
        )
    {
        parent::__construct();
        $this->appointment_repo = $appointment_repo;
        $this->appointment_service_repo = $appointment_service_repo;
        $this->user_service_repo = $user_service_repo;
        $this->user_repo = $user_repo;
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
        
        //user free or not checking
        $check_appointment = $this->appointment_repo->checkUserAvailable($request);
        if(!empty($check_appointment) || empty($user_available)){
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
                        'appointment_date' => $request->appointment_date,
                        'appointment_time' => $request->appointment_time,
                        'user_service_id' => !empty($request->user_service_id) ? $request->user_service_id : null,
                        'full_day' => isset($request->full_day) ? $request->full_day : 0,
                        'status' => '0'
                    ];
            
        // $send_notification = [
        //         'sender_id' => $request->user()->id,
        //         'receiver_id' => $request->user_id,
        //         'title' => 'Appointment',
        //         'message' => 'Appointment Book',
        //         'parameter' => json_encode(['notification_time'=> $this->notification_repo->getCurrentDateTime()]),
        //         'msg_type' => '1',
        //         ];   
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
            // $this->notification_repo->sendingNotification($send_notification, $request);
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
                  ];

        try {
            DB::beginTransaction();
            $this->appointment_repo->dataCrud($update, $request->id);
            $data = $this->appointment_repo->getById($request->id);
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

}
