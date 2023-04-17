<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\AdminNotificationRequest;
use App\Repositories\AdminNotificationRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\CategoryRepository;

class AdminNotificationController extends Controller
{
    private $admin_notification_repo, $category_repo, $notification_repo;

    public function __construct(AdminNotificationRepository $admin_notification_repo, CategoryRepository $category_repo, NotificationRepository $notification_repo)
    {
        $this->admin_notification_repo = $admin_notification_repo;
        $this->category_repo = $category_repo;
        $this->notification_repo = $notification_repo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->all()){
            return $this->admin_notification_repo->getDatatable($request);
        }
        return view('admin.notification.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ids=['1','2','3'];
        $hcp_types = $this->category_repo->getByMultipleParentIds($ids);
        return view('admin.notification.add',compact('hcp_types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminNotificationRequest $request)
    {
        $data = [
            'title' => $request->title,
            'message' => $request->message,
            'send_category' => json_encode($request->send_category),
        ];

        $notificationData = [
            'title' => $request->title,
            'message' => $request->message,
            'type' => '0',
        ];
        
        if(!empty($request->id)){
            $category = $this->admin_notification_repo->getById($request->id);
            if(!empty($category)){
                $this->admin_notification_repo->dataCrud($data, $request->id);
           
                $notification_topic = $this->notification_repo->getNotificationTopic();
                if(!empty($request->send_category)){
                    foreach ($notification_topic as $key => $value) {
                        if(in_array($key,$request->send_category)){
                            $this->notification_repo->sendNotificationTopicWise($notificationData, $value);
                            $send_notification = [
                                'sender_id' => NULL,
                                'receiver_id' => NULL,
                                'title' => $request->title,
                                'message' => $request->message,
                                'general_notification_type' => $key,
                                'msg_type' => '0',
                                'read' => '0',
                                'is_admin_send' => 1,
                            ];  
                            $this->notification_repo->dataCrud($send_notification); 
                        }
                    }
                }else{
                    $this->notification_repo->sendNotificationTopicWise($notificationData, 'Ezzycare');
                    $send_notification = [
                        'sender_id' => NULL,
                        'receiver_id' => NULL,
                        'title' => $request->title,
                        'message' => $request->message,
                        'general_notification_type' => 100,
                        'msg_type' => '0',
                        'read' => '0',
                        'is_admin_send' => 1,
                    ];  
                    $this->notification_repo->dataCrud($send_notification); 
                }
            }
        } else{
            $notification_topic = $this->notification_repo->getNotificationTopic();
            $this->admin_notification_repo->dataCrud($data);  
            if(!empty($request->send_category)){
                foreach ($notification_topic as $key => $value) {
                    if(in_array($key,$request->send_category)){
                        $this->notification_repo->sendNotificationTopicWise($notificationData, $value);
                        $send_notification = [
                            'sender_id' => NULL,
                            'receiver_id' => NULL,
                            'title' => $request->title,
                            'message' => $request->message,
                            'general_notification_type' => $key,
                            'msg_type' => '0',
                            'read' => '0',
                            'is_admin_send' => 1,
                        ];  
                        $this->notification_repo->dataCrud($send_notification); 
                    }
                }
            }else{
                $this->notification_repo->sendNotificationTopicWise($notificationData, 'Ezzycare');
                $send_notification = [
                    'sender_id' => NULL,
                    'receiver_id' => NULL,
                    'title' => $request->title,
                    'message' => $request->message,
                    'general_notification_type' => 100,
                    'msg_type' => '0',
                    'read' => '0',
                    'is_admin_send' => 1,
                ];  
                $this->notification_repo->dataCrud($send_notification); 
            }
        }
        
        return redirect('/donotezzycaretouch/notifications');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ids=['1','2','3'];
        $hcp_types = $this->category_repo->getByMultipleParentIds($ids);
        $data = $this->admin_notification_repo->getById($id);
        return view('admin.notification.add',compact('hcp_types','data'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->admin_notification_repo->getById($id);
        if(!empty($data)){
            $this->admin_notification_repo->forceDelete($id); 
            return response()->json(['msg'=>'Deleted success'], 200);
        }
        
        return response()->json(['msg'=>'Data Not success'], 500);
    }
}
