<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use App\Repositories\AppointmentRepository;
use App\Repositories\UserTransactionRepository;
use App\Repositories\UserRepository;
use App\Repositories\ShopMedicineDetailsRepository;
use App\Repositories\OrderRepository;
use App\Repositories\OrderProductRepository;
use App\Repositories\OrderTrackingRepository;
use App\Repositories\ManageFeesRepository;
use App\Repositories\NotificationRepository;
use App\Http\Requests\Api\CartCheckoutRequest;
use App\Http\Requests\Api\AppointmentStatusRequest;
use App\Http\Requests\Api\OrderStatusRequest;
use App\Http\Requests\Api\AppointmentPayStatusRequest;
use App\Http\Requests\Api\OrderPayStatusRequest;
use App\Http\Requests\Api\TreatmentPlanRequestBillPay;
use Illuminate\Support\Facades\DB;
use App\Repositories\ChatHistoryRepository;
use Carbon\Carbon as Carbon;

class TransactionController extends BaseApiController
{

    private $appointment_repo, $chat_history_repo, $fees_repo, $notification_repo,  $user_transaction_repo, $user_repo, $shop_medicine_repo, $order_repo, $order_product_repo, $order_tracking_repo;

    public function __construct(
        AppointmentRepository $appointment_repo, 
        ChatHistoryRepository $chat_history_repo, 
        UserTransactionRepository $user_transaction_repo, 
        UserRepository $user_repo,
        ShopMedicineDetailsRepository $shop_medicine_repo,
        OrderRepository $order_repo,
        OrderProductRepository $order_product_repo,
        OrderTrackingRepository $order_tracking_repo,
        ManageFeesRepository $fees_repo,
        NotificationRepository $notification_repo
        )
    {
        parent::__construct();
        $this->appointment_repo = $appointment_repo;
        $this->chat_history_repo = $chat_history_repo;
        $this->user_transaction_repo = $user_transaction_repo;
        $this->user_repo = $user_repo;
        $this->shop_medicine_repo = $shop_medicine_repo;
        $this->order_repo = $order_repo;
        $this->order_product_repo = $order_product_repo;
        $this->order_tracking_repo = $order_tracking_repo;
        $this->fees_repo = $fees_repo;
        $this->notification_repo = $notification_repo;
    }

    public function updateUserWalletBalance($user_id)
    {          
        try {
            $wallet_balance = $this->user_transaction_repo->getUserbyWalletBalance($user_id); 
            $update = ['wallet_balance'=> $wallet_balance];
            $this->user_repo->dataCrudUsingData($update, $user_id);             
            return self::sendSuccess($data, 'Transaction Completed');
        } catch (\Exception $e) {
            return self::sendException($e);
        }
        return $total_earning;
    }

    public function appointmentBillPay(AppointmentStatusRequest $request)
    {
        $data = array();
        $appointment_details = $this->appointment_repo->getbyIdCheckTransaction($request->id);
        if(empty($appointment_details)){
            return self::sendError([], 'Transaction already Completed');
        }
        
        try {
            DB::beginTransaction();        
            $add_transaction = [
                    'user_id'=> $appointment_details->client_id,
                    'client_id'=> $appointment_details->user_id,
                    'transaction_date'=> $this->appointment_repo->getCurrentDateTime(),
                    'amount'=> $appointment_details->appointment_price,
                    'mode_of_payment'=> '1',
                    'transaction_type'=> '1',
                    'status'=> '1',
                    'appointment_id' => $appointment_details->id,
                ];
                
            $transaction = $this->user_transaction_repo->dataCrud($add_transaction);
                    
            if(!empty($transaction)){
                $ezzycare_charge = 0;
                $user_payout = 0;
                $ezzycare_fees = 0;
                $transaction_amount = $appointment_details->appointment_price;
                if(!empty($appointment_details->user->category_id)){
                    $manage_fees = $this->fees_repo->getbyCategoryId($appointment_details->user->category_id);
                    if(!empty($manage_fees->fees_percentage)){
                        $ezzycare_fees = $manage_fees->fees_percentage;
                    }
                }
                $ezzycare_charge = (($transaction_amount * $ezzycare_fees ) / 100);
                $user_payout = $transaction_amount - $ezzycare_charge;
                $add_payout = [
                        'payout_amount'=> $user_payout,
                        'fees_charge'=> $ezzycare_charge,
                    ];
                $this->user_transaction_repo->dataCrud($add_payout, $transaction->id);

                $update = [
                        'transaction_id'=> $transaction->id,
                    ];
                $this->appointment_repo->dataCrud($update, $request->id);
                $data = $this->appointment_repo->getById($request->id);
                DB::commit();
                return self::sendSuccess($data, 'Transaction Completed');
            }
            return self::sendError([], 'Transaction Uncompleted Error');
        } catch (\Exception $e) {
             DB::rollBack();
            return self::sendException($e);
        }
    }

    public function orderPharmacyBillPay(OrderStatusRequest $request)
    {
        
        $data = array();
        $order_details = $this->order_repo->getbyIdCheckTransaction($request->id);
 
        if(empty($order_details)){
            return self::sendError([], 'Transaction already Completed');
        }

        if(!empty($order_details) && !empty($order_details->orderProductDetails)){
            foreach ($order_details->orderProductDetails as $key => $value) {
                $stock_available = $this->shop_medicine_repo->checkMedicineStock($value); 
                if(empty($stock_available)){
                     return self::sendError('', 'Stock is not available');
                }
            }
        }
        
        if(!empty($order_details) && !empty($order_details->orderProductDetails)){
            foreach ($order_details->orderProductDetails as $key => $value) {
                $stock_available = $this->shop_medicine_repo->checkMedicineStock($value);
                if (!empty($stock_available)) {
                    $product_data = [
                                    'capsual_quantity' => $stock_available->capsual_quantity - $value->quantity
                                    ];
                    $this->shop_medicine_repo->dataCrud($product_data, $stock_available->id);
                }
            }
        }
        try {
            DB::beginTransaction();
            $transaction_amount = 0;
            $transaction_amount = $order_details->total_price;
            if(!empty($request->transaction_id)){
                $updateUserTran = [
                        'transaction_type' => '0',
                        'payout_status' => '1',
                        'wallet_transaction' => '0',
                        'client_id'=> $order_details->user_id,
                        'order_id' => $order_details->id,
                    ];
                $this->user_transaction_repo->dataCrud($updateUserTran, $request->transaction_id);               
                $transaction = $this->user_transaction_repo->getById($request->transaction_id);
            }else{              

                $add_transaction = [
                        'user_id'=> $request->user()->id,
                        'client_id'=> $order_details->userDetails->id,
                        'payment_gateway_response'=> !empty($request->payment_transaction) ? $request->payment_transaction : '',
                        'transaction_date'=> $this->order_repo->getCurrentDateTime(),
                        'amount'=> $transaction_amount,
                        'mode_of_payment'=> '1',
                        'transaction_type'=> '1',
                        'status'=> '0',
                        'payout_status' => '1',
                        'order_id' => $order_details->id,
                    ];
                $transaction = $this->user_transaction_repo->dataCrud($add_transaction);
            }


            if(!empty($transaction)){
                $ezzycare_charge = 0;
                $user_payout = 0;
                $ezzycare_fees = 0;
                if(!empty($order_details->userDetails->category_id)){
                    $manage_fees = $this->fees_repo->getbyCategoryId($order_details->userDetails->category_id);
                    if(!empty($manage_fees->fees_percentage)){
                        $ezzycare_fees = $manage_fees->fees_percentage;
                    }
                }
                $ezzycare_charge = (($transaction_amount * $ezzycare_fees ) / 100);
                $user_payout = $transaction_amount - $ezzycare_charge;
                $add_payout = [
                        'payout_amount'=> $user_payout,
                        'fees_charge'=> $ezzycare_charge,
                    ];
                 $this->user_transaction_repo->dataCrud($add_payout, $transaction->id);

                $update = [
                        'status'=> '4',
                        'completed_datetime'=> $this->order_repo->getCurrentDateTime(),
                        'transaction_id'=> $transaction->id,
                    ];
                $this->order_repo->dataCrud($update, $request->id);
          
                $add_tracking = [
                        'order_id'=> $order_details->id,
                        'title'=> 'Order Placed',
                        'description'=> '',
                        'status'=> '0',
                        'estimation_datetime'=>  $this->order_repo->getCurrentDateTime(),
                    ];
                $this->order_tracking_repo->dataCrud($add_tracking);

                $data = $this->order_repo->getById($request->id);
                if (!empty($data)) {
                    $send_notification = [
                                            'sender_id' => $request->user()->id,
                                            'receiver_id' => ($request->user()->id == $data->user_id) ? $data->client_id : $data->user_id,
                                            'title' => 'Order',
                                            'message' => 'Order payment completed by '. $request->user()->user_name,
                                            'parameter' => json_encode(['order_id'=> $data->id]),
                                            'msg_type' => '6',
                                        ];
                    $this->notification_repo->sendingNotification($send_notification);
                }
                 DB::commit();
                return self::sendSuccess($data, 'Transaction Completed');
            }
            return self::sendError([], 'Transaction Uncompleted Error');
        } catch (\Exception $e) {
             DB::rollBack();
            return self::sendException($e);
        }
    }


    public function appointmentBillPaymentStatus(AppointmentPayStatusRequest $request)
    {
        $data = array();
        $appointment_details = $this->appointment_repo->getbyIdCheckTransaction($request->id);
        if(empty($appointment_details)){
            return self::sendError([], 'Transaction already Completed');
        }

        try {
            DB::beginTransaction();
            if(!empty($request->transaction_id)){
                $updateUserTran = [
                        'transaction_type' => '0',
                        'payout_status' => '1',
                        'wallet_transaction' => '0',
                        'client_id'=> $appointment_details->user_id,
                        'appointment_id' => $appointment_details->id,
                    ];
                $this->user_transaction_repo->dataCrud($updateUserTran, $request->transaction_id);               
                $transaction = $this->user_transaction_repo->getById($request->transaction_id);
            }else{
                $add_transaction = [
                        'user_id'=> $appointment_details->client_id,
                        'client_id'=> $appointment_details->user_id,
                        'amount'=> $appointment_details->appointment_price,
                        'mode_of_payment'=> '1',
                        'transaction_type'=> '1',
                        'transaction_date'=> $this->appointment_repo->getCurrentDateTime(),
                        'payment_gateway_response'=> isset($request->payment_transaction) ? $request->payment_transaction : '',
                        'status'=> $request->status,
                        'payout_status' => '1',
                        'appointment_id' => $appointment_details->id,
                    ];
                    
                $transaction = $this->user_transaction_repo->dataCrud($add_transaction);
            }
                    
            if (!empty($transaction)) {
                $ezzycare_charge = 0;
                $user_payout = 0;
                $ezzycare_fees = 0;
                $transaction_amount = $appointment_details->appointment_price;
                if (!empty($appointment_details->user->category_id)) {
                    $manage_fees = $this->fees_repo->getbyCategoryId($appointment_details->user->category_id);
                    if (!empty($manage_fees->fees_percentage)) {
                        $ezzycare_fees = $manage_fees->fees_percentage;
                    }
                }
                $ezzycare_charge = (($transaction_amount * $ezzycare_fees) / 100);
                $user_payout = $transaction_amount - $ezzycare_charge;
                $add_payout = [
                        'payout_amount'=> $user_payout,
                        'fees_charge'=> $ezzycare_charge,
                    ];
                $this->user_transaction_repo->dataCrud($add_payout, $transaction->id);

                $update = [
                        'transaction_id'=> $transaction->id,
                        'status'=> '5',
                    ];
                $this->appointment_repo->dataCrud($update, $request->id);
                $data = $this->appointment_repo->getById($request->id);
            
                if (!empty($data)) {
                    $send_notification = [
                                    'sender_id' => $request->user()->id,
                                    'receiver_id' => ($request->user()->id == $data->user_id) ? $data->client_id : $data->user_id,
                                    'title' => 'Appointment',
                                    'message' => 'Appointmnent payment completed by '. $request->user()->user_name,
                                    'parameter' => json_encode(['appointment_id'=> $data->id]),
                                    'msg_type' => '3',
                                ];
                    $this->notification_repo->sendingNotification($send_notification);
                }
                
                DB::commit();
                return self::sendSuccess($data, 'Transaction Completed');
            }
            return self::sendError([], 'Transaction Uncompleted Error');
        } catch (\Exception $e) {
             DB::rollBack();
            return self::sendException($e);
        }
    }
 
    public function treatmentPlanBillPay(TreatmentPlanRequestBillPay $request)
    {
        $data = array();
        $chat_history = $this->chat_history_repo->getTransactionCompleted($request->id);
        if(empty($chat_history)){
            return self::sendError([], 'Transaction already Completed');
        }

        try {
            DB::beginTransaction();
                $updateUserTran = [
                        'transaction_type' => '0',
                        'payout_status' => '1',
                        'wallet_transaction' => '0',
                        'client_id'=> $chat_history->user_id,
                    ];
                $this->user_transaction_repo->dataCrud($updateUserTran, $request->transaction_id);               
                $transaction = $this->user_transaction_repo->getById($request->transaction_id);
                    
            if (!empty($transaction)) {
                $ezzycare_charge = 0;
                $user_payout = 0;
                $ezzycare_fees = 0;
                $transaction_amount = $appointment_details->appointment_price;
                if (!empty($appointment_details->user->category_id)) {
                    $manage_fees = $this->fees_repo->getbyCategoryId($appointment_details->user->category_id);
                    if (!empty($manage_fees->fees_percentage)) {
                        $ezzycare_fees = $manage_fees->fees_percentage;
                    }
                }
                $ezzycare_charge = (($transaction_amount * $ezzycare_fees) / 100);
                $user_payout = $transaction_amount - $ezzycare_charge;
                $add_payout = [
                        'payout_amount'=> $user_payout,
                        'fees_charge'=> $ezzycare_charge,
                    ];
                $this->user_transaction_repo->dataCrud($add_payout, $transaction->id);

                $update = [
                        'transaction_id'=> $transaction->id,
                    ];
                $this->chat_history_repo->dataCrud($update, $request->id);
                $data = $this->chat_history_repo->getById($request->id);
            
                if (!empty($data)) {
                    $send_notification = [
                                    'sender_id' => $request->user()->id,
                                    'receiver_id' => ($request->user()->id == $data->user_id) ? $data->client_id : $data->user_id,
                                    'title' => 'Treatment plan',
                                    'message' => 'Treatment plan payment completed by '. $request->user()->user_name,
                                    'parameter' => json_encode(['treatment_plan'=> $data->id]),
                                    'msg_type' => '0',
                                ];
                    $this->notification_repo->sendingNotification($send_notification);
                }
                
                DB::commit();
                return self::sendSuccess($data, 'Transaction Completed');
            }
            return self::sendError([], 'Transaction Uncompleted Error');
        } catch (\Exception $e) {
             DB::rollBack();
            return self::sendException($e);
        }
    }



}
