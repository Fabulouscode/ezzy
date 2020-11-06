<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\Static_pages;
use Illuminate\Support\Str;

class StaticPageRepository extends Repository
{
    protected $model_name = 'App\Models\Static_pages';
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
                    $data .= '<a href="'.url('static_pages/'.$selected->id.'/edit').'" class="btn btn-sm btn-info" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;';
                    // $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-danger" title="Delete" id="delete-rows" onclick="deleteRow('.$selected->id.')"><i class="fa fa-trash"></i></a>';
                    return $data;
                })
                ->editColumn('status',function($selected)
                {
                    //0-Active, 1-Inactive
                    $data = '';
                    if($selected->status == '0'){
                        $data .= '<div class="text-info"><strong>Active</strong></div>';
                    }else if($selected->status == '1'){
                        $data .= '<div class="text-danger"><strong>Inactive</strong></div>';
                    }
                    return $data;
                })
                ->rawColumns(['action','status'])
                ->make(true);
    }
    
}