<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\Appointment;
use Illuminate\Support\Str;
use App\Repositories\OrderRepository;
use DB;

class AppointmentRepository extends Repository
{
    protected $model_name = 'App\Models\Appointment';
    protected $model;
    private $order_repo;

    public function __construct(OrderRepository $order_repo)
    {
        parent::__construct();
        $this->order_repo = $order_repo;
    }

    public function getStatusValue()
    {
        return $this->model->status_value;
    }
  
    public function getAppointmentTypeValue()
    {
        return $this->model->appointment_type_value;
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
    public function getAppointmentStatusWiseCount($status = '', $provider = '')
    {
        $query = $this->model;
        
        if($status != ''){
            $query = $query->whereIn('status', $status);
        }

        if($provider != ''){
            $query = $query->whereHas('user', function($query) use ($provider){
                $query = $query->whereHas('categoryParent', function($query) use ($provider){
                    $query->where('parent_id', $provider);
                });
            });
        }
        
        $query = $query->orderBy('id','desc')->count();

        return $query;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTodayAppointmentStatusWiseCount($status = '', $provider = '')
    {
        $query = $this->model->where('appointment_date', '=', Carbon::now()->format('Y-m-d'));
        
        if($status != ''){
            $query = $query->whereIn('status', $status);
        } 

        if($provider != ''){
            $query = $query->whereHas('user', function($query) use ($provider){
                $query = $query->whereHas('categoryParent', function($query) use ($provider){
                    $query->where('parent_id', $provider);
                });
            });
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
        $query = $this->model->with(['user','client','user.categoryParent','user.categoryChild']);    
        if(isset($request->status) && $request->status != ''){
            $query = $query->where('status', $request->status);
        }else{
            $query = $query->whereNotIn('status',['5','6']);
        }
     
        if(!empty($request->hacp_type)){
            $query = $query->whereHas('user', function($query) use ($request){
                $query = $query->whereHas('categoryParent', function($query) use ($request){
                    $query->where('parent_id', $request->hacp_type);
                });
            });
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
                ->addColumn('action',function($selected)
                {
                    $data = '';
                    // Edit
                    // $data .= '<a href="'.url('appointment/'.$selected->id.'/edit').'" class="btn btn-sm btn-info" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;';
                    
                    // View
                    if (Auth::user()->hasPermissionTo('appointments-list')) {
                        $data .= '<a href="'.url('appointment/'.$selected->id).'" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;';
                    }
                    if (Auth::user()->hasPermissionTo('appointments-invoice')) {
                        if ($selected->status == '5') {
                            $data .= '<a href="'.url('appointment/invoice/'.$selected->id).'" class="btn btn-sm btn-info" title="Invoice"><i class="fa fa-file"></i></a>&nbsp;&nbsp;';
                        }
                    }
                
                    // Delete
                    // $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-danger" title="Delete" id="delete-rows" onclick="deleteRow('.$selected->id.')"><i class="fa fa-trash"></i></a>';
                    
                    return $data;
                })
                ->editColumn('user_name',function($selected)
                {                   
                    return $selected->client->user_name;
                })
                ->editColumn('service_provider',function($selected)
                {                   
                     return $selected->user->user_name;
                })
                ->editColumn('appointment_date',function($selected)
                {                   
                     return $this->getDateTimeFormate($selected->appointment_date .' '.$selected->appointment_time);
                })
                ->editColumn('hcp_type',function($selected)
                {
                    $data = '';
                    if(!empty($selected->user->categoryParent)){
                        $data .= '<div class="text-success"><strong>'.$selected->user->categoryParent->name.'</strong></div>';
                    }
                    if(!empty($selected->user->categoryChild)){
                        $data .= '<div class="text-success"><strong>'.$selected->user->categoryChild->name.'</strong></div>';
                    } 
                    
                    return $data;
                })
                ->editColumn('appointment_type',function($selected)
                {
                    //	0-In Clinic, 1-Home Care, 2-Video Call
                    $data = '';
                    if($selected->appointment_type == '2'){
                        $data .= '<div class="badge badge-info">'.$selected->appointment_type_name.'</div>';
                    }else if($selected->appointment_type == '1'){
                        $data .= '<div class="badge badge-info">'.$selected->appointment_type_name.'</div>';
                    }else {
                        $data .= '<div class="badge badge-success">'.$selected->appointment_type_name.'</div>';
                    }
                    
                    return $data;
                })
                ->editColumn('status',function($selected)
                {
                    //	0-Pending, 1-Upcoming, 2-in_progress, 3-Paid, 4-Unpaid, 5-Success, 6-Cancel
                    $data = '';
                    if($selected->status == '0'){
                        $data .= '<div class="badge badge-info">'.$selected->status_name.'</div>';
                    }else if($selected->status == '1'){
                        $data .= '<div class="badge badge-warning">'.$selected->status_name.'</div>';
                    }else if($selected->status == '2'){
                        $data .= '<div class="badge badge-warning">'.$selected->status_name.'</div>';
                    }else if($selected->status == '3'){
                        $data .= '<div class="badge badge-success">'.$selected->status_name.'</div>';
                    }else if($selected->status == '4'){
                        $data .= '<div class="badge badge-danger">'.$selected->status_name.'</div>';
                    }else if($selected->status == '5'){
                        $data .= '<div class="badge badge-success">'.$selected->status_name.'</div>';
                    }else if($selected->status == '6'){
                        $data .= '<div class="badge badge-danger">'.$selected->status_name.'</div>';
                    }
                    //  $data .= '<div class="badge badge-danger" >'.$selected->status_name.'</div>';
                    return $data;
                })
                ->rawColumns(['action','hcp_type','appointment_type','status'])
                ->make(true);
    }

     /**
     * Display a edit of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getbyIdedit($id)
    {   
        return $this->model->with(['user','client','cancelUser', 'appointmentServices', 'userService','getTransaction','appointmentServices.userService.service','user.categoryParent','user.categoryChild'])->find($id);

    }
 
    /**
     * Display a edit of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getbyIdCheckTransaction($id)
    {   
        return $this->model->whereNull('transaction_id')->where('id',$id)->whereNotIn('status',['5','6'])->first();

    }
 
    /**
     * Display a edit of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkUserAvailable($request)
    {   
            // appointment same time not book
        $start_appointment  = new Carbon($request->appointment_time);
        $end_appointment  = new Carbon($request->appointment_time);
        $query = $this->model->where('appointment_date', $request->appointment_date)
                ->where('appointment_time','<=', $start_appointment->addMinute('10')->format('H:i:s'))
                ->where('appointment_time','>=', $end_appointment->subMinute('10')->format('H:i:s'))
                ->where('user_id',$request->user_id);
   
        $query = $query->first();

        return $query;
      
    }
    
    /**
     * Display a list of Upcoming Appointment record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllAppointment($request)
    {   
        $query = $this->model;
        
        if(!empty($request->last_id)){
            $query = $query->where('id', '<', $request->last_id);    
        }
        
        $query = $query->limit($this->api_data_limit); 

        if(!empty($request->user()->category_id)){
            $query = $query->with(['client'])->where('user_id',$request->user()->id);
        }else{
            $query = $query->with(['user'])->where('client_id',$request->user()->id);
        }
        
        $query = $query->orderBy('id','desc')->get();
        
        return $query;
       
    }

    /**
     * Display a list of Upcoming Appointment record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUpcomingAppointment($request)
    {   
        $query = $this->model;
        
        if(!empty($request->last_id)){
            $query = $query->where('id', '<', $request->last_id);    
        }
        
        $query = $query->limit($this->api_data_limit); 

        if(!empty($request->user()->category_id)){
            $query = $query->with(['client'])->where('user_id',$request->user()->id);
        }else{
            $query = $query->with(['user'])->where('client_id',$request->user()->id);
        }
        
        $query = $query->whereIn('status',['1','2','3','4'])->orderBy('id','desc')->get();
        
        return $query;
       
    }
   
    /**
     * Display a list of Pending Appointment record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPendingAppointment($request)
    {   
        $query = $this->model;
        
        if(!empty($request->last_id)){
            $query = $query->where('id', '<', $request->last_id);    
        }
        
        $query = $query->limit($this->api_data_limit);      
       
        if(!empty($request->user()->category_id)){
            $query = $query->with(['client'])->where('user_id',$request->user()->id);
        }else{
            $query = $query->with(['user'])->where('client_id',$request->user()->id);
        }
        
        $query = $query->whereIn('status',['0'])->orderBy('id','desc')->get();
        
        return $query;

    }
   
    /**
     * Display a list of Cancelled Appointment record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCancelledAppointment($request)
    {   
        $query = $this->model;
        
        if(!empty($request->last_id)){
            $query = $query->where('id', '<', $request->last_id);    
        }
        
        $query = $query->limit($this->api_data_limit);    
       
        if(!empty($request->user()->category_id)){
            $query = $query->with(['client'])->where('user_id',$request->user()->id);
        }else{
            $query = $query->with(['user'])->where('client_id',$request->user()->id);
        }
        
        $query = $query->whereIn('status',['6'])->orderBy('id','desc')->get();
        
        return $query;
    }
   
    /**
     * Display a list of Completed Appointment record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCompletedAppointment($request)
    {   
        $query = $this->model;
        
        if(!empty($request->last_id)){
            $query = $query->where('id', '<', $request->last_id);    
        }
        
        $query = $query->limit($this->api_data_limit);     
        
        if(!empty($request->user()->category_id)){
            $query = $query->with(['client'])->where('user_id',$request->user()->id);
        }else{
            $query = $query->with(['user'])->where('client_id',$request->user()->id);
        }
        
        $query = $query->whereIn('status',['5'])->orderBy('id','desc')->get();

        return $query;
    }

    public function getReviewDatatable($request)
    {
        $request->status = [5,6];
        $data = $this->getWithRelationship($request);

        return Datatables::of($data)
            ->editColumn('user_name',function($selected)
            {
                return $selected->user ? $selected->user->user_name : '-';
            })
            ->editColumn('patient_name',function($selected)
            {
                return $selected->client ? $selected->client->user_name : '-';
            })
            ->editColumn('appointment_no',function($selected)
            {
                return '<a href="'.url('appointment/'.$selected->id).'" target="_blank">#'.$selected->id.' Appointment</a>';
            })
            ->rawColumns(['appointment_no'])->make(true);
    }

    


     /**
     * Dashboard Area Chart.
     *
     * @return \Illuminate\Http\Response
     */
    public function getHCPTypeWiseAppointment($request, $category_id, $provider_type = '')
    {

        $query = $this->model->where('status', '5')->select('appointment_date AS created_date');
        
        if(!empty($category_id) && $category_id == '1'){
             $query = $query->addSelect(DB::raw("'1' AS hcp_appointments"))
                        ->addSelect(DB::raw("'0' AS orders"))    
                        ->addSelect(DB::raw("'0' AS lab_appointments"));
        }else if(!empty($category_id) && $category_id == '3'){
             $query = $query->addSelect(DB::raw("'0' AS hcp_appointments"))
                        ->addSelect(DB::raw("'0' AS orders"))    
                        ->addSelect(DB::raw("'1' AS lab_appointments"));
        }

        if(!empty($category_id)){
            $query = $query->whereHas('user', function ($query) use ($category_id) {
                $query = $query->whereHas('categoryParent', function ($query) use ($category_id) {
                    $query->where('parent_id', $category_id);
                });
            });           
        }
       
        
        if(!empty($request->start_date) && !empty($request->end_date)){
           $query = $query->whereBetween('appointment_date', array($request->start_date, $request->end_date));
        }

        $query = $query->orderBy('appointment_date','desc');
        return $query;
    }

     /**
     * Dashboard Area Chart.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAreaChartdata($request)
    {
        $data = array();
        $hcp_provider = $this->getHCPTypeWiseAppointment($request, '1', 'hcp');
        $laboratories_provider = $this->getHCPTypeWiseAppointment($request, '3', 'lab');
        $pharmacy_provider = $this->order_repo->getOrdersQuery($request, $hcp_provider, $laboratories_provider);
          print_r($pharmacy_provider);
        die;      
        // $query = $hcp_provider->union($laboratories_provider)->union($pharmacy_provider);
        // $query = $query->select('created_date');
        // $query = $query->addSelect(DB::raw("SUM(hcp_appointments) AS hcp"))
        //                 ->addSelect(DB::raw("SUM(orders) AS order"))    
        //                 ->addSelect(DB::raw("SUM(lab_appointments) AS lab"));

        // $query = $query->groupBy('created_date')->get()->toArray();
        

        print_r($query);
        die;
        return $data;
    }

    /**
     * Dashboard pie Chart.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAppointmentCount($request, $paid = 0)
    {
        $query = $this->model;   
        
        if(!empty($request->start_date) && !empty($request->end_date)){
            $query = $query->whereBetween('appointment_date', array($request->start_date, $request->end_date));
        }
        
        if(!empty($paid) && $paid != '0'){
            $query = $query->whereNotNull('transaction_id');
        }else{
            $query = $query->whereNull('transaction_id');
        }

        $query = $query->orderBy('appointment_date','desc')->count();
        return $query;
    }
}

