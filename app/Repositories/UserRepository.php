<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use App\Models\City;
use App\Models\Country;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\User;
use App\Models\User_details;
use Illuminate\Support\Str;
use App\Repositories\UserTransactionRepository;
use Validator;
use DB;

class UserRepository extends Repository
{
    protected $model_name = 'App\Models\User';
    protected $model;
    private $user_transaction_repo;
   
    public $provider_name = array(
        'healthcare'=>'Health Care Provider', 
        'pharmacy'=>'Pharmacy', 
        'laboratories'=>'Laboratories',
        'patients'=>'Patients'
    );

    public function __construct(UserTransactionRepository $user_transaction_repo)
    {
        parent::__construct();
        $this->user_transaction_repo = $user_transaction_repo;
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
                'user_ip' => !empty($request->user_ip) ? $request->user_ip : null,
                'deleted_at' => NULL
            ])->restore();    
    
        return $this->model->where('mobile_no', $request->mobile_no)->where('country_code', $request->country_code)->first();
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
                'user_timezone'=> !empty(request()->header('X-TimeZone')) ? request()->header('X-TimeZone') : '',
                'user_ip' => !empty($request->user_ip) ? $request->user_ip : null,
                'deleted_at' => NULL
            ])->restore();    
    
        $user = $this->model->where('mobile_no', $request->mobile_no)->where('country_code', $request->country_code)->whereNull('ezzycare_card');
        if(!empty($user)){
            $this->model->where('mobile_no', $request->mobile_no)->where('country_code', $request->country_code)->update(['ezzycare_card'=> $card_number]);
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
        DB::enableQueryLog();
        $query = $this->model->select('users.*')->with(['categoryChild','categoryParent']);    
        if(!empty($request->category_id)){
            $query = $query->whereHas('categoryParent', function ($query) use ($request) {
                $query->where('parent_id', $request->category_id);
            });
            
            if(!empty($request->subcategory_id)){
                $query = $query->where('users.category_id', $request->subcategory_id);
            }
            
            if(is_array($request->status)){
                $query = $query->whereIn('users.status', $request->status);
            }else{
                $query = $query->where('users.status', $request->status);
            }
        }else{
            $query = $query->whereNull('users.category_id');
            $query = $query->whereNull('users.subcategory_id');
        }
        // dd($request->country_id);
        if(!empty($request->country_id)){
            $cq = Country::find($request->country_id);
            if(isset($cq)){
                $query = $query->whereHas('userDetails', function($query) use($cq){
                    $query->where('country',$cq->country_name);
                });
            }
            
        }
        
        if(!empty($request->city_id)){
            $query = $query->whereHas('userDetails',function($query) use($request){
                $query->where('city',$request->city_id);
            });
            
        }
        
        if(!empty($request->address)){
            $address = User_details::find($request->address);
            if(isset($address)){

                $query = $query->whereHas('userDetails',function($query) use($address){
                    $query->where('address',$address->address);
                });
            }
        }
        
        if(!empty($request->filter_status) || $request->filter_status == '0'){
            $query = $query->where('users.status', $request->filter_status);
        } 

        if(!empty($request->start_date) && !empty($request->end_date)){
            $query = $query->whereDate('users.created_at', '>=',$request->start_date)->whereDate('users.created_at' , '<=',$request->end_date);
        }

        if(!empty($request->user_approved_start_date) && !empty($request->user_approved_end_date)){
            $query = $query->whereDate('users.approved_date','>=',$request->user_approved_start_date)->whereDate('users.approved_date','<=',$request->user_approved_end_date);
        }

        $query = $query->leftJoin('categories as categoryParent', 'users.category_id', '=', 'categoryParent.id')
                        ->leftJoin('categories as categoryChild', 'users.subcategory_id', '=', 'categoryChild.id')
                        ->leftJoin('user_details as userDetails', 'users.id', '=', 'userDetails.user_id');
                        
       
        if(!empty($request->birth_start_date) && !empty($request->birth_end_date)){
            $query = $query->whereYear('userDetails.dob', '>=',$request->birth_start_date)->whereDate('userDetails.dob' , '<=',$request->birth_end_date);
        }

        if(!empty($request->completed_progress)){
            if($request->completed_progress == 100){
                $query = $query->where('users.completed_percentage', $request->completed_progress);
            }else{
                $query = $query->where('users.completed_percentage' , '>=',$request->completed_progress);
            }    
        }

        if(!empty($request->dob_year)){
            $query = $query->whereYear('userDetails.dob',$request->dob_year);
        }
        
        // $query = $query->orderBy('id','desc')->get();
        // print_r(DB::getQueryLog());
        // die;
        if(!empty($request->dob_month)){
            $query = $query->whereMonth('userDetails.dob',$request->dob_month);
        }
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
                    // $data .= '<a href="'.url('donotezzycaretouch/user/'.$selected->id.'/edit').'" class="btn btn-sm btn-info" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;';
                    
                    // View
                    if(!empty($request->provider)){
                        if (Auth::user()->hasPermissionTo($request->provider.'-list')) {
                            if (!empty($request->provider) && $request->provider == 'patients') {
                                $data .= '<a href="'.url('donotezzycaretouch/customer/patient/'.$selected->id).'" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a>&nbsp;&nbsp';
                                $data .= '<a href="'.url('donotezzycaretouch/customer/patient/edit/'.$selected->id).'" class="btn btn-sm btn-info" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;&nbsp';
                            }else{
                                $data .= '<a href="'.url('donotezzycaretouch/'.$request->provider.'/user/'.$selected->id).'" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a>&nbsp;&nbsp';
                                $data .= '<a href="'.url('donotezzycaretouch/'.$request->provider.'/user/edit/'.$selected->id).'" class="btn btn-sm btn-info" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;&nbsp';
                            }
                        }
                        
                        if (Auth::user()->hasPermissionTo($request->provider.'-transaction')) {
                            if($selected->status == '0' || $selected->status == '2'){
                                if (!empty($request->provider) && $request->provider == 'patients') {
                                    $data .=  '<a href="'.url('donotezzycaretouch/customer/patient/account/payment/'.$selected->id).'" class="btn btn-sm btn-success" title="User Transactions"><i class="fa fa-money"></i></a>&nbsp;&nbsp;';
                                }else{
                                    $data .=  '<a href="'.url('donotezzycaretouch/'.$request->provider.'/user/account/payment/'.$selected->id).'" class="btn btn-sm btn-success" title="User Transactions"><i class="fa fa-money"></i></a>&nbsp;&nbsp;';
                                    $data .=  '<a href="'.url('donotezzycaretouch/'.$request->provider.'/user/info/'.$selected->id).'" class="btn btn-sm btn-info" title="User Info"><i class="fa fa-info-circle"></i></a>&nbsp;&nbsp;';
                                }
                            }
                        }

                        if (Auth::user()->hasPermissionTo($request->provider.'-services')) {
                            if (!empty($selected->categoryParent->parent_id) && $selected->categoryParent->parent_id == '2') {
                                $data .= '<a href="'.url('donotezzycaretouch/'.$request->provider.'/user/medicine/'.$selected->id).'" class="btn btn-sm btn-warning" title="Medicine Details"><i class="fa fa-shopping-bag"></i></a>&nbsp;&nbsp;';
                            }
                        }
                        if (Auth::user()->hasPermissionTo($request->provider.'-services')) {
                            if (!empty($selected->categoryParent->parent_id) && $selected->categoryParent->parent_id == '3') {
                                $data .= '<a href="'.url('donotezzycaretouch/'.$request->provider.'/user/services/'.$selected->id).'" class="btn btn-sm btn-warning" title="Laboratories Services"><i class="fa fa-shopping-bag"></i></a>&nbsp;&nbsp;';
                            }
                        }
                        // if (Auth::user()->hasPermissionTo($request->provider.'-services')) {
                        //     if (!empty($selected->categoryParent) && $selected->categoryParent->id == '6') {
                        //         $data .= '<a href="'.url($request->provider.'/user/services/'.$selected->id).'" class="btn btn-sm btn-warning" title="Laboratories Services"><i class="fa fa-shopping-bag"></i></a>&nbsp;&nbsp;';
                        //     }
                        // }
                    }

                    // Change Status
                    if (Auth::user()->hasPermissionTo($request->provider.'-approval')) {                            
                        if (!empty($selected->status == '1')) {
                            $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-success" title="User Active" id="status-rows" onclick="changeStatusRow('.$selected->id.',0)"><i class="fa fa-check"></i></a>&nbsp;&nbsp;';
                        } elseif (!empty($selected->status == '2')) {
                            $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-success" title="User UnBlock" id="status-rows" onclick="changeStatusRow('.$selected->id.',0)"><i class="fa fa-check"></i></a>&nbsp;&nbsp;';
                        } else if (!empty($selected->status == '0')) {
                            $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-danger" title="User Block" id="status-rows" onclick="changeStatusRow('.$selected->id.',2)"><i class="fa fa-close"></i></a>&nbsp;&nbsp;';
                        }
                    }
              
                    //Block and unblock
                    if (Auth::user()->hasPermissionTo($request->provider.'-edit') && !empty($request->provider) && $request->provider == 'patients') {
                        if (!empty($selected->status == '2')) {
                            $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-success" title="User UnBlock" id="status-rows" onclick="changeStatusRow('.$selected->id.',0)"><i class="fa fa-check"></i></a>&nbsp;&nbsp;';
                        } else {
                            $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-danger" title="User Block" id="status-rows" onclick="changeStatusRow('.$selected->id.',2)"><i class="fa fa-close"></i></a>&nbsp;&nbsp;';
                        }
                    }

                    //Delete
                    if (Auth::user()->hasPermissionTo($request->provider.'-delete')) {
                        if (!empty($selected->category_id)) {
                            $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-danger" title="Delete" id="delete-rows" onclick="deleteRow('.$selected->id.')"><i class="fa fa-trash"></i></a>&nbsp;&nbsp;';
                        }else{
                            $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-danger" title="Delete" id="delete-rows" onclick="deleteRow('.$selected->id.')"><i class="fa fa-trash"></i></a>&nbsp;&nbsp;';
                        }
                    }
                    // Show Review
                    // $data .= '<a href="'.url('donotezzycaretouch/users/review/'.$selected->id).'" class="btn btn-sm btn-info" title="Review"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;';
          
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
                ->filterColumn('status', function ($query, $keyword) use ($request) {
                    if (in_array($request->search['value'], $this->getStatusValue())){
                        $user_status = array_search($request->search['value'], $this->getStatusValue());
                        $query->where("users.status", $user_status);                       
                    }
                })


                ->addColumn('user_name',function($selected)
                {
                     return $selected->user_name;
                })                
                ->filterColumn('user_name', function ($query, $keyword) {
                    $query->whereRaw("concat(users.first_name, ' ', users.last_name) like ?", ["%$keyword%"]);
                })
                ->orderColumn('user_name', function ($query, $order) {
                    $query->orderBy('users.first_name', $order)->orderBy('users.last_name', $order);
                })

                ->addColumn('mobile_no',function($selected)
                {
                     return $selected->mobile_no_country_code;
                })
                ->filterColumn('mobile_no', function ($query, $keyword) {
                    $query->whereRaw("concat(users.country_code, ' ', users.mobile_no) like ?", ["%$keyword%"]);
                })
                ->orderColumn('mobile_no', function ($query, $order) {
                    $query->orderBy('users.mobile_no', $order);
                })

                //wallet column
                ->addColumn('wallet_balance',function($selected)
                {
                     return $this->currency_symbol.$selected->wallet_balance;
                })
                ->filterColumn('wallet_balance', function ($query, $keyword) {
                    $query->whereRaw("users.wallet_balance like ?", ["%$keyword%"]);
                })
                ->orderColumn('wallet_balance', function ($query, $order) {
                    $query->orderBy('users.wallet_balance', $order);
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
                ->filterColumn('hcp_type', function ($query, $keyword) {
                    $query->whereRaw("concat(categoryParent.name, ' ', categoryChild.name) like ?", ["%$keyword%"]);
                })
                ->orderColumn('hcp_type', function ($query, $order) {
                    $query->orderBy('categoryParent.name', $order);
                })

                ->editColumn('created_at',function($selected){
                    return !empty($selected->created_at) ? $this->getDateTimeFormate($selected->created_at) : '-';
                })
              
                ->editColumn('dob',function($selected){
                    return (!empty($selected->userDetails) && !empty($selected->userDetails->dob)) ? $this->getDateTimeFormate($selected->userDetails->dob) : '-';
                })
                ->orderColumn('dob', function ($query, $order) {
                    $query->orderBy('userDetails.dob', $order);
                })

                ->editColumn('practicing_licence_date',function($selected){
                    if(!empty($selected->userDetails->practicing_licence_date)){
                        $expiry_days = $this->getRemainingDays($selected->userDetails->practicing_licence_date);
                        if($expiry_days > 0){
                            return '<div class="text-success">'.$this->getDateFormate($selected->userDetails->practicing_licence_date).' ('.$expiry_days.' days remains)</div>';
                        }else{
                            return '<div class="text-danger">'.$this->getDateFormate($selected->userDetails->practicing_licence_date) .' (License Expired)</div>';
                        }
                    } 
                    return  '-';
                })
                ->orderColumn('practicing_licence_date', function ($query, $order) {
                    $query->orderBy('userDetails.practicing_licence_date', $order);
                })

                ->editColumn('completed_percentage',function($selected){
                    $data = '';
                    if(!empty($selected->completed_percentage) && $selected->completed_percentage == '100'){
                        $data .='<div class="badge badge-success"><strong>'. $selected->completed_percentage.'%</strong></div>';
                    }else{
                        $data .='<div class="badge badge-warning"><strong>'. $selected->completed_percentage.'%</strong></div>';
                    }                            
                    return $data;                          
                })
                
                ->rawColumns(['action','categoryParent','status','hcp_type','practicing_licence_date','wallet_balance','dob','completed_percentage'])
                ->make(true);
    }
    
     /**
     * Display a edit of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getbyIdedit($id)
    {   
        return $this->model->with(['userDetails','userEduction','userExperiance','userBankAccount','userLocation','userAvailableTime','categoryParent','categoryChild','userOwnServices'])->find($id);

    }

    /**
     * Display a edit of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserbyCardNumber($card_number)
    {   
        return $this->model->with(['userDetails','userLabReport'])
                            ->where('ezzycare_card',$card_number)->first();

    }
   
    /**
     * Display a edit of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkUserAvailable($request)
    {  
        $day_arr = ['1','2','3','4','5'];
        $same_timing = $this->getById($request->user_id);
        $appointment_date = new Carbon($request->appointment_date);
        $appointment_day = $appointment_date->dayOfWeek;
        \Log::info("request send ".json_encode($request->all()));              
        if(in_array($appointment_day, $day_arr) && !empty($same_timing->userDetails->same_timing) && $same_timing->userDetails->same_timing != '0'){
        \Log::info("same timing ".json_encode($same_timing->userDetails->same_timing));   
            $query = $this->model->whereHas('userAvailableTime', function($query) use ($request, $appointment_day){
                            $query->where('appointment_type', $request->appointment_type);
                            $query->where('start_time', '<=' ,$request->appointment_time);
                            $query->where('end_time', '>=' ,$request->appointment_end_time);
                            $query->where('day', '7');
                            $query->where('same_timing', '1');
                            $query->where('user_id', $request->user_id);
                        });
        }else{
              \Log::info("not same timing day ".json_encode($appointment_day));   
            $query = $this->model->whereHas('userAvailableTime', function($query) use ($request, $appointment_day){
                        $query->where('appointment_type', $request->appointment_type);
                        $query->where('start_time', '<=' ,$request->appointment_time);
                        $query->where('end_time', '>=' ,$request->appointment_end_time);
                        $query->where('day', $appointment_day);
                        $query->where('same_timing', '0');
                        $query->where('user_id', $request->user_id);
                    });
        }

        $query = $query->whereHas('userDetails', function($query) use ($request){
                        $query->where('availability', '1');
                    });

        $query = $query->first();
        \Log::info("result diff ".json_encode($query));   
       
        if(empty($query)){
            if(in_array($appointment_day, $day_arr) && !empty($same_timing->userDetails->same_timing) && $same_timing->userDetails->same_timing != '0'){
                \Log::info("same timing ".json_encode($same_timing->userDetails->same_timing));   
                $query = $this->model->whereHas('userAvailableTime', function($query) use ($request, $appointment_day){
                                $query->where('appointment_type', $request->appointment_type);
                                $query->where('start_time', '<=' ,$request->appointment_time);
                                $query->where('day', '7');
                                $query->where('same_timing', '1');
                                $query->where('user_id', $request->user_id);
                            });
            }else{
                \Log::info("not same timing day ".json_encode($appointment_day));   
                $query = $this->model->whereHas('userAvailableTime', function($query) use ($request, $appointment_day){
                            $query->where('appointment_type', $request->appointment_type);
                            $query->where('start_time', '<=' ,$request->appointment_time);
                            $query->where('day', $appointment_day);
                            $query->where('same_timing', '0');
                            $query->where('user_id', $request->user_id);
                        });
            }
    
            $query = $query->whereHas('userDetails', function($query) use ($request){
                            $query->where('availability', '1');
                        });
    
            $query = $query->first();     
        }

        if(empty($query)){
            if(in_array($appointment_day, $day_arr) && !empty($same_timing->userDetails->same_timing) && $same_timing->userDetails->same_timing != '0'){
                \Log::info("same timing ".json_encode($same_timing->userDetails->same_timing));   
                $query = $this->model->whereHas('userAvailableTime', function($query) use ($request, $appointment_day){
                                $query->where('appointment_type', $request->appointment_type);
                                $query->where('end_time', '>=' ,$request->appointment_end_time);
                                $query->where('day', '7');
                                $query->where('same_timing', '1');
                                $query->where('user_id', $request->user_id);
                            });
            }else{
                \Log::info("not same timing day ".json_encode($appointment_day));   
                $query = $this->model->whereHas('userAvailableTime', function($query) use ($request, $appointment_day){
                            $query->where('appointment_type', $request->appointment_type);
                            $query->where('end_time', '>=' ,$request->appointment_end_time);
                            $query->where('day', $appointment_day);
                            $query->where('same_timing', '0');
                            $query->where('user_id', $request->user_id);
                        });
            }
    
            $query = $query->whereHas('userDetails', function($query) use ($request){
                            $query->where('availability', '1');
                        });
    
            $query = $query->first();     
        }

        \Log::info("result ".json_encode($query));     
        return $query;
    }

      /**
     * Display a edit of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkRescheduleAppointmentUserAvailable($request, $appointment)
    {   
        $day_arr = ['1','2','3','4','5'];
        $same_timing = $this->getById($appointment->user_id);

        $appointment_date = new Carbon($request->appointment_date);
        $appointment_day = $appointment_date->dayOfWeek;
        
        $start_appointment  = new Carbon($appointment->appointment_time);
        $end_appointment   = new Carbon($appointment->appointment_end_time);
        $appointment_timing_slot =  $start_appointment->diffInMinutes($end_appointment);
        $appointment_end_time_calculate = Carbon::parse($request->appointment_time)->addMinute($appointment_timing_slot)->format('H:i:s');
        $request->merge(['appointment_end_time' => $appointment_end_time_calculate]);

        \Log::info("request send ".json_encode($request->all()));              
        // \Log::info("appointment ".json_encode($appointment));              
        if(in_array($appointment_day, $day_arr) && !empty($same_timing->userDetails->same_timing) && $same_timing->userDetails->same_timing != '0'){
        \Log::info("same timing ".json_encode($same_timing->userDetails->same_timing));   
            $query = $this->model->whereHas('userAvailableTime', function($query) use ($request, $appointment){
                            $query->where('appointment_type', $appointment->appointment_type);
                            $query->where('start_time', '<=' ,$request->appointment_time);
                            $query->where('end_time', '>=' ,!empty($request->appointment_end_time) ? $request->appointment_end_time : $request->appointment_time);
                            $query->where('day', '7');
                            $query->where('same_timing', '1');
                            $query->where('user_id', $appointment->user_id);
                        });
        }else{
              \Log::info("not same timing day ".json_encode($appointment_day));   
            $query = $this->model->whereHas('userAvailableTime', function($query) use ($request, $appointment_day, $appointment){
                        $query->where('appointment_type', $appointment->appointment_type);
                        $query->where('start_time', '<=' ,$request->appointment_time);
                        $query->where('end_time', '>=' , !empty($request->appointment_end_time) ? $request->appointment_end_time : $request->appointment_time);
                        $query->where('day', $appointment_day);
                        $query->where('same_timing', '0');
                        $query->where('user_id', $appointment->user_id);
                    });
        }

        $query = $query->whereHas('userDetails', function($query) use ($request){
                        $query->where('availability', '1');
                    });

        $query = $query->first();
        \Log::info("result diff".json_encode($query));  
        if(empty($query)){
            if(in_array($appointment_day, $day_arr) && !empty($same_timing->userDetails->same_timing) && $same_timing->userDetails->same_timing != '0'){
            \Log::info("same timing ".json_encode($same_timing->userDetails->same_timing));   
                $query = $this->model->whereHas('userAvailableTime', function($query) use ($request, $appointment){
                                $query->where('appointment_type', $appointment->appointment_type);
                                $query->where('start_time', '<=' ,$request->appointment_time);
                                $query->where('day', '7');
                                $query->where('same_timing', '1');
                                $query->where('user_id', $appointment->user_id);
                            });
            }else{
                    \Log::info("not same timing day ".json_encode($appointment_day));   
                $query = $this->model->whereHas('userAvailableTime', function($query) use ($request, $appointment_day, $appointment){
                            $query->where('appointment_type', $appointment->appointment_type);
                            $query->where('start_time', '<=' ,$request->appointment_time);
                            $query->where('day', $appointment_day);
                            $query->where('same_timing', '0');
                            $query->where('user_id', $appointment->user_id);
                        });
            }
    
            $query = $query->whereHas('userDetails', function($query) use ($request){
                            $query->where('availability', '1');
                        });
    
            $query = $query->first();     
        }

        if(empty($query) && !empty($request->appointment_end_time)){
            if(in_array($appointment_day, $day_arr) && !empty($same_timing->userDetails->same_timing) && $same_timing->userDetails->same_timing != '0'){
            \Log::info("same timing ".json_encode($same_timing->userDetails->same_timing));   
                $query = $this->model->whereHas('userAvailableTime', function($query) use ($request, $appointment){
                                $query->where('appointment_type', $appointment->appointment_type);
                                $query->where('end_time', '>=' ,$request->appointment_end_time);
                                $query->where('day', '7');
                                $query->where('same_timing', '1');
                                $query->where('user_id', $appointment->user_id);
                            });
            }else{
                    \Log::info("not same timing day ".json_encode($appointment_day));   
                $query = $this->model->whereHas('userAvailableTime', function($query) use ($request, $appointment_day, $appointment){
                            $query->where('appointment_type', $appointment->appointment_type);
                            $query->where('end_time', '>=' , $request->appointment_end_time);
                            $query->where('day', $appointment_day);
                            $query->where('same_timing', '0');
                            $query->where('user_id', $appointment->user_id);
                        });
            }
    
            $query = $query->whereHas('userDetails', function($query) use ($request){
                            $query->where('availability', '1');
                        });
    
            $query = $query->first();     
        }
        \Log::info("result ".json_encode($query));  
        return $query;
    }

    /**
     * Display a edit of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkbyMobileNo($request)
    {   
        return $this->model->where('mobile_no',$request->mobile_no)->where('country_code',$request->country_code)->whereIn('status',['0','1','2'])->first();
    }
   
    /**
     * Display a edit of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkUserLocation($request)
    {   
        $query = $this->model;
        $query = $query->whereHas('userLocation', function($query) use ($request){
                        $query->where('user_id', $request->user()->id);
                        $query->where('primary_address', 1);
                    });

        $query = $query->first();
        return $query;
    }

    /**
     * Display a edit of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkbyEmailId($request)
    {   
        return $this->model->where('email',$request->email)->first();
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
   * Display a edit of the record.
   *
   * @return \Illuminate\Http\Response
   */
    public function getEmailToUser($email)
    {   
        return $this->model->where('email',$email)->first();
    }

    /**
     * Display a list of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLaboratoryProvider($request)
    {   
        \Log::info("LaboratoryProvider request ".json_encode($request->all()));   
        DB::connection()->enableQueryLog(); 
        $query = $this->model->select('users.*'); 

        $query = $query->whereHas('categoryParent', function($query){
            $query->where('parent_id', '3');
        });
        
        // edignostics
        if(!empty($request->edignostics)){
           $query = $query->whereHas('userservices', function($query) use ($request){
                        $query->whereHas('service', function($query) use ($request){
                            $query->whereRaw("FIND_IN_SET(".$request->edignostics.",sevice_usages)");
                        });
                    });
        }          
       
        // urgent and not urgent filter
        if(!empty($request->urgent)){
           $query = $query->whereHas('userDetails', function($query) use ($request){
                        $query->where('urgent', $request->urgent);
                    });
        }          
        
        // search filter
        if(isset($request->search)){
            $query = $query->where(function($query) use($request){
                $searchTerm = strtolower($request->search);
                $query->orWhereRaw('LOWER(first_name) LIKE ?', ['%' . $searchTerm . '%']);
                $query->orWhereRaw('LOWER(last_name) LIKE ?', ['%' . $searchTerm . '%']);
                $query->orWhereRaw("concat(LOWER(first_name), ' ', LOWER(last_name)) like ?", ["%".$searchTerm."%"]);
            });
        }          
        
        // rating filter
        if(isset($request->rating)){
            $query = $query->withCount(['userReview as rating' => function ($query) {
                        $query->select(DB::raw('avg(rating)'))->where('status', '0');
                    }])->havingRaw('rating >= '. $request->rating)->orderBy('rating','desc');
        }          
        
        // top listing
        if(isset($request->last_id)){            
            if(!empty($request->last_id)){
                $query = $query->where('id', '<', $request->last_id);    
            }            
            $query = $query->limit($this->api_data_limit);     
        } else{
            $query = $query->offset(0)->limit(10);  
        }         
        
        $query = $query->where('status', '0')->orderBy('id','desc')->get();
 

        return $query;
    }

    /**
     * Display a list of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getHealthcareProviders($request)
    {   
        \Log::info("HealthcareProviders request ".json_encode($request->all()));   
        // DB::connection()->enableQueryLog(); 
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
                           
            $query = $query->withCount(['userAppointmentRating as rating' => function($query){
                $query->select(DB::raw('avg(user_rating) as rating'));
            }])->orderBy('rating','desc');
        } else{
            // $query = $query->orderBy('id','desc');
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
     
        // subcategory filter
        if(!empty($request->subcategory_id)){
            $query = $query->where('subcategory_id', $request->subcategory_id);
        }          
       
        // erecommendation filter
        if(!empty($request->erecommendation)){
            $query = $query->where('id', '!=' , $request->user()->id);
        }          
        
         // consultation filter
        if(isset($request->consultation)){
            $query = $query->whereHas('userAvailableTime', function($query) use ($request){
                        $query->where('appointment_type', $request->consultation);
                    });
        }          
        // dd($request->all());
        // search filter
        if(isset($request->search)){
            $query = $query->where(function($query) use($request){
                $searchTerm = strtolower($request->search);
                $query->orWhereRaw('LOWER(first_name) LIKE ?', ['%' . $searchTerm . '%']);
                $query->orWhereRaw('LOWER(last_name) LIKE ?', ['%' . $searchTerm . '%']);
                $query->orWhereRaw("concat(LOWER(first_name), ' ', LOWER(last_name)) like ?", ["%".$searchTerm."%"]);
            });
        }          
        
        // rating filter
        if(!empty($request->rating)){
            $query = $query->withCount(['userAppointmentRating as rating' => function($query){
                $query->select(DB::raw('avg(user_rating) as rating'));
            }])->havingRaw('rating >= '. $request->rating)->orderBy('rating','desc');
            $query = $query->orderBy('id','desc');
        }
 
        // country name filter
        if(!empty($request->country_names) && is_array($request->country_names)){
            $query = $query->whereHas('userDetails', function($query) use ($request){
                $query->whereIn('country', $request->country_names);
            });
        }
        
        if((empty($request->distance) || empty($request->latitude) || empty($request->longitude)) && empty($request->rating)){
            $query = $query->withCount(['userAppointmentRating as rating' => function($query){
                $query->select(DB::raw('avg(user_rating) as rating'));
            }])->orderBy('rating','desc');
            $query = $query->orderBy('id','desc');
        }          

        //pagination
        if(isset($request->last_id)){  
            if(!empty($request->last_id)){
                $query = $query->skip($request->last_id);    
            }            
            $query = $query->limit($this->api_data_limit);    
        }
        // else{
        //      $query = $query->offset(0)->limit($this->api_data_limit);  
        // }     
        
        $query = $query->where('status', '0')->get();
 
        // print_r(DB::getQueryLog());
        // die;
        return $query;
    }
   
    /**
     * Display a list of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function getHealthcareProvidersTop($request)
    {   
        \Log::info("HealthcareProvidersTop request ".json_encode($request->all()));   
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
            $query = $query->withCount(['userAppointmentRating as rating' => function($query){
                $query->select(DB::raw('avg(user_rating) as rating'));
            }])->orderBy('rating','desc');
        } else{
            // $query = $query->orderBy('id','desc');
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
     
        // subcategory filter
        if(!empty($request->subcategory_id)){
            $query = $query->where('subcategory_id', $request->subcategory_id);
        }          
       
        // erecommendation filter
        if(!empty($request->erecommendation)){
            $query = $query->where('id', '!=' , $request->user()->id);
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
                $searchTerm = strtolower($request->search);
                $query->orWhereRaw('LOWER(first_name) LIKE ?', ['%' . $searchTerm . '%']);
                $query->orWhereRaw('LOWER(last_name) LIKE ?', ['%' . $searchTerm . '%']);
                $query->orWhereRaw("concat(LOWER(first_name), ' ', LOWER(last_name)) like ?", ["%".$searchTerm."%"]);
            });
        }          
        
        // rating filter
        if(!empty($request->rating)){
            $query = $query->withCount(['userAppointmentRating as rating' => function($query){
                $query->select(DB::raw('avg(user_rating) as rating'));
            }])->havingRaw('rating >= '. $request->rating)->orderBy('rating','desc');
            $query = $query->orderBy('id','desc');
        }   

        // country name filter
        if(!empty($request->country_names) && is_array($request->country_names)){
            $query = $query->whereHas('userDetails', function($query) use ($request){
                $query->whereIn('country', $request->country_names);
            });            
        }
        
        if((empty($request->distance) || empty($request->latitude) || empty($request->longitude)) && empty($request->rating)){
            $query = $query->withCount(['userAppointmentRating as rating' => function($query){
                $query->select(DB::raw('avg(user_rating) as rating'));
            }])->orderBy('rating','desc');
            $query = $query->orderBy('id','desc');
        }  

        // top listing
        $query = $query->offset(0)->limit(10);  
        
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
        // DB::enableQueryLog();
        \Log::info("HealthcareProvidersUrgent request ".json_encode($request->all()));
        $query = $this->model->select('users.*'); 

        // distance filter
        if(!empty($request->latitude) && !empty($request->longitude)){
    
            if(isset($request->appointment_type)){
                $query = $query->whereHas('userDetails', function ($query) use ($request) {
                    $query->whereRaw("FIND_IN_SET('".$request->appointment_type."', urgent_criteria)");
                });
            }

            $query = $query->where('category_id', '4');

            $query = $query->has('urgenAppointmentDetails', '=', 0);  
        
            $query = $query->has('nonUrgentAppointmentDetails', '=', 0);  

            if(!empty($request->distance)){
                $query = $query->addSelect(DB::raw('((ACOS(SIN('.$request->latitude.' * PI() / 180) * SIN(`users`.`current_latitude` * PI() / 180) + COS('.$request->latitude.' * PI() / 180) * COS(`users`.`current_latitude` * PI() / 180) * COS(('.$request->longitude.' - `users`.`current_longitude`) * PI() / 180)) * 180 / PI()) * 60 * 1.1515 * 1.609344) as distance '))
                ->where([
                         ['users.current_latitude', '!=', ''],
                         ['users.current_longitude', '!=', '']
                     ])
                ->havingRaw('distance <= '.$request->distance)
                ->orderBy('distance','asc');
            }else{
                $query = $query->addSelect(DB::raw('((ACOS(SIN('.$request->latitude.' * PI() / 180) * SIN(`users`.`current_latitude` * PI() / 180) + COS('.$request->latitude.' * PI() / 180) * COS(`users`.`current_latitude` * PI() / 180) * COS(('.$request->longitude.' - `users`.`current_longitude`) * PI() / 180)) * 180 / PI()) * 60 * 1.1515 * 1.609344) as distance '))
                ->where([
                         ['users.current_latitude', '!=', ''],
                         ['users.current_longitude', '!=', '']
                     ])
                ->havingRaw('distance <= 200000')
                ->orderBy('distance','asc');
            }

            $query = $query->withCount(['userAppointmentRating as rating' => function($query){
                    $query->select(DB::raw('avg(user_rating) as rating'));
                }])->orderBy('rating','desc');
        } else{

            if(isset($request->appointment_type)){
                $query = $query->whereHas('userDetails', function ($query) use ($request) {
                    $query->whereRaw("FIND_IN_SET('".$request->appointment_type."', urgent_criteria)");
                });
            }

            $query = $query->where('category_id', '4');

            $query = $query->has('urgenAppointmentDetails', '=', 0);  
        
            $query = $query->has('nonUrgentAppointmentDetails', '=', 0);  
            
             // country name filter
            if(!empty($request->country_names) && is_array($request->country_names) && isset($request->consultation) && $request->consultation == '2'){
                $query = $query->whereHas('userDetails', function($query) use ($request){
                    $query->whereIn('country', $request->country_names);
                }); 
                $query = $query->withCount(['userAppointmentRating as rating' => function($query){
                    $query->select(DB::raw('avg(user_rating) as rating'));
                }])->orderBy('rating','desc');
                $query = $query->orderBy('id','desc');
        
            }else if(!empty($request->user()->latitude) && !empty($request->user()->longitude)){

                $query = $query->addSelect(DB::raw('((ACOS(SIN('.$request->user()->latitude.' * PI() / 180) * SIN(`users`.`current_latitude` * PI() / 180) + COS('.$request->user()->latitude.' * PI() / 180) * COS(`users`.`current_latitude` * PI() / 180) * COS(('.$request->user()->longitude.' - `users`.`current_longitude`) * PI() / 180)) * 180 / PI()) * 60 * 1.1515 * 1.609344) as distance '))
                            ->where([
                                        ['users.current_latitude', '!=', ''],
                                        ['users.current_longitude', '!=', '']
                                    ])
                            ->havingRaw('distance <= 200000')
                            ->orderBy('distance','asc');
                
            }else{
                $query = $query->withCount(['userAppointmentRating as rating' => function($query){
                    $query->select(DB::raw('avg(user_rating) as rating'));
                }])->orderBy('rating','desc');
                $query = $query->orderBy('id','desc');
            }
       
        }         
        
        
        // urgent and not urgent filter
        $query = $query->whereHas('userDetails', function($query) use ($request){
                        $query->where('urgent', '1');
                    });
        
         // category filter
        if(!empty($request->category_id)){
            $query = $query->where('category_id', $request->category_id);
        }      
        
         // subcategory filter
        if(!empty($request->subcategory_id)){
            $query = $query->where('subcategory_id', $request->subcategory_id);
        }  
        
         // consultation filter
        if(isset($request->consultation)){
            $query = $query->whereHas('userAvailableTime', function($query) use ($request){
                        $query->where('appointment_type', $request->consultation);
                    });
        }                
        
        // country name filter
        if(!empty($request->country_names) && is_array($request->country_names)){
            $query = $query->whereHas('userDetails', function($query) use ($request){
                $query->whereIn('country', $request->country_names);
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

        $current_time  =  Carbon::now();
        $current_time = $current_time->subHour(12);
        $current_time = $current_time->format('Y-m-d H:i:s');   

        // $query = $query->where('users.status', '0')->where('users.updated_at', '<=', $current_time)->get();
        $query = $query->where('users.status', '0')->get();
        // print_r(DB::getQueryLog());
        // die;
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
    public function getUserSubCategoryWiseCount($subcategory_id, $status = '')
    {
        $query = $this->model;    
        if(!empty($subcategory_id)){
            $query = $query->whereHas('categoryChild', function ($query) use ($subcategory_id) {
                $query->where('id', $subcategory_id);
            });
        }

        if($status != ''){
            $query = $query->where('status', $status);
        }

        $query = $query->whereNotNull('subcategory_id')->orderBy('id','desc')->count();
        return $query;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserParentCategoryWiseCountToday($category_id, $status = '')
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

        $query = $query->whereDate('created_at',Carbon::now())->orderBy('id','desc')->count();
        return $query;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserParentCategoryWiseCountApprovedToday($category_id, $status = '')
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

        $query = $query->whereDate('approved_date',Carbon::now())->orderBy('id','desc')->count();
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
    public function getUserCategoryWiseCountToday($category_id, $status = '')
    {
        $query = $this->model->where('category_id',$category_id);
       
        if($status != ''){
            $query = $query->where('status', $status);
        }

        $query = $query->whereDate('created_at',Carbon::now())->orderBy('id','desc')->count();
        return $query;
    }
 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPatientsCount()
    {

        $query = $this->model->whereNULL('category_id');
        $query = $query->orderBy('id','desc')->count();
        return $query;
    }
 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPatientsCountToday()
    {

        $query = $this->model->whereNULL('category_id');
        $query = $query->whereDate('created_at',Carbon::now())->orderBy('id','desc')->count();
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

    /**
     * find nerest healthcare provider.
     *
     * @return \Illuminate\Http\Response
     */
    public function findHealthcareProvider($request)
    {
        $query = $this->model;   
        $query = $query->whereHas('categoryParent', function ($query) {
                $query->where('urgent', '1');
            });

        $query = $query->orderBy('appointment_date','desc')->get();
        return $query;
    }

    /**
     * find nerest healthcare provider.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllHealthcareProvider($category_id = '')
    {
        $query = $this->model;   
        $query = $query->whereNotNull('category_id');

        if(!empty($category_id) && $category_id != '0'){
            $query = $query->where('category_id', $category_id);
        }

        $query = $query->orderBy('first_name','asc')->get();
        return $query;
    }
   
    public function userWalletUpdate($user_id)
    {
        $wallet_balance = $this->user_transaction_repo->checkPatientWalletBalance($user_id); 
        $lock_wallet_balance = $this->user_transaction_repo->checkPatientWalletLockBalance($user_id); 
        $update = ['wallet_balance'=> $wallet_balance, 'lock_wallet_balance'=> $lock_wallet_balance];
        $this->dataCrudUsingData($update, $user_id);     
        return true;    
    }

    function genrateInterSwitchRefrenceNo() 
    {     
        $interSwitch =  date('ymdhi') . rand(111111111, 999999999);
        $validator = Validator::make(
            [
                'payment_gateway_response' => $interSwitch
            ],
            [
                'payment_gateway_response' => 'required|unique:user_transactions',
            ]
        );
        if ($validator->fails()) {
           self::genrateInterSwitchRefrenceNo();
        }
        return $interSwitch; 
    } 

    function getIpRegisterdAndLoginIp($ip) 
    {     
        $query = $this->model->where('user_ip', $ip);
        $query = $query->orderBy('id','desc')->count();
        return $query;
    } 

    public function getCompletedProfileUserParentCategoryWiseCount($category_id)
    {
        $query = User::query();  
        if(!empty($category_id)){
            $query = $query->whereHas('categoryParent', function ($query) use ($category_id) {
                $query->where('parent_id', $category_id);
            });
        }

        $query = $query->where('status', 1);

        $query = $query->where('completed_percentage', 100);

        $query = $query->orderBy('id','desc')->count();

        return $query;
    }
}