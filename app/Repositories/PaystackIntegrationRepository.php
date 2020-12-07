<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\User_transaction;
use Illuminate\Support\Str;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Paystack;
use Redirect;

class PaystackIntegrationRepository extends Repository
{
    protected $model_name = 'App\Models\User_transaction';
    protected $base_url = 'https://api.paystack.co/';
    protected $headers;
    protected $model;
    

    public function __construct()
    {
        parent::__construct();
        $this->headers = [
                            'Content-Type'=> 'application/json',
                            'Authorization'=> 'Bearer '.config('app.PAYSTACK_SECRET')
                        ];
    }

    /**
     * Redirect the User to Paystack Payment Page
     * @return Url
     */
    public function makePaymentRequest($request)
    {
        return Paystack::getAuthorizationResponse($request);
    }

    /**
     * Obtain Paystack payment information
     * @return void
     */
    public function handleGatewayCallback()
    {
        $paymentDetails = Paystack::getPaymentData();
        return  $paymentDetails;
        // Now you have the payment details,
        // you can store the authorization_code in your db to allow for recurrent subscriptions
        // you can then redirect or do whatever you want
    }


    public function getBankList(){
        $client = new Client();
        $api_url = $this->base_url . 'bank' ;
        $params = [
        //If you have any Params Pass here
        ];
        
        $response = $client->request ('GET', $api_url, [
            'json' => $params,
            'headers' => $this->headers
        ]);

        return json_decode ((string)$response->getBody());
    }
   
    public function getAllCustomers(){
       return Paystack::getAllCustomers();
    }

    public function createCustomer($request){
       return Paystack::createCustomer();
    }

    public function resolvedAccount(){
        $client = new Client();
        $mobile_no = '12345678912';
        $api_url = $this->base_url . 'bank/resolve_bvn/'.$mobile_no ;
        $params = [
        ];
        
        $response = $client->request ('GET', $api_url, [
            'json' => $params,
            'headers' => $this->headers
        ]);

        return json_decode ((string)$response->getBody());
    }

    public function verificationAccount(){
        $client = new Client();
        $api_url = $this->base_url . 'bvn/match' ;
        $params = [
            'account_number'=>"0000000000",
            'bank_code'=>"087",
            'bvn'=>"12345678912", // mobile no
            'first_name'=>"bojack",
        ];
        
        $response = $client->request ('POST', $api_url, [
            'json' => $params,
            'headers' => $this->headers
        ]);

        return json_decode ((string)$response->getBody());
    }

    public function addSubaccountsDetails(){
        $client = new Client();
        $api_url = $this->base_url . 'subaccount' ;
        $params = [
            'business_name'=>'Sunshine Studios',
            'settlement_bank'=>'044',
            'account_number'=>'0193274682',
            'percentage_charge'=>'18.2'
        ];
        
        $response = $client->request ('POST', $api_url, [
            'json' => $params,
            'headers' => $this->headers
        ]);

        return json_decode ((string)$response->getBody());
    }

}