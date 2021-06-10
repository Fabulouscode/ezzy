<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\Services;
use Illuminate\Support\Str;
use Validator;
use DB;

class ServicesRepository extends Repository
{
    protected $model_name = 'App\Models\Services';
    protected $model;
    
    public function __construct()
    {
        parent::__construct();
    }

    public function getStatusValue()
    {
        return $this->model->status_value;
    }

    public function getServiceTypeValue()
    {
        return $this->model->service_type_value;
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
        DB::enableQueryLog();
        $query = $this->model->select('services.*')->with(['category']); 

        if(!empty($request->filter_status) || $request->filter_status == '0'){
            $query = $query->where('services.status', $request->filter_status);
        } 
        
        if(!empty($request->subcategory_id)){
            $query = $query->where('services.service_type', $request->subcategory_id);
        } 

        $query = $query->leftJoin('categories as category', 'services.service_type', '=', 'category.id');
        // $query = $query->orderBy('id','desc')->get();
        // print_r(DB::getQueryLog());
        // die;
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
                    if (Auth::user()->hasPermissionTo('services-edit')) {
                        $data .= '<a href="'.url('services/'.$selected->id.'/edit').'" class="btn btn-sm btn-info" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;';
                    }
                    if (Auth::user()->hasPermissionTo('services-edit')) {
                        $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-danger" title="Delete" id="delete-rows" onclick="deleteRow('.$selected->id.')"><i class="fa fa-trash"></i></a>';
                    }
                    return $data;
                })
                
                ->editColumn('service_type',function($selected)
                {
                    return $selected->service_type_name;
                })
                ->filterColumn('service_type', function ($query, $keyword) {
                    $query->whereRaw("category.name like ?", ["%$keyword%"]);
                })
                ->orderColumn('service_type', function ($query, $order) {
                    $query->orderBy('category.name', $order);
                })

                ->editColumn('status',function($selected)
                {
                    //	0-Active, 1-Inactive	
                    $data = '';
                    if($selected->status == '0'){
                        $data .= '<div class="badge badge-success">'.$selected->status_name.'</div>';
                    }else if($selected->status == '1'){
                         $data .= '<div class="badge badge-danger" >'.$selected->status_name.'</div>';
                    }
                    return $data;
                })
                ->filterColumn('status', function ($query, $keyword) use ($request) {
                    if (in_array($request->search['value'], $this->getStatusValue())){
                        $user_status = array_search($request->search['value'], $this->getStatusValue());
                        $query->where("services.status", $user_status);                       
                    }
                })

                ->rawColumns(['action','status','service_type'])
                ->make(true);
    }

    
    /**
     * get Model and return the instance.
     *
     * @param int $service_type
     */
    public function getbyServiceType($service_type)
    {
        return $this->model->where('service_type', $service_type)->where('status','0')->get();
    }
    
}