<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\Lab_report;
use Illuminate\Support\Str;

class LabReportRepository extends Repository
{
    protected $model_name = 'App\Models\Lab_report';
    protected $model;

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
     * get Model and return the instance.
     *
     * @param int $client_id
     */
    public function getbyUserId($id)
    {
        return $this->model->where('client_id', $id)->get();
    }
    
    /**
     * Display a list of lab report record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getbyUserIdLabReport($request)
    {   
        $query = $this->model;
        
        if(!empty($request->last_id)){
            $query = $query->where('id', '<', $request->last_id);    
        }
        
        $query = $query->limit($this->api_data_limit);     
        
        if(!empty($request->user()->id)){
            $query = $query->where('client_id',$request->user()->id);
        }
        
        $query = $query->orderBy('id','desc')->get();

        return $query;
    }
    
}