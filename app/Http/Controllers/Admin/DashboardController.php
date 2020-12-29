<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\AppointmentRepository;
use App\Repositories\OrderRepository;
use App\Repositories\MedicineCategoryRepository;
use App\Repositories\MedicineSubcategoryRepository;
use App\Repositories\UserTransactionRepository;


class DashboardController extends Controller
{
    private $user_repo, $order_repo, $appointment_repo, $medicine_category_repo, $medicine_subcategory_repo, $user_transaction_repo;

    public function __construct(
        UserRepository $user_repo, 
        AppointmentRepository $appointment_repo, 
        OrderRepository $order_repo,
        MedicineCategoryRepository $medicine_category_repo,
        MedicineSubcategoryRepository $medicine_subcategory_repo,
        UserTransactionRepository $user_transaction_repo
        )
    {
        $this->user_repo = $user_repo;
        $this->order_repo = $order_repo;
        $this->appointment_repo = $appointment_repo;
        $this->medicine_category_repo = $medicine_category_repo;
        $this->medicine_subcategory_repo = $medicine_subcategory_repo;
        $this->user_transaction_repo = $user_transaction_repo;
    }
     
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($provider = '')
    {
        $data = array();
        
     
        $data['patient'] = $this->user_repo->getPatientsCount();
        if($provider == 'healthcare'){

            $data['approved_count'] = $this->user_repo->getUserParentCategoryWiseCount('1','0');
            $data['pending_count'] = $this->user_repo->getUserParentCategoryWiseCount('1','1');

            $data['doctor'] = $this->user_repo->getUserCategoryWiseCount('4');
            $data['nurses'] = $this->user_repo->getUserCategoryWiseCount('5');
            $data['massage_therapist'] = $this->user_repo->getUserCategoryWiseCount('6');
            
            $data['appointments'] = $this->appointment_repo->getAppointmentStatusWiseCount('', '1');
            $data['today_appointments'] = $this->appointment_repo->getTodayAppointmentStatusWiseCount('', '1');

            $data['upcoming_appointments'] = $this->appointment_repo->getAppointmentStatusWiseCount(['0','1','2','3','4'], '1');
            $data['completed_appointments'] = $this->appointment_repo->getAppointmentStatusWiseCount(['5'], '1');
            $data['cancel_appointments'] = $this->appointment_repo->getAppointmentStatusWiseCount(['6'], '1');    

            $data['today_upcoming_appointments'] = $this->appointment_repo->getTodayAppointmentStatusWiseCount(['0','1','2','3','4'], '1');
            $data['today_completed_appointments'] = $this->appointment_repo->getTodayAppointmentStatusWiseCount(['5'], '1');
            $data['today_cancel_appointments'] = $this->appointment_repo->getTodayAppointmentStatusWiseCount(['6'], '1');    

            return view('admin.healthcare.dashboard', compact('data'));
        }else if($provider == 'pharmacy'){

            $data['approved_count'] = $this->user_repo->getUserParentCategoryWiseCount('2','0');
            $data['pending_count'] = $this->user_repo->getUserParentCategoryWiseCount('2', '1');

            $data['orders'] = $this->order_repo->getOrderStatusWiseCount();
            $data['today_orders'] = $this->order_repo->getTodayOrderStatusWiseCount();

            $data['completed_orders'] = $this->order_repo->getOrderStatusWiseCount('1');
            $data['cancel_orders'] = $this->order_repo->getOrderStatusWiseCount('2');
            $data['pending_orders'] = $this->order_repo->getOrderStatusWiseCount('0');
            
            $data['today_completed_orders'] = $this->order_repo->getTodayOrderStatusWiseCount('1');
            $data['today_cancel_orders'] = $this->order_repo->getTodayOrderStatusWiseCount('2');
            $data['today_pending_orders'] = $this->order_repo->getTodayOrderStatusWiseCount('0');

            return view('admin.pharmacy.dashboard', compact('data'));
        }else if($provider == 'laboratories'){

            $data['approved_count'] = $this->user_repo->getUserParentCategoryWiseCount('3', '0');
            $data['pending_count'] = $this->user_repo->getUserParentCategoryWiseCount('3', '1');

            $data['pathologist'] = $this->user_repo->getUserCategoryWiseCount('9');
            $data['scientist'] = $this->user_repo->getUserCategoryWiseCount('8');
            $data['radiologist'] = $this->user_repo->getUserCategoryWiseCount('10');
      
            $data['appointments'] = $this->appointment_repo->getAppointmentStatusWiseCount('', '3');
            $data['today_appointments'] = $this->appointment_repo->getTodayAppointmentStatusWiseCount('', '3');

            $data['upcoming_appointments'] = $this->appointment_repo->getAppointmentStatusWiseCount(['0','1','2','3','4'], '3');
            $data['completed_appointments'] = $this->appointment_repo->getAppointmentStatusWiseCount(['5'], '3');
            $data['cancel_appointments'] = $this->appointment_repo->getAppointmentStatusWiseCount(['6'], '3');    

            $data['today_upcoming_appointments'] = $this->appointment_repo->getTodayAppointmentStatusWiseCount(['0','1','2','3','4'], '3');
            $data['today_completed_appointments'] = $this->appointment_repo->getTodayAppointmentStatusWiseCount(['5'], '3');
            $data['today_cancel_appointments'] = $this->appointment_repo->getTodayAppointmentStatusWiseCount(['6'], '3');   
            
            return view('admin.laboratories.dashboard', compact('data'));
        }else{            

            $data['healthcare'] = $this->user_repo->getUserParentCategoryWiseCount('1');
            $data['pharmacist'] = $this->user_repo->getUserParentCategoryWiseCount('2');
            $data['laboratories'] = $this->user_repo->getUserParentCategoryWiseCount('3');
            
            $data['orders'] = $this->order_repo->getOrderStatusWiseCount();
            $data['today_orders'] = $this->order_repo->getTodayOrderStatusWiseCount();
            $data['completed_orders'] = $this->order_repo->getOrderStatusWiseCount('1');
            $data['pending_orders'] = $this->order_repo->getOrderStatusWiseCount('0');
            $data['cancel_orders'] = $this->order_repo->getOrderStatusWiseCount('2');

            $data['appointments'] = $this->appointment_repo->getAppointmentStatusWiseCount();             
            $data['today_appointments'] = $this->appointment_repo->getTodayAppointmentStatusWiseCount();
            $data['completed_appointments'] = $this->appointment_repo->getAppointmentStatusWiseCount(['5']);            
            $data['pending_appointments'] = $this->appointment_repo->getAppointmentStatusWiseCount(['0','1','2','3','4']);
            $data['cancel_appointments'] = $this->appointment_repo->getAppointmentStatusWiseCount(['6']); 

            $data['medicine_categories'] = $this->medicine_category_repo->getCount(); 
            $data['medicine_subcategories'] = $this->medicine_subcategory_repo->getCount(); 
            
            $data['pending_payout'] = $this->user_transaction_repo->getPayoutCount('1'); 
            $data['approved_payout'] = $this->user_transaction_repo->getPayoutCount('0'); 

            $currency_symbol = $this->user_transaction_repo->currency_symbol;

            return view('admin.dashboard.dashboard', compact('data','currency_symbol'));
        }
    }
   
    /**
     * area chart data prepared.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRevenueChartdata(Request $request)
    {
        if(!empty($request->start_date) && !empty($request->end_date)){
            $data = $this->appointment_repo->getAreaChartdata($request);
            return response()->json(['status'=> true, 'data'=> $data], 200);
        }
        
        return response()->json(['msg'=>'Data Not Found'], 500);
    }

    /**
     * bar chart data prepared for payout.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIncomeChartdata(Request $request)
    {
        $data =$request->all();
        if(!empty($request->start_date) && !empty($request->end_date)){
            $data['chart_data'] = $this->user_transaction_repo->userPayoutIncome($request);
            $data['total_income'] = $this->user_transaction_repo->userIncomeCalculate($request, 'amount');
            $data['total_payout'] = $this->user_transaction_repo->userIncomeCalculate($request, 'payout_amount');
            return response()->json(['status'=> true, 'data'=> $data], 200);
        }
        
        return response()->json(['msg'=>'Data Not Found'], 500);
    }
  
    /**
     * bar chart data prepared for payout.
     *
     * @return \Illuminate\Http\Response
     */
    public function getEarningdata(Request $request)
    {
        $data =$request->all();
        if(!empty($request->start_date) && !empty($request->end_date)){
            $data['appointments_percentage'] = 0;
            $data['appointments_and_order_paid'] = 0;
            $data['appointments_and_order_total'] = 0;
            $data['orders_percentage'] = 0;
            $data['ezzycare_earning'] = $this->user_transaction_repo->userIncomeCalculate($request, 'fees_charge');
            $data['appointment_paid'] = $this->appointment_repo->getAppointmentCount($request, '1');
            $data['appointment_pending'] = $this->appointment_repo->getAppointmentCount($request, '0');
            $data['order_paid'] = $this->order_repo->getOrderCount($request, '1');
            $data['order_pending'] = $this->order_repo->getOrderCount($request, '0');
            if(!empty($data['appointment_paid']) || !empty($data['appointment_pending'])){
                $data['appointments_percentage'] = ($data['appointment_paid'] * 100) / ($data['appointment_paid'] + $data['appointment_pending']);
            }
            if(!empty($data['order_paid']) || !empty($data['order_pending'])){
                $data['orders_percentage'] = ($data['order_paid'] * 100) / ($data['order_paid'] + $data['order_pending']);
            }
            $data['appointments_and_order_paid'] = $data['order_paid'] + $data['appointment_paid'];
            $data['appointments_and_order_total'] = $data['order_paid'] + $data['appointment_paid'] + $data['order_pending'] + $data['appointment_pending'];
            return response()->json(['status'=> true, 'data'=> $data], 200);
        } 
        
        return response()->json(['msg'=>'Data Not Found'], 500);
    }

}
