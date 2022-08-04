<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\Manage_fees;
use Illuminate\Support\Str;

class ManageFeesRepository extends Repository
{
    protected $model_name = 'App\Models\Manage_fees';
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
     * @param int $CategoryId
     */
    public function getbyCategoryId($category_id)
    {
        return $this->model->where('category_id', $category_id)->first();
    }
   
    /**
     * get Model and return the instance.
     *
     * @param int $CategoryId
     */
    public function getbyFeesKey($fees_key)
    {
        return $this->model->where('fees_key', $fees_key)->first();
    }
    
    /**
     * get Model and return the instance.
     *
     * @param int $CategoryId
     */
    public function getWithRelationship()
    {
        $query = $this->model->with(['category']);    
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
                    if (Auth::user()->hasPermissionTo('fees-edit')) {
                        $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-info" title="Edit" id="edit-rows" onclick="editRow('.$selected->id.')"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;';
                    }
                    if (Auth::user()->hasPermissionTo('fees-delete')) {
                        // $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-danger" title="Delete" id="delete-rows" onclick="deleteRow('.$selected->id.')"><i class="fa fa-trash"></i></a>';
                    }
                    return $data;
                })
                ->editColumn('fees_percentage',function($selected)
                {
                    if($selected->fees_type == '1'){
                       return $selected->fees_percentage;
                    }else {
                        return '-';
                    }
                    return '';
                })
                ->editColumn('fees_amount',function($selected)
                {
                    if($selected->fees_type == '0'){
                       return $selected->fees_percentage;
                    }else {
                        return '-';
                    }
                    return '';
                })
                ->editColumn('category',function($selected)
                {
                    if(!empty($selected->category)){
                       return $selected->category->name .' HCP Type';
                    }else if($selected->fees_name){
                        return $selected->fees_name;
                    }
                    return '';
                })
                ->rawColumns(['action','fees_amount'])
                ->make(true);
    }
    
}