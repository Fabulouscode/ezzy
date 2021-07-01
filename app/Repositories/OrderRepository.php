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
            $query = $query->whereIn('status', ['1','2']);
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
    public function getOrderStatusWiseCount($status = '', $user_id = '')
    {
        $query = $this->model;
        
        if($status != '' && is_array($status)){
            $query = $query->whereIn('status', $status);
        }else if($status != ''){
            $query = $query->where('status', $status);
        }
        
        if(!empty($user_id)){
            $query = $query->where('user_id', $user_id);
        }

        $query = $query->orderBy('id','desc')->count();
        return $query;
    }
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getOrderTypeWiseCount($order_type = '', $user_id = '')
    {
        $query = $this->model;
        
        if($order_type != ''){
            $query = $query->where('delivery_type', $order_type);
        }
        
        if(!empty($user_id)){
            $query = $query->where('user_id', $user_id);
        }

        $query = $query->orderBy('id','desc')->count();
        return $query;
    }
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTodayOrderStatusWiseCount($status = '', $user_id = '')
    {
        
        $query = $this->model->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'));
        
        if($status != '' && is_array($status)){
            $query = $query->whereIn('status', $status);
        }else if($status != ''){
            $query = $query->where('status', $status);
        }

        if(!empty($user_id)){
            $query = $query->where('user_id', $user_id);
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
        $query = $this->model->select('orders.*')->with(['clientDetails','userDetails']);    
          
        if(is_array($request->status) && count($request->status) > 0){
            $query = $query->whereNotIn('orders.status', $request->status);       
        }else if(isset($request->status)){
            $query = $query->where('orders.status', $request->status);
        } 
        
        if(!empty($request->user_id)){
            $query = $query->where('orders.user_id', $request->user_id);
        } 

        if(!empty($request->start_date) && !empty($request->end_date)){
            $query = $query->whereDate('orders.created_at', '>=',$request->start_date)->whereDate('orders.created_at' , '<=',$request->end_date);
        }

        $query = $query->leftJoin('users as user', 'orders.user_id', '=', 'user.id')
                        ->leftJoin('users as client', 'orders.client_id', '=', 'client.id');
        // $query = $query->orderBy('id','desc')->get();
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
                        $data .= '<div class="badge badge-warning">'.$selected->status_name.'</div>';
                    }
                    return $data;
                })
                ->filterColumn('status', function ($query, $keyword) use ($request) {
                    if (in_array($request->search['value'], $this->getStatusValue())){
                        $order_status = array_search($request->search['value'], $this->getStatusValue());
                        $query->where("orders.status", $order_status);                       
                    }
                })

                ->addColumn('action',function($selected)
                {
                    $data = '';
                    // $data .= '<a href="'.url('donotezzycaretouch/pharmacy/order/'.$selected->id.'/edit').'" class="btn btn-sm btn-info" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;';
                    // $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-danger" title="Delete" id="delete-rows" onclick="deleteRow('.$selected->id.')"><i class="fa fa-trash"></i></a>';
                    
                    if (Auth::user()->hasPermissionTo('order-list')) {
                        $data .= '<a href="'.url('donotezzycaretouch/pharmacy/order/'.$selected->id).'" class="btn btn-sm btn-primary title="View"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;';
                    }
                    if (Auth::user()->hasPermissionTo('order-invoice')) {
                        if ($selected->status == '3') {
                                $data .= '<a href="'.url('donotezzycaretouch/pharmacy/order/invoice/'.$selected->id).'" class="btn btn-sm btn-info" title="Invoice"><i class="fa fa-file"></i></a>&nbsp;&nbsp;';
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
                ->filterColumn('service_provider', function ($query, $keyword) {
                    $query->whereRaw("concat(user.first_name, ' ', user.last_name) like ?", ["%$keyword%"]);
                })
                ->orderColumn('service_provider', function ($query, $order) {
                    $query->orderBy('user.first_name', $order);
                })

                ->editColumn('user_name',function($selected){
                    if(!empty($selected->clientDetails)){
                        return $selected->clientDetails->user_name;
                    }
                })
                ->filterColumn('user_name', function ($query, $keyword) {
                    $query->whereRaw("concat(client.first_name, ' ', client.last_name) like ?", ["%$keyword%"]);
                })
                ->orderColumn('user_name', function ($query, $order) {
                    $query->orderBy('client.first_name', $order);
                })

                ->editColumn('created_at',function($selected)
                {                   
                     return $this->getDateTimeFormate($selected->created_at);
                })
                ->orderColumn('created_at', function ($query, $order) {
                    $query->orderBy('orders.created_at', $order);
                })

                ->rawColumns(['action','status','created_at'])
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
            $query = $query->whereIn('status',['1','2']);
        }else{
            $query = $query->with(['userDetails'])->where('client_id',$request->user()->id);
            $query = $query->whereIn('status',['0','1','2']);
        }

        if(!empty($request->status)){
            $query = $query->where('status', $request->status);
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
            ->filterColumn('user_name', function ($query, $keyword) {
                $query->whereRaw("concat(user.first_name, ' ', user.last_name) like ?", ["%$keyword%"]);
            })
            ->orderColumn('user_name', function ($query, $order) {
                $query->orderBy('user.first_name', $order);
            })

            ->editColumn('patient_name',function($selected)
            {
                return $selected->clientDetails ? $selected->clientDetails->user_name : '-';
            })
            ->filterColumn('patient_name', function ($query, $keyword) {
                $query->whereRaw("concat(client.first_name, ' ', client.last_name) like ?", ["%$keyword%"]);
            })
            ->orderColumn('patient_name', function ($query, $order) {
                $query->orderBy('client.first_name', $order);
            })

            ->editColumn('order_no',function($selected)
            {
                return '<a href="'.url('donotezzycaretouch/pharmacy/order/'.$selected->id).'" target="_blank">#'.$selected->id.' Order</a>';
            })
            ->orderColumn('order_no', function ($query, $order) {
                $query->orderBy('orders.id', $order);
            })

            ->rawColumns(['order_no'])->make(true);
    }

     /**
     * Dashboard Area Chart.
     *
     * @return \Illuminate\Http\Response
     */
    public function getOrdersQuery($request, $hcp_provider, $laboratories_provider, $treatment_plan)
    {

        $query = $this->model->where('status', '3')->select(DB::raw('DATE(created_at) as created_date'));
        
        $query = $query->addSelect(DB::raw("'0' AS hcp_appointments"))
                ->addSelect(DB::raw("count(id) AS orders"))    
                ->addSelect(DB::raw("'0' AS lab_appointments"))
                ->addSelect(DB::raw("'0' AS treatment_plan"));       
        
   
        if(!empty($request->start_date) && !empty($request->end_date)){
           $query = $query->whereBetween(DB::raw('DATE(created_at)'), array($request->start_date, $request->end_date));
        }

        if(!empty($request->category_id)){
            $query = $query->whereHas('userDetails', function ($query) use ($request) {
                $query = $query->whereHas('categoryParent', function ($query) use ($request) {
                    $query->where('id', $request->category_id);
                });
            });           
        }

        $query = $query->orderBy('created_date','desc')->groupBy('created_date');

        $query = $query->union($hcp_provider)->union($laboratories_provider)->union($treatment_plan);
        
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

        if(!empty($request->category_id)){
            $query = $query->whereHas('userDetails', function ($query) use ($request) {
                $query = $query->whereHas('categoryParent', function ($query) use ($request) {
                    $query->where('id', $request->category_id);
                });
            });           
        }

        if(!empty($paid) && $paid != '0'){
            $query = $query->whereNotNull('transaction_id');
        }else{
            $query = $query->whereNull('transaction_id');
        }

        $query = $query->orderBy('created_at','desc')->count();
        return $query;
    }

    public function checkOrderisRunning($request, $id)
    {   			
        return $this->model->where('client_id', $request->user()->id)->where('user_id', $id)->whereIn('status',['1','2'])->first();   
    }
 
    public function checkVoucherCodeUsed($client_id, $voucher_id)
    {   			
        return $this->model->where('client_id', $client_id)->where('voucher_code_id', $voucher_id)->first();   
    }

}