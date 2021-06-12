<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Support_request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class SupportRequestRepository extends Repository
{
    protected $model_name = 'App\Models\Support_request';
    protected $model;
    
    public function __construct()
    {
        parent::__construct();
    }

    public function getStatusValue()
    {
        return $this->model->status_value;
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
    public function getWithRelationship()
    {
        $query = $this->model->with(['userDetails']);    
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
        $data = $this->getWithRelationship();
        return Datatables::of($data)
                ->addColumn('action',function($selected)
                {
                    $data = '';
                    if (Auth::user()->hasPermissionTo('support_ticket-list')) {
                    $data .= '<a href="'.url('donotezzycaretouch/support_request/'.$selected->id).'" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;';
                    }
                    if (Auth::user()->hasPermissionTo('support_ticket-edit')) {
                        $data .= '<a href="'.url('donotezzycaretouch/support_request/'.$selected->id.'/edit').'" class="btn btn-sm btn-info" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;';
                    }
                    if (Auth::user()->hasPermissionTo('support_ticket-edit') && $selected->status != '3') {
                        $data .= '<a href="javascript:void(0)" onclick="closeTicketRow('.$selected->id.')" class="btn btn-sm btn-danger" title="Close Ticket"><i class="fa fa-recycle"></i></a>&nbsp;&nbsp;';
                    }
                    if (Auth::user()->hasPermissionTo('support_ticket-delete')) {
                        $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-danger" title="Delete" id="delete-rows" onclick="deleteRow('.$selected->id.')"><i class="fa fa-trash"></i></a>';
                    }

                    return $data;
                })
                ->editColumn('status',function($selected)
                {
                    //0-Pending, 1-Success, 2-Cancel	
                    $data = '';
                    if($selected->status == '1'){
                        $data .= '<div class="badge badge-success">'.$selected->status_name.'</div>';
                    }else if($selected->status == '2'){
                        $data .= '<div class="badge badge-warning">'.$selected->status_name.'</div>';
                    }else if($selected->status == '3'){
                        $data .= '<div class="badge badge-danger" >'.$selected->status_name.'</div>';                        
                    }else {
                        $data .= '<div class="badge badge-info" >'.$selected->status_name.'</div>';
                    }
                    return $data;
                })
                ->editColumn('description',function($selected)
                {
                    $data = '';
                    if(!empty($selected->description)){
                       $data = strlen($selected->description) > 50 ? substr($selected->description,0,50)."..." : $selected->description;
                    }
                    return $data;
                })
                ->editColumn('userDetails',function($selected)
                {	
                    $data = '';
                    if(!empty($selected->userDetails)){
                        $data .='<div><strong>Name: </strong>'. $selected->userDetails->user_name.'</div>';
                        $data .='<div><strong>Email: </strong>'. $selected->userDetails->email.'</div>';
                        $data .='<div><strong>Mobile No.: </strong>'. $selected->userDetails->mobile_no.'</div>';
                    }                    
                    return $data;
                })
                ->editColumn('created_at', function($selected) {
                    return $selected->created_at ? $this->getDateTimeFormate($selected->created_at) : '-';
                })
                ->addColumn('support_id', function($selected) {
                    return $selected->support_request_no_generate ? $selected->support_request_no_generate : '-';
                })
                ->rawColumns(['action','description','status','userDetails','support_id'])
                ->make(true);
    }

    /**
     * Display a edit of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getbyIdedit($id)
    {
        return $this->model->with(['userDetails'])->find($id);
    }

    /**
     * Display a edit of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getbyIdeditChat($id)
    {   
        return $this->model->with(['userDetails','chatSupport','chatSupport.user','chatSupport.admin'])->find($id);
    }

     /**
     * Display a list of Cancelled Appointment record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSupportRequest($request)
    {   
        $query = $this->model;
        
        if(!empty($request->search)){
            $query = $query->where(function ($query) use ($request) {
                $query->orWhere('title', 'LIKE', '%'.$request->search.'%');
            });
        }else{
            if(!empty($request->last_id)){
                $query = $query->where('id', '<', $request->last_id);    
            }
            $query = $query->limit($this->api_data_limit); 
        }   
       
        $query = $query->where('user_id',$request->user()->id);
        // $query = $query->with(['userDetails']);
        
        $query = $query->orderBy('id','desc')->get();
        
        return $query;
    }
}