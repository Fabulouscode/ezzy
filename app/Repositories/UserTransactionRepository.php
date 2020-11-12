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
   
    public function getHCPTYPEWalletBalance($provider, $user_id)
    {
        $query = $this->model->with(['users','transactionAppointment','transactionOrder']);
      
        if($provider != 'patients'){
            $query = $query->orWhere(function ($query) use ($user_id) {
                $query = $query->orWhereHas('transactionAppointment', function ($query) use ($user_id) {
                    $query->where('user_id', $user_id);
                });
                $query = $query->orWhereHas('transactionOrder', function ($query) use ($user_id) {
                    $query->where('user_id', $user_id);
                });
            });
        }else{            
            $query = $query->where('user_id',$user_id);
        }
        $query = $query->orderBy('id','desc')->sum('amount');

        return $query;
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
        $data = $this->getPayoutWithRelationship(); //->getWithRelationship($request);
        return Datatables::of($data)
            ->editColumn('mode_of_payment',function($selected)
            {
                if($selected->mode_of_payment == '1')
                    return '<div class="badge badge-success">Credit</div>';
                return '<div class="badge badge-danger">Debit</div>';
            })
            ->editColumn('status',function($selected)
            {
                if($selected->status == '0')
                    return '<div class="badge badge-success">Success</div>';
                return '<div class="badge badge-danger">Failed</div>';
            })
            ->editColumn('transaction_type',function($selected)
            {
                return '<div class="badge badge-success">'.$this->transaction_type[$selected->transaction_type].'</div>';
            })
            ->editColumn('user_name', function($selected) {
                if(!empty($selected->transactionAppointment) && !empty($selected->transactionAppointment->user)){
                   return $selected->transactionAppointment->user->first_name.' '.$selected->transactionAppointment->user->last_name;
                }else if(!empty($selected->transactionOrder) && !empty($selected->transactionOrder->userDetails)){
                    return $selected->transactionOrder->userDetails->first_name.' '.$selected->transactionOrder->userDetails->last_name;
                }
            })
            ->editColumn('created_at', function($selected) {
                return $selected->created_at ? date('d M, Y h:i A',strtotime($selected->created_at)) : '-';
            })
            ->editColumn('transaction_date', function($selected) {
                return $selected->transaction_date ? date('d M, Y h:i A',strtotime($selected->transaction_date)) : '-';
            })
            ->rawColumns(['mode_of_payment','transaction_type','status'])
            ->make(true);
    }

    public function getWithRelationship($request)
    {
        $query = $this->model->with(['users','transactionAppointment','transactionOrder','transactionOrder.userDetails','transactionAppointment.user']);
      
        if($request->provider != 'patients'){
            $query = $query->orWhere(function ($query) use ($request) {
                $query = $query->orWhereHas('transactionAppointment', function ($query) use ($request) {
                    $query->where('user_id', $request->id);
                });
                $query = $query->orWhereHas('transactionOrder', function ($query) use ($request) {
                    $query->where('user_id', $request->id);
                });
            });
        }else{            
            $query = $query->where('user_id',$request->id);
        }
        $query = $query->orderBy('id','desc')->get();
   
        return $query;
    }
  
    public function getPayoutWithRelationship()
    {
        $query = $this->model->with(['users','transactionAppointment','transactionOrder','transactionOrder.userDetails','transactionAppointment.user']);
        $query = $query->orderBy('id','desc')->get();
   
        return $query;
    }

    public function getDatatablebyUserId($request)
    {
        $data = $this->getWithRelationship($request); 
        return Datatables::of($data)
            ->editColumn('user_name', function($selected) use ($request) {     

                if ($request->id != $selected->user_id) {
                    return $selected->users ? $selected->users->first_name.' '.$selected->users->last_name : '-';
                }else if(!empty($selected->transactionAppointment) && !empty($selected->transactionAppointment->user)){
                   return $selected->transactionAppointment->user->first_name.' '.$selected->transactionAppointment->user->last_name;
                }else if(!empty($selected->transactionOrder) && !empty($selected->transactionOrder->userDetails)){
                    return $selected->transactionOrder->userDetails->first_name.' '.$selected->transactionOrder->userDetails->last_name;
                }
         
            })
            ->editColumn('created_at', function($selected) {
                return $selected->created_at ? date('d M, Y h:i A',strtotime($selected->created_at)) : '-';
            })
            ->editColumn('transaction_data',function($selected)
            {
                $data = '';
                if(!empty($selected->transactionAppointment)){
                    $data .= '<a href="'.url('appointment/'.$selected->transactionAppointment->id).'" target="_blank">Appointment #'.$selected->transactionAppointment->id.'</a>';
                }else if(!empty($selected->transactionOrder)){
                    $data .= '<a href="'.url('pharmacy/order/'.$selected->transactionOrder->id).'" target="_blank">Order #'.$selected->transactionOrder->id.'</a>';
                }
                return $data;
            })
            ->editColumn('transaction_date', function($selected) {
                return $selected->transaction_date ? date('d M, Y h:i A',strtotime($selected->transaction_date)) : '-';
            })
            ->rawColumns(['transaction_data'])
            ->make(true);
    }

}