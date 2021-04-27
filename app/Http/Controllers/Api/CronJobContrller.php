<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\UserTransactionRepository;
use App\Repositories\AppointmentRepository;
use App\Repositories\NotificationRepository;
use App\Http\Helpers\Helper;
use Carbon\Carbon;
use Log;

class CronJobContrller extends BaseApiController
{
    private $appointment_repo, $user_repo, $notification_repo, $user_transaction_repo;

    public function __construct(
            AppointmentRepository $appointment_repo, 
            NotificationRepository $notification_repo,
            UserTransactionRepository $user_transaction_repo,
            UserRepository $user_repo
        )
    {
        parent::__construct();
        $this->appointment_repo = $appointment_repo;
        $this->user_repo = $user_repo;
        $this->notification_repo = $notification_repo;
        $this->user_transaction_repo = $user_transaction_repo;
    }

    public function sendAppointmentExtendNotification(Request $request){        
         //5 min before send notification
        //Your appointment time will extend
        $running_appointment = $this->appointment_repo->getCurrentlyRunningAppointment();        
        
        // Log::info("running_appointment ".json_encode($running_appointment));     

        if(!empty($running_appointment) && count($running_appointment) > 0){
            foreach ($running_appointment as $key => $value) {
                $receiver_user = $this->user_repo->getById($value->user_id);
                $sender_user = '';
                $notification_user = [
                    'sender_id' => NULL,
                    'receiver_id' => $value->user_id,
                    'title' => 'Appointment',
                    'message' => 'Your appointment time will extend.',               
                    'parameter' => json_encode(['appointment_id'=> $value->id,'notification_time'=>$this->user_repo->getCurrentDateTime()]),   
                    'msg_type' => '1',
                ]; 
                Helper::sendOfflineChatNotification($notification_user, $receiver_user, $sender_user);
                
                $receiver_client = $this->user_repo->getById($value->client_id);
                $sender_client = '';
                $notification_client = [
                    'sender_id' => NULL,
                    'receiver_id' => $value->client_id,
                    'title' => 'Appointment',
                    'message' => 'Your appointment time will extend.',               
                    'parameter' => json_encode(['appointment_id'=> $value->id,'notification_time'=>$this->user_repo->getCurrentDateTime()]),   
                    'msg_type' => '1',
                ]; 
                Helper::sendOfflineChatNotification($notification_client, $receiver_client, $sender_client);
            }
        }
        return self::sendSuccess([], 'Notification send.');
    }

    public function sendAppointmentUpcomingNotification(Request $request){        
        //10 min before send notification
        //Your appointment will soon start please get ready
        $upcoming_appointment = $this->appointment_repo->getCurrentlyUpcomingAppointment();
      
        // Log::info("upcoming_appointment ".json_encode($upcoming_appointment));       
      
        if(!empty($upcoming_appointment) && count($upcoming_appointment) > 0){
            foreach ($upcoming_appointment as $key => $value) {
                $receiver_user = $this->user_repo->getById($value->user_id);
                $sender_user = '';
                $notification_user = [
                    'sender_id' => NULL,
                    'receiver_id' => $value->user_id,
                    'title' => 'Appointment',
                    'message' => 'Your appointment will soon start please get ready.',               
                    'parameter' => json_encode(['appointment_id'=> $value->id,'notification_time'=>$this->user_repo->getCurrentDateTime()]),   
                    'msg_type' => '1',
                ]; 
                Helper::sendOfflineChatNotification($notification_user, $receiver_user, $sender_user);
                
                $receiver_client = $this->user_repo->getById($value->client_id);
                $sender_client = '';
                $notification_client = [
                    'sender_id' => NULL,
                    'receiver_id' => $value->client_id,
                    'title' => 'Appointment',
                    'message' => 'Your appointment will soon start please get ready.',               
                    'parameter' => json_encode(['appointment_id'=> $value->id,'notification_time'=>$this->user_repo->getCurrentDateTime()]),   
                    'msg_type' => '1',
                ]; 
                Helper::sendOfflineChatNotification($notification_client, $receiver_client, $sender_client);
            }
        }

        return self::sendSuccess([], 'Notification send.');
    }

    // public function updateAppointmentElapsed(Request $request){        
    //     //5 min after not start to elapsed
    //     $upcoming_past_appointment = $this->appointment_repo->getUpcomingPastAppointment();
      
    //     // Log::info("upcoming_appointment ".json_encode($upcoming_past_appointment));       
      
    //     if(!empty($upcoming_past_appointment) && count($upcoming_past_appointment) > 0){
    //         foreach ($upcoming_past_appointment as $key => $value) {
    //                 $update_appointment = [
    //                     'status'=> '7',
    //                 ];                    
    //             $this->appointment_repo->dataCrud($update_appointment, $value->id);
    //         }
    //     }

    //     return self::sendSuccess([], 'Appointment update.');
    // }
   
    public function updateAppointmentCancel(Request $request){          
        $old_appointment = $this->appointment_repo->getOldAppointmentPending();
        $old_urgent_appointment = $this->appointment_repo->getOldUrgentAppointmentPending();
        // Log::info("old_appointment ".json_encode($old_appointment));    
        if(!empty($old_appointment) && count($old_appointment) > 0){
            foreach ($old_appointment as $key => $value) {
                $old_transaction = $value->transaction_id;
                $update = [
                    'status' => 6,
                    'cancel_date' => $this->appointment_repo->getCurrentDateTime(),
                    'cancel_reason' => 'Appointment Cancelled',
                    'transaction_id' => NULL,
                ];
                
                $this->appointment_repo->dataCrud($update, $value->id);       
                if(!empty($value->transaction_id)){ 
                    $this->user_transaction_repo->destroy($value->transaction_id);
                    $this->user_repo->userWalletUpdate($value->client_id); 
                }   
            }
        } 
        if(!empty($old_urgent_appointment) && count($old_urgent_appointment) > 0){
            foreach ($old_urgent_appointment as $key => $value) {
                $this->appointment_repo->destroy($value->id);
            }
        } 
        return self::sendSuccess([], 'old appointment cancel.');
    }
}
