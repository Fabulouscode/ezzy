<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\AppointmentRepository;
use App\Repositories\OrderRepository;
use App\Repositories\UserTransactionRepository;
use App\Repositories\NotificationRepository;
use App\Http\Resources\Api\HeathCareProviderResource;
use App\Http\Resources\Api\PharmacyResource;
use App\Http\Resources\Api\LaboratoriesResource;
use App\Http\Resources\Api\PatientResource;

class DashboardController extends BaseApiController
{
    private $user_repo, $order_repo, $category_repo, $appointment_repo, $user_trans_repo, $notification_repo;

    public function __construct(
        UserRepository $user_repo,
        CategoryRepository $category_repo,
        AppointmentRepository $appointment_repo,
        OrderRepository $order_repo,
        UserTransactionRepository $user_trans_repo,
        NotificationRepository $notification_repo
        )
    {
        parent::__construct();
        $this->user_repo = $user_repo;
        $this->category_repo = $category_repo;
        $this->appointment_repo = $appointment_repo;
        $this->order_repo = $order_repo;
        $this->user_trans_repo = $user_trans_repo;
        $this->notification_repo = $notification_repo;
    }
    
    public function getDashboardDetails(Request $request)
    {
        $data = array();        
        $data['user'] = $this->user_repo->getbyId($request->user()->id);
        if(!empty($request->user()->category_id)){
            if($request->user()->categoryParent->parent_id == '2'){
                $data['orders'] = $this->order_repo->getActiveOrder($request);
                $data['user'] = new PharmacyResource($data['user']);
            }else if($request->user()->categoryParent->parent_id == '3'){
                $data['appointments'] = $this->appointment_repo->getUpcomingAppointment($request);
                $data['user'] = new LaboratoriesResource($data['user']);
            }else{
                $data['appointments'] = $this->appointment_repo->getUpcomingAppointment($request);
                $data['user'] = new HeathCareProviderResource($data['user']);
            }
        }else{
            $data['user'] = new PatientResource($data['user']);
        }
        return self::sendSuccess($data, 'User Dashboard');
    }
 
    public function getHealthCareTypes($id)
    {
        $data = array();        
        $data = $this->category_repo->getByParentId($id);
        return self::sendSuccess($data, 'HCP Types');
    }

    public function getPaymentHistory(Request $request)
    {
        $data = array();        
        $data = $this->user_trans_repo->getTransactionHistory($request);
        return self::sendSuccess($data, 'User Transaction History');
    }

    public function sendingNotification(Request $request)
    {
         $data = [
                    'sender_id' => $request->user()->id,
                    'receiver_id' => '13',
                    'title' => 'Test',
                    'message' => 'Testing',
                    'parameter' => json_encode(['notification_time'=> $this->notification_repo->getCurrentDateTime()]),
                    'msg_type' => '0',
                ];       
        try{
            $this->notification_repo->sendingNotification($data, $request);
            return self::sendSuccess('','Notification Send Success');
        }catch(\Exception $e){
            return self::sendException($e);
        }
    }
 
}
