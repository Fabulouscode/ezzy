<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\AppointmentRepository;
use App\Repositories\NotificationRepository;
use Log;

class CronJobContrller extends BaseApiController
{
    private $appointment_repo, $user_repo, $notification_repo;

    public function __construct(
            AppointmentRepository $appointment_repo, 
            NotificationRepository $notification_repo,
            UserRepository $user_repo
        )
    {
        parent::__construct();
        $this->appointment_repo = $appointment_repo;
        $this->user_repo = $user_repo;
        $this->notification_repo = $notification_repo;
    }

    public function sendAppointmentExtendNotification(Request $request){          
        $running_appointment = $this->appointment_repo->getCurrentlyRunningAppointment();
        Log::info("running_appointment ".json_encode($running_appointment));     
        return self::sendSuccess([], 'Notification send.');
    }
}
