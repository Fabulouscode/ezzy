<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\User_transaction;
use Illuminate\Support\Str;
use DB;

class UserTransactionRepository extends Repository
{
    protected $model_name = 'App\Models\User_transaction';
    protected $model;
    

    public function __construct()
    {
        parent::__construct();
    }
    
    public function getStatusValue()
    {
        return $this->model->status_value;
    }
 
    public function getTransactionTypeValue()
    {
        return $this->model->transaction_type_value;
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
        // return $this->model->where('mode_of_payment', $mode_of_payment)->where('transaction_type', '0')->where('status', '0')->where('user_id', $user_id)->sum('amount');
        return $this->model->where('transaction_type', '0')->where('status', '0')->where('user_id', $user_id)->sum('amount');
    }
    
    public function getPayoutCalculte($user_id, $payout_status = '1')
    {
        return $this->model->where('payout_status', $payout_status)->where('status', '0')->where('user_id', $user_id)->sum('payout_amount');
    }
   
    public function getUserbyWalletBalance($user_id)
    {
        $total_earning =  $credit_balance = $debit_balance  = 0;
        $debit_balance = $this->getUserbyCalculate($user_id, '0'); 
        $credit_balance = $this->getUserbyCalculate($user_id, '1');
        $total_earning = $debit_balance - $credit_balance;      
        return $total_earning;
    }
   
    public function getHCPTYPEWalletBalanceDateRange($request)
    {
        $query = $this->model->with(['users','transactionAppointment','transactionOrder']);
      
        if($request->provider != 'patients'){
            $query = $query->where('user_id',$request->id);
        }else{            
            $query = $query->where('client_id',$request->id);
        }

        if(!empty($request->start_date) && !empty($request->end_date)){
            $query = $query->where('transaction_date', '>=',$request->start_date)->where('transaction_date' , '<=',$request->end_date);
        }
        
        $query = $query->orderBy('id','desc')->sum('amount');

        return $query;
    }


    public function getPayoutCount($payout_status = '1')
    {
        return $this->model->where('status', '0')->where('payout_status', $payout_status)->count();
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
      
        $query = $query->orderBy('transaction_date','desc');
        
        $query = $query->get();

        return $query;
    }



    public function getWithRelationship($request)
    {
        $query = $this->model->with(['users','transactionAppointment','transactionOrder','transactionOrder.userDetails','transactionAppointment.user']);
      
        if($request->provider != 'patients'){
            $query = $query->where('user_id',$request->id);
        }else{            
            $query = $query->where('client_id',$request->id);
        }
        
        if(!empty($request->start_date) && !empty($request->end_date)){
            $query = $query->where('transaction_date', '>=',$request->start_date)->where('transaction_date' , '<=',$request->end_date);
        }
        
        $query = $query->orderBy('id','desc')->get();

        return $query;
    }
  
    

    public function getDatatablebyUserId($request)
    {
        $data = $this->getWithRelationship($request); 
        return Datatables::of($data)
            ->editColumn('client_name', function($selected) use ($request) {   
                return $selected->client ? $selected->client->user_name : '-';
            })
            ->editColumn('user_name', function($selected) use ($request) { 
                return $selected->users ? $selected->users->user_name : '-';      
            })
            ->editColumn('created_at', function($selected) {
                return $selected->created_at ? $this->getDateTimeFormate($selected->created_at) : '-';
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
                return $selected->transaction_date ? $this->getDateTimeFormate($selected->transaction_date) : '-';
            })
            ->editColumn('amount', function($selected) {
                return $this->currency_symbol.$selected->amount ;
            })
            ->editColumn('status', function($selected) {
                 if($selected->status == '0'){
                    return '<div class="badge badge-success">'.$selected->status_name.'</div>';
                } else{
                    return '<div class="badge badge-info">'.$selected->status_name.'</div>';
                }
            })
            ->editColumn('payout_amount', function($selected) {
                return $this->currency_symbol.$selected->payout_amount ;
            })
            ->editColumn('fees_charge', function($selected) {
                return $this->currency_symbol.$selected->fees_charge ;
            })
            ->editColumn('payout_status', function($selected) {
                 if($selected->payout_status == '0'){
                    return '<div class="badge badge-success">'.$selected->payout_status_name.'</div>';
                } else{
                    return '<div class="badge badge-info">'.$selected->payout_status_name.'</div>';
                }
            })
            ->rawColumns(['transaction_data','status','payout_amount','fees_charge','payout_status'])
            ->make(true);
    }
 
 
    public function getPayoutsWithRelationship($request)
    {
        $query = $this->model->with(['users'])->select()->addSelect(DB::raw('sum(user_transactions.payout_amount) as payout_total'));
      
        if(isset($request->payout_status)){
            $query = $query->where('payout_status',$request->payout_status);
        }
        
        $query = $query->where('status', '0')->groupBy('user_id')->orderBy('id','desc')->get();

        return $query;
    }
  
 
    public function getDatatable($request)
    {
        $data = $this->getPayoutsWithRelationship($request); 
        return Datatables::of($data)
            ->addColumn('checkbox', function($selected) {   
                return '<input type="checkbox" name="id" class="minimal" value="'.$selected->id.'">';
            })
            ->addColumn('service_provider', function($selected) {   
                 $data = '';
                if(!empty($selected->users->categoryParent)){
                    $data .='<div class="text-success"><strong>'. $selected->users->categoryParent->name.'</strong></div>';
                }                            
                if(!empty($selected->users->categoryChild)){
                    $data .='<div class="text-success"><strong>'. $selected->users->categoryChild->name.'</strong></div>';
                }  
                return $data; 
            })
            ->editColumn('user_name', function($selected) { 
                return $selected->users ? $selected->users->user_name : '-';      
            })
            ->editColumn('created_at', function($selected) {
                return $selected->created_at ? $this->getDateTimeFormate($selected->created_at) : '-';
            })
            ->editColumn('transaction_date', function($selected) {
                return $selected->transaction_date ? $this->getDateTimeFormate($selected->transaction_date) : '-';
            })
            ->editColumn('payout_date', function($selected) {
                return $selected->payout_date ? $this->getDateTimeFormate($selected->payout_date) : '-';
            })
            ->editColumn('payout_amount', function($selected) {
                return $this->currency_symbol.$selected->payout_total ;
            })
            ->editColumn('payout_status', function($selected) {
                 if($selected->payout_status == '0'){
                    return '<div class="badge badge-success">'.$selected->payout_status_name.'</div>';
                } else{
                    return '<div class="badge badge-info">'.$selected->payout_status_name.'</div>';
                }
            })
            ->addColumn('action',function($selected)
            { 
                $data = '';
                // if (Auth::user()->hasPermissionTo('payout-edit')) {
                    $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-info" title="Payout" id="payout-rows" onclick=""><i class="fa fa-edit"></i></a>&nbsp;&nbsp;';
                // }
                return $data;
            })
            ->rawColumns(['checkbox','service_provider', 'payout_amount','payout_status','action'])
            ->make(true);
    }

}