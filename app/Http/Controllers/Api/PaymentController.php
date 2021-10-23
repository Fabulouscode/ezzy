<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use App\Repositories\PaystackIntegrationRepository;
use App\Http\Requests\api\paystack\CustomerCreateRequest;
use App\Repositories\UserTransactionRepository;
use App\Repositories\AppointmentRepository;

class PaymentController extends BaseApiController
{
     private $paystack_integration_repo, $user_transaction_repo, $appointment_repo;

    public function __construct(
        AppointmentRepository $appointment_repo, 
        UserTransactionRepository $user_transaction_repo,
        PaystackIntegrationRepository $paystack_integration_repo
        )
    {
        parent::__construct();
        $this->paystack_integration_repo = $paystack_integration_repo;
        $this->user_transaction_repo = $user_transaction_repo;
        $this->appointment_repo = $appointment_repo;
    }
    
    /**
     * Redirect the User to Paystack Payment Page
     * @return Url
     */
    public function makePaymentRequest(Request $request)
    {
        $data = $request->all();
        try{       
            \Log::info("makePaymentRequest ".json_encode($data));     
            $response = $this->paystack_integration_repo->makePaymentRequest($data);
            $wallet_transaction = [
                'user_id'=> $request->user()->id,
                'transaction_date'=> $this->appointment_repo->getCurrentDateTime(),
                'amount'=> $request->amount,                        
                'payment_gateway_response'=> (!empty($response['reference'])) ? $response['reference'] : '',
                'mode_of_payment'=> '1',
                'transaction_type'=> '1',
                'wallet_transaction'=> '1',
                'payout_status'=> '0',
                'status'=> '2',
                'transaction_msg'=>'Add Wallet to online pay',
            ];
            $response['transaction'] = $this->user_transaction_repo->dataCrud($wallet_transaction);
            return self::sendSuccess($response, 'Payment initialize');
        }catch(\Exception $e) {
            return self::sendException($e);
        }        
    }

     /**
     * Obtain Paystack payment information
     * @return void
     */
    public function handleGatewayCallback($refrence)
    {
        try{        
            $response = $this->paystack_integration_repo->handleGatewayCallback($refrence);
            return self::sendSuccess($response, 'Payment verify');
        }catch(\Exception $e) {
            return self::sendException($e);
        } 
        
        // Now you have the payment details,
        // you can store the authorization_code in your db to allow for recurrent subscriptions
        // you can then redirect or do whatever you want
    }

    /**
     * create customer
     * @return Url
     */
    public function createCustomer(CustomerCreateRequest $request)
    {
                // email:customer1@gmail.com
                // first_name:customer1
                // phone:+28000865549

        try{       
            return $this->paystack_integration_repo->createCustomer($request);
        }catch(\Exception $e) {
            return self::sendException($e);
        }        
    }
    
    /**
     * get all customer
     * @return Url
     */
    public function getAllCustomers()
    {
        try{       
            return $this->paystack_integration_repo->getAllCustomers();
        }catch(\Exception $e) {
            return self::sendException($e);
        }        
    }
    



}
