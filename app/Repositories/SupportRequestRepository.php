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

    public $status = array(
        '0' => 'Pending',
        '1' => 'Success',
        '2' => 'Cancel'
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
                    $data .= '<a href="'.url('support_request/'.$selected->id).'" class="btn btn-sm btn-outline-info" title="View"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;';
                    $data .= '<a href="'.url('support_request/'.$selected->id.'/edit').'" class="btn btn-sm btn-outline-info" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;';
                    $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-outline-danger" title="Delete" id="delete-rows" onclick="deleteRow('.$selected->id.')"><i class="fa fa-trash"></i></a>';
                    return $data;
                })
                ->editColumn('status',function($selected)
                {
                    //0-Pending, 1-Success, 2-Cancel	
                    $data = '';
                    if($selected->status == '0'){
                        $data .= '<div class="text-info"><strong>Pending</strong></div>';
                    }else if($selected->status == '1'){
                        $data .= '<div class="text-success"><strong>Success</strong></div>';
                    }else if($selected->status == '2'){
                        $data .= '<div class="text-danger" ><strong>Cancel</strong></div>';
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
                        $data .= $selected->userDetails->first_name.' '.$selected->userDetails->last_name.' ('.$selected->userDetails->email.')';
                    }                    
                    return $data;
                })
                ->rawColumns(['action','description','status','userDetails'])
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
     * Display a list of Cancelled Appointment record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSupportRequest($request)
    {   
        $query = $this->model;
        
        if(!empty($request->last_id)){
            $query = $query->where('id', '<', $request->last_id);    
        }
        
        $query = $query->limit($this->api_data_limit);     
       
        $query = $query->with(['userDetails'])->where('user_id',$request->user()->id);
        
        $query = $query->orderBy('id','desc')->get();
        
        return $query;
    }
}