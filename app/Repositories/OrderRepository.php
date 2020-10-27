<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\Order;
use Illuminate\Support\Str;

class OrderRepository extends Repository
{
    protected $model_name = 'App\Models\Order';
    protected $model;

    public $status = array(
        '0' => 'Active',
        '1' => 'Success',
        '2' => 'Cancel'
    );

    public $delivery_type = array(
        '0' => 'Home Delievry',
        '1' => 'pick-up from store'
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
     * Display a list of Order record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getOrderHistory($request)
    {   
        $offset = $request->offset * $this->api_data_limit;

        $query = $this->model->offset($offset)->limit($this->api_data_limit);    
        
        if(!empty($request->user()->category_id)){
            $query = $query->with(['clientDetails'])->where('user_id',$request->user()->id);
        }else{
            $query = $query->with(['userDetails'])->where('client_id',$request->user()->id);
        }
        
        $query = $query->orderBy('id','desc')->get();

        return $query;
    }

    /**
     * get Model and return the instance.
     *
     * @param int $user_id
     */
    public function getbyEditId($order_id)
    {
        return $this->model->with(['orderProductDetails','orderProductDetails.shopMedicineDetails', 'orderProductDetails.medicineDetails'])->where('id', $order_id)->first();
    }
    
     /**
     * Display a list of Completed Order record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCompletedOrder($request)
    {   
        $offset = $request->offset * $this->api_data_limit;
        
        $query = $this->model->offset($offset)->limit($this->api_data_limit);    

        // $query = $query->with(['orderProductDetails','orderProductDetails.shopMedicineDetails', 'orderProductDetails.medicineDetails']);
       
        if(!empty($request->user()->category_id)){
            $query = $query->with(['clientDetails'])->where('user_id',$request->user()->id);
        }else{
            $query = $query->with(['userDetails'])->where('client_id',$request->user()->id);
        }
        
        $query = $query->where('status','1')->orderBy('id','desc')->get();
        
        return $query;
    }
  
    /**
     * Display a list of Cancelled Order record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCancelledOrder($request)
    {   
        $offset = $request->offset * $this->api_data_limit;
        
        $query = $this->model->offset($offset)->limit($this->api_data_limit);    
      
        // $query = $query->with(['orderProductDetails','orderProductDetails.shopMedicineDetails', 'orderProductDetails.medicineDetails']);
       
        if(!empty($request->user()->category_id)){
            $query = $query->with(['clientDetails'])->where('user_id',$request->user()->id);
        }else{
            $query = $query->with(['userDetails'])->where('client_id',$request->user()->id);
        }
        
        $query = $query->where('status','2')->orderBy('id','desc')->get();
        
        return $query;
    }

    /**
     * Display a list of Active Order record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getActiveOrder($request)
    {   
        $offset = $request->offset * $this->api_data_limit;
        
        $query = $this->model->offset($offset)->limit($this->api_data_limit);    
       
        // $query = $query->with(['orderProductDetails','orderProductDetails.shopMedicineDetails', 'orderProductDetails.medicineDetails']);
                
        if(!empty($request->user()->category_id)){
            $query = $query->with(['clientDetails'])->where('user_id',$request->user()->id);
        }else{
            $query = $query->with(['userDetails'])->where('client_id',$request->user()->id);
        }
        
        $query = $query->where('status','0')->orderBy('id','desc')->get();
        
        return $query;
    }
    
}