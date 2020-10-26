<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\Order_tracking;
use Illuminate\Support\Str;

class OrderTrackingRepository extends Repository
{
    protected $model_name = 'App\Models\Order_tracking';
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
     * @param int $user_id
     */
    public function getbyOrderId($order_id)
    {
        return $this->model->where('order_id', $order_id)->get();
    }
    
    
}