<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use App\Repositories\PaystackIntegrationRepository;
use App\Http\Requests\api\paystack\CustomerCreateRequest;
use App\Repositories\UserTransactionRepository;
use App\Repositories\AppointmentRepository;
use App\Repositories\UserRepository;

class CallbackThirdPartyController extends BaseApiController
{
     private $user_transaction_repo, $user_repo, $paystack_repo;

    public function __construct(
        UserTransactionRepository $user_transaction_repo,
        PaystackIntegrationRepository $paystack_repo,
        UserRepository $user_repo
        )
    {
        parent::__construct();
        $this->user_transaction_repo = $user_transaction_repo;
        $this->user_repo = $user_repo;
        $this->paystack_repo = $paystack_repo;
    }


    public function getPaystackCallback(Request $request)
    {
       
        \Log::info("============Callback getPaystackCallback=================");
        \Log::info(json_encode($request->all()));
        if(!empty($request->event) && $request->event == "charge.success"){
            if(!empty($request->data) && !empty($request->data['reference']) && !empty($request->data['gateway_response']) && !empty($request->data['customer']) && !empty($request->data['customer']['email']) && $request->data['status'] == 'success'){
                $walletBalance = $this->user_transaction_repo->getPendingTransactionCallback($request->data['customer']['email'], $request->data['reference']);                
                if(empty($walletBalance)){
                    $userCheck = $this->user_repo->getEmailToUser($request->data['customer']['email']); 
                    $walletTransaction = [
                        'user_id'=> $userCheck->id,
                        'transaction_date'=> $this->user_transaction_repo->getCurrentDateTime(),
                        'amount'=> ($request->data['amount'] / 100),                        
                        'payment_gateway_response'=> $request->data['reference'],
                        'payment_gateway_full_response'=> json_encode($request->all()),
                        'mode_of_payment'=> '1',
                        'transaction_type'=> '1',
                        'wallet_transaction'=> '1',
                        'payout_status'=> '0',
                        'status'=> '0',
                        'transaction_msg'=>'Add Wallet to online pay',
                        'online_transaction_pay'=>'1',
                    ];  
                    $this->user_transaction_repo->dataCrud($walletTransaction);
                    $add_transaction = [
                        'user_id'=> $userCheck->id,
                        'transaction_date'=> $this->user_transaction_repo->getCurrentDateTime(),
                        'amount'=> ($request->data['amount'] / 100),                             
                        'payment_gateway_response'=> $request->data['reference'],
                        'payment_gateway_full_response'=> json_encode($request->all()),
                        'mode_of_payment'=> '0',
                        'transaction_type'=> '0',
                        'wallet_transaction'=> '1',
                        'payout_status'=> '0',
                        'status'=> '0',
                        'transaction_msg'=>'Wallet Topup',
                        'online_transaction_pay'=>'1',
                    ];
                    $this->user_transaction_repo->dataCrud($add_transaction);
                    $this->user_repo->userWalletUpdate($userCheck->id); 
                }else{
                    $userCheck = $this->user_repo->getEmailToUser($request->data['customer']['email']); 
                    if(!empty($userCheck)){
                        $transactionCompleted = $this->user_transaction_repo->getTransactionCheck($request->data['customer']['email'], $request->data['reference']); 
                        \Log::info($transactionCompleted);
                        if(!empty($transactionCompleted)){
                            \Log::info("============Callback getPaystackCallback transaction already completed=================");
                            if($transactionCompleted->status == '2'){
                                if($transactionCompleted->payout_status == '1'){
                                    $paystackVerify = $this->paystack_repo->handleGatewayCallback($request->data['reference']); 
                                    if(!empty($paystackVerify) && !empty($paystackVerify['status']) && $paystackVerify['status'] == 'true'){
                                        $walletTransaction = [                  
                                            'payment_gateway_full_response'=> json_encode($paystackVerify),
                                            'status'=> '0',
                                            'online_transaction_pay'=>'1',
                                        ];  
                                        $this->user_transaction_repo->dataCrud($walletTransaction, $transactionCompleted->id);
                                    }                                   
                                }                 
                            }
                            return true;
                        }else{
                            $addNewTransaction = [
                                'user_id'=> $userCheck->id,
                                'transaction_date'=> $this->user_transaction_repo->getCurrentDateTime(),
                                'amount'=> ($request->data['amount'] / 100),                        
                                'payment_gateway_response'=> $request->data['reference'],
                                'payment_gateway_full_response'=> json_encode($request->all()),
                                'mode_of_payment'=> '1',
                                'transaction_type'=> '1',
                                'wallet_transaction'=> '1',
                                'payout_status'=> '0',
                                'status'=> '2',
                                'online_transaction_pay'=>'2',
                            ];  
                            $this->user_transaction_repo->dataCrud($addNewTransaction);
                        }
                        return true;
                    }
                }
                 
            }
        }

    }
   
    public function getInterswitchCallback(Request $request)
    {
        \Log::info("============Callback getInterswitchCallback=================");
        \Log::info(json_encode($request->all()));
        if(!empty($request->event) && $request->event == "TRANSACTION.COMPLETED"){
            if(!empty($request->data) && !empty($request->uuid) && !empty($request->data) && !empty($request->data['merchantCustomerId']) && !empty($request->data['amount']) && !empty($request->data['merchantReference']) && isset($request->data['responseCode']) && $request->data['responseCode'] == '00'){
                $walletBalance = $this->user_transaction_repo->getPendingTransaction($request->data['merchantCustomerId'], $request->uuid);  
                if(!empty($walletBalance) && !empty($walletBalance->id)){
                    $wallet_transaction = [
                        'user_id'=> $walletBalance->user_id,
                        'transaction_date'=> $this->user_transaction_repo->getCurrentDateTime(),
                        'amount'=> ($request->data['amount'] / 100),                        
                        'payment_gateway_response'=> $request->uuid,
                        'payment_gateway_full_response'=> json_encode($request->all()),
                        'status'=> '0',
                        'transaction_msg'=>'Add Wallet to online pay',
                        'online_transaction_pay'=>'2',
                    ];  
                    $this->user_transaction_repo->dataCrud($wallet_transaction, $walletBalance->id);
                    $add_transaction = [
                        'user_id'=> $walletBalance->user_id,
                        'transaction_date'=> $this->user_transaction_repo->getCurrentDateTime(),
                        'amount'=> ($request->data['amount'] / 100),                             
                        'payment_gateway_response'=> $request->uuid,
                        'payment_gateway_full_response'=> json_encode($request->all()),
                        'mode_of_payment'=> '0',
                        'transaction_type'=> '0',
                        'wallet_transaction'=> '1',
                        'payout_status'=> '0',
                        'status'=> '0',
                        'transaction_msg'=>'Wallet Topup',
                        'online_transaction_pay'=>'2',
                    ];
                    $this->user_transaction_repo->dataCrud($add_transaction);
                    $this->user_repo->userWalletUpdate($walletBalance->user_id); 
                }
                 
            }
        }

    }


}
