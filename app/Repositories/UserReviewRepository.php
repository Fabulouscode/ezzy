<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User_review;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class UserReviewRepository extends Repository
{
    protected $model_name = 'App\Models\User_review';
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
        $query = $this->model->orderBy('id','desc')->get();
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
                    $data .= '<a href="'.url('user/review/'.$selected->id).'" class="btn btn-sm btn-outline-info" title="View"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;';
                    $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-outline-danger" title="Delete" id="delete-rows" onclick="deleteRow('.$selected->id.')"><i class="fa fa-trash"></i></a>';
                    return $data;
                })
                ->addColumn('status',function($selected)
                {
                    //0-Pending, 1-Success, 2-Cancel	
                    $data = '';
                    if($selected->status == '0'){
                        $data .= '<div class="text-info"><strong>Active</strong></div>';
                    }else if($selected->status == '1'){
                        $data .= '<div class="text-success"><strong>Inactive</strong></div>';
                    }
                    return $data;
                })
                ->addColumn('comment',function($selected)
                {
                    $data = '';
                    if(!empty($selected->comment)){
                       $data = strlen($selected->comment) > 50 ? substr($selected->comment,0,50)."..." : $selected->comment;
                    }
                    return $data;
                })
                ->addColumn('userDetails_email',function($selected){
                    if(!empty($selected->userDetails)){
                        return $selected->userDetails->email;
                    }                            
                })
                ->addColumn('userDetails_mobile',function($selected){
                    if(!empty($selected->userDetails)){
                        return $selected->userDetails->mobile_no;
                    }                            
                })
                ->rawColumns(['action','comment','status','userDetails_email','userDetails_mobile'])
                ->make(true);
    }

    /**
     * Display a edit of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getbyIdedit($id)
    {   
        return $this->model->with(['userDetails'])->find($id);

    }
}