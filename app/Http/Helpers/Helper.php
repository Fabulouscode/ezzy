<?php 

namespace App\Http\Helpers;

use Log;
use App\Repositories\CategoryRepository;
use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;
use Auth;

class Helper
{
    private $category_repo;

    public function __construct(CategoryRepository $category_repo)
    {
        $this->category_repo = $category_repo;

    }

    public static function getCategoryName($id)
    {
        $category_name = '';
        $categories = Category::get();
        foreach ($categories as $key => $value) {
            if ($value->id == $id) {
                $category_name = $value->name;
                break;
            }
        }
        return $category_name;
    }
    
    /**
     * get timestamp formate date and time
     */  
    public static function getDateTimeLocalFormate($date_time, $timezone)
    { 
        $date_time_formate = new Carbon($date_time);
        (!empty($timezone)) ? $date_time_formate->setTimezone($timezone) : '' ;
        return $date_time_formate->format('d M, Y H:i:s');
    }
  
    /**
     * get timestamp formate date and time
     */  
    public static function getDateTimeFormate($date_time)
    {
        $date_time_formate = new Carbon($date_time);
        (!empty(Auth::user()) && !empty(Auth::user()->timezone)) ? $date_time_formate->setTimezone(Auth::user()->timezone) : '' ;
        return $date_time_formate->format('d M, Y H:i:s');
    }

    /**
     * get timestamp formate date
     */  
    public static function getDateFormate($date)
    {
        $date_formate = new Carbon($date);        
        (!empty(Auth::user()) && !empty(Auth::user()->timezone)) ? $date_formate->setTimezone(Auth::user()->timezone) : '' ;
        return $date_formate->format('d M, Y');
    }
   
    /**
     * get timestamp formate time
     */  
    public static function getTimeFormate($time)
    {
        $time_formate = new Carbon($time);        
        (!empty(Auth::user()) && !empty(Auth::user()->timezone)) ? $time_formate->setTimezone(Auth::user()->timezone) : '' ;
        return $time_formate->format('H:i:s');
    }
 
    /**
     * get timestamp formate time
     */  
    public static function getUserTimezoneConvertFormate($time, $timezone = 'UTC')
    {
        //$timezone
        $timezone = !empty($timezone) ? $timezone : 'UTC';
        $time_formate = Carbon::createFromFormat('H:i:s', $time, 'UTC')->setTimezone($timezone);
        return $time_formate->format('h:i a');
    }

    /**
     * sending firebase notification
     */ 
    public static function sendNotification($notification, $receiver, $sender = '', $unreadNotification = 0) 
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $serverApiKey = config('app.FCM_KEY');
        
        $notification_check = User::where('id', $receiver->id)->where('notification_status','1')->first();
        if(!empty($notification_check)){
            return true;
        }

        $parameter = json_decode($notification->parameter,true);
        $image = (isset($parameter['notification_image']) && $parameter['notification_image'] != '') ? $parameter['notification_image'] : '';
        $message = [
            'id' => $notification->id,
            'message' => $notification->message,
            'parameter' => json_decode($notification->parameter,true),
            'sender_id' => $notification->sender_id,
            'sender_name' => (!empty($sender))?$sender->user_name:'-',
            'receiver_id' => $notification->receiver_id,
            'type' => $notification->msg_type,
            'sender_avatar' => (!empty($sender))?$sender->profile_image:'',
            'attachment' => '',
            'notification_count' => $unreadNotification,
            'media_type' => "image",
        ];

        $dataTemp = [
            'click_action' => "FLUTTER_NOTIFICATION_CLICK",
            'screen' => $notification->msg_type,
            'object' => $message
        ];
        

        if(!empty($notification->msg_type) && in_array($notification->msg_type,['1','2','3'])){
            $data = array(
                'to' => $receiver->device_token,
                'data' => $dataTemp,
                'notification'=>array(
                    'title'=> config('app.name'),
                    'body'=>$notification->message,
                    'sound' => 'ezzycare_ringtone.wav',
                    'android_channel_id' => 'ezzycare_channel_1',
                )
            );
        }else if(!empty($notification->msg_type) && in_array($notification->msg_type,['4','5','6'])){
            $data = array(
                'to' => $receiver->device_token,
                'data' => $dataTemp,
                'notification'=>array(
                    'title'=> config('app.name'),
                    'body'=>$notification->message,
                    'sound' => 'ezzycare_ringtone.wav',
                    'android_channel_id' => 'ezzycare_channel_2',
                )
            );
        }else{
            $data = array(
                'to' => $receiver->device_token,
                'data' => $dataTemp,
                'notification'=>array(
                    'title'=> config('app.name'),
                    'body'=>$notification->message
                )
            );
        }

   
        if(!empty($data)){
             self::sendCurlRequest($url, $data);
        }
        return true;
    }
  
    /**
     * sending firebase notification using topic
     */ 
    public static function sendNotificationTopicWise($notification, $topic_name = 'ezzycare') 
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $serverApiKey = config('app.FCM_KEY');
 
        $message = [
            'title' => $notification['title'],
            'message' => $notification['message'],
            'type' => $notification['type'],
        ];

        $data = [            
            "to"=> "/topics/".$topic_name,
            'notification' => [
                    'title' => config('app.name'),
                    'data' => $message
                ],

        ];

        self::sendCurlRequest($url, $data);
    }
    
 
    /**
     * Subscribe firebase topic
     */ 
    public static function subscribeNotificationTopic($notification_tokens, $topic_name = 'ezzycare') 
    {
        $url = 'https://iid.googleapis.com/iid/v1:batchAdd';

        $data = [            
            "to"=> "/topics/".$topic_name,
            "registration_tokens"=> $notification_tokens
        ];
        self::sendCurlRequest($url, $data);
    }

    /**
     * Unsubscribe firebase topic
     */ 
    public static function unsubscribeNotificationTopic($notification_tokens, $topic_name = 'ezzycare') 
    {
        $url = 'https://iid.googleapis.com/iid/v1:batchRemove';

        $data = [            
            "to"=> "/topics/".$topic_name,
            "registration_tokens"=> $notification_tokens
        ];
        self::sendCurlRequest($url, $data);
    }

    /**
     * check notification
     */ 
    public static function sendOfflineChatNotification($notification, $receiver, $sender = '', $unreadNotification = 0) 
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $serverApiKey = config('app.FCM_KEY');
 
        $parameter = json_decode($notification['parameter'],true);
        $image = (isset($parameter['notification_image']) && $parameter['notification_image'] != '') ? $parameter['notification_image'] : '';
        
        $message = [
            'message' => $notification['message'],
            'parameter' => json_decode($notification['parameter'],true),
            'sender_id' => $notification['sender_id'],
            'sender_name' => (!empty($sender))?$sender->user_name:'-',
            'receiver_id' => $notification['receiver_id'],
            'type' => $notification['msg_type'],
            'sender_avatar' => (!empty($sender))?$sender->profile_image : asset('/admin/images/avatar.jpg'),
            'attachment' => '',
            'notification_count' => $unreadNotification,
            'media_type' => "image",
        ];

        $dataTemp = [
            'click_action' => "FLUTTER_NOTIFICATION_CLICK",
            'screen' => $notification['msg_type'],
            'object' => $message
        ];
        
       
        $data = array(
            'to' => $receiver->device_token,
            'data' => $dataTemp,
            'notification'=>array(
                'title'=> config('app.name'),
                'body'=>$notification['message'],
            )
        );
        // Log::info('data'.json_encode($data));
        if(!empty($data)){
             self::sendCurlRequest($url, $data);
        }
        return true;
    }


    /**
     * check notification
     */ 
    public static function checkNotification() 
    {
        $notification_token = "dR3b-2AH7UhZqnsZ1zpva9:APA91bFX5lh0Dc5qcyQq6PbeIUXaibGmuu7FdvZgGLsVcKXPVdL7BrxXFT_eqqSqZV6tmayTqd1MVx_j-bk2dWsGorJllFoQdEHo_AFpB2GkkdQDmqHXJXxBTX4HMFq63lRMGaFtBGEY";
        $url = 'https://fcm.googleapis.com/fcm/send';
    
        $message = [
            'message' => 'This is test Notificationas',
            'parameter' => "",
            'sender_id' => "",
            'sender_name' => "",
            'receiver_id' => "",
            'type' => "99",
            'sender_avatar' => "",
            'attachment' => '',
            'notification_count' => "0",
            'media_type' => "image",
            'TTL'=>"5"
        ];

        $dataTemp = [
            'click_action' => "FLUTTER_NOTIFICATION_CLICK",
            'screen' => '91',
            'object' => $message,
            'TTL'=>"5"
        ];
        
       
        $data = array(
            'to' => $notification_token,
            'data' => $dataTemp,
            'notification'=>array(
                'title'=> config('app.name'),
                'body'=>'This is test Notificationas',
                'sound' => 'ezzycare_ringtone.wav',
                'android_channel_id' => 'ezzycare_channel_1',
                'TTL'=>"5"
            )
        );
        self::sendCurlRequest($url, $data);
    }
  
    /**
     * sending curl request
     */ 
    public static function sendCurlRequest($url, $data) 
    {
        $serverApiKey = config('app.FCM_KEY');
        if(!empty($url)){
            $headers = array( 'Content-Type:application/json', 'Authorization:key=' . $serverApiKey);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            if ($headers)
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            $response = curl_exec($ch);
            $response_arr =  json_decode($response, true);
            // Log::info('sendCurlRequest '.$response);
            if(isset($response_arr['success']) && $response_arr['success'] == 0) {
                // Log::info($response);
                // Log::info('Push Notification Send Failed');
            }
        }
        return true;
    }
   
    /**
     * msg sending curl request
     */ 
    public static function sendBULKSMSRequest($url) 
    { 
        if(!empty($url)){
            $headers = array( 'Content-Type:application/json');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            if ($headers)
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPGET, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            $response_arr =  json_decode($response, true);
            if(!empty($response_arr['error'])) {
                Log::info($response);
                Log::info('SMS Send Failed');
                return $response_arr['error'];
            }
            return true;
        }
        $response_arr = 'SMS Send Failed';
        return $response_arr;
    }
    
    /**
     * sending curl request paystack
     */ 
    public static function sendCurlRequestPaystack($url, $headers, $method = 'GET', $data = '') 
    {
        if(!empty($url) && !empty($headers)){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            if ($headers)
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            if($method == 'POST'){
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            }else{
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
                curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            }
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            $response_arr =  json_decode($response, true);
            return $response_arr;
            
        }
        return true;
    }
    
    
}