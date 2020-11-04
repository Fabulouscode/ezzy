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
use App\Http\Requests\Api\CartCheckoutRequest;
use App\Http\Requests\Api\AppointmentStatusRequest;
use Carbon\Carbon as Carbon;

class TransactionController extends BaseApiController
{

    private $appointment_repo, $user_transaction_repo, $user_repo, $shop_medicine_repo, $order_repo, $order_product_repo;

    public function __construct(
        AppointmentRepository $appointment_repo, 
        UserTransactionRepository $user_transaction_repo, 
        UserRepository $user_repo,
        ShopMedicineDetailsRepository $shop_medicine_repo,
        OrderRepository $order_repo,
        OrderProductRepository $order_product_repo
        )
    {
        parent::__construct();
        $this->appointment_repo = $appointment_repo;
        $this->user_transaction_repo = $user_transaction_repo;
        $this->user_repo = $user_repo;
        $this->shop_medicine_repo = $shop_medicine_repo;
        $this->order_repo = $order_repo;
        $this->order_product_repo = $order_product_repo;
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
            $transaction_amount = 0;
            $start_appointment  = new Carbon($appointment_details->appointment_date.''.$appointment_details->appointment_time);
            $end_appointment   = new Carbon($appointment_details->completed_datetime);
            $appointment_timing =  $start_appointment->diffInMinutes($end_appointment);
            if($appointment_details->urgent == '1'){
                $transaction_amount = $appointment_details->user->userDetails->urgent_fees * ($appointment_timing/60);
            }else{
                 $transaction_amount = $appointment_details->user->userDetails->normal_fees * ($appointment_timing/60);
            }
            
            if($appointment_details->appointment_type == '1'){
                $transaction_amount = $transaction_amount + $appointment_details->user->userDetails->home_visit_fees;
            }
                $add_credit_transaction = [
                        'user_id'=> $request->user()->id,
                        'transaction_date'=> $this->appointment_repo->getCurrentDateTime(),
                        'amount'=> $transaction_amount,
                        'mode_of_payment'=> '1',
                        'transaction_type'=> '0',
                        'status'=> '0',
                    ];
                
                $add_debit_transaction = [
                        'user_id'=> $appointment_details->user->id,
                        'transaction_date'=> $this->appointment_repo->getCurrentDateTime(),
                        'amount'=> $transaction_amount,
                        'mode_of_payment'=> '0',
                        'transaction_type'=> '0',
                        'status'=> '0',
                    ];
            $credit_transaction = $this->user_transaction_repo->dataCrud($add_credit_transaction);
            $debit_transaction = $this->user_transaction_repo->dataCrud($add_debit_transaction);

            if(!empty($credit_transaction) && !empty($debit_transaction)){
                $update = [
                        'status'=> '5',
                        'appointment_price'=> $transaction_amount,
                        'credit_transaction_id'=> $credit_transaction->id,
                        'debit_transaction_id'=> $debit_transaction->id,
                    ];
                $this->appointment_repo->dataCrud($update, $request->id);
                $data = $this->appointment_repo->getById($request->id);
                self::updateUserWalletBalance($request->user()->id);
                self::updateUserWalletBalance($appointment_details->user->id);
                return self::sendSuccess($data, 'Transaction Completed');
            }
            return self::sendError([], 'Transaction Uncompleted Error');
        } catch (\Exception $e) {
            return self::sendException($e);
        }
    }



}
