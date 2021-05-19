<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\AppointmentRepository;
use App\Repositories\OrderRepository;
use App\Repositories\UserTransactionRepository;
use App\Repositories\PayoutAmountRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\PaystackIntegrationRepository;
use App\Http\Resources\Api\HeathCareProviderResource;
use App\Http\Resources\Api\PharmacyResource;
use App\Http\Resources\Api\LaboratoriesResource;
use App\Http\Resources\Api\PatientResource;
use App\Http\Requests\Api\PayAmountHistoryRequest;
use App\Http\Helpers\Helper;

class DashboardController extends BaseApiController
{
    private $user_repo, $payout_repo, $paystack_integration_repo, $order_repo, $category_repo, $appointment_repo, $user_trans_repo, $notification_repo;

    public function __construct(
        UserRepository $user_repo,
        CategoryRepository $category_repo,
        AppointmentRepository $appointment_repo,
        OrderRepository $order_repo,
        UserTransactionRepository $user_trans_repo,
        PaystackIntegrationRepository $paystack_integration_repo,
        NotificationRepository $notification_repo,
        PayoutAmountRepository $payout_repo
        )
    {
        parent::__construct();
        $this->user_repo = $user_repo;
        $this->category_repo = $category_repo;
        $this->appointment_repo = $appointment_repo;
        $this->order_repo = $order_repo;
        $this->user_trans_repo = $user_trans_repo;
        $this->notification_repo = $notification_repo;
        $this->paystack_integration_repo = $paystack_integration_repo;
        $this->payout_repo = $payout_repo;
    }
    
    public function getDashboardDetails(Request $request)
    {
        $data = array();        
        $data['user'] = $this->user_repo->getbyId($request->user()->id);
        if(!empty($request->user()->category_id)){
            if($request->user()->categoryParent->parent_id == '2'){
                $data['orders'] = $this->order_repo->getActiveOrder($request)->map(function ($response){
                                    return [
                                        'id'=>$response->id,
                                        'order_no_generate'=>$response->order_no_generate,
                                        'total_price'=>$response->total_price,
                                        'client'=>(isset($response->clientDetails))?
                                                        [
                                                            'id'=>$response->clientDetails->id,
                                                            'user_name'=>$response->clientDetails->user_name,
                                                            'profile_image'=>$response->clientDetails->profile_image
                                                        ]:'',
                                        'status'=>$response->status,
                                        'status_name'=>$response->status_name,
                                    ];
                                });
                $data['user'] = new PharmacyResource($data['user']);
            }else if($request->user()->categoryParent->parent_id == '3'){
                $data['appointments'] = $this->appointment_repo->getUpcomingAppointment($request)->map(function ($response){
                                            return [
                                                'id'=>$response->id,
                                                'appointment_type'=>$response->appointment_type,
                                                'appointment_type_name'=>$response->appointment_type_name,
                                                'appointment_date'=>$response->appointment_date,
                                                'appointment_time'=>$response->appointment_time,
                                                'client'=>(isset($response->client))?
                                                    [
                                                    'id'=>$response->client->id,
                                                    'user_name'=>$response->client->user_name,
                                                    'profile_image'=>$response->client->profile_image
                                                    ]:'',
                                                'status'=>$response->status,
                                                'status_name'=>$response->status_name,
                                            ];
                                        });
                $data['user'] = new LaboratoriesResource($data['user']);
            }else{
                
                $data['appointments'] = $this->appointment_repo->getUpcomingAppointment($request)->map(function ($response){
                                            return [
                                                'id'=>$response->id,
                                                'appointment_type'=>$response->appointment_type,
                                                'appointment_type_name'=>$response->appointment_type_name,
                                                'appointment_date'=>$response->appointment_date,
                                                'appointment_time'=>$response->appointment_time,
                                                'client'=>(isset($response->client))?
                                                    [
                                                    'id'=>$response->client->id,
                                                    'user_name'=>$response->client->user_name,
                                                    'profile_image'=>$response->client->profile_image
                                                    ]:'',
                                                'status'=>$response->status,
                                                'status_name'=>$response->status_name,
                                            ];
                                        });
                
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

    public function getPaymentHistory(PayAmountHistoryRequest $request)
    {
        $data = array();        
        $data = $this->user_trans_repo->getTransactionHistory($request)->map(function ($response){
                                            return [
                                                'id'=>$response->id,
                                                'amount'=>$response->amount,
                                                'transaction_type'=>$response->transaction_type,
                                                'transaction_date'=>$response->transaction_date,
                                                'wallet_transaction'=>$response->wallet_transaction,
                                                'mode_of_payment'=>$response->mode_of_payment,
                                                'client'=>(isset($response->client))?
                                                            [
                                                                'id'=>$response->client->id,
                                                                'user_name'=>$response->client->user_name,
                                                                'profile_image'=>$response->client->profile_image
                                                            ]:'',
                                                'users'=>(isset($response->users))?
                                                        [
                                                            'id'=>$response->users->id,
                                                            'user_name'=>$response->users->user_name,
                                                            'profile_image'=>$response->users->profile_image
                                                        ]:'',
                                                'status'=> $response->status,
                                                'status_name'=> $response->status_name,
                                            ];
                                        });;
        return self::sendSuccess($data, 'User Transaction History');
    }
  
    public function getHCPPaymentHistory(PayAmountHistoryRequest $request)
    {
        $data = array();        
        $data = $this->user_trans_repo->getHCPTransactionHistory($request)->map(function ($response){
                                            return [
                                                'id'=>$response->id,
                                                'amount'=>$response->amount,
                                                'transaction_type'=>$response->transaction_type,
                                                'transaction_date'=>$response->transaction_date,
                                                'wallet_transaction'=>$response->wallet_transaction,
                                                'mode_of_payment'=>$response->mode_of_payment,
                                                'users'=>(isset($response->users))?
                                                        [
                                                            'id'=>$response->users->id,
                                                            'user_name'=>$response->users->user_name,
                                                            'profile_image'=>$response->users->profile_image
                                                        ]:NULL,
                                                'appointment'=>(isset($response->tranAppointment))?
                                                        [
                                                            'id'=>$response->tranAppointment->id,
                                                            'appointment_type_name'=>$response->tranAppointment->appointment_type_name,
                                                            'appointment_date'=>$response->tranAppointment->appointment_date .' '.$response->tranAppointment->appointment_time,
                                                        ]:NULL,
                                                'order'=>(isset($response->tranOrder))?
                                                        [
                                                            'id'=>$response->tranOrder->id,
                                                            'products' => $response->tranOrder->order_medicine_name,
                                                            'order_date'=>$response->tranOrder->created_at,
                                                        ]:NULL,
                                                'treatment_plan'=>(isset($response->transactionTreatmentPlan))?
                                                        [
                                                            'id'=>$response->transactionTreatmentPlan->id,
                                                            'plan_name'=>$response->transactionTreatmentPlan->plan_name,
                                                            'plan_date'=>$response->transactionTreatmentPlan->created_at,
                                                        ]:NULL,
                                                'status'=> $response->status,
                                                'status_name'=> $response->status_name,
                                            ];
                                        });;
        return self::sendSuccess($data, 'User Transaction History');
    }
 
    public function getPaymentHistoryInfo($id)
    {
        $data = array();        
        $data = $this->user_trans_repo->getbyId($id);
        if(!empty($data)){
           $data = $data->userFormat();
        }
        return self::sendSuccess($data, 'User Transaction History');
    }

    public function getPayoutAmountHistory(PayAmountHistoryRequest $request)
    {
        $data = array();        
        $data = $this->payout_repo->getPayoutAmoutHistory($request)->map(function ($response){
                                            return [
                                                'id'=>$response->id,
                                                'amount'=>$response->amount,
                                                'approved_date'=>$response->approved_date,
                                                'bank_transaction_id'=>$response->bank_transaction_id,
                                                'appointment_time'=>$response->appointment_time,
                                                'user_details'=>(isset($response->admin))?
                                                    [
                                                    'name'=>$response->admin->name,
                                                    'email'=>$response->admin->email
                                                    ]:'',
                                                'bank_details'=>(isset($response->userBankAccount))?
                                                    [
                                                    'name'=>$response->userBankAccount->name,
                                                    'bank_name'=>$response->userBankAccount->bank_name,
                                                    'bank_branch_name'=>$response->userBankAccount->bank_branch_name,
                                                    'account_number'=>$response->userBankAccount->account_number,
                                                    'ifsc_code'=>$response->userBankAccount->ifsc_code
                                                    ]:'',
                                                'status'=> '0',
                                                'status_name'=> 'Paid',
                                            ];
                                        });
        return self::sendSuccess($data, 'User Payout History');    
    }

    public function sendingNotification(Request $request)
    {
         $data = [
                    'sender_id' => $request->user()->id,
                    'receiver_id' => '18',
                    'title' => 'Test',
                    'message' => 'Testing',
                    'parameter' => json_encode(['notification_time'=> $this->notification_repo->getCurrentDateTime()]),
                    'msg_type' => '0',
                ];       
        try{
            $this->notification_repo->sendingNotification($data);
            return self::sendSuccess('','Notification Send Success');
        }catch(\Exception $e){
            return self::sendException($e);
        }
    }

    public function checkNotification(Request $request)
    {
        Helper::checkNotification();
    }

    public function getBankDetails(Request $request)
    {
        $data = array();        
        $data = $this->paystack_integration_repo->resolvedAccount();
        return self::sendSuccess($data, 'Paystack Bank details');
    }
 
    public function checkUserChatModule(Request $request, $id = '')
    {
        $data = array();    
        if(!empty($id)){
            $appointment = $this->appointment_repo->checkAppointmentisRunning($request, $id);
            $order = $this->order_repo->checkOrderisRunning($request, $id);
            if(!empty($appointment) || !empty($order)){
                return self::sendSuccess(true, 'user chat module show');    
            }
        }    
        return self::sendSuccess(false, 'user chat module hide');
    }
 
}
