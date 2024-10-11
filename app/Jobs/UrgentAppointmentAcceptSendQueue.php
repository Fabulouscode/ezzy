<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\Appointment;
use Carbon\Carbon as Carbon;

class UrgentAppointmentAcceptSendQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tries = 3;
    private $data;
    /**
     * Create a new job instance.
     * 
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('UrgentAppointmentAccept start');
        Log::info($this->data);
        if(!empty($this->data) && !empty($this->data['id'])){
            Log::info('UrgentAppointmentAccept data if');
            $appointment_det = Appointment::where('id', $this->data['id'])->where('urgent','1')->where('status','0')->first(); 
            Log::info($appointment_det);
            if(empty($appointment_det)){
                Log::info('UrgentAppointmentAccept if');
                $declineNotification = [
                    'sender_id' => NULL,
                    'receiver_id' => $this->data['user_id'],
                    'title' => 'Urgent Appointment',
                    'message' => 'Urgent appointment request declined',
                    'parameter' => json_encode(['appointment_id'=> $this->data['id'],'notification_time'=>Carbon::now()->format('Y-m-d H:i:s')]),
                    'msg_type' => '2',
                ];  
                try{
                    app('App\Http\Controllers\Api\NotificationController')->sendingNotification($declineNotification);
                }catch(\Throwable $th){
                    Log::info('UrgentAppointmentAccept end');
                    Log::info($th);
                } 
            }else{
                Log::info('UrgentAppointmentAccept else');
                app('App\Http\Controllers\Api\AppointmentController')->acceptAppointmentQueueWise($this->data);
            }
        }
       
        Log::info('UrgentAppointmentAccept end');
    }
}
