<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\User;
use Illuminate\Support\Str;

class UserRepository extends Repository
{
    protected $model_name = 'App\Models\User';
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
    public function dataCrud($request, $id = '')
    {   $data = [];
        if(!empty($id)){
            return $this->update($data, $id);
        } else {
            return $this->store($data);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getWithRelationship()
    {
        return $this->model->with(['categoryParent','categoryChild'])->get();
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
                    // $data .= '<a href="'.url('user/'.$selected->id.'/edit').'" class="btn btn-sm btn-outline-info" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;';
                    $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-outline-danger" title="Delete" id="delete-rows" onclick="deleteRow('.$selected->id.')"><i class="fa fa-trash"></i></a>';
                    return $data;
                })
                ->addColumn('status',function($selected)
                {
                    $data = '';
                    if($selected->status == '0'){
                        $data .= '<div class="text-success"><strong>Active</strong></div>';
                    }else {
                        $data .= '<div class="text-danger"><strong>Pending</strong></div>';
                    }
                    //  $data .= '<div class="text-danger" ><strong>Inactive</strong></div>';
                    return $data;
                })
                // ->addColumn('actiondetails',function($selected)
                // {
                //     $data = '';
                //     $data .= '<a href="'.url('user/'.$selected->id.'/edit').'" class="btn btn-sm btn-outline-info" title="User Education"><i class="fa fa-graduation-cap"></i></a>&nbsp;&nbsp;';
                //     $data .= '<a href="'.url('user/'.$selected->id.'/edit').'" class="btn btn-sm btn-outline-info" title="User Experiance"><i class="fa fa-user-circle-o"></i></a>&nbsp;&nbsp;';
                //     $data .= '<a href="'.url('user/'.$selected->id.'/edit').'" class="btn btn-sm btn-outline-info" title="User Bank Account"><i class="fa fa-bank"></i></a>&nbsp;&nbsp;';
                //     return $data;
                // })
                ->addColumn('categoryParent',function($selected){
                    if(!empty($selected->categoryParent)){
                        return $selected->categoryParent->name;
                    }                            
                })
                ->addColumn('categoryChild',function($selected){
                    if(!empty($selected->categoryChild)){
                        return $selected->categoryChild->name;
                    }                            
                })
                ->rawColumns(['action','categoryParent','categoryChild','status'])
                ->make(true);
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $data
     * @return \Illuminate\Http\Response
     */
    public function getbyIdedit($id)
    {   
        return $this->model->with(['userDetails','userEduction','userExperiance','userBankAccount'])->find($id);

    }

}