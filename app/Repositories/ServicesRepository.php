<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\Services;
use Illuminate\Support\Str;

class ServicesRepository extends Repository
{
    protected $model_name = 'App\Models\Services';
    protected $model;

    public $status = array(
        '0' => 'Active',
        '1' => 'Inactive',
    );

    public $service_type = array(
        '0' => 'Massage Therapist',
        '2' => 'Scientist',
        '3' => 'Pathologist',
        '4' => 'Radiologist',
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
     * Display a listing of the Datatable.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDatatable($request)
    {
        $data = $this->getAll();
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
                    return $this->service_type[$selected->service_type];
                })
                ->editColumn('status',function($selected)
                {
                    //	0-Active, 1-Inactive	
                    $data = '';
                    if($selected->status == '0'){
                        $data .= '<div class="badge badge-success">Active</div>';
                    }else if($selected->status == '1'){
                         $data .= '<div class="badge badge-danger" >Inactive</div>';
                    }
                    return $data;
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