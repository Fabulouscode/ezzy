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

    public function getbyUserId($user_id)
    {
        return $this->model->where('user_id', $user_id)->get();
    }
    
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

    public function getDatatable($request)
    {
        $data = $this->getbyUserId($request->id); //->getWithRelationship($request);
        return Datatables::of($data)
            ->editColumn('mode_of_payment',function($selected)
            {
                if($selected->mode_of_payment == '1')
                    return '<div class="text-success"><strong>Credit</strong></div>';
                return '<div class="text-danger"><strong>Debit</strong></div>';
            })
            ->editColumn('status',function($selected)
            {
                if($selected->status == '1')
                    return '<div class="text-success"><strong>Success</strong></div>';
                return '<div class="text-danger"><strong>Failed</strong></div>';
            })
            ->editColumn('transaction_type',function($query)
            {
                $type = ['0' => 'Wallet','1' => 'Net Banking','2' => 'Debit/Credit Card','3' => 'Paypal' ] ;
                return '<div class="text-success"><strong>'.$type[$query->transaction_type].'</strong></div>';
            })
            ->editColumn('user_name', function($query) {
                return $query->users ? $query->users->first_name.' '.$query->users->last_name : '-';
            })
            ->editColumn('created_at', function($query) {
                return $query->created_at ? date('d M, Y h:i A',strtotime($query->created_at)) : '-';
            })
            ->editColumn('transaction_date', function($query) {
                return $query->transaction_date ? date('d M, Y h:i A',strtotime($query->transaction_date)) : '-';
            })
            ->rawColumns(['mode_of_payment','transaction_type','status'])
            ->make(true);
    }

    public function getWithRelationship($request)
    {
        $query = $this->model->with('users');
        $query = $query->orderBy('id','desc')->get();
        return $query;
    }
    
    
}