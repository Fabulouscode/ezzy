<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Repositories\AppointmentRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\UserTransactionRepository;
use App\Repositories\UserServiceRepository;
use App\Repositories\UserRepository;
use Auth;
use DB;

class AppointmentController extends Controller
{
    private $appointment_repo, $category_repo, $user_transaction_repo, $user_service_repo, $user_repo;

    public function __construct(
        AppointmentRepository $appointment_repo, CategoryRepository $category_repo, 
        UserRepository $user_repo, UserTransactionRepository $user_transaction_repo,
        UserServiceRepository $user_service_repo
    )
    {
        $this->appointment_repo = $appointment_repo;
        $this->category_repo = $category_repo;
        $this->user_transaction_repo = $user_transaction_repo;
        $this->user_service_repo = $user_service_repo;
        $this->user_repo = $user_repo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->all()) {
            return $this->appointment_repo->getDatatable($request);
        }
        $categories = $this->category_repo->getByMultipleParentIds(['1','3']);
        return view('admin.appointment.index', compact('categories'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUpcomingAppointments()
    {
        $categories = $this->category_repo->getByMultipleParentIds(['1','3']);
        $statuses = $this->appointment_repo->getStatusValue();
        return view('admin.appointment.upcoming', compact('categories','statuses'));
    }
  
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCancelAppointments()
    { 
        $categories = $this->category_repo->getByMultipleParentIds(['1','3']);
        return view('admin.appointment.cancel', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.appointment.add');
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $categories = $this->category_repo->get();
        $currency_symbol = $this->category_repo->currency_symbol;
        $data = $this->appointment_repo->getbyIdedit($id);
        // dd($data);
        return view('admin.appointment.view', compact('data', 'categories','currency_symbol'));
    }

    public function getReviews()
    {
        return view();
    }

    public function getAppointmentReviews(Request $request)
    {
        if ($request->all()) {
            return $this->appointment_repo->getReviewDatatable($request);
        }
        $categories = $this->category_repo->getByMultipleParentIds(['1','3']);
        return view('admin.appointment.reviews', compact('categories'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getInvoice($id)
    {
        $categories = $this->category_repo->get();
        $currency_symbol = $this->category_repo->currency_symbol;
        $data = $this->appointment_repo->getbyIdedit($id);
        // dd($data);
        return view('admin.appointment.invoice', compact('data', 'categories','currency_symbol'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->appointment_repo->getById($id);
        try{
            if(!empty($data)){
                $this->appointment_repo->forceDelete($id); 
                return response()->json(['msg'=>'Deleted success'], 200);
            }
        }catch(\Exception $e){
            return response()->json(['msg'=>'Can not delete this appointment'], 500);
        }  
        return response()->json(['msg'=>'Data Not success'], 500);

    }
    public function isHappyClient($id)
    {
        $data = $this->appointment_repo->getById($id);
        try{
            if(!empty($data)){
                $status = $data->is_happy_clients ? 0 : 1;
                $data_arr['is_happy_clients'] = $status;
                $this->appointment_repo->update($data_arr,$id); 
                return response()->json(['msg'=>'Status updated successfully'], 200);
            }
        }catch(\Exception $e){
            return response()->json(['msg'=>'Can not change status'], 500);
        }  
        return response()->json(['msg'=>'Data Not success'], 500);

    }

    public function updateAppointmentCancel($id){          
        $appointmentCancel = $this->appointment_repo->getById($id);
        if(!empty($appointmentCancel) && !empty($appointmentCancel->id)){
            DB::beginTransaction();
            try {
                if(!empty($appointmentCancel->transaction_id)){ 
                    $this->user_transaction_repo->destroy($appointmentCancel->transaction_id);
                    $this->user_repo->userWalletUpdate($appointmentCancel->client_id); 
                }  
                $update = [
                    'status' => 6,
                    'cancel_date' => $this->appointment_repo->getCurrentDateTime(),
                    'cancel_reason' => 'Appointment Cancelled',
                    'transaction_id' => NULL,
                ];
                
                $this->appointment_repo->dataCrud($update, $appointmentCancel->id);                
                DB::commit();  
                return response()->json(['msg'=>'Appointmrnt Cancel successfully'], 200);
            } catch(\Throwable $e) {
                DB::rollBack();
                return response()->json(['msg'=>'Can not cancel this appointment'], 500);
            }
        }
        return response()->json(['msg'=>'Can not cancel this appointment'], 500);
    }

    public function getAppointmentPendingCount(Request $request)
    {
        $pendingCount = $this->appointment_repo->getAppointmentPendingCount();
        return response()->json(['status'=>true,'data'=>$pendingCount], 200);
    }
}
