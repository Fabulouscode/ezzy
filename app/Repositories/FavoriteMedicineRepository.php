<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\Favorite_product;
use Illuminate\Support\Str;

class FavoriteMedicineRepository extends Repository
{
    protected $model_name = 'App\Models\Favorite_product';
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
        return $this->model->where('user_id', $user_id)->get();
    }
    
    /**
     * Display a list of Favorite Medicine record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getFavoriteMedicine($request)
    {   
        $query = $this->model;
        
        if(!empty($request->last_id)){
            $query = $query->where('id', '<', $request->last_id);    
        }

        $query = $query->limit($this->api_data_limit);     
      
        $query = $query->with(['shopMedicineDetails','shopMedicineDetails.medicineDetails'])->where('user_id',$request->user()->id);
      
        $query = $query->orderBy('id','desc')->get();
        
        return $query;
       
    }
   
    /**
     * remove favourite medicine.
     *
     * @return \Illuminate\Http\Response
     */
    public function removeFavoriteMedicine($request)
    {    
        $query = $this->model;
        $query = $query->where('user_id',$request->user()->id)
                       ->where('shop_medicine_detail_id',$request->shop_medicine_detail_id)->first();

        $this->destroy($query->id);
        return true;
       
    }
    
}