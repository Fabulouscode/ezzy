<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\Support_chat;
use Illuminate\Support\Str;

class SupportChatRepository extends Repository
{
    protected $model_name = 'App\Models\Support_chat';
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
     * @param int $support_id
     */
    public function getbySupportId($support_id)
    {
        return $this->model->with(['user','admin'])->where('support_request_id', $support_id)->orderby('created_at','asc')->get();
    }
    
    /**
     * get Model and return the instance.
     *
     * @param int $support_id
     */
    public function getbySupportIdDelete($support_id)
    {
        return $this->model->where('support_request_id', $support_id)->get();
    }

     /**
     * Display a list of record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSupportMessages($request)
    {   
        $query = $this->model->with(['user','admin']);

        if(!empty($request->last_id)){
            $query = $query->where('id', '<', $request->last_id);    
        }
        $query = $query->limit($this->api_data_limit); 
       
        if (!empty($request->support_id)) {
           $query = $query->where('support_request_id', $request->support_id);
        }
        
        $query = $query->orderBy('id','asc')->get();
        
        return $query;
    }
}