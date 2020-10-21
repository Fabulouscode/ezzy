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
    public function dataCrud($request, $id = '')
    {   $data = [
                    'client_id' => $request->user()->id,
                    'user_id' => $request->user_id,
                    'appointment_type' => $request->appointment_type,
                    'name' => $request->name,
                    'email' => $request->email,
                    'mobile_no' => $request->mobile_no,
                    'age' => $request->age,
                    'gender' => $request->gender,
                    'reason' => $request->reason,
                    'appointment_date' => $request->appointment_date,
                    'appointment_time' => $request->appointment_time,
                    'status' => '0'
                ];
        if(!empty($id)){
            return $this->update($data, $id);
        } else {
            return $this->store($data);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getWithRelationship($request)
    {
        $query = $this->model->with(['userDetails','clientDetails','userDetails.categoryParent','userDetails.categoryChild']);    
        if(!empty($request->status)){
            $query = $query->where('status', $request->status);
        }else{
            $query = $query->whereNotIn('status',['5','6']);
        }
        
        $query->orderBy('id','desc')->get();
        
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
                   
                    // Delete
                    // $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-outline-danger" title="Delete" id="delete-rows" onclick="deleteRow('.$selected->id.')"><i class="fa fa-trash"></i></a>';
                    
                    return $data;
                })
                ->addColumn('appointment_category',function($selected)
                {
                    $data = '';
                    if(!empty($selected->userDetails->categoryChild)){
                        $data .= '<div class="text-success"><strong>'.$selected->userDetails->categoryChild->name.'</strong></div>';
                    } else if(!empty($selected->userDetails->categoryParent)){
                        $data .= '<div class="text-success"><strong>'.$selected->userDetails->categoryParent->name.'</strong></div>';
                    }
                    
                    return $data;
                })
                ->addColumn('appointment_type',function($selected)
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
                ->addColumn('status',function($selected)
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
                ->rawColumns(['action','appointment_category','appointment_type','status'])
                ->make(true);
    }

     /**
     * Display a edit of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getbyIdedit($id)
    {   
        return $this->model->with(['userDetails','clientDetails','cancelUserDetails','userDetails.categoryParent','userDetails.categoryChild'])->find($id);

    }
    
    /**
     * Display a list of Upcoming Appointment record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUpcomingAppointment($request)
    {   
        $offset = $request->offset * $this->api_data_limit;
      
        $query = $this->model->offset($offset)->limit($this->api_data_limit);    
       
        if(!empty($request->user()->category_id)){
            $query = $query->with(['clientDetails'])->where('user_id',$request->user()->id);
        }else{
            $query = $query->with(['userDetails'])->where('client_id',$request->user()->id);
        }
        
        $query = $query->whereIn('status',['1','2'])->orderBy('id','desc')->get();
        
        return $query;
       
    }
   
    /**
     * Display a list of Pending Appointment record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPendingAppointment($request)
    {   
        $offset = $request->offset * $this->api_data_limit;

        $query = $this->model->offset($offset)->limit($this->api_data_limit);    
       
        if(!empty($request->user()->category_id)){
            $query = $query->with(['clientDetails'])->where('user_id',$request->user()->id);
        }else{
            $query = $query->with(['userDetails'])->where('client_id',$request->user()->id);
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
        $offset = $request->offset * $this->api_data_limit;
        
        $query = $this->model->offset($offset)->limit($this->api_data_limit);    
       
        if(!empty($request->user()->category_id)){
            $query = $query->with(['clientDetails'])->where('user_id',$request->user()->id);
        }else{
            $query = $query->with(['userDetails'])->where('client_id',$request->user()->id);
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
        $offset = $request->offset * $this->api_data_limit;

        $query = $this->model->offset($offset)->limit($this->api_data_limit);    
        
        if(!empty($request->user()->category_id)){
            $query = $query->with(['clientDetails'])->where('user_id',$request->user()->id);
        }else{
            $query = $query->with(['userDetails'])->where('client_id',$request->user()->id);
        }
        
        $query = $query->whereIn('status',['5'])->orderBy('id','desc')->get();

        return $query;
    }

}

