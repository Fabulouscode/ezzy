<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Illuminate\Support\Facades\Hash;
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

    public $days = array(
        '0'=>'Sunday', 
        '1'=>'Monday', 
        '2'=>'Tuesday', 
        '3'=>'Wednesday', 
        '4'=>'Thursday', 
        '5'=>'Friday', 
        '6'=>'Saturday'
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
    public function registerWithRestore($request)
    {   
        $card_number = $this->genrateCardNumber();
        $mobile_code = rand(1000, 9999);
        $this->model->withTrashed()->updateOrCreate(['mobile_no' => $request->mobile_no,'country_code' => $request->country_code], [
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'category_id' => isset($request->category_id) ? $request->category_id : NULL,
                'subcategory_id' => isset($request->subcategory_id) ? $request->subcategory_id : NULL,
                'otp_code' => $mobile_code,
                'deleted_at' => NULL
            ])->restore();    
    
         $message = 'The OTP is '.$mobile_code.' to verify '.config('app.name').' Account.';
        $this->sendMessage($mobile_code, '+'.$request->country_code.$request->mobile_no);
        $user = $this->model->where('mobile_no', $request->mobile_no)->whereNull('ezzycare_card');
        if(!empty($user)){
            $this->model->where('mobile_no', $request->mobile_no)->update(['ezzycare_card'=> $card_number]);
        }

    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $data
     * @return \Illuminate\Http\Response
     */
    public function dataCrud($data, $id = '')
    {   if(!empty($id)){
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
                    // $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-outline-danger" title="Delete" id="delete-rows" onclick="deleteRow('.$selected->id.')"><i class="fa fa-trash"></i></a>&nbsp;&nbsp;';
                    
                    // Change Status
                    $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-outline-warning" title="Change Status" id="status-rows" onclick="changeStatusRow('.$selected->id.','.$change_status.')"><i class="fa fa-user-circle-o"></i></a>&nbsp;&nbsp;';
                  
                    // Show Review
                    $data .= '<a href="'.url('users/review/'.$selected->id).'" class="btn btn-sm btn-outline-info" title="Review"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;';
          
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
        return $this->model->with(['userDetails','userEduction','userExperiance','userBankAccount','userAvailableTime'])->find($id);

    }

    /**
     * Display a edit of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkbyMobileNo($request)
    {   
        return $this->model->where('mobile_no',$request->mobile_no)->where('country_code',$request->country_code)->get();
    }

    /**
     * Display a edit of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getbyMobileNo($request)
    {   
        return $this->model->where('mobile_no',$request->mobile_no)->where('country_code',$request->country_code)->first();
    }
    
    /**
     * Display a list of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getbyIdUserDetails($id)
    {   
        return $this->model->with(['userDetails'])->find($id);
    }

     /**
     * Display a list of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getbyUserIdBankDetails($id)
    {   
        return $this->model->with(['userBankAccount'])->find($id);
    }
   
    /**
     * Display a list of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getbyUserIdEductionDetails($id)
    {   
        return $this->model->with(['userEduction'])->find($id);
    }

    /**
     * generate card no for ezzy care card.
     *
     * @return \Illuminate\Http\Response
     */   
    function genrateCardNumber() 
    {     
        $length = 10;
        $str_result = '0123456789'; 
        $card_number = 'EZZY_'.substr(str_shuffle($str_result), 0, $length);
        $validator = Validator::make(
            [
                'ezzycare_card' => $card_number
            ],
            [
                'ezzycare_card' => 'required|unique:users',
            ]
        );
        if ($validator->fails()) {
            self::genrateCardNumber();
        }
        return $card_number; 
    } 

}