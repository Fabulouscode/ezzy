<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\Chat_history;
use Illuminate\Support\Str;

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
    
}