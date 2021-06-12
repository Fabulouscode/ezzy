<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\Payout_amount;
use Illuminate\Support\Str;
use DB;

class PayoutAmountRepository extends Repository
{
    protected $model_name = 'App\Models\Payout_amount';
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getWithRelationship($request)
    {
        $query = $this->model->select('*')->addSelect(DB::raw('sum(payable_amount) as payable_amount'))
        ->addSelect(DB::raw('sum(amount) as amount'))
        ->addSelect(DB::raw('sum(deduction_amount) as deduction_amount'));
     
        if(!empty($request->category_id)){
            $query = $query->whereHas('user', function ($query) use ($request) {
                $query->where('category_id', $request->category_id);
            });
        }
        
        $query = $query->groupBy('user_id')->orderBy('id','desc')->get();
        return $query;
    }
    
    /**
     * Display a listing of the Datatable.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDatatable($request)
    {
        $data = $this->getWithRelationship($request);
        return Datatables::of($data)        
                ->editColumn('user_name', function($selected) use ($request) { 
                    return $selected->user ? $selected->user->user_name : '-';      
                })
                ->addColumn('service_provider', function($selected) {   
                    $data = '';
                    if(!empty($selected->user->categoryParent)){
                        $data .='<div class="text-success"><strong>'. $selected->user->categoryParent->name.'</strong></div>';
                    }                            
                    if(!empty($selected->user->categoryChild)){
                        $data .='<div class="text-success"><strong>'. $selected->user->categoryChild->name.'</strong></div>';
                    }  
                    return $data; 
                })
                ->editColumn('approved_date', function($selected) {
                    return $selected->approved_date ? $this->getDateTimeFormate($selected->approved_date) : '-';
                })
                ->editColumn('admin_name', function($selected) {
                    return $selected->admin ? $selected->admin->name : '-';
                })
                ->addColumn('action', function($selected) {   
                     $data = '';
                    // if (Auth::user()->hasPermissionTo('payout-edit')) {
                        $data .= '<a href="'.url('donotezzycaretouch/payout/transaction/'.$selected->user_id).'" class="btn btn-sm btn-info" title="Payout" id="payout-rows"  ><i class="fa fa-money"></i></a>&nbsp;&nbsp;';
                    // }
                    return $data;
                })
                ->rawColumns(['service_provider','action'])
                ->make(true);
    }
    
    /**
     * Display a listing of the Datatable.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTransactionByUser($request)
    {
        $query = $this->model;
        if(!empty($request->user_id)){
             $query = $query->where('user_id',$request->user_id);
        }
        $query = $query->orderBy('id','desc')->get();
        return $query;
    }

    /**
     * Display a listing of the Datatable.
     *
     * @return \Illuminate\Http\Response
     */
    public function getHistoryDatatable($request)
    {
        $data = $this->getTransactionByUser($request);
        return Datatables::of($data)        
                ->editColumn('user_name', function($selected) use ($request) { 
                    return $selected->user ? $selected->user->user_name : '-';      
                })
                ->editColumn('approved_date', function($selected) {
                    return $selected->approved_date ? $this->getDateTimeFormate($selected->approved_date) : '-';
                })
                ->editColumn('admin_name', function($selected) {
                    return $selected->admin ? $selected->admin->name : '-';
                })
                ->rawColumns(['service_provider'])
                ->make(true);
    }


     /**
     * Display a list of payout amout.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPayoutAmoutHistory($request)
    {   
        $query = $this->model;
        
        if(!empty($request->last_id)){
            $query = $query->where('id', '<', $request->last_id);    
        }
        
        $query = $query->limit($this->api_data_limit);     
       
        $query = $query->where('user_id',$request->user()->id);
        
        $query = $query->orderBy('id','desc')->get();
        
        return $query;
    }
}