<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use App\Http\Requests\Api\AppointmentRequest;
use App\Http\Requests\Api\AppointmentStatusRequest;

class AppointmentController extends BaseApiController
{

    public function getUpcomingAppointment(Request $request){
        $data = array();
        $data['status'] = $this->appointment_repo->status;
        $data['appointment_types'] = $this->appointment_repo->appointment_types;
        $data['result'] = $this->appointment_repo->getUpcomingAppointment($request);
        return self::sendSuccess($data);
    }
   
    public function getPendingAppointment(Request $request){
        $data = array();
        $data['status'] = $this->appointment_repo->status;
        $data['appointment_types'] = $this->appointment_repo->appointment_types;
        $data['result'] = $this->appointment_repo->getPendingAppointment($request);
        return self::sendSuccess($data);
    }

    public function getCancelledAppointment(Request $request){
        $data = array();
        $data['status'] = $this->appointment_repo->status;
        $data['appointment_types'] = $this->appointment_repo->appointment_types;
        $data['result'] = $this->appointment_repo->getCancelledAppointment($request);
        return self::sendSuccess($data);
    }

    public function getCompletedAppointment(Request $request){
        $data = array();
        $data['status'] = $this->appointment_repo->status;
        $data['appointment_types'] = $this->appointment_repo->appointment_types;
        $data['result'] = $this->appointment_repo->getCompletedAppointment($request);
        return self::sendSuccess($data);
    }
    
    public function addAppointment(AppointmentRequest $request){
        $data = array();
        $data = $this->appointment_repo->dataCrud($request);
        return self::sendSuccess($data);
    }

    public function acceptAppointment(AppointmentStatusRequest $request){
        $data = array();
        $update = ['status'=> '1'];
        $this->appointment_repo->update($update, $request->id);
        $data = $this->appointment_repo->getById($request->id);
        return self::sendSuccess($data);
    }

}
