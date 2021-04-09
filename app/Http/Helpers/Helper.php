<?php 

namespace App\Http\Helpers;

use Log;
use App\Repositories\CategoryRepository;
use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;
use Timezone;

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
    public static function getDateTimeFormate($date_time)
    {
        $date_time_formate = new Carbon($date_time);
        $date_time_formate = Timezone::convertToLocal($date_time_formate, 'd M, Y h:i:s a');
        return $date_time_formate;
    }

    /**
     * get timestamp formate date
     */  
    public static function getDateFormate($date)
    {
        $date_formate = new Carbon($date);
        $date_formate = Timezone::convertToLocal($date_formate, 'd M, Y');
        return $date_formate;
    }
   
    /**
     * get timestamp formate time
     */  
    public static function getTimeFormate($time)
    {
        $time_formate = new Carbon($time);
        $time_formate = Timezone::convertToLocal($time_formate, 'h:i:s a');
        return $time_formate;
    }
 
    /**
     * get timestamp formate time
     */  
    public static function getUserTimezoneConvertFormate($time, $timezone = 'UTC')
    {
        //$timezone
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
        
       
        $data = array(
            'to' => $receiver->device_token,
            'data' => $dataTemp,
            'notification'=>array(
                'title'=> config('app.name'),
                'body'=>$notification->message
            )
        );
   
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
        Log::info('data'.json_encode($data));
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
        $notification_token = "ceeU5WOtSR-y3BXpscLyjX:APA91bF78VEwEMjSLydKNI94OaJpTgL2pd-CDSgz3Lu4z-ZqczoS8pKuihYDEkzk2l3ZP_jy7xle3bYjvd223-cmyq5javHXKj5HGBib8Xz0iyfTiMfTxCEmyJFa-F0bb_9mn9diu3m6";
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
            'screen' => '99',
            'object' => $message,
            'TTL'=>"5"
        ];
        
       
        $data = array(
            'to' => $notification_token,
            'data' => $dataTemp,
            'notification'=>array(
                'title'=> config('app.name'),
                'body'=>'This is test Notificationas',
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
            if(isset($response_arr['success']) && $response_arr['success'] == 0) {
                Log::info($response);
                Log::info('Push Notification Send Failed');
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