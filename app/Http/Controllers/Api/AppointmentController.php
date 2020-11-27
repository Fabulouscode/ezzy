<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use App\Repositories\AppointmentRepository;
use App\Repositories\AppointmentServiceRepository;
use App\Http\Requests\Api\AppointmentRequest;
use App\Http\Requests\Api\AppointmentStatusRequest;
use App\Http\Requests\Api\AppointmentRescheduleRequest;
use App\Http\Requests\Api\AppointmentLaboratoryRequest;
use App\Http\Requests\Api\ReviewRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon as Carbon;

class AppointmentController extends BaseApiController
{
    private $appointment_repo;
    private $appointment_service_repo;

    public function __construct(AppointmentRepository $appointment_repo, AppointmentServiceRepository $appointment_service_repo)
    {
        parent::__construct();
        $this->appointment_repo = $appointment_repo;
        $this->appointment_service_repo = $appointment_service_repo;
    }


    public function getRequestAppointment(Request $request)
    {
        $data = array();
        $data = $this->appointment_repo->getPendingAppointment($request);
        return self::sendSuccess($data, 'User Appointment Request');
    }
   
    public function getUpcomingAppointment(Request $request)
    {
        $data = array();
        $data['status'] = $this->appointment_repo->status;
        $data['appointment_types'] = $this->appointment_repo->appointment_types;
        $data['result'] = $this->appointment_repo->getUpcomingAppointment($request);
        return self::sendSuccess($data);
    }
   
    public function getPendingAppointment(Request $request)
    {
        $data = array();
        $data['status'] = $this->appointment_repo->status;
        $data['appointment_types'] = $this->appointment_repo->appointment_types;
        $data['result'] = $this->appointment_repo->getPendingAppointment($request);
        return self::sendSuccess($data);
    }

    public function getCancelledAppointment(Request $request)
    {
        $data = array();
        $data['status'] = $this->appointment_repo->status;
        $data['appointment_types'] = $this->appointment_repo->appointment_types;
        $data['result'] = $this->appointment_repo->getCancelledAppointment($request);
        return self::sendSuccess($data);
    }

    public function getCompletedAppointment(Request $request)
    {
        $data = array();
        $data['status'] = $this->appointment_repo->status;
        $data['appointment_types'] = $this->appointment_repo->appointment_types;
        $data['result'] = $this->appointment_repo->getCompletedAppointment($request);
        return self::sendSuccess($data);
    }
    
    public function addAppointment(AppointmentRequest $request)
    {
        $data = array();
        $check_appointment = $this->appointment_repo->checkUserAvailable($request);
        if(!empty($check_appointment)){
            return self::sendError([], 'Please Change Appointment Time');
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
                        'status' => '0'
                    ];
        try {
            DB::beginTransaction();
            $data = $this->appointment_repo->dataCrud($add_data);
            DB::commit();
            return self::sendSuccess($data);
        } catch (\Exception $e) {
            DB::rollBack();
            return self::sendException($e);
        }
    }

    public function addLaboratoryAppointment(AppointmentLaboratoryRequest $request) 
    {
        $data = array();
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
                        'status' => '0'
                    ];
        try {
            DB::beginTransaction();
            $data = $this->appointment_repo->dataCrud($add_data);
            if (!empty($request->user_services) && !empty($data)) {
                foreach ($request->user_services as $key => $value) {
                    $service_data=[
                                    'appointment_id'=> $data->id,
                                    'user_service_id'=>$value,
                                  ];
                    $this->appointment_service_repo->dataCrud($service_data);
                }
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

    public function completedAppointment(Request $request)
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
                    'user_rating'=> isset($request->rating) ? $request->rating : NULL,
                    'user_review'=> isset($request->comment) ? $request->comment : NULL,
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

}
