<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\UserTransactionRepository;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\UserDetailsRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\AppointmentRepository;
use App\Repositories\ShopMedicineDetailsRepository;
use App\Repositories\UserServiceRepository;
use Auth;
use Carbon\Carbon;
use DB;

class UserController extends Controller
{

    private $user_repo, $category_repo, $user_details_repo, $appointment_repo, $user_trans_repo, $shop_medicine_repo, $user_service_repo;

    public function __construct(
        UserRepository $user_repo, 
        UserDetailsRepository $user_details_repo, 
        CategoryRepository  $category_repo, 
        AppointmentRepository $appointment_repo,
        UserTransactionRepository $user_trans_repo,
        ShopMedicineDetailsRepository $shop_medicine_repo,
        UserServiceRepository $user_service_repo
        )
    {
        $this->user_repo = $user_repo;
        $this->category_repo = $category_repo;
        $this->appointment_repo = $appointment_repo;
        $this->user_trans_repo = $user_trans_repo;
        $this->shop_medicine_repo = $shop_medicine_repo;
        $this->user_service_repo = $user_service_repo;
        $this->user_details_repo = $user_details_repo;
    }
     
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($provider = 'patients')
    {   
        $provider_names = $this->user_repo->provider_name;
        return view('admin.provider.index', compact('provider','provider_names'));
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPending($provider = '')
    {
        $provider_names = $this->user_repo->provider_name;
        return view('admin.provider.pending', compact('provider','provider_names'));
    }
    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDatatable(Request $request)
    {
        if($request->all()){
           return $this->user_repo->getDatatable($request);
        }
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = $this->category_repo->get();
        $data = $this->user_repo->getbyIdedit($id);
        return view('admin.user.add',compact('data','categories'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showPatient($id)
    {
        $categories = $this->category_repo->get();
        $data = $this->user_repo->getbyIdedit($id);
        $currency_symbol = $this->user_repo->currency_symbol;         
        return view('admin.patients.view',compact('data','categories','currency_symbol'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($provider = '', $id)
    {
        $categories = $this->category_repo->get();
        $data = $this->user_repo->getbyIdedit($id);
        $currency_symbol = $this->user_repo->currency_symbol;
        // dd($data);
        
        // $provider_names = $this->user_repo->provider_name;
        // return view('admin.provider.view', compact('data','categories','days','appointment_types','provider','provider_names'));
        if($provider == 'healthcare'){
             return view('admin.healthcare.view',compact('data','categories','currency_symbol'));
        }else if($provider == 'pharmacy'){
             return view('admin.pharmacy.view',compact('data','categories','currency_symbol'));
        }else if($provider == 'laboratories'){
             return view('admin.laboratories.view',compact('data','categories','currency_symbol'));
        }else{            
            return view('admin.patients.view',compact('data','categories','currency_symbol'));
        }

    }


    public function showTransaction($provider = '', $id)
    {
        $total_balance = '0';
        $payout_approved_balance = '0';
        $payout_pending_balance = '0';
        $payout_approved_balance = $this->user_trans_repo->getPayoutCalculte($id, '0');
        $payout_pending_balance = $this->user_trans_repo->getPayoutCalculte($id, '1');
        $currency_symbol = $this->user_repo->currency_symbol;
        $provider_names = $this->user_repo->provider_name;
        return view('admin.provider.transactions',compact('currency_symbol','provider','provider_names','id','payout_pending_balance','payout_approved_balance','total_balance'));
    }
    
    public function showPatientTransaction($id)
    {
        $provider = 'patients';
        $total_balance = '0';
        $payout_approved_balance = '0';
        $payout_pending_balance = '0';
        $payout_approved_balance = $this->user_trans_repo->getPayoutCalculte($id, '0');
        $payout_pending_balance = $this->user_trans_repo->getPayoutCalculte($id, '1');
        $currency_symbol = $this->user_repo->currency_symbol;
        $provider_names = $this->user_repo->provider_name;
        return view('admin.provider.transactions',compact('currency_symbol','provider','provider_names','id','payout_pending_balance','payout_approved_balance','total_balance'));
    }

    public function getTransactionDatatable(Request $request)
    {
        if($request->all()){
            return $this->user_trans_repo->getDatatablebyUserId($request);
        }
    }
    
    public function getWalletBalance(Request $request)
    {
        try{
            $balance= $this->user_trans_repo->getHCPTYPEWalletBalanceDateRange($request);
            $currency_symbol = $this->user_repo->currency_symbol;
            $wallet_balance = $currency_symbol.$balance;
            return response()->json(['status'=> true, 'data'=>$wallet_balance], 200);
        }catch(\Exception $e){
            return response()->json(['msg'=>'Can not get wallet balance'], 500);
        }  
    }

    public function showMedicineDetails($id ='', Request $request)
    {
        if($request->all()){
            return $this->shop_medicine_repo->getDatatable($request);
        }
        return view('admin.pharmacy.medicine',compact('id'));
    }

    public function showHCPService($provider = '', $id ='', Request $request)
    {
        if($request->all()){
            return $this->user_service_repo->getDatatable($request);
        }
        $provider_names = $this->user_repo->provider_name;
        return view('admin.provider.services',compact('id','provider','provider_names'));
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->user_repo->getById($id);
        try{
            DB::beginTransaction();
            if(!empty($data)){
                $this->user_details_repo->getbyDelete($id); 
                $this->user_repo->forceDelete($id); 
                DB::commit();
                return response()->json(['msg'=>'Deleted success'], 200);
            }
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json(['msg'=>'Can not delete this user'], 500);
        }  
        return response()->json(['msg'=>'Data Not success'], 500);
    }

    /**
     * editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request)
    {
        $data = $this->user_repo->getById($request->user_id);
        if(!empty($data)){
            if(isset($request->status) && $data->status == '1' && $request->status == '0'){
                 $data = ['status' => $request->status, 'approved_date' => Carbon::now()];
            }else{
                 $data = ['status' => $request->status];
            }
            $this->user_repo->update($data, $request->user_id); 
            return response()->json(['msg'=>'Status Change success'], 200);
        }
        
        return response()->json(['msg'=>'Data Not success'], 500);
    }

}
