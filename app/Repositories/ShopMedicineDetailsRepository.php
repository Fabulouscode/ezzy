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

    public $status = array(
        '0' => 'Active',
        '1' => 'Inactive',
    );
    
    public $medicine_types = array(
        '0' => 'Capsules',
        '1' => 'Bottle',
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
     * Display a listing of the Datatable.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDatatable($request)
    {
        $data = $this->getAll();
        return Datatables::of($data)
                ->addColumn('action',function($selected)
                {
                    $data = '';
                    $data .= '<a href="'.url('medicine/shop/'.$selected->id.'/edit').'" class="btn btn-sm btn-outline-info" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;';
                    // $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-outline-danger" title="Delete" id="delete-rows" onclick="deleteRow('.$selected->id.')"><i class="fa fa-trash"></i></a>';
                    return $data;
                })
                ->editColumn('status',function($selected)
                {
                    //	0-Active, 1-Inactive	
                    $data = '';
                    if($selected->status == '0'){
                        $data .= '<div class="text-success"><strong>Active</strong></div>';
                    }else if($selected->status == '1'){
                         $data .= '<div class="text-danger" ><strong>Inactive</strong></div>';                    
                    }
                    return $data;
                })
                ->rawColumns(['action','status'])
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
    public function checkMeditionStock($data)
    {   
       
        return $this->model->where('id', $data['shop_medicine_detail_id'])
                    ->where('capsual_quantity','>=',$data['quantity'])->first();

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
  
        //user filter for patient and client side 
        if(!empty($request->user()->category_id)){
            $query = $query->where('user_id', $request->user()->id);

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