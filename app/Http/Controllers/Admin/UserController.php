<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\UserTransactionRepository;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\AppointmentRepository;
use Auth;


class UserController extends Controller
{

    private $user_repo, $category_repo, $appointment_repo,$user_trans_repo;

    public function __construct(UserRepository $user_repo, CategoryRepository  $category_repo, AppointmentRepository $appointment_repo,UserTransactionRepository $userTransactionRepository)
    {
        $this->user_repo = $user_repo;
        $this->category_repo = $category_repo;
        $this->appointment_repo = $appointment_repo;
        $this->user_trans_repo = $userTransactionRepository;
    }
     
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($provider = '')
    {
        if($provider == 'healthcare'){
             return view('admin.healthcare.index');
        }else if($provider == 'pharmacy'){
             return view('admin.pharmacy.index');
        }else if($provider == 'laboratories'){
             return view('admin.laboratories.index');
        }else{            
            return view('admin.patients.index');
        }
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPending($provider = '')
    {
        if($provider == 'healthcare'){
             return view('admin.healthcare.pending');
        }else if($provider == 'pharmacy'){
             return view('admin.pharmacy.pending');
        }else if($provider == 'laboratories'){
             return view('admin.laboratories.pending');
        }else{            
            return view('admin.patients.index');
        }
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
    public function show($provider='', $id)
    {
        $days = $this->user_repo->days;
        $appointment_types = $this->appointment_repo->appointment_types;
        $categories = $this->category_repo->get();
        $data = $this->user_repo->getbyIdedit($id);
        if($provider == 'healthcare'){
             return view('admin.healthcare.view',compact('data','categories','days','appointment_types'));
        }else if($provider == 'pharmacy'){
             return view('admin.pharmacy.view',compact('data','categories','days','appointment_types'));
        }else if($provider == 'laboratories'){
             return view('admin.laboratories.view',compact('data','categories','days','appointment_types'));
        }else{            
            return view('admin.patients.view',compact('data','categories','days','appointment_types'));
        }

        // dd($data->toArray());
        // return view('admin.user.view',compact('data','categories','days','appointment_types'));
    }


     public function showTransaction($provider = '', $id)
    {
        $debit_balance = $this->user_trans_repo->getUserbyCalculate($id, '0');
        $credit_balance = $this->user_trans_repo->getUserbyCalculate($id, '1');
        $total_balance = $this->user_trans_repo->getUserbyWalletBalance($id);

        return view('admin.transactions.view',compact('provider','id','debit_balance','credit_balance','total_balance'));
    }

    public function getTransactionDatatable(Request $request)
    {
        if($request->all()){
            return $this->user_trans_repo->getDatatable($request);
        }
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
        if($data){
            $this->user_repo->destroy($id); 
            return response()->json(['msg'=>'Deleted success'], 200);
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
            $data = ['status' => $request->status];
            $this->user_repo->update($data, $request->user_id); 
            return response()->json(['msg'=>'Status Change success'], 200);
        }
        
        return response()->json(['msg'=>'Data Not success'], 500);
    }

}
