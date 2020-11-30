<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\User_services;
use Illuminate\Support\Str;

class UserServiceRepository extends Repository
{
    protected $model_name = 'App\Models\User_services';
    protected $model;

    public function __construct()
    {
        parent::__construct();
    }

    public function getServiceChargeTypeValue()
    {
        return $this->model->service_charge_type_value;
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
     * get Model and return the instance.
     *
     * @param int $user_id
     */
    public function getbyUserId($user_id)
    {
        return $this->model->where('user_id', $user_id)->get();
    }


        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getWithRelationship($request)
    {

        $query = $this->model->with(['service']);    
        if(!empty($request->user_id)){
            $query->where('user_id', $request->user_id);
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
                ->editColumn('service_detail',function($selected)
                {
                    if(!empty($selected->service)){
                        return $selected->service->service_name;
                    }
                })
                ->editColumn('service_charge_type',function($selected)
                {
                    $data = '';
                    if($selected->service_charge_type == '1'){
                        $data .= '<div class="badge badge-success">per Minute</div>';
                    }else if($selected->service_charge_type == '2'){
                         $data .= '<div class="badge badge-success">per Hours</div>';
                    }else if($selected->service_charge_type == '3'){
                         $data .= '<div class="badge badge-success">per Day</div>';
                    }
                    return $data;
                })
                ->editColumn('status',function($selected)
                {
                    //	0-Active, 1-Inactive	
                    $data = '';
                    if($selected->status == '0'){
                        $data .= '<div class="badge badge-success">'.$selected->status_name.'</div>';
                    }else if($selected->status == '1'){
                         $data .= '<div class="badge badge-danger">'.$selected->status_name.'</div>';
                    }
                    return $data;
                })
                ->rawColumns(['service_charge_type','status'])
                ->make(true);
    }
}