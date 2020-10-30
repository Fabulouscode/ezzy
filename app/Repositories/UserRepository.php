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
use DB;

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
        $mobile_code = $this->generateOTPCode();
        $message = 'The OTP is '.$mobile_code.' to verify '.config('app.name').' Account.';
        $this->sendMessage($mobile_code, $request->country_code.$request->mobile_no);

        $this->model->withTrashed()->updateOrCreate(['mobile_no' => $request->mobile_no,'country_code' => $request->country_code], [
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'category_id' => isset($request->category_id) ? $request->category_id : NULL,
                'subcategory_id' => isset($request->subcategory_id) ? $request->subcategory_id : NULL,
                'otp_code' => $mobile_code,
                'status' => !empty($request->category_id) ? 1 : 0,
                'device_type' => !empty($request->device_type) ? $request->device_type : NULL,
                'device_token' => !empty($request->device_token) ? $request->device_token : NULL,
                'deleted_at' => NULL
            ])->restore();    
    
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
    public function dataCrudUsingData($data, $id = '')
    {   
        if(!empty($id)){
            return $this->update($data, $id);
        } else {
            return $this->store($data);
        }
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $data
     * @return \Illuminate\Http\Response
     */
    public function dataCrud($request, $id = '')
    {   $data = array();
        if(!empty($request)){
            $filter = $request->all();
            foreach ($filter as $key => $value) {
                $data[$key] = $value;
            }
        }
        if(!empty($id)){
            return $this->update($data, $id);
        } else {
            return $this->store($data);
        }
    }

    /**
     * remove oauth access tokens in db.
     *
     * @param  \Illuminate\Http\Request  $data
     * @return \Illuminate\Http\Response
     */
    public function removeOauthAccessTokens($user_id)
    {  
        DB::table('oauth_access_tokens')->where('user_id', $user_id)->delete();
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
                ->addColumn('action',function($selected) use ($request)
                {
                    $change_status = $selected->status == '1' ? 0 : 1;
                    $data = '';
                   
                    // Edit
                    // $data .= '<a href="'.url('user/'.$selected->id.'/edit').'" class="btn btn-sm btn-outline-info" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;';
                    
                    // View
                    if(!empty($request->provider)){
                        $data .= '<a href="'.url($request->provider.'/user/'.$selected->id).'" class="btn btn-sm btn-outline-info" title="View"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;';
                    }

                    // Delete
                    // $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-outline-danger" title="Delete" id="delete-rows" onclick="deleteRow('.$selected->id.')"><i class="fa fa-trash"></i></a>&nbsp;&nbsp;';
                    
                    // Change Status
                    $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-outline-warning" title="Change Status" id="status-rows" onclick="changeStatusRow('.$selected->id.','.$change_status.')"><i class="fa fa-user-circle-o"></i></a>&nbsp;&nbsp;';
                  
                    // Show Review
                    // $data .= '<a href="'.url('users/review/'.$selected->id).'" class="btn btn-sm btn-outline-info" title="Review"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;';
          
                    return $data;
                })
                ->editColumn('status',function($selected)
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
                ->editColumn('categoryParent',function($selected){
                    if(!empty($selected->categoryParent)){
                        return $selected->categoryParent->name;
                    }                            
                })
                ->editColumn('categoryChild',function($selected){
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
    public function getUserbyCardNumber($card_number)
    {   
        return $this->model->with(['userDetails','userEduction','userExperiance','userBankAccount','userAvailableTime'])
                            ->where('ezzycare_card',$card_number)->first();

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
     * Display a list of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getHealthcareProviders($request)
    {   
        $query = $this->model->select('users.*'); 

        // distance filter
        if(!empty($request->distance) && !empty($request->latitude) && !empty($request->longitude)){
            $query = $query->addSelect(DB::raw('((ACOS(SIN('.$request->latitude.' * PI() / 180) * SIN(`users`.`latitude` * PI() / 180) + COS('.$request->latitude.' * PI() / 180) * COS(`users`.`latitude` * PI() / 180) * COS(('.$request->longitude.' - `users`.`longitude`) * PI() / 180)) * 180 / PI()) * 60 * 1.1515 * 1.609344) as distance '))
                           ->where([
                                    ['users.latitude', '!=', ''],
                                    ['users.longitude', '!=', '']
                                ])
                           ->havingRaw('distance <= '. $request->distance)
                           ->orderBy('distance','asc');
        } else{
            $query = $query->orderBy('id','desc');
        }         
        
        
        // urgent and not urgent filter
        if(isset($request->urgent)){
           $query = $query->whereHas('userDetails', function($query) use ($request){
                        $query->where('urgent', $request->urgent);
                    });
        }          
        
         // category filter
        if(!empty($request->category_id)){
            $query = $query->where('category_id', $request->category_id);
        }          
        
         // consultation filter
        if(isset($request->consultation)){
            $query = $query->whereHas('userAvailableTime', function($query) use ($request){
                        $query->where('appointment_type', $request->consultation);
                    });
        }          
        
        // rating filter
        if(isset($request->rating)){
            $query = $query->withCount(['userReview as rating' => function ($query) {
                        $query->select(DB::raw('avg(rating)'))->where('status', '0');
                    }])->havingRaw('rating >= '. $request->rating);
        }          
        
        // top listing
        if(isset($request->offset)){
            $offset = $request->offset * $this->api_data_limit;
            $query = $query->offset($offset)->limit($this->api_data_limit);   
        } else{
            $query = $query->offset(0)->limit(5);  
        }         
        
        $query = $query->where('status', '0')->get();
        
        return $query;
    }

    /**
     * Display a list of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getHealthcareProvidersUrgent($request)
    {   
        $query = $this->model->select('users.*'); 

        // distance filter
        if(!empty($request->latitude) && !empty($request->longitude)){
            $query = $query->addSelect(DB::raw('((ACOS(SIN('.$request->latitude.' * PI() / 180) * SIN(`users`.`latitude` * PI() / 180) + COS('.$request->latitude.' * PI() / 180) * COS(`users`.`latitude` * PI() / 180) * COS(('.$request->longitude.' - `users`.`longitude`) * PI() / 180)) * 180 / PI()) * 60 * 1.1515 * 1.609344) as distance '))
                           ->where([
                                    ['users.latitude', '!=', ''],
                                    ['users.longitude', '!=', '']
                                ])
                           ->havingRaw('distance <= 10')
                           ->orderBy('distance','asc');
        } else{
            $query = $query->orderBy('id','desc');
        }         
        
        
        // urgent and not urgent filter
        $query = $query->whereHas('userDetails', function($query) use ($request){
                        $query->where('urgent', '1');
                    });
        
         // category filter
        if(!empty($request->category_id)){
            $query = $query->where('category_id', $request->category_id);
        }          
        
         // consultation filter
        if(isset($request->consultation)){
            $query = $query->whereHas('userAvailableTime', function($query) use ($request){
                        $query->where('appointment_type', $request->consultation);
                    });
        }                
        
        // top listing
        if(isset($request->offset)){
            $offset = $request->offset * $this->api_data_limit;
            $query = $query->offset($offset)->limit($this->api_data_limit);   
        } else{
            $query = $query->offset(0)->limit(5);  
        }         
        
        $query = $query->where('status', '0')->get();
        
        return $query;
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


    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserCategoryWiseApprovedCount($category_id)
    {

        $query = $this->model->where('status', '0');    
        if(!empty($category_id)){
            $query = $query->whereHas('categoryParent', function ($query) use ($category_id) {
                $query->where('parent_id', $category_id);
            });
        }
        $query = $query->orderBy('id','desc')->count();
        return $query;
    }
  
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserCategoryWisePendingCount($category_id)
    {

        $query = $this->model->where('status', '1');    
        if(!empty($category_id)){
            $query = $query->whereHas('categoryParent', function ($query) use ($category_id) {
                $query->where('parent_id', $category_id);
            });
        }
        $query = $query->orderBy('id','desc')->count();
        return $query;
    }

}