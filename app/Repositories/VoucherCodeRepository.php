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

    public function getVoucherUsedValue()
    {
        return $this->model->voucher_used_value;
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
                        $data .= '<a href="'.url('donotezzycaretouch/voucher_code/'.$selected->id.'/edit').'" class="btn btn-sm btn-info" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;';
                    }
                    if (Auth::user()->hasPermissionTo('voucher_code-delete')) {
                        $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-danger" title="Delete" id="delete-rows" onclick="deleteRow('.$selected->id.')"><i class="fa fa-trash"></i></a>';
                    }
                    
                    return $data;
                })
                ->editColumn('status',function($selected)
                {
                    //0-Active, 1-Inactive
                    $data = '';
                    if($selected->status == '0'){
                        $data .= '<div class="badge badge-info">'.$selected->status_name.'</div>';
                    }else if($selected->status == '1'){
                        $data .= '<div class="badge badge-danger">'.$selected->status_name.'</div>';
                    }
                    return $data;
                })
                ->rawColumns(['action','status'])
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
       
        if(isset($request->voucher_code_type)){
            $query = $query->where('voucher_type', $request->voucher_code_type);    
        }
        
         // search filter
        if(!empty($request->search)){
            $query = $query->where(function($query) use($request){
                $query->orWhere('voucher_name', 'LIKE', '%'.$request->search.'%');
                $query->orWhere('voucher_code', 'LIKE', '%'.$request->search.'%');
            });
        }    

        $query = $query->limit($this->api_data_limit);     
        $query = $query->where('quantity','>','0')->where('status','0')->where('expiry_date','>=', Carbon::now());     
        
        $query = $query->orderBy('id','desc')->get();

        return $query;
    }

    /**
     * get Model and return the instance.
     *
     * @param int $ids
     */
    public function getbyIdVoucherType($id, $voucher_type)
    {
        return $this->model->where('quantity','>','0')->where('status','0')->where('expiry_date','>=', Carbon::now())->where('voucher_type', $voucher_type)->where('id', $id)->first();
        // return $this->model->where('quantity','>','0')->where('status','0')->where('expiry_date','>=', Carbon::now())->where('voucher_type', $voucher_type)->where('id', $id)->first();
    }
   
    public function getbyIdVoucherTypeget($id, $voucher_type)
    {
        return $this->model->where('id', $id)->where('voucher_type', $voucher_type)->first();
        // return $this->model->where('quantity','>','0')->where('status','0')->where('expiry_date','>=', Carbon::now())->where('voucher_type', $voucher_type)->where('id', $id)->first();
    }
}