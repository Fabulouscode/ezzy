<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\User_transaction;
use Illuminate\Support\Str;

class UserTransactionRepository extends Repository
{
    protected $model_name = 'App\Models\User_transaction';
    protected $model;
    
    public $status = array(
        '0' => 'Success',
        '1' => 'Unsuccess',
        '2' => 'Pending',
    );
    
    public $mode_of_payment = array(
        '0' => 'Debit',
        '1' => 'Credit',
    );
    
    public $transaction_type = array(
        '0' => 'Wallet',
        '1' => 'Net Banking',
        '2' => 'Debit/Credit Card',
        '3' => 'Paypal',
    );

    public function __construct()
    {
        parent::__construct();
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $data
     * @return \Illuminate\Http\Response
     */
    public function dataCrud($data, $id = '')
    {   
        if(!empty($data)){
            if(!empty($id)){
                return $this->update($data, $id);
            } else {
                return $this->store($data);
            }
        }
    }

 
    /**
     * get Model and return the instance.
     *
     * @param int $user_id
     */
    public function getUserbyCalculate($user_id, $mode_of_payment = 0)
    {
        return $this->model->where('mode_of_payment', $mode_of_payment)->where('transaction_type', '0')->where('status', '0')->where('user_id', $user_id)->sum('amount');
    }
   
    public function getUserbyWalletBalance($user_id)
    {
        $total_earning =  $credit_balance = $debit_balance  = 0;
        $debit_balance = $this->getUserbyCalculate($user_id, '0'); 
        $credit_balance = $this->getUserbyCalculate($user_id, '1');
        $total_earning = $debit_balance - $credit_balance;      
        return $total_earning;
    }

    /**
     * get Model and return the instance.
     *
     * @param int $user_id
     */
    public function getbyUserId($user_id)
    {
        return $this->model->where('user_id', $user_id)->get();
    }
    
        /**
     * get Model and return the instance.
     *
     * @param int $user_id
     */
    public function getTransactionHistory($request)
    {
       
        $query = $this->model;
        
        if(!empty($request->last_id)){
            $query = $query->where('id', '<', $request->last_id);    
        }            

        $query = $query->limit($this->api_data_limit);

        $query = $this->model->where('user_id',$request->user()->id)->where('status','0');
      
        $query = $query->orderBy('transaction_date','desc')->offset($offset)->limit($this->api_data_limit);
        
        $query = $query->get();

        return $query;
    }
    
    
}