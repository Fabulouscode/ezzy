<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\UserTransactionRepository;
use App\Repositories\AppointmentRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\ManageFeesRepository;
use App\Repositories\VoucherCodeRepository;
use App\Http\Helpers\Helper;
use Carbon\Carbon;
use Log;
use DB;

class CronJobContrller extends BaseApiController
{
    private $appointment_repo, $voucher_code_repo, $user_repo, $notification_repo, $user_transaction_repo, $manage_fees_repo;
    
    public function __construct(
            AppointmentRepository $appointment_repo, 
            NotificationRepository $notification_repo,
            UserTransactionRepository $user_transaction_repo,
            ManageFeesRepository $manage_fees_repo,
            VoucherCodeRepository $voucher_code_repo,
            UserRepository $user_repo
        )
    {
        parent::__construct();
        $this->appointment_repo = $appointment_repo;
        $this->user_repo = $user_repo;
        $this->notification_repo = $notification_repo;
        $this->user_transaction_repo = $user_transaction_repo;
        $this->manage_fees_repo = $manage_fees_repo;
        $this->voucher_code_repo = $voucher_code_repo;
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
                if(!empty($value->voucher_code_id)){
                    $voucher_code = $this->voucher_code_repo->getbyIdVoucherType($value->voucher_code_id, '1'); 
                    if(!empty($voucher_code)){
                        $this->voucher_code_repo->dataCrud(['quantity' => ($voucher_code->quantity + 1)], $value->voucher_code_id);   
                        $updateVoucher = [
                            'voucher_code_id'=> NULL,
                            'voucher_amount'=> NULL,
                        ];
                        $this->appointment_repo->dataCrud($updateVoucher, $value->id);   
                    }
                }
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
   
    public function completedVideoAppointment(Request $request){          
        $video_appointment = $this->appointment_repo->getInProgressVideoAppointment();
        if(!empty($video_appointment) && count($video_appointment) > 0){
            foreach ($video_appointment as $key => $value) {
                $current_time  =  Carbon::now();
                $urgent_appointment_time = new Carbon($value->appointment_date.' '.$value->appointment_time);
                $urgent_appointment_time = $urgent_appointment_time->addMinute(59);
                $appointment_end_time = new Carbon($value->appointment_end_date.' '.$value->appointment_end_time);
                $appointment_end_time = $appointment_end_time->subMinute(1);
                $url = config('app.url')."api/user/video/appointment/completed";
                if($value->urgent == '1' && !empty($appointment_end_time) && $current_time > $appointment_end_time){
                    $data = [
                        "id"=> $value->id,
                        "status"=> 4,
                        "completed_datetime"=> $current_time,
                        "consult_notes"=> '',
                    ];
                    self::completedAppointment($data);

                }else if($value->urgent == '1' && $value->appointment_type == '2' && $current_time > $urgent_appointment_time){
                    $data = [
                        "id"=> $value->id,
                        "status"=> 4,
                        "completed_datetime"=> $current_time,
                        "consult_notes"=> '',
                    ];
                    self::completedAppointment($data);
                 
                }else if($value->urgent == '0' && !empty($appointment_end_time) && $current_time > $appointment_end_time){
                    $data = [
                        "id"=> $value->id,
                        "status"=> 4,
                        "completed_datetime"=> $current_time,
                        "consult_notes"=> '',
                    ];
                    self::completedAppointment($data);
                }
            }
        } 
        return self::sendSuccess([], 'Video appointment complted.');
    }

    public function completedAppointment($data)
    {
        $update = [
                    'completed_datetime'=> Carbon::parse($data['completed_datetime'])->format('Y-m-d H:i:s'),
                    'consult_notes'=> (!empty($data['consult_notes'])) ? $data['consult_notes'] : "",
                    'status'=> $data['status'],
                  ];

        try {
            DB::beginTransaction();
            $this->appointment_repo->dataCrud($update, $data['id']);
            $appointment_details = $this->appointment_repo->getById($data['id']);
            $transaction_amount = 0;
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
            if(!empty($appointment_details->voucher_code_id)){
                $voucher_code = $this->voucher_code_repo->getbyIdVoucherType($appointment_details->voucher_code_id, '1'); 
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
                $transaction_amount = $transaction_amount - $voucher_amount_apply;
            }

            $update = [
                    'status'=> '5',
                    'full_day'=> $full_day,
                    'appointment_price'=> $transaction_amount,
                    'voucher_amount'=> $voucher_amount_apply,
                ];
            $this->appointment_repo->dataCrud($update, $data['id']);
            
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
                $ezzycare_charge = (($transaction_amount * $ezzycare_fees ) / 100);
                $user_payout = $transaction_amount - $ezzycare_charge;
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
                $this->appointment_repo->dataCrud($update_appoint, $data['id']);    
                if(!empty($old_transaction) && !empty($transaction) && $transaction_amount > $old_transaction->amount){
                        $send_notification = [
                            'sender_id' => $appointment_details->user_id,
                            'receiver_id' => $appointment_details->client_id,
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
                $user_details = $this->user_repo->getById($appointment_details->user_id);

                $send_notification = [
                                        'sender_id' => $appointment_details->user_id,
                                        'receiver_id' => $appointment_details->client_id,
                                        'title' => 'Appointment',
                                        'message' => 'Appointment completed by '. $user_details->user_name,
                                        'parameter' => json_encode(['appointment_id'=> $appointment_details->id, 'status'=>$appointment_details->status]),
                                        'msg_type' => '2',
                                    ];
                $this->notification_repo->sendingNotification($send_notification);

            }

            $data = $this->appointment_repo->getById($data['id']);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}
