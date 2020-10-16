<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\User;
use Illuminate\Support\Str;
use Validator;

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
    public function getWithRelationship($request)
    {

        $query = $this->model->with(['categoryChild','categoryParent']);    
        if(!empty($request->category_id)){
            $query = $query->whereHas('categoryParent', function ($query) use ($request) {
                $query->where('parent_id', $request->category_id);
            });
            $query = $query->where('status', $request->status);
        }else{
            $query = $query->whereNull('category_id');
            $query = $query->whereNull('subcategory_id');
        }
        $query->orderBy('id','desc')->get();
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
                ->addIndexColumn()
                ->addColumn('action',function($selected)
                {
                    $change_status = $selected->status == '1' ? 0 : 1;
                    $data = '';
                   
                    // Edit
                    // $data .= '<a href="'.url('user/'.$selected->id.'/edit').'" class="btn btn-sm btn-outline-info" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;';
                    
                    // View
                    $data .= '<a href="'.url('user/'.$selected->id).'" class="btn btn-sm btn-outline-info" title="View"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;';
                   
                    // Delete
                    // $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-outline-danger" title="Delete" id="delete-rows" onclick="deleteRow('.$selected->id.')"><i class="fa fa-trash"></i></a>';
                    
                    // Change Status
                    $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-outline-warning" title="Change Status" id="status-rows" onclick="changeStatusRow('.$selected->id.','.$change_status.')"><i class="fa fa-user-circle-o"></i></a>';
                    
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
     * Display a edit of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getbyIdedit($id)
    {   
        return $this->model->with(['userDetails','userEduction','userExperiance','userBankAccount'])->find($id);

    }

    /**
     * generate card no for eazzy care card.
     *
     * @return \Illuminate\Http\Response
     */   
    function genrateCardNumber() 
    {     
        $length = 12;
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; 
        $card_number = 'EAZZY_'.substr(str_shuffle($str_result), 0, $length);
        $validator = Validator::make(
            [
                'eazzycare_card' => $card_number
            ],
            [
                'eazzycare_card' => 'required|unique:users',
            ]
        );
        if ($validator->fails()) {
            self::genrateCardNumber();
        }
        return $card_number; 
    } 

}