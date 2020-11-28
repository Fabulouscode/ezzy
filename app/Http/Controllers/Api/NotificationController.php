<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Repositories\UserRepository;
use App\Repositories\NotificationRepository;
use Illuminate\Http\Request;

class NotificationController extends BaseApiController
{
    private $user_repo, $notification_repo;

    public function __construct(UserRepository $user_repo, NotificationRepository $notification_repo)
    {
        parent::__construct();
        $this->user_repo = $user_repo;
        $this->notification_repo = $notification_repo;
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
                return self::sendError('', 'You can not delete this Notification', 500);
            }
          
        }
        return self::sendError('', 'Notification not Deleted', 500);
    }
    
}
