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
use App\Http\Requests\Api\AddWalletBalanceRequest;
use App\Http\Requests\Api\DeductionWalletBalanceRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon as Carbon;

class WalletController extends BaseApiController
{
     private $appointment_repo, $fees_repo, $notification_repo,  $user_transaction_repo, $user_repo, $shop_medicine_repo, $order_repo, $order_product_repo, $order_tracking_repo;

    public function __construct(
        AppointmentRepository $appointment_repo, 
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
        $this->user_transaction_repo = $user_transaction_repo;
        $this->user_repo = $user_repo;
        $this->shop_medicine_repo = $shop_medicine_repo;
        $this->order_repo = $order_repo;
        $this->order_product_repo = $order_product_repo;
        $this->order_tracking_repo = $order_tracking_repo;
        $this->fees_repo = $fees_repo;
        $this->notification_repo = $notification_repo;
    }

    public function walletUpdateBalance($user_id)
    {          
        try {
            $wallet_balance = $this->user_transaction_repo->checkPatientWalletBalance($user_id); 
            $lock_wallet_balance = $this->user_transaction_repo->checkPatientWalletBalance($user_id); 
            $update = ['wallet_balance'=> $wallet_balance, 'lock_wallet_balance'=> $lock_wallet_balance];
            $this->user_repo->dataCrudUsingData($update, $user_id);             
            return self::sendSuccess($data, 'Wallet Update');
        } catch (\Exception $e) {
            return self::sendException($e);
        }
        return $total_earning;
    }

    public function addWalletBalance(AddWalletBalanceRequest $request)
    {          
            $add_transaction = [
                        'user_id'=> $request->user()->id,
                        'transaction_date'=> $this->appointment_repo->getCurrentDateTime(),
                        'amount'=> $request->amount,                        
                        'payment_gateway_response'=> $request->payment_transaction,
                        'mode_of_payment'=> '0',
                        'transaction_type'=> '1',
                        'wallet_transaction'=> '1',
                        'payout_status'=> '0',
                        'status'=> '0',
                    ];
        try {
            $this->user_transaction_repo->dataCrud($add_transaction);
            $this->user_repo->userWalletUpdate($request->user()->id);        
            return self::sendSuccess([], 'Wallet balance add Successfully');
        } catch (\Exception $e) {
            return self::sendException($e);
        }
    }

    public function deductionWalletBalance(DeductionWalletBalanceRequest $request)
    {          
        $wallet_balance = $this->user_transaction_repo->checkPatientWalletBalance($request->user()->id);
        if($wallet_balance <= $request->amount){
            return self::sendError([], "You haven't enough balance in wallet. Please Top up your wallet.", 402);
        } 
       
            $add_transaction = [
                        'user_id'=> $request->user()->id,
                        'transaction_date'=> $this->appointment_repo->getCurrentDateTime(),
                        'amount'=> $request->amount,                        
                        'payment_gateway_response'=> $request->payment_transaction,
                        'mode_of_payment'=> '1',
                        'transaction_type'=> '1',
                        'wallet_transaction'=> '1',
                        'payout_status'=> '0',
                        'status'=> '0',
                    ];
        try {
            $transaction = $this->user_transaction_repo->dataCrud($add_transaction);
            $this->user_repo->userWalletUpdate($request->user()->id);        
            return self::sendSuccess($transaction, 'Wallet balance deduction Successfully');
        } catch (\Exception $e) {
            return self::sendException($e);
        }
    }
  
    public function getWalletBalance(Request $request)
    {          
        $data = array();
        $wallet_balance = $this->user_repo->getById($request->user()->id)->patientWalletFormat();
        $data [] = ["balance"=> $wallet_balance['wallet_balance'],"balance_type"=> "Available Balance"];
        $data []= ["balance"=> $wallet_balance['lock_wallet_balance'],"balance_type"=> "Locked Balance"];
        return self::sendSuccess($data, 'Wallet balance add Successfully');
    }
}
