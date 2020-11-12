<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\Order;
use Illuminate\Support\Str;

class OrderRepository extends Repository
{
    protected $model_name = 'App\Models\Order';
    protected $model;

    public $status = array(
        '0' => 'Active',
        '1' => 'Success',
        '2' => 'Cancel'
    );

    public $delivery_type = array(
        '0' => 'Home Delievry',
        '1' => 'pick-up from store'
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
     * Display a list of Order record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getOrderHistory($request)
    {   
        $query = $this->model;
        
        if(!empty($request->last_id)){
            $query = $query->where('id', '<', $request->last_id);    
        }
        
        $query = $query->limit($this->api_data_limit);  
        
        if(!empty($request->user()->category_id)){
            $query = $query->with(['clientDetails'])->where('user_id',$request->user()->id);
        }else{
            $query = $query->with(['userDetails'])->where('client_id',$request->user()->id);
        }
        
        $query = $query->orderBy('id','desc')->get();

        return $query;
    }

    /**
     * Display a edit of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getbyIdCheckTransaction($id)
    {   
        return $this->model->whereNull('credit_transaction_id')->whereNull('debit_transaction_id')->where('id',$id)->whereNotIn('status',['1','2'])->first();

    }
        
     /**
     * Display a edit of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getbyEditId($id)
    {   
        return $this->model->with(['clientDetails', 'userDetails', 'orderProductDetails','getTransaction','orderProductDetails.shopMedicineDetails', 'orderProductDetails.shopMedicineDetails.medicineDetails','userLocationDetails'])->find($id);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getOrderStatusWiseCount($status = '')
    {
        $query = $this->model;
        
        if($status != ''){
            $query = $query->where('status', $status);
        }
        
        $query = $query->orderBy('id','desc')->count();
        return $query;
    }
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTodayOrderStatusWiseCount($status = '')
    {
        
        $query = $this->model->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'));
        
        if($status != ''){
            $query = $query->where('status', $status);
        }
        
        $query = $query->orderBy('id','desc')->count();
        return $query;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getWithRelationship($request)
    {
        $query = $this->model->with(['clientDetails','userDetails']);    
        if(isset($request->status) && $request->status != ''){
            $query = $query->where('status', $request->status);
        }
        $query = $query->orderBy('id','desc')->get();
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
                ->editColumn('status',function($selected)
                {
                    $data = '';
                    if($selected->status == '0'){
                        $data .= '<div class="badge badge-info">Active</div>';
                    }else  if($selected->status == '1'){
                        $data .= '<div class="badge badge-success">Success</div>';
                    }else  if($selected->status == '2'){
                        $data .= '<div class="badge badge-danger">Cancel</div>';
                    }
                    return $data;
                })
                ->addColumn('action',function($selected)
                {
                    $data = '';
                    // $data .= '<a href="'.url('pharmacy/order/'.$selected->id.'/edit').'" class="btn btn-sm btn-info" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;';
                    // $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-outline-danger" title="Delete" id="delete-rows" onclick="deleteRow('.$selected->id.')"><i class="fa fa-trash"></i></a>';
                    
                    if (Auth::user()->hasPermissionTo('order-list')) {
                        $data .= '<a href="'.url('pharmacy/order/'.$selected->id).'" class="btn btn-sm btn-info" title="View"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;';
                    }
                    if (Auth::user()->hasPermissionTo('order-invoice')) {
                        if ($selected->status == '1') {
                                $data .= '<a href="'.url('pharmacy/order/invoice/'.$selected->id).'" class="btn btn-sm btn-info" title="View"><i class="fa fa-file"></i></a>&nbsp;&nbsp;';
                        }
                    }
                    //  $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-danger" title="Delete" id="delete-rows" onclick="deleteRow('.$selected->id.')"><i class="fa fa-trash"></i></a>';
                   
                    return $data;
                })                
                ->editColumn('service_provider',function($selected){
                    if(!empty($selected->userDetails)){
                        return $selected->userDetails->first_name .' '. $selected->userDetails->last_name;
                    } 
                })
                ->editColumn('user_name',function($selected){
                    if(!empty($selected->clientDetails)){
                        return $selected->clientDetails->first_name .' '. $selected->clientDetails->last_name;
                    }
                })
                ->rawColumns(['action','status'])
                ->make(true);
        
    }

    
     /**
     * Display a list of Completed Order record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCompletedOrder($request)
    {   
        $query = $this->model;
        
        if(!empty($request->last_id)){
            $query = $query->where('id', '<', $request->last_id);    
        }
        
        $query = $query->limit($this->api_data_limit);  

        // $query = $query->with(['orderProductDetails','orderProductDetails.shopMedicineDetails', 'orderProductDetails.medicineDetails']);
       
        if(!empty($request->user()->category_id)){
            $query = $query->with(['clientDetails'])->where('user_id',$request->user()->id);
        }else{
            $query = $query->with(['userDetails'])->where('client_id',$request->user()->id);
        }
        
        $query = $query->where('status','1')->orderBy('id','desc')->get();
        
        return $query;
    }
  
    /**
     * Display a list of Cancelled Order record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCancelledOrder($request)
    {   
        $query = $this->model;
        
        if(!empty($request->last_id)){
            $query = $query->where('id', '<', $request->last_id);    
        }
        
        $query = $query->limit($this->api_data_limit);    
      
        // $query = $query->with(['orderProductDetails','orderProductDetails.shopMedicineDetails', 'orderProductDetails.medicineDetails']);
       
        if(!empty($request->user()->category_id)){
            $query = $query->with(['clientDetails'])->where('user_id',$request->user()->id);
        }else{
            $query = $query->with(['userDetails'])->where('client_id',$request->user()->id);
        }
        
        $query = $query->where('status','2')->orderBy('id','desc')->get();
        
        return $query;
    }

    /**
     * Display a list of Active Order record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getActiveOrder($request)
    {   
        $query = $this->model;
        
        if(!empty($request->last_id)){
            $query = $query->where('id', '<', $request->last_id);    
        }
        
        $query = $query->limit($this->api_data_limit);     
       
        // $query = $query->with(['orderProductDetails','orderProductDetails.shopMedicineDetails', 'orderProductDetails.medicineDetails']);
                
        if(!empty($request->user()->category_id)){
            $query = $query->with(['clientDetails'])->where('user_id',$request->user()->id);
        }else{
            $query = $query->with(['userDetails'])->where('client_id',$request->user()->id);
        }
        
        $query = $query->where('status','0')->orderBy('id','desc')->get();
        
        return $query;
    }

    public function getReviewDatatable($request)
    {
        $data = $this->getWithRelationship($request);

        return Datatables::of($data)
            ->editColumn('user_name',function($selected)
            {
                return $selected->clientDetails ? $selected->clientDetails->first_name.' '.$selected->clientDetails->last_name : '-';
            })
            ->editColumn('patient_name',function($selected)
            {
                return $selected->clientDetails ? $selected->clientDetails->first_name.' '.$selected->clientDetails->last_name : '-';
            })->editColumn('order_no',function($selected)
            {
                return '<a href="'.url('pharmacy/order/'.$selected->id).'" target="_blank">#'.$selected->id.'</a>';
            })->rawColumns(['order_no'])->make(true);
    }


}