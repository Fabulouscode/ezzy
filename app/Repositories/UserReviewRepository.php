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
    {   
        if(!empty($request)){
            if(!empty($id)){
                return $this->update($data, $id);
            } else {
                return $this->model->updateOrCreate([
                                                        'user_id'=>$request->user_id,
                                                        'client_id'=>$request->user()->id
                                                    ], 
                                                    [
                                                        'comment'=>$request->comment,
                                                        'rating'=>$request->rating,
                                                        'review_date'=>Carbon::now(),
                                                        'status'=>'0'
                                                    ]);
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
                    $data .= '<a href="'.url('user/review/'.$selected->id).'" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;';
                    $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-danger" title="Delete" id="delete-rows" onclick="deleteRow('.$selected->id.')"><i class="fa fa-trash"></i></a>';
                    return $data;
                })
                ->editColumn('status',function($selected)
                {
                    //0-Pending, 1-Success, 2-Cancel	
                    $data = '';
                    if($selected->status == '0'){
                        $data .= '<div class="badge badge-info">Active</div>';
                    }else if($selected->status == '1'){
                        $data .= '<div class="badge badge-success">Inactive</div>';
                    }
                    return $data;
                })
                ->editColumn('comment',function($selected)
                {
                    $data = '';
                    if(!empty($selected->comment)){
                       $data = strlen($selected->comment) > 50 ? substr($selected->comment,0,50)."..." : $selected->comment;
                    }
                    return $data;
                })
                ->editColumn('userDetails_email',function($selected){
                    if(!empty($selected->userDetails)){
                        return $selected->userDetails->email;
                    }                            
                })
                ->editColumn('userDetails_mobile',function($selected){
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