<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\Chat_eservices;
use Illuminate\Support\Str;

class ChateServicesRepository extends Repository
{
    protected $model_name = 'App\Models\Chat_eservices';
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
     * Display a list of lab report record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSuggestItemTreatmentPlan($request)
    {   
        $query = $this->model->with(['chat']);
        
        if(!empty($request->last_id)){
            $query = $query->where('id', '<', $request->last_id);    
        }
        
        $query = $query->limit($this->api_data_limit);     
        
        $query = $query->whereHas('chat', function ($query) {
            $query->where('chat_type', '3');
        });

        if(!empty($request->search)){
        $query =  $query->where(function ($query) use ($request) {
                        $query->orWhere('medicine_name', 'LIKE', '%'.$request->search.'%');
                    });
        }


        $query = $query->orderBy('id','desc')->get();
        return $query;
    }
    
    
}