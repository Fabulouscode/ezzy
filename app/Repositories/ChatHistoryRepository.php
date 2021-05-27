<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\Chat_history;
use Illuminate\Support\Str;
use DB;

class ChatHistoryRepository extends Repository
{
    protected $model_name = 'App\Models\Chat_history';
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
     * Display a edit of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getbyIdedit($id)
    {   
        return $this->model->with(['user','client','recommended','chatDetails','chatDetails.userService.service'])->find($id);

    }

    public function getTreatmentPlanbyId($id)
    {   
        return $this->model->with(['user','client','recommended','chatDetails.userService.shopMedicineDetails'])->where('chat_type','3')->where('id',$id)->first();

    }

    public function getePrescibePlanbyId($id)
    {   
        return $this->model->with(['user','client','recommended','chatDetails.userService.shopMedicineDetails'])->where('chat_type','0')->where('id',$id)->first();

    }
   
    public function getTransactionCompleted($id)
    {   
        return $this->model->where('chat_type','3')->whereNotNull('transaction_id')->where('id',$id)->first();

    }

        /**
     * Dashboard Area Chart.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTreatmentPlanChart($request)
    {

        $query = $this->model->where('chat_type', '3')->whereNotNull('transaction_id')->select(DB::raw('DATE(created_at) AS created_date'));

        $query = $query->addSelect(DB::raw("'0' AS hcp_appointments"))
                ->addSelect(DB::raw("'0' AS orders"))    
                ->addSelect(DB::raw("'0' AS lab_appointments"))
                ->addSelect(DB::raw("count(id) AS treatment_plan"));       
        
        if(!empty($request->start_date) && !empty($request->end_date)){
           $query = $query->whereBetween(DB::raw('DATE(created_at)'), array($request->start_date, $request->end_date));
        }

        $query = $query->orderBy('created_date','desc')->groupBy('created_date');

        return $query;
    }

      /**
     * Dashboard pie Chart.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTreatmentPlanCount($request, $paid = 0)
    {
        $query = $this->model;   
        
        if(!empty($request->start_date) && !empty($request->end_date)){
            $query = $query->whereBetween(DB::raw('DATE(created_at)'), array($request->start_date, $request->end_date));
        }
        
        if(!empty($paid) && $paid != '0'){
            $query = $query->whereNotNull('transaction_id');
        }else{
            $query = $query->whereNull('transaction_id');
        }

        $query = $query->orderBy('created_at','desc')->count();
        return $query;
    }

    
}