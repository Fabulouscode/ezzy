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
   
    public function getPayoutStatusValue()
    {
        return $this->model->payout_status_value;
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
 
   
  
    public function userIncomeCalculate($request, $column_name = 'amount')
    {   
        $query = $this->model;  
        
        if(!empty($request->category_id)){
            $query = $query->whereHas('client', function ($query) use ($request) {
                $query = $query->whereHas('categoryParent', function ($query) use ($request) {
                    $query->where('id', $request->category_id);
                });
            });           
        }

        $query = $query->where('wallet_transaction','0')
                            ->whereBetween(DB::raw('DATE(transaction_date)'), array($request->start_date, $request->end_date))
                            ->where('status','0')
                            ->sum($column_name);

        return $query;
    }
    
    public function userPayoutIncome($request)
    {   
        return $this->model->addSelect(DB::raw('MONTH(transaction_date) as month'))
                            ->addSelect(DB::raw('SUM(amount) as total_income'))
                            ->addSelect(DB::raw('SUM(payout_amount) as total_payout'))
                            ->where('wallet_transaction','0')
                            ->where('status','0')
                            ->whereBetween(DB::raw('DATE(transaction_date)'), array($request->start_date, $request->end_date))
                            ->groupBy('month')->get();
    }
  
    public function userPayoutData($user_ids, $payout_status = '1')
    {   
        return $this->model->where('wallet_transaction','0')->whereIn('client_id',$user_ids)->where('payout_status', $payout_status)->get();
    }

    public function getUserbyCalculate($user_id, $mode_of_payment = 0)
    {
        // return $this->model->where('mode_of_payment', $mode_of_payment)->where('transaction_type', '0')->where('status', '0')->where('user_id', $user_id)->sum('amount');
        return $this->model->where('wallet_transaction','0')->where('transaction_type', '0')->where('status', '0')->where('user_id', $user_id)->sum('amount');
    }
    
    public function getPayoutCalculte($user_id, $payout_status = '1')
    {
        return $this->model->where('wallet_transaction','0')->where('payout_status', $payout_status)->where('status', '0')->where('client_id', $user_id)->sum('payout_amount');
    }
 

    public function calculatePatientWalletBalance($user_id, $mode_of_payment = 0)
    {
        return $this->model->where('mode_of_payment', $mode_of_payment)->where('transaction_type','0')->where('status', '0')->where('user_id', $user_id)->sum('amount');
    }

    public function calculatePatientWalletLockBalance($user_id)
    {
        return $this->model->where('mode_of_payment', '1')->where('status', '3')->where('transaction_type','0')->where('user_id', $user_id)->sum('amount');
    }

    public function checkPatientWalletBalance($user_id)
    {
        $total_earning =  $credit_balance = $debit_balance = $lock_balance = 0;
        $credit_balance = $this->calculatePatientWalletBalance($user_id, '0'); 
        $debit_balance = $this->calculatePatientWalletBalance($user_id, '1');
        $lock_balance =  $this->calculatePatientWalletLockBalance($user_id, '0'); 
        $total_earning = $credit_balance - $lock_balance - $debit_balance; 
        return $total_earning;
    }
  
    public function checkPatientWalletLockBalance($user_id)
    {
        $total_earning =  $credit_balance = 0;
        $credit_balance = $this->calculatePatientWalletLockBalance($user_id, '0'); 
        $total_earning = $credit_balance; 
        return $total_earning;
    }
   
    public function getUserbyWalletBalance($user_id)
    {
        $total_earning =  $credit_balance = $debit_balance  = 0;
        $credit_balance = $this->getUserbyCalculate($user_id, '0'); 
        $debit_balance = $this->getUserbyCalculate($user_id, '1');
        $total_earning = $credit_balance - $debit_balance;      
        return $total_earning;
    }
  
    public function getPatientWalletBalanceCalculte($user_id)
    {
        return $this->model->where('wallet_transaction','1')->where('mode_of_payment', '0')->where('status', '0')->where('user_id', $user_id)->sum('amount');
    }

    public function getPatientWalletCalculate($today = 0 ,$modeType = 0)
    {
        $query = $this->model->where('mode_of_payment', $modeType);
        if(!empty($today)){
            $query = $query->whereDate('created_at',Carbon::now());
        }
        if(isset($modeType) && $modeType == 0){
            $query = $query->where('wallet_transaction', 1);
        }else{
            $query = $query->where('wallet_transaction', 0);
        }
        $query =  $query->where('status', '0')                 
                    ->sum('amount');
        return $query;
    }

    public function getHCPWalletCalculate($today = 0)
    {
        $query = $this->model->where('mode_of_payment', 1)->whereNotNull('client_id');
        if(!empty($today)){
            $query = $query->whereDate('created_at',Carbon::now());
        }
        $query =  $query->where('wallet_transaction','0')                            
                    ->where('status', '0')                 
                    ->sum('payout_amount');
        return $query;
    }
 
    public function getHCPPayoutWalletCalculate($payoutStatus = 1)
    {
        $query = $this->model->where('mode_of_payment', 1)->whereNotNull('client_id');

        if(isset($payoutStatus)){
            $query = $query->where('payout_status', $payoutStatus);
        }
        
        $query = $query->where('status', '0');

        $data = $query->sum('payout_amount');

        return $data;
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
            $query = $query->whereDate('transaction_date', '>=',$request->start_date)->whereDate('transaction_date' , '<=',$request->end_date);
        }
        
        $query = $query->where('wallet_transaction','0')->orderBy('id','desc')->sum('amount');

        return $query;
    }


    public function getPayoutCount($payout_status = '1')
    {
        $user = $this->model->where('wallet_transaction','0')->where('status', '0')->where('payout_status', $payout_status)->groupBy('client_id')->get();
        return count($user);
    }
   
    public function getbyUserId($user_id)
    {
        return $this->model->where('user_id', $user_id)->get();
    }
   
    public function getCompletedTransaction($id)
    {
        return $this->model->where('status', '0')->where('id', $id)->first();
    }
    
    public function getTransactionHistory($request)
    {
       
        $query = $this->model;
        
        if(!empty($request->last_id)){
            $query = $query->where('id', '<', $request->last_id);    
        }            
     
        $query = $query->where(function($query) use ($request){
            $query->orWhere('user_id',$request->user()->id);
            $query->orWhere('client_id',$request->user()->id);
        });

        // $query = $query->where([
        //             ['transaction_type', '!=', '1'],
        //             ['wallet_transaction', '!=', '1']
        //         ]);
        $query = $query->where(function($query) use ($request){
            $query->where('transaction_type','!=', '1');
            $query->orWhere('wallet_transaction','!=', '1');
            $query->orWhere('mode_of_payment','!=', '1');
        });
     
     
        $query = $query->where('status','0')->orderBy('id','desc');

        $query = $query->limit($this->api_data_limit);

        $query = $query->get();
        
        return $query;
    }
 
    public function getHCPTransactionHistory($request)
    {
       
        $query = $this->model;
        
        if(!empty($request->last_id)){
            $query = $query->where('id', '<', $request->last_id);    
        }            
     

        $query = $query->where('client_id',$request->user()->id);
        // $query = $query->where([
        //             ['transaction_type', '!=', '1'],
        //             ['wallet_transaction', '!=', '1']
        //         ]);
        $query = $query->where(function($query) use ($request){
            $query->where('transaction_type','!=', '1');
            $query->orWhere('wallet_transaction','!=', '1');
            $query->orWhere('mode_of_payment','!=', '1');
        });
     
     
        $query = $query->where('status','0')->orderBy('id','desc');

        $query = $query->limit($this->api_data_limit);

        $query = $query->get();
        
        return $query;
    }



    public function getWithRelationship($request)
    {
        $query = $this->model->with(['users','transactionAppointment','transactionOrder','transactionOrder.userDetails','transactionAppointment.user']);
      
        if($request->provider == 'patients'){
            $query = $query->where(function($query) use ($request){
                $query->orWhere('user_id',$request->id);
                $query->orWhere('client_id',$request->id);
            })->where('transaction_type','0');
        }else{            
            $query = $query->where('client_id',$request->id)->where('transaction_type','0');
        }


        
        if(!empty($request->start_date) && !empty($request->end_date)){
            $query = $query->whereDate('transaction_date', '>=',$request->start_date)->whereDate('transaction_date' , '<=',$request->end_date);
        }
        
        $query = $query->where('status', '0')->orderBy('id','desc')->get();

        return $query;
    }
  
    public function getTransactionData($request)
    {
        $query = $this->model->with(['users','client']);
       
        $query = $query->whereNotNull('client_id')
            ->select('user_transactions.*')
            ->leftJoin('users', 'user_transactions.user_id', '=', 'users.id')
            ->leftJoin('users as client', 'user_transactions.client_id', '=', 'client.id');
        if(!empty($request->category_id)){
            $query = $query->whereHas('client', function ($query) use ($request) {
                $query = $query->whereHas('categoryParent', function ($query) use ($request) {
                    $query->where('id', $request->category_id);
                });
            });           
        }

        if(!empty($request->start_date) && !empty($request->end_date)){
            $query = $query->whereDate('user_transactions.transaction_date', '>=',$request->start_date)->whereDate('user_transactions.transaction_date' , '<=',$request->end_date);
        }

        if(!empty($request->transaction_msg)){
            $query = $query->where('user_transactions.transaction_msg', $request->transaction_msg);
        }
        
        $query = $query->where('user_transactions.mode_of_payment', '1')->where('user_transactions.status', '0');

        return $query;
    }
    
    public function getHCPTransactionCalculate($request)
    {
        $query = $this->model;
       
        $query = $query->whereNotNull('client_id');
        
        if(!empty($request->category_id)){
            $query = $query->whereHas('client', function ($query) use ($request) {
                $query = $query->whereHas('categoryParent', function ($query) use ($request) {
                    $query->where('id', $request->category_id);
                });
            });           
        }

        if(!empty($request->start_date) && !empty($request->end_date)){
            $query = $query->whereDate('transaction_date', '>=',$request->start_date)->whereDate('transaction_date' , '<=',$request->end_date);
        }

        if(!empty($request->transaction_msg)){
            $query = $query->where('transaction_msg', $request->transaction_msg);
        }
        
        $query = $query->where('mode_of_payment', '1')->where('status', '0');

        $data = [];
        $data['amount'] = $query->sum('amount');
        $data['payout_amount'] = $query->sum('payout_amount');
        $data['fees_charge'] = $query->sum('fees_charge');

        return $data;
    }

    public function getHCPPayoutCalculation($request, $status)
    {
        $query = $this->model;
               
        $query = $query->where('client_id',$request->user()->id);   

        if(isset($status)){
            $query = $query->where('payout_status', $status);
        }
        
        $query = $query->where('status', '0');

        $data = $query->sum('payout_amount');

        return $data;
    } 
    

    public function getDatatablebyUserId($request)
    {
        $data = $this->getWithRelationship($request); 
        
        return Datatables::of($data)
            ->editColumn('user_name', function($selected) use ($request) {  
                if(!empty($selected->wallet_transaction) && $selected->wallet_transaction == '1'){
                    return '-';
                }else{
                    return $selected->client ? $selected->client->user_name : '-'; 
                }     
            })
            ->editColumn('client_name', function($selected) use ($request) { 
                if(!empty($selected->wallet_transaction) && $selected->wallet_transaction == '1'){
                    return $selected->users ? $selected->users->user_name : '-'; 
                }else{
                    return $selected->users ? $selected->users->user_name : '-';
                }                 
            })
            ->editColumn('created_at', function($selected) {
                return $selected->created_at ? $this->getDateTimeFormate($selected->created_at) : '-';
            })
            ->editColumn('transaction_data',function($selected)
            {
                $data = '';
                if(!empty($selected->appointment_id) && !empty($selected->transactionAppointment)){
                    $data .= '<a href="'.url('donotezzycaretouch/appointment/'.$selected->appointment_id).'" target="_blank">Appointment #'.$selected->appointment_id.'</a>';
                }else if(!empty($selected->order_id)){
                    $data .= '<a href="'.url('donotezzycaretouch/pharmacy/order/'.$selected->order_id).'" target="_blank">Order #'.$selected->order_id.'</a>';
                }else if(!empty($selected->appointment_id)){
                     $data .= 'Appointment charges';
                }else if(!empty($selected->wallet_transaction) && $selected->wallet_transaction == '1' && $selected->mode_of_payment == '0'){
                     $data .= 'Add in Wallet';
                }else if(!empty($selected->wallet_transaction) && $selected->wallet_transaction == '1' && $selected->mode_of_payment == '1' && $selected->transaction_type == '1'){
                     $data .= 'Add in Wallet';
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
            ->editColumn('payment_type', function($selected) {
                 if($selected->transaction_type == '1'){
                    return '<div >Online Pay</div>';
                } else{
                    return '<div >Wallet</div>';
                }
            })
            ->editColumn('transaction_type', function($selected) {
                 if($selected->mode_of_payment == '0'){
                    return '<div class="badge badge-success">Credit</div>';
                } else{
                    return '<div class="badge badge-danger">Debit</div>';
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
            ->rawColumns(['transaction_data','transaction_type','status','payout_amount','fees_charge','payout_status','payment_type'])
            ->make(true);
    }
 
    public function getTransactionDatatable($request)
    {
        $data = $this->getTransactionData($request); 
        
        return Datatables::of($data)
            ->editColumn('user_name', function($selected) use ($request) {  
                return $selected->users ? $selected->users->user_name : '-';
            })
            ->filterColumn('user_name', function ($query, $keyword) {
                $query->whereRaw("concat(users.first_name, ' ', users.last_name) like ?", ["%$keyword%"]);
            })
            ->orderColumn('user_name', function ($query, $order) {
                $query->orderBy('users.first_name', $order);
            })

            ->editColumn('service_provider', function($selected) use ($request) {                 
                return $selected->client ? $selected->client->user_name : '-'; 
            })
            ->filterColumn('service_provider', function ($query, $keyword) {
                $query->whereRaw("concat(client.first_name, ' ', client.last_name) like ?", ["%$keyword%"]);
            })
            ->orderColumn('service_provider', function ($query, $order) {
                $query->orderBy('client.first_name', $order);
            })

            ->editColumn('transaction_date', function($selected) {
                return $selected->transaction_date ? $this->getDateTimeFormate($selected->transaction_date) : '-';
            })

            ->editColumn('amount', function($selected) {
                return $this->currency_symbol.$selected->amount ;
            })

            ->editColumn('payout_amount', function($selected) {
                return $this->currency_symbol.$selected->payout_amount ;
            })
            
            ->editColumn('fees_charge', function($selected) {
                return $this->currency_symbol.$selected->fees_charge ;
            })

            ->rawColumns(['payout_amount','fees_charge'])
            ->make(true);
    }
 
 
    public function getPayoutsExport($status = '0')
    {
        $query = $this->model->with(['users'])->select()
        ->addSelect(DB::raw('sum(user_transactions.payout_amount) as payout_total'))
        ->addSelect(DB::raw('sum(user_transactions.amount) as amount'))
        ->addSelect(DB::raw('sum(user_transactions.fees_charge) as fees_charge'));
      
        if(isset($status)){
            $query = $query->where('payout_status',$status);
        }
        
        $query = $query->where('wallet_transaction','0')->where('status', '0')->groupBy('user_id')->orderBy('id','desc')->get();

        return $query;
    }

    public function getPayoutsWithRelationship($request)
    {
        $query = $this->model->with(['client'])
        ->select('user_transactions.*')
        ->addSelect(DB::raw('sum(user_transactions.payout_amount) as sum_payout_total'))
        ->addSelect(DB::raw('sum(user_transactions.amount) as sum_amount'))
        ->addSelect(DB::raw('sum(user_transactions.fees_charge) as sum_fees_charge'))
        ->leftJoin('users as client', 'user_transactions.client_id', '=', 'client.id');
        if(isset($request->payout_status)){
            $query = $query->where('user_transactions.payout_status', '!=', '0');
        }

        if(!empty($request->category_id)){
            $query = $query->whereHas('client', function ($query) use ($request) {
                $query->where('category_id', $request->category_id);
            });
        }
        $query = $query->whereHas('client', function ($query){
            $query->whereNotNull('category_id');
        });
        $query = $query->where('user_transactions.wallet_transaction','0')
        ->whereNotNull('user_transactions.client_id')
        ->where('user_transactions.status', '0')
        ->groupBy('user_transactions.client_id','payout_status')
        ->orderBy('user_transactions.id','desc');

        return $query;
    }
  
 
    public function getDatatable($request)
    {
        $data = $this->getPayoutsWithRelationship($request); 
        return Datatables::of($data)
            ->addColumn('checkbox', function($selected) {   
                return '<input type="checkbox" name="id" class="minimal" value="'.$selected->client_id.'">';
            })
            ->addColumn('service_provider', function($selected) {   
                 $data = '';
                if(!empty($selected->client->categoryParent)){
                    $data .='<div class="text-success"><strong>'. $selected->client->categoryParent->name.'</strong></div>';
                }                            
                if(!empty($selected->client->categoryChild)){
                    $data .='<div class="text-success"><strong>'. $selected->client->categoryChild->name.'</strong></div>';
                }  
                return $data; 
            })
            ->addColumn('bank_details', function($selected) {   
                 $data = '';
                if(!empty($selected->client->userPrimaryBankAccount)){
                    $data .='<div><strong>Bank Name: </strong>'. $selected->client->userPrimaryBankAccount->bank_name.'</div>';
                    $data .='<div><strong>Account Name: </strong>'. $selected->client->userPrimaryBankAccount->name.'</div>';
                    $data .='<div><strong>Account No.: </strong>'. $selected->client->userPrimaryBankAccount->account_number.'</div>';
                }      
                return $data; 
            })
            ->editColumn('user_name', function($selected) { 
                return $selected->client ? $selected->client->user_name : '-';      
            })
            ->filterColumn('user_name', function ($query, $keyword) {
                $query->whereRaw("concat(client.first_name, ' ', client.last_name) like ?", ["%$keyword%"]);
            })
            ->orderColumn('user_name', function ($query, $order) {
                $query->orderBy('client.first_name', $order);
            })

            ->editColumn('payout_amount', function($selected) {
                return $this->currency_symbol.$selected->sum_payout_total ;
            })
            ->filterColumn('payout_amount', function ($query, $keyword) {
                $query->whereRaw("sum_payout_total like ?", ["%$keyword%"]);
            })
            ->orderColumn('payout_amount', function ($query, $order) {
                $query->orderBy('sum_payout_total', $order);
            })

            ->editColumn('amount', function($selected) {
                return $this->currency_symbol.$selected->sum_amount ;
            })
            ->filterColumn('amount', function ($query, $keyword) {
                $query->whereRaw("sum_amount like ?", ["%$keyword%"]);
            })
            ->orderColumn('amount', function ($query, $order) {
                $query->orderBy('sum_amount', $order);
            })

            ->editColumn('fees_charge', function($selected) {
                return $this->currency_symbol.$selected->sum_fees_charge ;
            })
            ->filterColumn('fees_charge', function ($query, $keyword) {
                $query->whereRaw("sum_fees_charge like ?", ["%$keyword%"]);
            })
            ->orderColumn('fees_charge', function ($query, $order) {
                $query->orderBy('sum_fees_charge', $order);
            })

            ->editColumn('payout_status', function($selected) {
                if($selected->payout_status == '0'){
                    return '<div class="badge badge-success">'.$selected->payout_status_name.'</div>';
                } else if($selected->payout_status == '2'){
                    return '<div class="badge badge-danger">'.$selected->payout_status_name.'</div>';
                } else if($selected->payout_status == '3'){
                    return '<div class="badge badge-warning">'.$selected->payout_status_name.'</div>';
                } else {
                    return '<div class="badge badge-info">'.$selected->payout_status_name.'</div>';
                }
            })
            ->filterColumn('payout_status', function ($query, $keyword) use ($request) {
                if (in_array($request->search['value'], $this->getPayoutStatusValue())){
                    $appointment_status = array_search($request->search['value'], $this->getPayoutStatusValue());
                    $query->where("user_transactions.payout_status", $appointment_status);                       
                }
            })
            ->orderColumn('status', function ($query, $order) {
                $query->orderBy('user_transactions.payout_status', $order);
            })

            ->addColumn('action',function($selected)
            { 
                $data = '';
                if($selected->payout_status == '3'){
                // if (Auth::user()->hasPermissionTo('payout-edit')) {
                    $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-info" title="Payout Transaction Details Add" id="payout-rows"  data-user_id="'.$selected->client_id.'" data-amount="'.$selected->sum_amount.'" data-deduction="'.$selected->sum_fees_charge.'" data-payout_amount="'.$selected->sum_payout_total.'" onclick="editRow(this)"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;';
                }
                if($selected->payout_status == '1'){
                // if (Auth::user()->hasPermissionTo('payout-edit')) {
                    $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-success" title="Payout Approved" id="payout-rows"   onclick="payoutUser('.$selected->client_id.')"><i class="fa fa-check"></i></a>&nbsp;&nbsp;';
                }
                return $data;
            })
            ->rawColumns(['checkbox','service_provider','bank_details','amount','fees_charge','payout_amount','payout_status','action'])
            ->make(true);
    }

    public function getPendingTransaction($user_id, $reference)
    {
        return $this->model->where('user_id', $user_id)->where('payment_gateway_response', $reference)->where('status', '2')->orderBy('id','desc')->first();
    }
 
    public function getPendingTransactionCallback($email, $reference)
    {
        return $this->model->whereHas('users', function ($query) use ($email) {
                $query->where('email', $email);
        })
        ->where('payment_gateway_response', $reference)
        ->where('status', '2')->orderBy('id','desc')->first();
    }

    public function getHCPPayoutHistory($request)
    {
       
        $query = $this->model;
        
        if(!empty($request->last_id)){
            $query = $query->where('id', '<', $request->last_id);    
        }            
     
        $query = $query->where('client_id',$request->user()->id);     
     
        $query = $query->where('status','0')->orderBy('id','desc');

        $query = $query->limit($this->api_data_limit);

        $query = $query->get();
        
        return $query;
    }
}