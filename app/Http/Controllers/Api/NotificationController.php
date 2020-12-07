<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Repositories\UserRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\AdminNotificationRepository;
use Illuminate\Http\Request;


class NotificationController extends BaseApiController
{
    private $messaging, $user_repo, $notification_repo, $admin_notification_repo;

    public function __construct( UserRepository $user_repo, NotificationRepository $notification_repo, AdminNotificationRepository $admin_notification_repo)
    {
        parent::__construct();
        $this->user_repo = $user_repo;
        $this->notification_repo = $notification_repo;
        $this->admin_notification_repo = $admin_notification_repo;
    }

    public function getNotificationDetails(Request $request)
    {
        $data = array();
        $data = $this->notification_repo->getNotificationList($request);
        return self::sendSuccess($data, 'Notification List');
    }
    
    public function readNotificationDetails($id)
    {
        $data = array();
        $update_data = ['read' => '0'];
        try{
            $data = $this->notification_repo->dataCrud($update_data, $id);
            return self::sendSuccess($data, 'Notification read Successfully');
        }catch(\Exception $e){
            return self::sendException($e);
        }
    }

    public function getByIdNotificationDetails($id)
    {
        $data = array();
        $data = $this->notification_repo->getbyId($id);
        return self::sendSuccess($data, 'Notification details');
    }
   
    public function deleteNotificationDetails($id)
    {
        $data = $this->notification_repo->getById($id);
        if(!empty($data)){
            try{
                $this->notification_repo->destroy($id); 
                return self::sendSuccess([], 'Notification details Deleted Successfully');
            }catch(\Exception $e){
                return self::sendException($e);
            }
          
        }
        return self::sendError('', 'Notification not Deleted', 500);
    }
    
    public function changeNotificationStatus(Request $request, $status)
    {
        $data = array();
        $user = $this->user_repo->getbyId($request->user()->id);
        if(!empty($user)){
            try{
                $update = ['notification_status'=> $status];
                $this->user_repo->update($update, $user->id);
                $data = $this->user_repo->getbyId($request->user()->id);
                $notification_topic = $this->notification_repo->getNotificationTopic();
                // if(!empty($request->user()->device_token) && $status == '0'){                    
                //     $this->notification_repo->subscribeNotificationTopic($request->user()->device_token, 'Ezzycare');
                //     $this->notification_repo->subscribeNotificationTopic($request->user()->device_token, !empty($request->user()->category_id) ? $notification_topic[$request->user()->category_id] : $notification_topic['1']);
                // }else if(!empty($request->user()->device_token) && $status == '1'){
                //     $this->notification_repo->unsubscribeNotificationTopic($request->user()->device_token, 'Ezzycare');
                //     $this->notification_repo->unsubscribeNotificationTopic($request->user()->device_token, !empty($request->user()->category_id) ? $notification_topic[$request->user()->category_id] : $notification_topic['1']);
                // }

                // // $this->admin_notification_repo->getNotificationTopic();
                // $tokens = ['feMVbI_jT36Ujv5aN0tk31:APA91bFfU0WoRWB2cSm4grKh2iMoECaoeledYUPqi75wwCLJ1FZOelnhbrTxP5SwjQn5tedxfJuuRugqWREsuneBFby5gYgE7OqcaeTkTl5zcCG3t_9dG8fhsCYmS_AQuYIRsLTDlnfO'];
                // $this->notification_repo->checkNotification($tokens);      
                // $notification['title'] = "Ezzycare";
                // $notification['message'] = "Test Notification";
                // $notification['type'] = "topic";
                // $this->notification_repo->subscribeNotificationTopic($tokens, 'Ezzycare');                
                // $this->notification_repo->unsubscribeNotificationTopic($tokens, 'Ezzycare');                
                // $this->notification_repo->sendNotificationTopicWise($notification, 'Ezzycare');                
                return self::sendSuccess($data, 'User Notification Status change Successfully');   
            }catch(\Exception $e){
                return self::sendException($e);
            }         
        }
        return self::sendError($data, 'User Notification Status not change');
    }
}
