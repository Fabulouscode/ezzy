<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\ContactDetails;
use Illuminate\Support\Str;

class ContactDetailsRepository extends Repository
{
    protected $model_name = 'App\Models\ContactDetails';
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
    public function getWithRelationship($request)
    {
        $query = $this->model;    

        if(!empty($request->start_date) && !empty($request->end_date)){
            $query = $query->whereDate('created_at', '>=',$request->start_date)->whereDate('created_at' , '<=',$request->end_date);
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
                ->addColumn('action',function($selected)
                {
                    $data = '';
                    $data .= '<a href="'.url('donotezzycaretouch/contact_form/'.$selected->id).'" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a>&nbsp;&nbsp';
                    $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-danger" title="Delete" id="delete-rows" onclick="deleteRow('.$selected->id.')"><i class="fa fa-trash"></i></a>';
                    return $data;
                })
                ->rawColumns(['action'])
                ->make(true);;
    }
    
}