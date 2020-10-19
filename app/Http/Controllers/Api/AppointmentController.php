<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use App\Repositories\AppointmentRepository;

class AppointmentController extends BaseApiController
{
   private $appointment_repo;

    public function __construct(AppointmentRepository $appointment_repo){
        $this->appointment_repo = $appointment_repo;
    }

    public function getUpcomingAppointment(Request $request){
        $data = array();
        $data['status'] = $this->appointment_repo->status;
        $data['appointment_types'] = $this->appointment_repo->appointment_types;
        $data['result'] = $this->appointment_repo->getUpcomingAppointment($request);
        return self::sendSuccess($data, 'Upcoming Appointment details');
    }
   
    public function getPendingAppointment(Request $request){
        $data = array();
        $data['status'] = $this->appointment_repo->status;
        $data['appointment_types'] = $this->appointment_repo->appointment_types;
        $data['result'] = $this->appointment_repo->getPendingAppointment($request);
        return self::sendSuccess($data, 'Upcoming Appointment details');
    }

    public function getCancelledAppointment(Request $request){
        $data = array();
        $data['status'] = $this->appointment_repo->status;
        $data['appointment_types'] = $this->appointment_repo->appointment_types;
        $data['result'] = $this->appointment_repo->getCancelledAppointment($request);
        return self::sendSuccess($data, 'Upcoming Appointment details');
    }

    public function getCompletedAppointment(Request $request){
        $data = array();
        $data['status'] = $this->appointment_repo->status;
        $data['appointment_types'] = $this->appointment_repo->appointment_types;
        $data['result'] = $this->appointment_repo->getCompletedAppointment($request);
        return self::sendSuccess($data, 'Upcoming Appointment details');
    }

}
