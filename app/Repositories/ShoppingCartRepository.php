<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\Shopping_cart;
use Illuminate\Support\Str;

class ShoppingCartRepository extends Repository
{
    protected $model_name = 'App\Models\Shopping_cart';
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
    public function getbyUserId($user_id)
    {
        return $this->model->with(['shopMedicineDetails','shopMedicineDetails.medicineDetails'])->where('user_id', $user_id)->get();
    }

    /**
     * get Model and return the instance.
     *
     * @param int $user_id
     */
    public function getUserCart($user_id)
    {
        return $this->model->with(['shopMedicineDetails','shopMedicineDetails.medicineDetails'])->where('user_id', $user_id)->get();
    }

    /**
     * get Model and return the instance.
     *
     * @param int $user_id
     */
    public function clearUserCart($user_id)
    {
        return $this->model->where('user_id', $user_id)->delete();
    }

    /**
     * get Model and return the instance.
     *
     * @param int $user_id
     */
    public function clearShopCart($user_id, $shop_id)
    {
        return $this->model->where('user_id', $user_id)
                    ->whereHas('shopMedicineDetails', function($q) use ($shop_id){
                                 $q->where('user_id', $shop_id);
                    })->delete();
    }
  
    /**
     * get Model and return the instance.
     *
     * @param int $user_id
     */
    public function checkCart($user_id, $medicine_id)
    {
        return $this->model->where('user_id', $user_id)->where('shop_medicine_detail_id', $medicine_id)->first();
    }
 
    
    
}