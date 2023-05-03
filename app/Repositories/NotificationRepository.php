<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;
use App\Models\Notification;
use Illuminate\Support\Str;
use App\Http\Helpers\Helper;
use App\Repositories\UserRepository;

class NotificationRepository extends Repository
{
    protected $model_name = 'App\Models\Notification';
    protected $model, $user_repo;
    
    public function __construct(UserRepository $user_repo)
    {
        $this->user_repo = $user_repo;
        parent::__construct();
    }

    public function getNotificationTopic()
    {
        return $this->model->notification_topic;
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $data
     * @return \Illuminate\Http\Response
     */
    public function dataCrud($data, $id = '')
    {   
        if(!empty($data)){
            if(!empty($id)){
                return $this->update($data, $id);
            } else {
                return $this->store($data);
            }
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getWithRelationship()
    {
        $query = $this->model->orderBy('id','desc')->get();
        return $query;
    }
 
    /**
     * Display a count of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUnreadNotificationCount($receiver_id)
    {
        $query = $this->model->where('read','1')->where('receiver_id',$receiver_id)->count();
        return $query;
    }

    public function sendingNotification($data){
        $notification = $this->dataCrud($data);            
        $receiver = $this->user_repo->getById($notification->receiver_id);
        $sender = $this->user_repo->getById($notification->sender_id);
        $unreadNotification = $this->getUnreadNotificationCount($notification->receiver_id);
        return Helper::sendNotification($notification, $receiver, $sender, $unreadNotification);
    }
  
    public function sendingWithoutSenderNotification($data){
        $sender = '';
        $notification = $this->dataCrud($data);            
        $receiver = $this->user_repo->getById($notification->receiver_id);
        $unreadNotification = $this->getUnreadNotificationCount($notification->receiver_id);
        return Helper::sendNotification($notification, $receiver, $sender, $unreadNotification);
    }
   
    /**
     * Display a list of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getNotificationList($request)
    {   
        $query = $this->model;
        
        if(!empty($request->last_id)){
            $query = $query->where('id', '<', $request->last_id);    
        }
        
        $query = $query->limit($this->api_data_limit);     
        
        if(!empty($request->user()->category_id)){
            $userId = $request->user()->id;
            $categoryId = $request->user()->category_id;
            $query = $query->where(function($query) use ($userId, $categoryId){
                $query->where('receiver_id',$userId)
                ->orWhere(function ($query) use ($categoryId) {
                    $query->where('is_admin_send', 1)->where('general_notification_type', $categoryId);
                })
                ->orWhere(function ($query) use ($categoryId) {
                    $query->where('is_admin_send', 1)->where('general_notification_type', '100');
                });  
            });  
            // $query = $query->where('receiver_id',$request->user()->id);
        }else{
            $userId = $request->user()->id;
            $query = $query->where(function($query) use ($userId){
                $query->where('receiver_id',$userId)
                ->orWhere(function ($query) {
                    $query->where('is_admin_send', 1)->where('general_notification_type', '1');
                })
                ->orWhere(function ($query) {
                    $query->where('is_admin_send', 1)->where('general_notification_type', '100');
                });  
            });  
            // $query = $query->where('receiver_id',$request->user()->id);
        }

        $query = $query->where('message', '!=','Appointment charges is exceeded');
        
        $query = $query->orderBy('id','desc')->get();

        return $query;
    }

}