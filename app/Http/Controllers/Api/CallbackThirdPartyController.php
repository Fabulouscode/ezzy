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
     private $user_transaction_repo, $user_repo;

    public function __construct(
        UserTransactionRepository $user_transaction_repo,
        UserRepository $user_repo
        )
    {
        parent::__construct();
        $this->user_transaction_repo = $user_transaction_repo;
        $this->user_repo = $user_repo;
    }


    public function getPaystackCallback(Request $request)
    {
        \Log::info("============Callback getPaystackCallback=================");
        \Log::info(json_encode($request->all()));
        if(!empty($request->event) && $request->event == "charge.success"){
            if(!empty($request->data) && !empty($request->data['reference']) && !empty($request->data['gateway_response']) && !empty($request->data['customer']) && !empty($request->data['customer']['email']) && $request->data['gateway_response'] == 'Successful'){
                $walletBalance = $this->user_transaction_repo->getPendingTransactionCallback($request->data['customer']['email'], $request->data['reference']); 
                if(!empty($walletBalance) && !empty($walletBalance->id)){
                    $wallet_transaction = [
                        'user_id'=> $walletBalance->user_id,
                        'transaction_date'=> $this->user_transaction_repo->getCurrentDateTime(),
                        'amount'=> ($request->data['amount'] / 100),                        
                        'payment_gateway_response'=> $request->data['reference'],
                        'payment_gateway_full_response'=> json_encode($request->all()),
                        'status'=> '0',
                        'transaction_msg'=>'Add Wallet to online pay',
                    ];  
                    $this->user_transaction_repo->dataCrud($wallet_transaction, $walletBalance->id);
                    $add_transaction = [
                        'user_id'=> $walletBalance->user_id,
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
                    ];
                    $this->user_transaction_repo->dataCrud($add_transaction);
                    $this->user_repo->userWalletUpdate($walletBalance->user_id); 
                }
                 
            }
        }

    }
   
    public function getInterswitchCallback(Request $request)
    {
        \Log::info("============Callback getInterswitchCallback=================");
        \Log::info(json_encode($request->all()));
  
        // if(!empty($request->event) && $request->event == "TRANSACTION.COMPLETED"){
        //     if(!empty($request->data) && !empty($request->data['reference']) && !empty($request->data['gateway_response']) && !empty($request->data['customer']) && !empty($request->data['customer']['email']) && $request->data['gateway_response'] == 'Successful'){
        //         $walletBalance = $this->user_transaction_repo->getPendingTransactionCallback($request->data['customer']['email'], $request->data['reference']); 
        //         if(!empty($walletBalance) && !empty($walletBalance->id)){
        //             $wallet_transaction = [
        //                 'user_id'=> $walletBalance->user_id,
        //                 'transaction_date'=> $this->user_transaction_repo->getCurrentDateTime(),
        //                 'amount'=> ($request->data['amount'] / 100),                        
        //                 'payment_gateway_response'=> json_encode($request->all()),
        //                 'payment_gateway_full_response'=> json_encode($request->all()),
        //                 'status'=> '0',
        //                 'transaction_msg'=>'Add Wallet to online pay',
        //             ];  
        //             $this->user_transaction_repo->dataCrud($wallet_transaction, $walletBalance->id);
        //             $add_transaction = [
        //                 'user_id'=> $walletBalance->user_id,
        //                 'transaction_date'=> $this->user_transaction_repo->getCurrentDateTime(),
        //                 'amount'=> ($request->data['amount'] / 100),                             
        //                 'payment_gateway_response'=> json_encode($request->all()),
        //                 'payment_gateway_full_response'=> json_encode($request->all()),
        //                 'mode_of_payment'=> '0',
        //                 'transaction_type'=> '0',
        //                 'wallet_transaction'=> '1',
        //                 'payout_status'=> '0',
        //                 'status'=> '0',
        //                 'transaction_msg'=>'Wallet Topup',
        //             ];
        //             $this->user_transaction_repo->dataCrud($add_transaction);
        //             $this->user_repo->userWalletUpdate($walletBalance->user_id); 
        //         }
                 
        //     }
        // }

    }


}
