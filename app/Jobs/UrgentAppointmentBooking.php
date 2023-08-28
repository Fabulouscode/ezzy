<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Http\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Appointment;
use App\Repositories\AppointmentRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon as Carbon;
class UrgentAppointmentBooking implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $appointmentId, $userId, $request;
    private $appointment_repo, $user_repo;
    public $tries = 1;
    public $timeout = 6000;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($appointmentId, $userId, $request)
    {
        $this->appointmentId = $appointmentId;
        $this->userId = $userId;
        $this->request = $request;
        Log::info("healthcare provider not available " . date('H:i:s'));
        Log::info($this->appointmentId);
        Log::info($this->userId);
        Log::info(json_encode($this->request));

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $data = Appointment::find($this->appointmentId);
            $healthcare_providers = $this->getHealthcareProvidersUrgent($this->request, $this->userId);
            $healthcare_provider_assign = 0;
            if (!empty($healthcare_providers) && count($healthcare_providers) > 0) {
                foreach ($healthcare_providers as $healthcare_provider) {
                    $healthcare_providerReq = Appointment::find($data->id);
                    $healthcare_provider_assign = $healthcare_providerReq->user_id;

                    // send notification
                    if (empty($healthcare_provider_assign) || $healthcare_provider_assign == '0') {
                        $receiver_user = User::find($healthcare_provider->id);
                        $sender_user = User::find($this->userId);
                        $notification_user = [
                            'sender_id' => $this->userId,
                            'receiver_id' => $healthcare_provider->id,
                            'title' => 'Urgent Appointment',
                            'message' => 'Urgent appointment booked by ' . $sender_user->user_name . ' on ' . $this->getConvertLocalTimezoneDateTime($data->appointment_date . '' . $data->appointment_time, $receiver_user->user_timezone),
                            'parameter' => json_encode(['appointment_id' => $data->id, 'notification_time' => Carbon::now()->format('Y-m-d H:i:s')]),
                            'msg_type' => '1',
                        ];

                        try {
                            Helper::sendOfflineChatNotification($notification_user, $receiver_user, $sender_user);
                            Log::info("Notification send " . date('H:i:s'));
                        } catch (\Exception $e) {
                            Log::info("Notification Exception not send " . date('H:i:s'));
                            Log::info($e);
                        } catch (\Throwable $e) {
                            Log::info("Notification Throwable not send " . date('H:i:s'));
                            Log::info($e);
                        }

                        sleep(40);
                    } else {
                        break;
                    }
                }
                $healthcareProvider = Appointment::find($data->id);
                $healthcare_provider_assign = $healthcareProvider->user_id;
                Log::info($healthcare_provider_assign);
                Log::info("healthcare provider assign time " . date('H:i:s'));
                if (!empty($healthcare_provider_assign)) {
                    Log::info("healthcare provider assign " . date('H:i:s'));
                    // return self::sendSuccess($healthcareProvider);
                    return true;
                } else {
                    Log::info("healthcare provider not available " . date('H:i:s'));
                    Appointment::find($data->id)->delete();
                    Log::info("response send " . date('H:i:s'));
                    $notification_user = [
                        'sender_id' => NULL,
                        'receiver_id' => $this->userId,
                        'title' => 'Urgent Appointment',
                        'message' => "The providers you requested are all currently engaged please expand your search and try again.",
                        'parameter' => json_encode(['notification_time' => Carbon::now()->format('Y-m-d H:i:s')]),
                        'msg_type' => '80',
                    ];
                    try {
                        Helper::sendOfflineChatNotification($notification_user, $receiver_user);
                        Log::info("Notification send " . date('H:i:s'));
                    } catch (\Exception $e) {
                        Log::info("Notification Exception not send " . date('H:i:s'));
                        Log::info($e);
                    } catch (\Throwable $e) {
                        Log::info("Notification Throwable not send " . date('H:i:s'));
                        Log::info($e);
                    }
    
                    // return self::sendError([],"The providers you requested are all currently engaged please expand your search and try again.");
                    return true;
                }
            } else {
                Log::info("healthcare provider not available " . date('H:i:s'));
                Appointment::find($data->id)->delete();
                Log::info("response send " . date('H:i:s'));
                $receiver_user = User::find($this->userId);
                $notification_user = [
                    'sender_id' => NULL,
                    'receiver_id' => $this->userId,
                    'title' => 'Urgent Appointment',
                    'message' => "The providers you requested are all currently engaged please expand your search and try again.",
                    'parameter' => json_encode(['notification_time' => Carbon::now()->format('Y-m-d H:i:s')]),
                    'msg_type' => '80',
                ];
                try {
                    Helper::sendOfflineChatNotification($notification_user, $receiver_user);
                    Log::info("Notification send " . date('H:i:s'));
                } catch (\Exception $e) {
                    Log::info("Notification Exception not send " . date('H:i:s'));
                    Log::info($e);
                } catch (\Throwable $e) {
                    Log::info("Notification Throwable not send " . date('H:i:s'));
                    Log::info($e);
                }

                // return Self::sendError([],"The providers you requested are all currently engaged please expand your search and try again.");
                return true;
            }
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info("response Exception ");
            Log::info($e);
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::info("response Throwable ");
            Log::info($th);
            return true;
        }
    }

    public function getConvertLocalTimezoneDateTime($timestamp, $timezone = '')
    {
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, 'UTC');
        return !empty($timezone) ? $date->setTimezone($timezone) : $date;
    }

    public function getHealthcareProvidersUrgent($request, $userId)
    {   
        // DB::enableQueryLog();
        Log::info("HealthcareProvidersUrgent request ".json_encode($request));
        $user = User::find($userId);

        $query = User::select('users.*'); 

        // distance filter
        if(!empty($request->latitude) && !empty($request->longitude)){
    
            if(isset($request->appointment_type)){
                $query = $query->whereHas('userDetails', function ($query) use ($request) {
                    $query->whereRaw("FIND_IN_SET('".$request->appointment_type."', urgent_criteria)");
                });
            }

            $query = $query->where('category_id', '4');

            $query = $query->has('urgenAppointmentDetails', '=', 0);  
        
            $query = $query->has('nonUrgentAppointmentDetails', '=', 0);  

            if(!empty($request->distance)){
                $query = $query->addSelect(DB::raw('((ACOS(SIN('.$request->latitude.' * PI() / 180) * SIN(`users`.`current_latitude` * PI() / 180) + COS('.$request->latitude.' * PI() / 180) * COS(`users`.`current_latitude` * PI() / 180) * COS(('.$request->longitude.' - `users`.`current_longitude`) * PI() / 180)) * 180 / PI()) * 60 * 1.1515 * 1.609344) as distance '))
                ->where([
                         ['users.current_latitude', '!=', ''],
                         ['users.current_longitude', '!=', '']
                     ])
                ->havingRaw('distance <= '.$request->distance)
                ->orderBy('distance','asc');
            }else{
                $query = $query->addSelect(DB::raw('((ACOS(SIN('.$request->latitude.' * PI() / 180) * SIN(`users`.`current_latitude` * PI() / 180) + COS('.$request->latitude.' * PI() / 180) * COS(`users`.`current_latitude` * PI() / 180) * COS(('.$request->longitude.' - `users`.`current_longitude`) * PI() / 180)) * 180 / PI()) * 60 * 1.1515 * 1.609344) as distance '))
                ->where([
                         ['users.current_latitude', '!=', ''],
                         ['users.current_longitude', '!=', '']
                     ])
                ->havingRaw('distance <= 200000')
                ->orderBy('distance','asc');
            }

            $query = $query->withCount(['userAppointmentRating as rating' => function($query){
                    $query->select(DB::raw('avg(user_rating) as rating'));
                }])->orderBy('rating','desc');
        } else{

            if(isset($request->appointment_type)){
                $query = $query->whereHas('userDetails', function ($query) use ($request) {
                    $query->whereRaw("FIND_IN_SET('".$request->appointment_type."', urgent_criteria)");
                });
            }

            $query = $query->where('category_id', '4');

            $query = $query->has('urgenAppointmentDetails', '=', 0);  
        
            $query = $query->has('nonUrgentAppointmentDetails', '=', 0);  
            
             // country name filter
            if(!empty($request->country_names) && is_array($request->country_names) && isset($request->consultation) && $request->consultation == '2'){
                $query = $query->whereHas('userDetails', function($query) use ($request){
                    $query->whereIn('country', $request->country_names);
                }); 
                $query = $query->withCount(['userAppointmentRating as rating' => function($query){
                    $query->select(DB::raw('avg(user_rating) as rating'));
                }])->orderBy('rating','desc');
                $query = $query->orderBy('id','desc');
        
            }else if(!empty($user->latitude) && !empty($user->longitude)){

                $query = $query->addSelect(DB::raw('((ACOS(SIN('.$user->latitude.' * PI() / 180) * SIN(`users`.`current_latitude` * PI() / 180) + COS('.$user->latitude.' * PI() / 180) * COS(`users`.`current_latitude` * PI() / 180) * COS(('.$user->longitude.' - `users`.`current_longitude`) * PI() / 180)) * 180 / PI()) * 60 * 1.1515 * 1.609344) as distance '))
                            ->where([
                                        ['users.current_latitude', '!=', ''],
                                        ['users.current_longitude', '!=', '']
                                    ])
                            ->havingRaw('distance <= 1200')
                            ->orderBy('distance','asc');
                
            }else{
                $query = $query->withCount(['userAppointmentRating as rating' => function($query){
                    $query->select(DB::raw('avg(user_rating) as rating'));
                }])->orderBy('rating','desc');
                $query = $query->orderBy('id','desc');
            }
       
        }         
        
        
        // urgent and not urgent filter
        $query = $query->whereHas('userDetails', function($query) use ($request){
                        $query->where('urgent', '1');
                    });
        
         // category filter
        if(!empty($request->category_id)){
            $query = $query->where('category_id', $request->category_id);
        }      
        
         // subcategory filter
        if(!empty($request->subcategory_id)){
            $query = $query->where('subcategory_id', $request->subcategory_id);
        }  
        
         // consultation filter
        if(isset($request->consultation)){
            $query = $query->whereHas('userAvailableTime', function($query) use ($request){
                        $query->where('appointment_type', $request->consultation);
                    });
        }                
        
        // country name filter
        if(!empty($request->country_names) && is_array($request->country_names)){
            $query = $query->whereHas('userDetails', function($query) use ($request){
                $query->whereIn('country', $request->country_names);
            });            
        }
        
        // top listing
        if(isset($request->last_id)){
            if(!empty($request->last_id)){
                $query = $query->where('id', '<', $request->last_id);    
            }            
            $query = $query->limit($this->api_data_limit);    
        } else{
            $query = $query->offset(0)->limit(5);  
        }    

        $current_time  =  Carbon::now();
        $current_time = $current_time->subHour(12);
        $current_time = $current_time->format('Y-m-d H:i:s');   

        // $query = $query->where('users.status', '0')->where('users.updated_at', '<=', $current_time)->get();
        $query = $query->where('users.status', '0')->get();
        // print_r(DB::getQueryLog());
        // die;
        return $query;
    }
}