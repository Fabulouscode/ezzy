<?php 

namespace App\Http\Helpers;

use Log;
use App\Repositories\CategoryRepository;
use App\Models\Category;
use Carbon\Carbon;

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
        return $date_time_formate->format('d M, Y H:i:s');
    }

    /**
     * get timestamp formate date
     */  
    public static function getDateFormate($date)
    {
        $date_formate = new Carbon($date);
        return $date_formate->format('d M, Y');
    }
   
    /**
     * get timestamp formate time
     */  
    public static function getTimeFormate($time)
    {
        $time_formate = new Carbon($time);
        return $time_formate->format('H:i:s');
    }

    /**
     * sending firebase notification
     */ 
    public static function sendNotification($notification, $receiver, $sender = '', $unreadNotification = 0) 
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $serverApiKey = config('app.FCM_KEY');
 
        $parameter = json_decode($notification->parameter,true);
        $image = (isset($parameter['notification_image']) && $parameter['notification_image'] != '') ? $parameter['notification_image'] : '';
        $message = [
            'id' => $notification->id,
            'message' => $notification->message,
            'parameter' => json_decode($notification->parameter,true),
            'sender_id' => $notification->sender_id,
            'sender_name' => (!empty($sender))?$sender->first_name:'-',
            'receiver_id' => $notification->receiver_id,
            'type' => $notification->type,
            'sender_avatar' => (!empty($sender))?$sender->profile_image:'',
            'attachment' => $image,
            'notification_count' => $unreadNotification,
            'media_type' => "image",
        ];

        $dataTemp = [
            'title' => config('app.name'),
            'data' => $message
        ];
        
        if($receiver->device_type == '1' && $receiver->device_token != '') {
            $data = array(
                'to' => $receiver->device_token,
                'data' => $dataTemp,
                'priority'=>'high'
            );
        }

        if($receiver->device_type == '0' && $receiver->device_token != '') {
            $msg = array ('title' => config('app.name'), 'body' => $notification->message);
            $message = array(
                "message" => $notification->message,
                "data" => $message,
            );
            $data['registration_ids'] = array($receiver->device_token);
            $data['data'] = $message;
            $data['notification']['sound'] = "default";
            $data['notification']['title'] = config('app.APP_NAME');
            $data['notification']['mutable_content'] = true;
            $data['notification']['category'] = "CustomSamplePush";
            $data['notification']['body'] = $notification->message;
            $data['notification']['badge'] = $unreadNotification;
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
    public static function checkNotification($notification_tokens) 
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $message = array(
            "message" => 'This is test Notification',
            "title" => 'EzzyCare'
        );
        $data['registration_ids'] = $notification_tokens;
        $data['data'] = $message;
        $data['notification']['sound'] = "default";
        $data['notification']['title'] = 'EzzyCare';
        $data['notification']['mutable_content'] = true;
        $data['notification']['category'] = "CustomSamplePush";
        $data['notification']['body'] = 'This is test Notification';
        $data['notification']['badge'] = '1';
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
    

    
}