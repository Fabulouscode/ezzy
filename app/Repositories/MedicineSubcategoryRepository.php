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
    public $status = array(
        '0' => 'Active',
        '1' => 'Inactive',
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getWithRelationship()
    {
        $query = $this->model->with(['medicineCategory']);
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
        $data = $this->getWithRelationship();
        return Datatables::of($data)
                ->addColumn('action',function($selected)
                {
                    $data = '';
                    $data .= '<a href="'.url('medicine/subcategories/'.$selected->id.'/edit').'" class="btn btn-sm btn-info" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;';
                    $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-danger" title="Delete" id="delete-rows" onclick="deleteRow('.$selected->id.')"><i class="fa fa-trash"></i></a>';
                    return $data;
                })
                ->editColumn('medicineCategory',function($selected){
                    if(!empty($selected->medicineCategory)){
                        return $selected->medicineCategory->name;
                    }                            
                })
                ->editColumn('status',function($selected)
                {
                    //	0-Active, 1-Inactive	
                    $data = '';
                    if($selected->status == '0'){
                        $data .= '<div class="badge badge-success">Active</div>';
                    }else if($selected->status == '1'){
                         $data .= '<div class="badge badge-danger" >Inactive</div>';                    
                    }
                    return $data;
                })
                ->rawColumns(['action','medicineCategory','status'])
                ->make(true);
    }
}