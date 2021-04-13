<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\Order;
use Illuminate\Support\Str;
use DB;

class OrderRepository extends Repository
{
    protected $model_name = 'App\Models\Order';
    protected $model;

    public function __construct()
    {
        parent::__construct();
    }

    public function getStatusValue()
    {
        return $this->model->status_value;
    }
  
    public function getDeliveryTypeValue()
    {
        return $this->model->delivery_type_value;
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
        
        if(isset($request->delivery_type)){
            $query = $query->where('delivery_type',$request->delivery_type);
            $query = $query->where('status', '1');
        }
      
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
        return $this->model->with(['orderProductDetails.shopMedicineDetails'])->whereNull('transaction_id')->where('id',$id)->whereNotIn('status',['1','2'])->first();

    }
   
    /**
     * Display a edit of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getbyIdCheckNotNullTransaction($id)
    {   
        return $this->model->whereNotNull('transaction_id')->where('id',$id)->first();

    }
        
     /**
     * Display a edit of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getbyEditId($id)
    {   
        return $this->model->with(['clientDetails', 'userDetails', 'orderProductDetails','voucherDetails','getTransaction','orderProductDetails.shopMedicineDetails', 'orderProductDetails.shopMedicineDetails.medicineDetails','userLocationDetails','orderTrackingDetails'])->find($id);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getOrderStatusWiseCount($status = '')
    {
        $query = $this->model;
        
        if($status != '' && is_array($status)){
            $query = $query->whereIn('status', $status);
        }else if($status != ''){
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
        
        if($status != '' && is_array($status)){
            $query = $query->whereIn('status', $status);
        }else if($status != ''){
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
                        $data .= '<div class="badge badge-warning">'.$selected->status_name.'</div>';
                    }else  if($selected->status == '1' || $selected->status == '2'){
                        $data .= '<div class="badge badge-info">'.$selected->status_name.'</div>';
                    }else  if($selected->status == '3'){
                        $data .= '<div class="badge badge-success">'.$selected->status_name.'</div>';
                    }else  if($selected->status == '4'){
                        $data .= '<div class="badge badge-danger">'.$selected->status_name.'</div>';
                   }else {
                        $data .= '<div class="badge badge-info">'.$selected->status_name.'</div>';
                    }
                    return $data;
                })
                ->addColumn('action',function($selected)
                {
                    $data = '';
                    // $data .= '<a href="'.url('pharmacy/order/'.$selected->id.'/edit').'" class="btn btn-sm btn-info" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;';
                    // $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-danger" title="Delete" id="delete-rows" onclick="deleteRow('.$selected->id.')"><i class="fa fa-trash"></i></a>';
                    
                    if (Auth::user()->hasPermissionTo('order-list')) {
                        $data .= '<a href="'.url('pharmacy/order/'.$selected->id).'" class="btn btn-sm btn-primary title="View"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;';
                    }
                    if (Auth::user()->hasPermissionTo('order-invoice')) {
                        if ($selected->status == '3') {
                                $data .= '<a href="'.url('pharmacy/order/invoice/'.$selected->id).'" class="btn btn-sm btn-info" title="Invoice"><i class="fa fa-file"></i></a>&nbsp;&nbsp;';
                        }
                    }
                    //  $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-danger" title="Delete" id="delete-rows" onclick="deleteRow('.$selected->id.')"><i class="fa fa-trash"></i></a>';
                   
                    return $data;
                })                
                ->editColumn('service_provider',function($selected){
                    if(!empty($selected->userDetails)){
                        return $selected->userDetails->user_name;
                    } 
                })
                ->editColumn('user_name',function($selected){
                    if(!empty($selected->clientDetails)){
                        return $selected->clientDetails->user_name;
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
        
        $query = $query->where('status','3')->orderBy('id','desc')->get();
        
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
        
        $query = $query->where('status','4')->orderBy('id','desc')->get();
        
        return $query;
    }

    /**
     * Display a list of Pending Order record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPendingOrder($request)
    {   
        $query = $this->model;
        
        $query = $query->limit($this->api_data_limit);     
                
        if(!empty($request->user()->category_id)){
            $query = $query->with(['clientDetails'])->where('user_id',$request->user()->id);
        }else{
            $query = $query->with(['userDetails'])->where('client_id',$request->user()->id);
        }
            
        $query = $query->where('status','0');

        $query = $query->orderBy('id','desc')->get();
        
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

        if(!empty($request->status)){
            $query = $query->where('status', $request->status);
        }else{
            $query = $query->whereIn('status',['0','1','2']);
        }
        
        if(isset($request->delivery_type)){
            $query = $query->where('delivery_type', $request->delivery_type);
        }

        $query = $query->orderBy('id','desc')->get();
        
        return $query;
    }

    public function getReviewDatatable($request)
    {
        $data = $this->getWithRelationship($request);

        return Datatables::of($data)
            ->editColumn('user_name',function($selected)
            {
                return $selected->clientDetails ? $selected->clientDetails->user_name : '-';
            })
            ->editColumn('patient_name',function($selected)
            {
                return $selected->clientDetails ? $selected->clientDetails->user_name : '-';
            })
            ->editColumn('order_no',function($selected)
            {
                return '<a href="'.url('pharmacy/order/'.$selected->id).'" target="_blank">#'.$selected->id.' Order</a>';
            })
            ->rawColumns(['order_no'])->make(true);
    }

     /**
     * Dashboard Area Chart.
     *
     * @return \Illuminate\Http\Response
     */
    public function getOrdersQuery($request, $hcp_provider, $laboratories_provider)
    {

        $query = $this->model->where('status', '3')->select(DB::raw('DATE(created_at) as created_date'));
        
        $query = $query->addSelect(DB::raw("'0' AS hcp_appointments"))
                ->addSelect(DB::raw("count(id) AS orders"))    
                ->addSelect(DB::raw("'0' AS lab_appointments"));       
        
   
        if(!empty($request->start_date) && !empty($request->end_date)){
           $query = $query->whereBetween(DB::raw('DATE(created_at)'), array($request->start_date, $request->end_date));
        }

        $query = $query->orderBy('created_date','desc')->groupBy('created_date');

        $query = $query->union($hcp_provider)->union($laboratories_provider);
        
        return $query;
    }
    /**
     * Dashboard pie Chart.
     *
     * @return \Illuminate\Http\Response
     */
    public function getOrderCount($request, $paid = 0)
    {
        $query = $this->model;   
        
        if(!empty($request->start_date) && !empty($request->end_date)){
            $query = $query->whereBetween(DB::raw('DATE(created_at)'), array($request->start_date, $request->end_date));
        }
        
        if(!empty($paid) && $paid != '0'){
            $query = $query->whereNotNull('transaction_id');
        }else{
            $query = $query->whereNull('transaction_id');
        }

        $query = $query->orderBy('created_at','desc')->count();
        return $query;
    }

}