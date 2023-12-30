<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\AppointmentRepository;
use App\Repositories\OrderRepository;
use App\Repositories\MedicineCategoryRepository;
use App\Repositories\MedicineSubcategoryRepository;
use App\Repositories\MedicineDetailsRepository;
use App\Repositories\UserTransactionRepository;
use App\Repositories\ChatHistoryRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\SupportRequestRepository;
use App\Repositories\ContactDetailsRepository;
use Helper;

class DashboardController extends Controller
{
    private $support_request_repo,$user_repo, $category_repo,$order_repo, $chat_history_repo, $medicine_details_repo, $appointment_repo, $medicine_category_repo, $medicine_subcategory_repo, $user_transaction_repo, $contact_form_repo;

    public function __construct(
        UserRepository $user_repo, 
        AppointmentRepository $appointment_repo, 
        OrderRepository $order_repo,
        MedicineCategoryRepository $medicine_category_repo,
        MedicineSubcategoryRepository $medicine_subcategory_repo,
        CategoryRepository $category_repo,
        MedicineDetailsRepository $medicine_details_repo,
        UserTransactionRepository $user_transaction_repo,
        SupportRequestRepository $support_request_repo,
        ChatHistoryRepository $chat_history_repo,
        ContactDetailsRepository $contact_form_repo
        )
    {
        $this->user_repo = $user_repo;
        $this->order_repo = $order_repo;
        $this->appointment_repo = $appointment_repo;
        $this->medicine_category_repo = $medicine_category_repo;
        $this->medicine_subcategory_repo = $medicine_subcategory_repo;
        $this->user_transaction_repo = $user_transaction_repo;
        $this->medicine_details_repo = $medicine_details_repo;
        $this->category_repo = $category_repo;
        $this->support_request_repo = $support_request_repo;
        $this->chat_history_repo = $chat_history_repo;
        $this->contact_form_repo = $contact_form_repo;
    }
     
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($provider = '')
    {
        $data = array();
        
        $categories = $this->category_repo->getByMultipleParentIds(['1','2','3']);
        $data['patient'] = $this->user_repo->getPatientsCount();
        $data['today_patient'] = $this->user_repo->getPatientsCountToday();
        if($provider == 'healthcare'){

            $data['approved_count'] = $this->user_repo->getUserParentCategoryWiseCount('1','0');
            $data['pending_count'] = $this->user_repo->getUserParentCategoryWiseCount('1','1');

            $data['today_approved_count'] = $this->user_repo->getUserParentCategoryWiseCountApprovedToday('1','0');
            $data['today_pending_count'] = $this->user_repo->getUserParentCategoryWiseCountToday('1','1');

            $data['doctor'] = $this->user_repo->getUserCategoryWiseCount('4');
            $data['nurses'] = $this->user_repo->getUserCategoryWiseCount('5');
            $data['massage_therapist'] = $this->user_repo->getUserCategoryWiseCount('6');
            $data['physiotherapy'] = $this->user_repo->getUserCategoryWiseCount('42');
            
            $data['today_doctor'] = $this->user_repo->getUserCategoryWiseCountToday('4');
            $data['today_nurses'] = $this->user_repo->getUserCategoryWiseCountToday('5');
            $data['today_massage_therapist'] = $this->user_repo->getUserCategoryWiseCountToday('6');
            $data['today_physiotherapy'] = $this->user_repo->getUserCategoryWiseCountToday('42');
            
            $data['appointments'] = $this->appointment_repo->getAppointmentStatusWiseCount('', '1');
            $data['today_appointments'] = $this->appointment_repo->getTodayAppointmentStatusWiseCount('', '1');

            $data['upcoming_appointments'] = $this->appointment_repo->getAppointmentStatusWiseCount(['0','1','2','3','4'], '1');
            $data['completed_appointments'] = $this->appointment_repo->getAppointmentStatusWiseCount(['5'], '1');
            $data['cancel_appointments'] = $this->appointment_repo->getAppointmentStatusWiseCount(['6'], '1');    

            $data['today_upcoming_appointments'] = $this->appointment_repo->getTodayAppointmentStatusWiseCount(['0','1','2','3','4'], '1');
            $data['today_completed_appointments'] = $this->appointment_repo->getTodayAppointmentStatusWiseCount(['5'], '1');
            $data['today_cancel_appointments'] = $this->appointment_repo->getTodayAppointmentStatusWiseCount(['6'], '1');    

            return view('admin.healthcare.dashboard', compact('data', 'categories'));
        }else if($provider == 'pharmacy'){

            $data['approved_count'] = $this->user_repo->getUserParentCategoryWiseCount('2','0');
            $data['pending_count'] = $this->user_repo->getUserParentCategoryWiseCount('2', '1');

            $data['today_approved_count'] = $this->user_repo->getUserParentCategoryWiseCountApprovedToday('2','0');
            $data['today_pending_count'] = $this->user_repo->getUserParentCategoryWiseCountToday('2','1');

            $data['orders'] = $this->order_repo->getOrderStatusWiseCount();
            $data['today_orders'] = $this->order_repo->getTodayOrderStatusWiseCount();

            $data['completed_orders'] = $this->order_repo->getOrderStatusWiseCount('3');
            $data['cancel_orders'] = $this->order_repo->getOrderStatusWiseCount('4');
            $data['pending_orders'] = $this->order_repo->getOrderStatusWiseCount(['0','1','2','5']);
            
            $data['today_completed_orders'] = $this->order_repo->getTodayOrderStatusWiseCount('3');
            $data['today_cancel_orders'] = $this->order_repo->getTodayOrderStatusWiseCount('4');
            $data['today_pending_orders'] = $this->order_repo->getTodayOrderStatusWiseCount(['0','1','2']);

            return view('admin.pharmacy.dashboard', compact('data', 'categories'));
        }else if($provider == 'laboratories'){

            $data['approved_count'] = $this->user_repo->getUserParentCategoryWiseCount('3', '0');
            $data['pending_count'] = $this->user_repo->getUserParentCategoryWiseCount('3', '1');

            $data['today_approved_count'] = $this->user_repo->getUserParentCategoryWiseCountApprovedToday('3','0');
            $data['today_pending_count'] = $this->user_repo->getUserParentCategoryWiseCountToday('3','1');

            $data['pathologist'] = $this->user_repo->getUserCategoryWiseCount('9');
            $data['scientist'] = $this->user_repo->getUserCategoryWiseCount('8');
            $data['radiologist'] = $this->user_repo->getUserCategoryWiseCount('10');

            $data['today_pathologist'] = $this->user_repo->getUserCategoryWiseCountToday('9');
            $data['today_scientist'] = $this->user_repo->getUserCategoryWiseCountToday('8');
            $data['today_radiologist'] = $this->user_repo->getUserCategoryWiseCountToday('10');
      
            $data['appointments'] = $this->appointment_repo->getAppointmentStatusWiseCount('', '3');
            $data['today_appointments'] = $this->appointment_repo->getTodayAppointmentStatusWiseCount('', '3');

            $data['upcoming_appointments'] = $this->appointment_repo->getAppointmentStatusWiseCount(['0','1','2','3','4'], '3');
            $data['completed_appointments'] = $this->appointment_repo->getAppointmentStatusWiseCount(['5'], '3');
            $data['cancel_appointments'] = $this->appointment_repo->getAppointmentStatusWiseCount(['6'], '3');    

            $data['today_upcoming_appointments'] = $this->appointment_repo->getTodayAppointmentStatusWiseCount(['0','1','2','3','4'], '3');
            $data['today_completed_appointments'] = $this->appointment_repo->getTodayAppointmentStatusWiseCount(['5'], '3');
            $data['today_cancel_appointments'] = $this->appointment_repo->getTodayAppointmentStatusWiseCount(['6'], '3');   
            
            return view('admin.laboratories.dashboard', compact('data', 'categories'));
        }else{            

            $data['healthcare'] = $this->user_repo->getUserParentCategoryWiseCount('1');
            $data['pharmacist'] = $this->user_repo->getUserParentCategoryWiseCount('2');
            $data['laboratories'] = $this->user_repo->getUserParentCategoryWiseCount('3');

            $data['today_healthcare'] = $this->user_repo->getUserParentCategoryWiseCountToday('1');
            $data['today_pharmacist'] = $this->user_repo->getUserParentCategoryWiseCountToday('2');
            $data['today_laboratories'] = $this->user_repo->getUserParentCategoryWiseCountToday('3');
            
            $data['orders'] = $this->order_repo->getOrderStatusWiseCount();
            $data['today_orders'] = $this->order_repo->getTodayOrderStatusWiseCount();
            $data['completed_orders'] = $this->order_repo->getOrderStatusWiseCount('3');
            $data['pending_orders'] = $this->order_repo->getOrderStatusWiseCount(['0','1','2','5']);
            $data['cancel_orders'] = $this->order_repo->getOrderStatusWiseCount('4');

            $data['appointments'] = $this->appointment_repo->getAppointmentStatusWiseCount();             
            $data['today_appointments'] = $this->appointment_repo->getTodayAppointmentStatusWiseCount();
            $data['completed_appointments'] = $this->appointment_repo->getAppointmentStatusWiseCount(['5']);            
            $data['pending_appointments'] = $this->appointment_repo->getAppointmentStatusWiseCount(['0','1','2','3','4']);
            $data['cancel_appointments'] = $this->appointment_repo->getAppointmentStatusWiseCount(['6']); 

            $data['medicine_categories'] = $this->medicine_category_repo->getCount(); 
            $data['medicine_details'] = $this->medicine_details_repo->getCount(); 
            
            $data['pending_payout'] = $this->user_transaction_repo->getPayoutCount('1'); 
            $data['approved_payout'] = $this->user_transaction_repo->getPayoutCount('0'); 

            $data['patient_wallet_total'] = Helper::currncyNumberFormat($this->user_transaction_repo->getPatientWalletCalculate(0, 0) - $this->user_transaction_repo->getPatientWalletCalculate(0, 1)); 
            $data['patient_wallet_today'] = Helper::currncyNumberFormat($this->user_transaction_repo->getAddWalletBalanceCalculate(1)); 
            // $data['patient_wallet_today'] = Helper::currncyNumberFormat($this->user_transaction_repo->getPatientWalletCalculate(1, 0) - $this->user_transaction_repo->getPatientWalletCalculate(1, 1)); 

            $data['hcp_wallet_total'] = Helper::currncyNumberFormat($this->user_transaction_repo->getHCPWalletCalculate(1,0)); 
            $data['hcp_wallet_today'] = Helper::currncyNumberFormat($this->user_transaction_repo->getHCPWalletCalculate(1,1)); 
          
            $data['pharmacy_wallet_total'] = Helper::currncyNumberFormat($this->user_transaction_repo->getHCPWalletCalculate(2,0)); 
            $data['pharmacy_wallet_today'] = Helper::currncyNumberFormat($this->user_transaction_repo->getHCPWalletCalculate(2,1)); 
           
            $data['laboratories_wallet_total'] = Helper::currncyNumberFormat($this->user_transaction_repo->getHCPWalletCalculate(3,0)); 
            $data['laboratories_wallet_today'] = Helper::currncyNumberFormat($this->user_transaction_repo->getHCPWalletCalculate(3,1)); 
            
            $data['hcp_withdraw_pending'] = Helper::currncyNumberFormat($this->user_transaction_repo->getHCPPayoutWalletCalculate(1, '1')); 
            $data['hcp_withdraw_inprogress'] = Helper::currncyNumberFormat($this->user_transaction_repo->getHCPPayoutWalletCalculate(1, '3')); 
            $data['hcp_withdraw_confirmed'] = Helper::currncyNumberFormat($this->user_transaction_repo->getHCPPayoutWalletCalculate(1, '0')); 
           
            $data['pharmacy_withdraw_pending'] = Helper::currncyNumberFormat($this->user_transaction_repo->getHCPPayoutWalletCalculate(2, '1')); 
            $data['pharmacy_withdraw_inprogress'] = Helper::currncyNumberFormat($this->user_transaction_repo->getHCPPayoutWalletCalculate(2, '3')); 
            $data['pharmacy_withdraw_confirmed'] = Helper::currncyNumberFormat($this->user_transaction_repo->getHCPPayoutWalletCalculate(2, '0')); 
            
            $data['laboratories_withdraw_pending'] = Helper::currncyNumberFormat($this->user_transaction_repo->getHCPPayoutWalletCalculate(3, '1')); 
            $data['laboratories_withdraw_inprogress'] = Helper::currncyNumberFormat($this->user_transaction_repo->getHCPPayoutWalletCalculate(3, '3')); 
            $data['laboratories_withdraw_confirmed'] = Helper::currncyNumberFormat($this->user_transaction_repo->getHCPPayoutWalletCalculate(3, '0')); 

            $currency_symbol = $this->user_transaction_repo->currency_symbol;
            
            return view('admin.dashboard.dashboard', compact('data','currency_symbol','categories'));
        }
    }
   
    public function healthcareDashboard(Request $request)
    {
        $data = array();        
        $categories = $this->category_repo->getByParentId('4');
        if(!empty($categories) && count($categories) > 0){
            foreach ($categories as $key => $value) {
                $temp_data = []; 
                $temp_data['id'] = $value->id;
                $temp_data['name'] = $value->name;
                $temp_data['color'] = $this->getRandomColor();
                $temp_data['total_count'] = $this->user_repo->getUserSubCategoryWiseCount($value->id);
                $temp_data['approved_count'] = $this->user_repo->getUserSubCategoryWiseCount($value->id, '0');
                $temp_data['unapproved_count'] = $this->user_repo->getUserSubCategoryWiseCount($value->id, '1');
                $data[] = $temp_data;
            }
        }
        return view('admin.healthcare.doctor_dashboard', compact('data'));
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
            if(!empty($data['total_income'])){
                $data['total_income'] = number_format($data['total_income'], 2);
            }
            if (!empty($data['total_payout'])) {
                $data['total_payout'] = number_format($data['total_payout'], 2);
            }
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
            $data['treatment_plan_percentage'] = 0;
            $data['ezzycare_earning'] = $this->user_transaction_repo->userIncomeCalculate($request, 'fees_charge');
            $data['appointment_paid'] = $this->appointment_repo->getAppointmentCount($request, '1');
            $data['appointment_pending'] = $this->appointment_repo->getAppointmentCount($request, '0');
            $data['order_paid'] = $this->order_repo->getOrderCount($request, '1');
            $data['order_pending'] = $this->order_repo->getOrderCount($request, '0');
            $data['treatment_plan_paid'] = $this->chat_history_repo->getTreatmentPlanCount($request, '1');
            $data['treatment_plan_pending'] = $this->chat_history_repo->getTreatmentPlanCount($request, '0');
            if(!empty($data['appointment_paid']) || !empty($data['appointment_pending'])){
                $data['appointments_percentage'] = ($data['appointment_paid'] * 100) / ($data['appointment_paid'] + $data['appointment_pending']);
            }
            if(!empty($data['order_paid']) || !empty($data['order_pending'])){
                $data['orders_percentage'] = ($data['order_paid'] * 100) / ($data['order_paid'] + $data['order_pending']);
            }
            if(!empty($data['treatment_plan_paid']) || !empty($data['treatment_plan_pending'])){
                $data['treatment_plan_percentage'] = ($data['treatment_plan_paid'] * 100) / ($data['treatment_plan_paid'] + $data['treatment_plan_pending']);
            }
            $data['appointments_and_order_paid'] = $data['order_paid'] + $data['appointment_paid'] + $data['treatment_plan_paid'];
            $data['appointments_and_order_total'] = $data['order_paid'] + $data['appointment_paid'] + $data['order_pending'] + $data['appointment_pending'] + $data['treatment_plan_paid'] + $data['treatment_plan_pending'];
            
            if (!empty($data['ezzycare_earning'])) {
                $data['ezzycare_earning'] = number_format($data['ezzycare_earning'], 2);
            }

            return response()->json(['status'=> true, 'data'=> $data], 200);
        } 
        
        return response()->json(['msg'=>'Data Not Found'], 500);
    }

    public function getRandomColor(){
        $color_codes=array("bg-warning","bg-secondary","bg-success","bg-danger","bg-info","bg-violet","bg-primary","bg-dark");        
        $rand_keys = array_rand($color_codes, 1);
        return $color_codes[$rand_keys];
    }

    public function getSidebarPendingCount(Request $request)
    {
        $data = [];
        $data['pending_support_ticket'] = $this->support_request_repo->getSupportRequestPendingCount();
        $data['pending_appointment_count'] = $this->appointment_repo->getAppointmentPendingCount();
        $data['pending_healthcare_count'] = $this->user_repo->getCompletedProfileUserParentCategoryWiseCount(1);
        $data['pending_pharmacy_count'] = $this->user_repo->getCompletedProfileUserParentCategoryWiseCount(2);
        $data['pending_laboratories_count'] = $this->user_repo->getCompletedProfileUserParentCategoryWiseCount(3);
        $data['pending_order_count'] = $this->order_repo->getOrderPendingCount();
        $data['pending_contact_form_count'] = $this->contact_form_repo->getContactFormCount();
        return response()->json(['status'=>true,'data'=>$data], 200);
    }

}
