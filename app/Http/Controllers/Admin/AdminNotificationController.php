<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\AdminNotificationRequest;
use App\Repositories\AdminNotificationRepository;
use App\Repositories\CategoryRepository;

class AdminNotificationController extends Controller
{
    private $admin_notification_repo, $category_repo;

    public function __construct(AdminNotificationRepository $admin_notification_repo, CategoryRepository $category_repo)
    {
        $this->admin_notification_repo = $admin_notification_repo;
        $this->category_repo = $category_repo;
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

        if(!empty($request->id)){
            $category = $this->admin_notification_repo->getById($request->id);
            if(!empty($category)){

                $this->admin_notification_repo->dataCrud($data, $request->id);
            }
        } else{
            $this->admin_notification_repo->dataCrud($data);
        }
        
        return redirect('notifications');
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
