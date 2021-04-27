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
use App\Repositories\NotificationRepository;
use Auth;
use Carbon\Carbon;
use DB;

class UserController extends Controller
{

    private $user_repo, $category_repo, $notification_repo, $user_details_repo, $appointment_repo, $user_trans_repo, $shop_medicine_repo, $user_service_repo;

    public function __construct(
        UserRepository $user_repo, 
        UserDetailsRepository $user_details_repo, 
        CategoryRepository  $category_repo, 
        AppointmentRepository $appointment_repo,
        UserTransactionRepository $user_trans_repo,
        ShopMedicineDetailsRepository $shop_medicine_repo,
        UserServiceRepository $user_service_repo,
        NotificationRepository $notification_repo
        )
    {
        $this->user_repo = $user_repo;
        $this->category_repo = $category_repo;
        $this->appointment_repo = $appointment_repo;
        $this->user_trans_repo = $user_trans_repo;
        $this->shop_medicine_repo = $shop_medicine_repo;
        $this->user_service_repo = $user_service_repo;
        $this->user_details_repo = $user_details_repo;
        $this->notification_repo = $notification_repo;
    }
     
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($provider = 'patients')
    {           
        $categories = [];
        if($provider == 'healthcare'){
            $categories = $this->category_repo->getByParentId('1');
        }else if($provider == 'laboratories'){
            $categories = $this->category_repo->getByParentId('3');
        }
        $provider_names = $this->user_repo->provider_name;
        return view('admin.provider.index', compact('provider','provider_names','categories'));
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPending($provider = '')
    {
        $categories = [];
        if($provider == 'healthcare'){
            $categories = $this->category_repo->getByParentId('1');
        }else if($provider == 'laboratories'){
            $categories = $this->category_repo->getByParentId('3');
        }
        $provider_names = $this->user_repo->provider_name;
        return view('admin.provider.pending', compact('provider','provider_names','categories'));
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
        $data = $request->all();
        unset($data['userDetails']);
        $user_document = $this->user_details_repo->user_documents;
        if(!empty($request->file('profile_image')) && !empty($user_document)) {          
            $file = $request->file('profile_image');
            $storagePath = 'images/'.$user_document[0];
            $file_name = $this->user_repo->uploadFolderWiseFile($file, $storagePath);
            $data['profile_image'] =  $file_name;
        }
        $this->user_repo->dataCrudUsingData($data, $request->id); 
        if(!empty($request->userDetails)){
                if(!empty($request->file('userDetails')['qualification_certificate']) && !empty($user_document)) {          
                    $file = $request->file('qualification_certificate');
                    $storagePath = 'images/'.$user_document[1];
                    $file_name = $this->user_repo->uploadFolderWiseFile($file, $storagePath);
                    $data['qualification_certificate'] =  $file_name;
                }
                if(!empty($request->file('userDetails')['practicing_licence']) && !empty($user_document)) {          
                    $file = $request->file('practicing_licence');
                    $storagePath = 'images/'.$user_document[2];
                    $file_name = $this->user_repo->uploadFolderWiseFile($file, $storagePath);
                    $data['practicing_licence'] =  $file_name;
                }
                if(!empty($request->file('userDetails')['regstration_certificate']) && !empty($user_document)) {          
                    $file = $request->file('regstration_certificate');
                    $storagePath = 'images/'.$user_document[4];
                    $file_name = $this->user_repo->uploadFolderWiseFile($file, $storagePath);
                    $data['regstration_certificate'] =  $file_name;
                }
                if(!empty($request->file('userDetails')['pharmacist_certificate']) && !empty($user_document)) {          
                    $file = $request->file('pharmacist_certificate');
                    $storagePath = 'images/'.$user_document[5];
                    $file_name = $this->user_repo->uploadFolderWiseFile($file, $storagePath);
                    $data['pharmacist_certificate'] =  $file_name;
                }
            $this->user_details_repo->dataCrudUsingData($request->userDetails,$request->userDetails['id']); 
        }
        $user = $this->user_repo->getByID($request->id);
        if(!empty($user->category_id) && in_array($user->category_id, ['4','5','6','42'])){
            if ($user->status == '1') {
                return redirect('healthcare/user/pending');
            }else{
                return redirect('healthcare/user');
            }
        }else if(!empty($user->category_id) && $user->category_id == '7'){
            if ($user->status == '1') {
                return redirect('pharmacy/user/pending');
            }else{                
                return redirect('pharmacy/user');
            }
        }else if(!empty($user->category_id) && in_array($user->category_id, ['8','9','10'])){
            if ($user->status == '1') {
                return redirect('laboratories/user/pending');
            }else{
                return redirect('laboratories/user');
            }            
        }else{            
            return redirect('customer/patient');
        }
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
    public function editPatient($id)
    {
        $categories = $this->category_repo->get();
        $data = $this->user_repo->getbyIdedit($id);
        $currency_symbol = $this->user_repo->currency_symbol;         
        return view('admin.patients.edit',compact('data','categories','currency_symbol'));
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editUser($provider = '', $id)
    {
        $categories = $this->category_repo->get();
        $data = $this->user_repo->getbyIdedit($id);
        $currency_symbol = $this->user_repo->currency_symbol;
        // dd($data);
        
        // $provider_names = $this->user_repo->provider_name;
        // return view('admin.provider.view', compact('data','categories','days','appointment_types','provider','provider_names'));
        if($provider == 'healthcare'){
             return view('admin.healthcare.edit',compact('data','categories','currency_symbol'));
        }else if($provider == 'pharmacy'){
             return view('admin.pharmacy.edit',compact('data','categories','currency_symbol'));
        }else if($provider == 'laboratories'){
             return view('admin.laboratories.edit',compact('data','categories','currency_symbol'));
        }else{            
            return view('admin.patients.edit',compact('data','categories','currency_symbol'));
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
        $patient_wallet_balance = '0';
        $payout_approved_balance = $this->user_trans_repo->getPayoutCalculte($id, '0');
        $payout_pending_balance = $this->user_trans_repo->getPayoutCalculte($id, '1');
        $patient_wallet_balance = $this->user_repo->getByID($id);
        $currency_symbol = $this->user_repo->currency_symbol;
        $provider_names = $this->user_repo->provider_name;
        return view('admin.provider.transactions',compact('patient_wallet_balance','currency_symbol','provider','provider_names','id','payout_pending_balance','payout_approved_balance','total_balance'));
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
                 $data = ['status' => $request->status, 'approved_date' => $this->user_repo->getCurrentDateTime()];
                 $send_notification = [
                                    'sender_id' => NULL,
                                    'receiver_id' => $request->user_id,
                                    'title' => 'Profile',
                                    'message' => 'Congratulations! Your profile has been approved, you can now accept appointments on Ezzycare',
                                    'parameter' => '',
                                    'msg_type' => '7',
                                ];  
                $this->notification_repo->sendingWithoutSenderNotification($send_notification);    
            }else{
                 $data = ['status' => $request->status];
            }
            $this->user_repo->update($data, $request->user_id); 
            return response()->json(['msg'=>'Status Change success'], 200);
        }
        
        return response()->json(['msg'=>'Data Not success'], 500);
    }

}
