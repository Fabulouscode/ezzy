<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\Shop_medicine_details;
use Illuminate\Support\Str;

class ShopMedicineDetailsRepository extends Repository
{
    protected $model_name = 'App\Models\Shop_medicine_details';
    protected $model;
    
    public function __construct()
    {
        parent::__construct();
    }

    public function getStatusValue()
    {
        return $this->model->status_value;
    }

    public function getMedicineTypeValue()
    {
        return $this->model->medicine_type_value;
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getWithRelationship($request)
    {

        $query = $this->model->with(['medicineDetails','medicineSubcategory','medicineCategory']);    
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
                ->editColumn('medicine_detail',function($selected)
                {
                    if(!empty($selected->medicineDetails)){
                        return !empty($selected->shirap_ml)? $selected->medicineDetails->medicine_name.' ('.$selected->shirap_ml.' ML)':$selected->medicineDetails->medicine_name;
                    }
                })
                ->editColumn('medicine_sku',function($selected)
                {
                    if(!empty($selected->medicineDetails)){
                        return $selected->medicineDetails->medicine_sku;
                    }
                })
                ->editColumn('mrp_price',function($selected)
                {
                    $data = '';
                    $data .= '<strong>MRP Price : </strong>'.$selected->mrp_price.'<br>';
                    $data .= '<strong>Offer Price : </strong>'.$selected->offer_price;           
                    return $data;
                })
                ->editColumn('medicine_type',function($selected)
                {
                    //	0-Capsules, 1-Bottle	
                    $data = '';
                    if($selected->medicine_type == '0'){
                        $data .= '<div class="badge badge-success">Capsules</div>';
                    }else if($selected->medicine_type == '1'){
                         $data .= '<div class="badge badge-success">Bottle</div>';
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
                ->rawColumns(['mrp_price','medicine_type','status'])
                ->make(true);
    }
     /**
     * Display a edit of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getbyIdedit($id)
    {   
        return $this->model->with(['medicineDetails','medicineDetails.medicineImages','medicineSubcategory','medicineCategory'])->find($id);

    }
    
    /**
     * Display a edit of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkMedicineStock($data)
    {   
        return $this->model->where('id', $data->shop_medicine_detail_id)
                    ->where('capsual_quantity','>=',$data->quantity)->first();

    }

     /**
     * Display a list of Medicine product record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getShopMedicineProducts($request)
    {   
        // \DB::connection()->enableQueryLog(); 

        $query = $this->model;
        
        if(!empty($request->last_id)){
            $query = $query->where('id', '<', $request->last_id);    
        }
        
        $query = $query->with(['medicineDetails'])->limit($this->api_data_limit);     
       
       
        //category filter
        if(!empty($request->medicine_category_id)){
            $query = $query->where('medicine_category_id', $request->medicine_category_id);
        }       
      
        //search filter
        if(!empty($request->search)){
            $query = $query->whereHas('medicineDetails', function ($query) use ($request) {
                        $query->where(function ($query) use ($request) {
                            $query->orWhere('medicine_name', 'LIKE', '%'.$request->search.'%');
                            $query->orWhere('medicine_sku', 'LIKE', '%'.$request->search.'%');
                        });
                    });

        }       
  
        //user filter for phamacy side 
        if(!empty($request->user()->category_id)){
            $query = $query->where('user_id', $request->user()->id);

        }       
       
        //user filter for patient or client side 
        if(!empty($request->shop_id)){
            $query = $query->where('user_id', $request->shop_id);
        }       

        //price order
        if(isset($request->price_order) && $request->price_order == '1'){
            $query = $query->orderBy('mrp_price', 'desc');
        } else if(isset($request->price_order) && $request->price_order == '0'){
            $query = $query->orderBy('mrp_price', 'asc');
        }else{
            $query = $query->orderBy('id','desc');
        }

        $query = $query->where('status','0')->get();
        
        // dd(\DB::getQueryLog());
        return $query;
       
    }
}