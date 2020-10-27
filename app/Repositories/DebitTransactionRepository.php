<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\Debit_transaction;
use App\Models\Credit_transaction;
use Illuminate\Support\Str;

class DebitTransactionRepository extends Repository
{
    protected $model_name = 'App\Models\Debit_transaction';
    protected $model;

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
        //  \DB::connection()->enableQueryLog(); 

        // $offset = $request->offset * $this->api_data_limit;
      
        // $debit = $this->model->where('user_id',$request->user()->id)->orderBy('transaction_date','desc')->get();
      
        // $credit = Credit_transaction::where('user_id',$request->user()->id)->orderBy('transaction_date','desc')->get();

        // $query = $credit->merge($debit);
        
        // dd($query->toArray());
        // $query = $query->orderBy('transaction_date','desc')->offset($offset)->limit($this->api_data_limit);
        
        // $query = $query->get();
        
        //   dd(\DB::getQueryLog());
        return '';
    }
    
   
}