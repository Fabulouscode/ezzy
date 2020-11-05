<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\Appointment;
use Illuminate\Support\Str;

class AppointmentRepository extends Repository
{
    protected $model_name = 'App\Models\Appointment';
    protected $model;
   
    public $status = array(
        '0' => 'Pending',
        '1' => 'Upcoming',
        '2' => 'in_progress',
        '3' => 'Paid',
        '4' => 'Unpaid',
        '5' => 'Success',
        '6' => 'Cancel'
    );

    public $appointment_types = array(
        '0' => 'In Clinic',
        '1' => 'Home Care',
        '2' => 'Video Call'
    );
    
    public $service_charge_type = array(
        '1' => 'per Minute',
        '2' => 'per Hours',
        '3' => 'per Day'
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAppointmentStatusWiseCount($status = '')
    {
        $query = $this->model;
        
        if($status != ''){
            $query = $query->whereIn('status', $status);
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
        if(!empty($request->status)){
            $query = $query->where('status', $request->status);
        }else{
            $query = $query->whereNotIn('status',['5','6']);
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
                    // $data .= '<a href="'.url('appointment/'.$selected->id.'/edit').'" class="btn btn-sm btn-outline-info" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;';
                    
                    // View
                    $data .= '<a href="'.url('appointment/'.$selected->id).'" class="btn btn-sm btn-outline-info" title="View"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;';
                   
                    if($selected->status == '5'){
                        $data .= '<a href="'.url('appointment/invoice/'.$selected->id).'" class="btn btn-sm btn-outline-info" title="Invoice"><i class="fa fa-files-o"></i></a>&nbsp;&nbsp;';
                    }
                
                    // Delete
                    // $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-outline-danger" title="Delete" id="delete-rows" onclick="deleteRow('.$selected->id.')"><i class="fa fa-trash"></i></a>';
                    
                    return $data;
                })
                ->editColumn('user_name',function($selected)
                {                   
                    return $selected->client->first_name .' '.$selected->client->last_name;
                })
                ->editColumn('service_provider',function($selected)
                {                   
                     return $selected->user->first_name .' '.$selected->user->last_name;
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
                    if($selected->appointment_type == '1'){
                        $data .= '<div class="text-info"><strong>Video Call</strong></div>';
                    }else if($selected->appointment_type == '2'){
                        $data .= '<div class="text-info"><strong>Home Care</strong></div>';
                    }else {
                        $data .= '<div class="text-success"><strong>In Clinic</strong></div>';
                    }
                    
                    return $data;
                })
                ->editColumn('status',function($selected)
                {
                    //	0-Pending, 1-Upcoming, 2-in_progress, 3-Paid, 4-Unpaid, 5-Success, 6-Cancel
                    $data = '';
                    if($selected->status == '0'){
                        $data .= '<div class="text-info"><strong>Pending</strong></div>';
                    }else if($selected->status == '1'){
                        $data .= '<div class="text-warning"><strong>Upcoming</strong></div>';
                    }else if($selected->status == '2'){
                        $data .= '<div class="text-warning"><strong>In Progress</strong></div>';
                    }else if($selected->status == '3'){
                        $data .= '<div class="text-success"><strong>Paid</strong></div>';
                    }else if($selected->status == '4'){
                        $data .= '<div class="text-danger"><strong>Unpaid</strong></div>';
                    }else if($selected->status == '5'){
                        $data .= '<div class="text-success"><strong>Success</strong></div>';
                    }else if($selected->status == '6'){
                        $data .= '<div class="text-danger"><strong>Cancel</strong></div>';
                    }
                    //  $data .= '<div class="text-danger" ><strong>Inactive</strong></div>';
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
        return $this->model->with(['user','client','cancelUser', 'appointmentServices', 'userService','creditTransaction', 'debitTransaction','appointmentServices.userService.service','user.categoryParent','user.categoryChild'])->find($id);

    }
 
    /**
     * Display a edit of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getbyIdCheckTransaction($id)
    {   
        return $this->model->whereNull('credit_transaction_id')->whereNull('debit_transaction_id')->where('id',$id)->whereNotIn('status',['5','6'])->first();

    }
 
    /**
     * Display a edit of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkUserAvailable($request)
    {   
        return $this->model->where('appointment_date', $request->appointment_date)->where('appointment_time', $request->appointment_time)->where('user_id',$request->user_id)->first();

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
                return $selected->user ? $selected->user->first_name.' '.$selected->user->last_name : '-';
            })
            ->editColumn('patient_name',function($selected)
            {
                return $selected->client ? $selected->client->first_name.' '.$selected->client->last_name : '-';
            })->make(true);
    }

}

