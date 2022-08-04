<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use App\Repositories\NotificationRepository;
use App\Repositories\UserRepository;
use App\Http\Helpers\Helper;
use Log;

class OfflineNotificationController extends BaseApiController
{
    private $user_repo, $notification_repo;

    public function __construct(UserRepository $user_repo, NotificationRepository $notification_repo)
    {
        parent::__construct();
        $this->user_repo = $user_repo;
        $this->notification_repo = $notification_repo;
    }

    public function offlineNotificationSend(Request $request){


        Log::info("=============offline message========");
        Log::info($request->getContent());
        Log::info("========================");

        if( $request->hasHeader('Authorization') && config('app.offline_message_token') == $request->header('Authorization') ) {
        	$input = $request->all();
            $content = $input['content'];
            $data = json_decode($content);
            $msg_type = json_decode($data->content);

            if(is_object($data) ) {
                $receiver_id = isset($input['to']) ? $input['to'] : '';
                $sender_id = isset($input['from']) ? $input['from'] : '';
                if(!empty($receiver_id) && !empty($sender_id)) {
                    // send notification
                    $receiver = $this->user_repo->getbyId($receiver_id);
                    $sender = $this->user_repo->getbyId($sender_id);
                    if(!empty($msg_type->type) && $msg_type->type == "topic_call"){
                        $notification_data = [
                            'sender_id' => $sender_id,
                            'receiver_id' => $receiver_id,
                            'title' => 'Chat',
                            'message' => (!empty($sender))? 'Missed call from '.$sender->user_name:'Missed call',
                            'parameter' => json_encode(['notification_time'=> $this->notification_repo->getCurrentDateTime()]),
                            'msg_type' => '99',
                        ];  
                    }else{
                        $notification_data = [
                            'sender_id' => $sender_id,
                            'receiver_id' => $receiver_id,
                            'title' => 'Chat',
                            'message' => (!empty($sender))? 'New message from '.$sender->user_name:'New message',
                            'parameter' => json_encode(['notification_time'=> $this->notification_repo->getCurrentDateTime()]),
                            'msg_type' => '99',
                        ];  
                    }
     
                    try{
                        Helper::sendOfflineChatNotification($notification_data, $receiver, $sender);
                        return self::sendSuccess('','Notification Send Success');
                    }catch(\Exception $e){
                        return self::sendException($e);
                    }
                }
            }
        } else {
            Log::info('unauthorization offline message call');
        }
    }
}
