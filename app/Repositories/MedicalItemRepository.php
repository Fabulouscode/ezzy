<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\Medical_item;
use Illuminate\Support\Str;

class MedicalItemRepository extends Repository
{
    protected $model_name = 'App\Models\Medical_item';
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
     * get Model and return the instance.
     *
     * @param int $medical_category_id
     */
    public function getbyMedicalCategoryId($id)
    {
        return $this->model->where('medical_category_id', $id)->where('status','0')->get();
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
                    if (Auth::user()->hasPermissionTo('medical_item-edit')) {
                        $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-info" title="Edit" id="edit-rows" onclick="editRow('.$selected->id.')"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;';
                    }
                    if (Auth::user()->hasPermissionTo('medical_item-delete')) {
                        $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-danger" title="Delete" id="delete-rows" onclick="deleteRow('.$selected->id.')"><i class="fa fa-trash"></i></a>';
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
                         $data .= '<div class="badge badge-danger" >'.$selected->status_name.'</div>';                    
                    }
                    return $data;
                })
                ->editColumn('medical_category',function($selected)
                {
                    if(!empty($selected->medicalCategory)){
                       return $selected->medicalCategory->medical_category_name;
                    }
                    return '';
                })
                ->rawColumns(['action','medical_category','status'])
                ->make(true);
    }
    
}