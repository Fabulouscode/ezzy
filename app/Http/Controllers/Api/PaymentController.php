<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use App\Repositories\PaystackIntegrationRepository;
use App\Http\Requests\api\paystack\CustomerCreateRequest;

class PaymentController extends BaseApiController
{
     private $paystack_integration_repo;

    public function __construct(
        PaystackIntegrationRepository $paystack_integration_repo
        )
    {
        parent::__construct();
        $this->paystack_integration_repo = $paystack_integration_repo;
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
    
    /**
     * Redirect the User to Paystack Payment Page
     * @return Url
     */
    public function makePaymentRequest(Request $request)
    {
        $data = $request->all();
        try{       
            return $this->paystack_integration_repo->makePaymentRequest($data);
        }catch(\Exception $e) {
            return self::sendException($e);
        }        
    }

    /**
     * Obtain Paystack payment information
     * @return void
     */
    public function handleGatewayCallback()
    {
        try{       
            return $this->paystack_integration_repo->handleGatewayCallback();
        }catch(\Exception $e) {
            return self::sendException($e);
        } 
        
        // Now you have the payment details,
        // you can store the authorization_code in your db to allow for recurrent subscriptions
        // you can then redirect or do whatever you want
    }
}
