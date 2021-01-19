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
        	Log::info('content', [$content, $data]);
            if(is_object($data) ) {
                $receiver_id = isset($input['to']) ? $input['to'] : '';
                $sender_id = isset($input['from']) ? $input['from'] : '';
                if(!empty($receiver_id) && !empty($sender_id)) {
                    // send notification
                    $receiver = $this->user_repo->getbyId($receiver_id);
                    $sender = $this->user_repo->getbyId($sender_id);
                    $data = [
                            'sender_id' => $sender_id,
                            'receiver_id' => $receiver_id,
                            'title' => 'Chat',
                            'message' => 'new message from '.(!empty($sender))?$sender->user_name:'-',
                            'parameter' => json_encode(['notification_time'=> $this->notification_repo->getCurrentDateTime()]),
                            'msg_type' => '0',
                        ];       
                    try{
                        Helper::sendOfflineChatNotification($data, $receiver, $sender);
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
