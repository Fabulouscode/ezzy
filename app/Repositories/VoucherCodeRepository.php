<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\Voucher_code;
use Illuminate\Support\Str;

class VoucherCodeRepository extends Repository
{
    protected $model_name = 'App\Models\Voucher_code';
    protected $model;

    public function __construct()
    {
        parent::__construct();
    }

    public function getStatusValue()
    {
        return $this->model->status_value;
    }

    public function getVoucherTypeValue()
    {
        return $this->model->voucher_type_value;
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
                    if (Auth::user()->hasPermissionTo('voucher_code-edit')) {
                        $data .= '<a href="'.url('voucher_code/'.$selected->id.'/edit').'" class="btn btn-sm btn-info" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;';
                    }
                    if (Auth::user()->hasPermissionTo('voucher_code-delete')) {
                        $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-danger" title="Delete" id="delete-rows" onclick="deleteRow('.$selected->id.')"><i class="fa fa-trash"></i></a>';
                    }
                    
                    return $data;
                })
                ->rawColumns(['action'])
                ->make(true);
    }


         /**
     * Display a list of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getVoucherCodeList($request)
    {   
        $query = $this->model;
        
        if(!empty($request->last_id)){
            $query = $query->where('id', '<', $request->last_id);    
        }
        
        $query = $query->limit($this->api_data_limit);     
        
        $query = $query->orderBy('id','desc')->get();

        return $query;
    }
}