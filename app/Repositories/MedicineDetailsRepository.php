<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\Medicine_details;
use Illuminate\Support\Str;

class MedicineDetailsRepository extends Repository
{
    protected $model_name = 'App\Models\Medicine_details';
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
     * Display a edit of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getbyIdedit($id)
    {   
        return $this->model->with(['medicineImages','medicineCategory'])->find($id);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getWithRelationship()
    {
        $query = $this->model->select('medicine_details.*')->with(['medicineCategory']);
        $query = $query->leftJoin('medicine_categories', 'medicine_details.medicine_category_id', '=', 'medicine_categories.id');
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
                    if (Auth::user()->hasPermissionTo('medicine_details-edit')) {
                         $data .= '<a href="'.url('donotezzycaretouch/medicine/details/'.$selected->id.'/edit').'" class="btn btn-sm btn-info" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;';
                    }
                    if (Auth::user()->hasPermissionTo('medicine_details-delete')) {
                          $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-danger" title="Delete" id="delete-rows" onclick="deleteRow('.$selected->id.')"><i class="fa fa-trash"></i></a>';
                    }

                    return $data;
                })
                
                ->editColumn('medicine_category',function($selected)
                {
                    $data = '';
                    if(!empty($selected->medicineCategory->name)){
                        $data .= $selected->medicineCategory->name;
                    } 
                    
                    return $data;
                })
                ->filterColumn('medicine_category', function ($query, $keyword) {
                    $query->whereRaw("medicine_categories.name like ?", ["%$keyword%"]);
                }) 
                ->orderColumn('medicine_category', function ($query, $order) {
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
                        $appointment_status = array_search($request->search['value'], $this->getStatusValue());
                        $query->where("medicine_details.status", $appointment_status);                       
                    }
                })

                ->rawColumns(['action','medicine_category','status'])
                ->make(true);
    }

    /**
     * Display a list of Completed Appointment record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMedicineDetailsWithSearch($request)
    {   
        $query = $this->model;
        
        if(!empty($request->last_id)){
            $query = $query->where('id', '<', $request->last_id);    
        }
        
        $query = $query->limit($this->api_data_limit);     
        
        if(!empty($request->search)){
            $query = $query->where('medicine_name', 'like', $request->search.'%');
        }
        
        $query = $query->where('status', '0')->orderBy('medicine_name','asc')->get();

        return $query;
    }

    
}