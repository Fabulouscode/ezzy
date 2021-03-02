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
use App\Http\Helpers\Helper;

class PaystackIntegrationRepository extends Repository
{
    protected $model_name = 'App\Models\User_transaction';
    protected $base_url = 'https://api.paystack.co';
    protected $headers;
    protected $model;
    

    public function __construct()
    {
        parent::__construct();

    }
    
    /**
     * make payment
     * @return Url
     */
    public function makePaymentRequest($data)
    {           
        $url = $this->base_url."/transaction/initialize";
        $fields_string = http_build_query($data);
        return Helper::sendCurlRequestPaystack($url, $fields_string);
    }
    
    /**
     * Obtain Paystack payment information
     * @return void
     */
    public function handleGatewayCallback($refrence)
    {
        $url = $this->base_url."/transaction/verify/".$refrence;
        return Helper::sendCurlGetRequestPaystack($url);
    }


 
   




    // public function getBankList(){
    //     $client = new Client();
    //     $api_url = $this->base_url . 'bank' ;
    //     $params = [
    //     //If you have any Params Pass here
    //     ];
        
    //     $response = $client->request ('GET', $api_url, [
    //         'json' => $params,
    //         'headers' => $this->headers
    //     ]);

    //     return json_decode ((string)$response->getBody());
    // }

    // public function resolvedAccount(){
    //     $client = new Client();
    //     $mobile_no = '12345678912';
    //     $api_url = $this->base_url . 'bank/resolve_bvn/'.$mobile_no ;
    //     $params = [
    //     ];
        
    //     $response = $client->request ('GET', $api_url, [
    //         'json' => $params,
    //         'headers' => $this->headers
    //     ]);

    //     return json_decode ((string)$response->getBody());
    // }

    // public function verificationAccount(){
    //     $client = new Client();
    //     $api_url = $this->base_url . 'bvn/match' ;
    //     $params = [
    //         'account_number'=>"0000000000",
    //         'bank_code'=>"087",
    //         'bvn'=>"12345678912", // mobile no
    //         'first_name'=>"bojack",
    //     ];
        
    //     $response = $client->request ('POST', $api_url, [
    //         'json' => $params,
    //         'headers' => $this->headers
    //     ]);

    //     return json_decode ((string)$response->getBody());
    // }

    // public function addSubaccountsDetails(){
    //     $client = new Client();
    //     $api_url = $this->base_url . 'subaccount' ;
    //     $params = [
    //         'business_name'=>'Sunshine Studios',
    //         'settlement_bank'=>'044',
    //         'account_number'=>'0193274682',
    //         'percentage_charge'=>'18.2'
    //     ];
        
    //     $response = $client->request ('POST', $api_url, [
    //         'json' => $params,
    //         'headers' => $this->headers
    //     ]);

    //     return json_decode ((string)$response->getBody());
    // }

}