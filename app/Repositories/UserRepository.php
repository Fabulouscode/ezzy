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
   
    public $provider_name = array(
        'healthcare'=>'Health Care Provider', 
        'pharmacy'=>'Pharmacy', 
        'laboratories'=>'Laboratories',
        'patients'=>'Patients'
    );

    public function __construct()
    {
        parent::__construct();
    }

    public function getStatusValue()
    {
        return $this->model->status_value;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $data
     * @return \Illuminate\Http\Response
     */
    public function registerWithMobileno($request)
    {   
        
        // $card_number = $this->genrateCardNumber();
        // $mobile_code = $this->generateOTPCode();
        // $message = 'The OTP is '.$mobile_code.' to verify '.config('app.name').' Account.';
        // $this->sendMessage($mobile_code, $request->country_code.$request->mobile_no);

        $this->model->withTrashed()->updateOrCreate(['mobile_no' => $request->mobile_no,'country_code' => $request->country_code], [
                'otp_code' => $request->otp_code,
                'status' => $request->status,
                'deleted_at' => NULL
            ])->restore();    
    
        return $this->model->where('mobile_no', $request->mobile_no)->first();
        // if(!empty($user)){
        //     $this->model->where('mobile_no', $request->mobile_no)->update(['ezzycare_card'=> $card_number]);
        // }
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
        $this->model->withTrashed()->updateOrCreate(['mobile_no' => $request->mobile_no,'country_code' => $request->country_code], [
                'first_name' => !empty($request->first_name) ? $request->first_name : NULL,
                'last_name' => !empty($request->last_name) ? $request->last_name : NULL,    
                'email' => isset($request->email) ? $request->email : NULL,
                'password' => isset($request->password) ? Hash::make($request->password) : NULL,
                'category_id' => isset($request->category_id) ? $request->category_id : NULL,
                'subcategory_id' => isset($request->subcategory_id) ? $request->subcategory_id : NULL,
                'status' => !empty($request->category_id) ? 1 : 0,
                'device_type' => isset($request->device_type) ? $request->device_type : NULL,
                'device_token' => !empty($request->device_token) ? $request->device_token : NULL,
                'social_type'=> isset($request->social_type) ? $request->social_type : NULL,
                'facebook_id'=> !empty($request->facebook_id) ? $request->facebook_id : NULL,
                'google_id'=>!empty($request->google_id) ? $request->google_id : NULL,
                'apple_id'=> !empty($request->apple_id) ? $request->apple_id : NULL,
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

            if(is_array($request->status)){
                $query = $query->whereIn('status', $request->status);
            }else{
                $query = $query->where('status', $request->status);
            }
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
                    $data = '';
                   
                    // Edit
                    // $data .= '<a href="'.url('user/'.$selected->id.'/edit').'" class="btn btn-sm btn-info" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;';
                    
                    // View
                    if(!empty($request->provider)){
                        if (Auth::user()->hasPermissionTo($request->provider.'-list')) {
                            if (!empty($request->provider) && $request->provider == 'patients') {
                                $data .= '<a href="'.url('customer/patient/'.$selected->id).'" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a>&nbsp;&nbsp';
                            }else{
                                $data .= '<a href="'.url($request->provider.'/user/'.$selected->id).'" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a>&nbsp;&nbsp';
                            }
                        }
                        
                        if (Auth::user()->hasPermissionTo($request->provider.'-transaction')) {
                            if (!empty($request->provider) && $request->provider == 'patients') {
                                $data .=  '<a href="'.url('customer/patient/account/payment/'.$selected->id).'" class="btn btn-sm btn-success" title="User Transactions"><i class="fa fa-money"></i></a>&nbsp;&nbsp;';
                            }else{
                                $data .=  '<a href="'.url($request->provider.'/user/account/payment/'.$selected->id).'" class="btn btn-sm btn-success" title="User Transactions"><i class="fa fa-money"></i></a>&nbsp;&nbsp;';
                            }
                        }

                        if (Auth::user()->hasPermissionTo($request->provider.'-services')) {
                            if (!empty($selected->categoryParent->parent_id) && $selected->categoryParent->parent_id == '2') {
                                $data .= '<a href="'.url($request->provider.'/user/medicine/'.$selected->id).'" class="btn btn-sm btn-warning" title="Medicine Details"><i class="fa fa-shopping-bag"></i></a>&nbsp;&nbsp;';
                            }
                        }
                        if (Auth::user()->hasPermissionTo($request->provider.'-services')) {
                            if (!empty($selected->categoryParent->parent_id) && $selected->categoryParent->parent_id == '3') {
                                $data .= '<a href="'.url($request->provider.'/user/services/'.$selected->id).'" class="btn btn-sm btn-warning" title="Laboratories Services"><i class="fa fa-shopping-bag"></i></a>&nbsp;&nbsp;';
                            }
                        }
                        if (Auth::user()->hasPermissionTo($request->provider.'-services')) {
                            if (!empty($selected->categoryParent) && $selected->categoryParent->id == '6') {
                                $data .= '<a href="'.url($request->provider.'/user/services/'.$selected->id).'" class="btn btn-sm btn-warning" title="Laboratories Services"><i class="fa fa-shopping-bag"></i></a>&nbsp;&nbsp;';
                            }
                        }
                    }

                    if (Auth::user()->hasPermissionTo($request->provider.'-edit')) {
                        // Change Status
                        if (!empty($selected->status == '1')) {
                            $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-info" title="Change Status" id="status-rows" onclick="changeStatusRow('.$selected->id.',0)"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;';
                        } elseif (!empty($selected->status == '2')) {
                            $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-info" title="Change Status" id="status-rows" onclick="changeStatusRow('.$selected->id.',0)"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;';
                        } else {
                            $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-info" title="Change Status" id="status-rows" onclick="changeStatusRow('.$selected->id.',2)"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;';
                        }
                    }

                    // Delete
                    if (Auth::user()->hasPermissionTo($request->provider.'-delete')) {
                       $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-danger" title="Delete" id="delete-rows" onclick="deleteRow('.$selected->id.')"><i class="fa fa-trash"></i></a>&nbsp;&nbsp;';
                    }
                    // Show Review
                    // $data .= '<a href="'.url('users/review/'.$selected->id).'" class="btn btn-sm btn-info" title="Review"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;';
          
                    return $data;
                })
                ->editColumn('status',function($selected)
                {
                    $data = '';
                    if($selected->status == '2'){
                        $data .= '<div class="badge badge-danger">'.$selected->status_name.'</div>';
                    }else if($selected->status == '1'){
                        $data .= '<div class="badge badge-danger">'.$selected->status_name.'</div>';
                    }else{                        
                        $data .= '<div class="badge badge-success">'.$selected->status_name.'</div>';
                    }
          
                    return $data;
                })
                ->addColumn('user_name',function($selected)
                {
                     return $selected->user_name;
                })
                ->addColumn('mobile_no',function($selected)
                {
                     return $selected->mobile_no_country_code;
                })
                ->editColumn('hcp_type',function($selected){
                    $data = '';
                    if(!empty($selected->categoryParent)){
                        $data .='<div class="text-success"><strong>'. $selected->categoryParent->name.'</strong></div>';
                    }                            
                    if(!empty($selected->categoryChild)){
                        $data .='<div class="text-success"><strong>'. $selected->categoryChild->name.'</strong></div>';
                    }  
                    return $data;                          
                })
                ->rawColumns(['action','categoryParent','status','hcp_type'])
                ->make(true);
    }
    
     /**
     * Display a edit of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getbyIdedit($id)
    {   
        return $this->model->with(['userDetails','userEduction','userExperiance','userBankAccount','userLocation','userAvailableTime','categoryParent','categoryChild'])->find($id);

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
        return $this->model->where('mobile_no',$request->mobile_no)->where('country_code',$request->country_code)->whereNotIn('status',[3])->whereNotNull('mobile_verified_at')->first();
    }
 
    /**
     * Display a edit of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkbyMobileNoAndEmail($request)
    {   
        return $this->model->where(function($query) use ($request){
                        $query->orWhere('mobile_no',$request->mobile_no)->orWhere('email',$request->email);
                    })->first();
    }

    /**
     * Display a edit of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkbyMobileNoVerify($request)
    {   
        return $this->model->where('mobile_no',$request->mobile_no)->where('country_code',$request->country_code)->where('status','3')->whereNotNull('mobile_verified_at')->first();
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
        if(!empty($request->urgent)){
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
        
        // search filter
        if(isset($request->search)){
            $query = $query->where(function($query) use($request){
                $query->orWhere('first_name', 'LIKE', '%'.$request->search.'%');
                $query->orWhere('last_name', 'LIKE', '%'.$request->search.'%');
            });
        }          
        
        // rating filter
        if(isset($request->rating)){
            $query = $query->withCount(['userReview as rating' => function ($query) {
                        $query->select(DB::raw('avg(rating)'))->where('status', '0');
                    }])->havingRaw('rating >= '. $request->rating);
        }          
        
        // top listing
        if(isset($request->last_id)){            
            if(!empty($request->last_id)){
                $query = $query->where('id', '<', $request->last_id);    
            }            
            $query = $query->limit($this->api_data_limit);     
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
                           ->havingRaw('distance <= 50')
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
        if(isset($request->last_id)){
            if(!empty($request->last_id)){
                $query = $query->where('id', '<', $request->last_id);    
            }            
            $query = $query->limit($this->api_data_limit);    
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
    public function getUserParentCategoryWiseCount($category_id, $status = '')
    {

        $query = $this->model;    
        if(!empty($category_id)){
            $query = $query->whereHas('categoryParent', function ($query) use ($category_id) {
                $query->where('parent_id', $category_id);
            });
        }

        if($status != ''){
            $query = $query->where('status', $status);
        }

        $query = $query->orderBy('id','desc')->count();
        return $query;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserCategoryWiseCount($category_id, $status = '')
    {
        $query = $this->model->where('category_id',$category_id);
       
        if($status != ''){
            $query = $query->where('status', $status);
        }

        $query = $query->orderBy('id','desc')->count();
        return $query;
    }
 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPatientsCount()
    {

        $query = $this->model->where('status', '0')->whereNULL('category_id');
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