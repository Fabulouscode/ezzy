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
use App\Http\Requests\Api\CartCheckoutRequest;
use App\Http\Requests\Api\AppointmentStatusRequest;
use App\Http\Requests\Api\OrderStatusRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon as Carbon;

class TransactionController extends BaseApiController
{

    private $appointment_repo, $user_transaction_repo, $user_repo, $shop_medicine_repo, $order_repo, $order_product_repo, $order_tracking_repo;

    public function __construct(
        AppointmentRepository $appointment_repo, 
        UserTransactionRepository $user_transaction_repo, 
        UserRepository $user_repo,
        ShopMedicineDetailsRepository $shop_medicine_repo,
        OrderRepository $order_repo,
        OrderProductRepository $order_product_repo,
        OrderTrackingRepository $order_tracking_repo
        )
    {
        parent::__construct();
        $this->appointment_repo = $appointment_repo;
        $this->user_transaction_repo = $user_transaction_repo;
        $this->user_repo = $user_repo;
        $this->shop_medicine_repo = $shop_medicine_repo;
        $this->order_repo = $order_repo;
        $this->order_product_repo = $order_product_repo;
        $this->order_tracking_repo = $order_tracking_repo;
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
            $transaction_amount = 0;
            $start_appointment  = new Carbon($appointment_details->appointment_date.''.$appointment_details->appointment_time);
            $end_appointment   = new Carbon($appointment_details->completed_datetime);
            $appointment_timing =  $start_appointment->diffInMinutes($end_appointment);
            if(!empty($appointment_details->appointmentServices)){           
                foreach ($appointment_details->appointmentServices as $key => $value) {
                    $transaction_amount += $value->userService->service_charge;
                }
                
            } else { 

                if (!empty($appointment_details->user_service_id)) {
                    if ($appointment_details->userService->service_charge_type == '1') {
                        $transaction_amount = $appointment_details->userService->service_charge * ($appointment_timing);
                    } elseif ($appointment_details->userService->service_charge_type == '2') {
                        $transaction_amount = $appointment_details->userService->service_charge * ($appointment_timing/60);
                    } elseif ($appointment_details->userService->service_charge_type == '3') {
                        $transaction_amount = $appointment_details->userService->service_charge;
                    }
                } else {
                    if ($appointment_details->user->category_id == '5') {
                        if (empty($appointment_details->completed_datetime)) {
                            $transaction_amount = $appointment_details->user->userDetails->fees_day;
                        } else {
                            $transaction_amount = $appointment_details->user->userDetails->fees_hour * ($appointment_timing/60);
                        }
                    } else {
                        if ($appointment_details->urgent == '1') {
                            $transaction_amount = $appointment_details->user->userDetails->urgent_fees * ($appointment_timing/60);
                        } else {
                            $transaction_amount = $appointment_details->user->userDetails->normal_fees * ($appointment_timing/60);
                        }
                    }
                }
            }
            
            if($appointment_details->appointment_type == '1'){
                $transaction_amount +=  $appointment_details->user->userDetails->home_visit_fees;
            }
                $add_transaction = [
                        'user_id'=> $request->user()->id,
                        'transaction_date'=> $this->appointment_repo->getCurrentDateTime(),
                        'amount'=> $transaction_amount,
                        'mode_of_payment'=> '1',
                        'transaction_type'=> '0',
                        'status'=> '0',
                    ];
                
            $transaction = $this->user_transaction_repo->dataCrud($add_transaction);

            if(!empty($transaction)){
                $update = [
                        'status'=> '5',
                        'appointment_price'=> $transaction_amount,
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

        try {
            DB::beginTransaction();
            $transaction_amount = 0;
            $transaction_amount += $order_details->total_price;
            if($order_details->delivery_type == '0'){
                $transaction_amount += $order_details->shipping_price;
            }

                $add_transaction = [
                        'user_id'=> $order_details->userDetails->id,
                        'transaction_date'=> $this->order_repo->getCurrentDateTime(),
                        'amount'=> $transaction_amount,
                        'mode_of_payment'=> '1',
                        'transaction_type'=> '0',
                        'status'=> '0',
                    ];
            $transaction = $this->user_transaction_repo->dataCrud($add_transaction);

            if(!empty($transaction)){
                $update = [
                        'status'=> '1',
                        'completed_datetime'=> $this->order_repo->getCurrentDateTime(),
                        'transaction'=> $transaction->id,
                    ];
                $this->order_repo->dataCrud($update, $request->id);
          
                $add_tracking = [
                        'order_id'=> $order_details->id,
                        'title'=> 'Order Placed',
                        'description'=> '',
                        'status'=> '0',
                    ];
                $this->order_tracking_repo->dataCrud($add_tracking);

                $data = $this->order_repo->getById($request->id);
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
