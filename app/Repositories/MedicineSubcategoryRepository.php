<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\Medicine_subcategory;
use Illuminate\Support\Str;

class MedicineSubcategoryRepository extends Repository
{
    protected $model_name = 'App\Models\Medicine_subcategory';
    protected $model;
    
    public function __construct()
    {
        parent::__construct();
    }

    public function getStatusValue()
    {
        return $this->model->status_value;
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
    public function getWithRelationship()
    {
        $query = $this->model->select('medicine_subcategories.*')->with(['medicineCategory']);
        $query = $query->leftJoin('medicine_categories', 'medicine_subcategories.medicine_category_id', '=', 'medicine_categories.id');
      
        // $query = $query->orderBy('id','desc')->get();
        return $query;
    }
    
    /**
     * Display a listing of the Datatable.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDatatable($request)
    {
        $data = $this->getWithRelationship();
        return Datatables::of($data)
                ->addColumn('action',function($selected)
                {
                    $data = '';
                    if (Auth::user()->hasPermissionTo('medicine_subcategory-edit')) {
                        $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-info" title="Edit" onclick="editRow('.$selected->id.')"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;';
                    }
                    if (Auth::user()->hasPermissionTo('medicine_subcategory-delete')) {
                        $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-danger" title="Delete" id="delete-rows" onclick="deleteRow('.$selected->id.')"><i class="fa fa-trash"></i></a>';
                    }

                    return $data;
                })

                ->editColumn('medicineCategory',function($selected){
                    if(!empty($selected->medicineCategory)){
                        return $selected->medicineCategory->name;
                    }                            
                })
                ->filterColumn('medicineCategory', function ($query, $keyword) {
                    $query->whereRaw("medicine_categories.name like ?", ["%$keyword%"]);
                })
                ->orderColumn('medicineCategory', function ($query, $order) {
                    $query->orderBy('medicine_categories.name', $order);
                })
                
                ->editColumn('status',function($selected)
                {
                    //	0-Active, 1-Inactive	
                    $data = '';
                    if($selected->status == '0'){
                        $data .= '<div class="badge badge-success">'.$selected->status_name.'</div>';
                    }else if($selected->status == '1'){
                         $data .= '<div class="badge badge-danger" >'.$selected->status_name.'</div>';                    
                    }
                    return $data;
                })
                ->filterColumn('status', function ($query, $keyword) use ($request) {
                    if (in_array($request->search['value'], $this->getStatusValue())){
                        $medicine_subcategories_status = array_search($request->search['value'], $this->getStatusValue());
                        $query->where("medicine_subcategories.status", $medicine_subcategories_status);                       
                    }
                })

                ->rawColumns(['action','medicineCategory','status'])
                ->make(true);
    }
}