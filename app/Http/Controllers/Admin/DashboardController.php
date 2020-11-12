<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\AppointmentRepository;
use App\Repositories\OrderRepository;


class DashboardController extends Controller
{
    private $user_repo, $order_repo, $appointment_repo;

    public function __construct(UserRepository $user_repo, AppointmentRepository $appointment_repo, OrderRepository $order_repo)
    {
        $this->user_repo = $user_repo;
        $this->order_repo = $order_repo;
        $this->appointment_repo = $appointment_repo;
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
            $data['doctor'] = $this->user_repo->getUserCategoryWiseCount('4', '0');
            $data['nurses'] = $this->user_repo->getUserCategoryWiseCount('5', '0');
            $data['massage_therapist'] = $this->user_repo->getUserCategoryWiseCount('6', '0');
            $data['upcoming_appointment'] = $this->appointment_repo->getAppointmentStatusWiseCount(['0','1','2','3','4']);
            $data['completed_appointment'] = $this->appointment_repo->getAppointmentStatusWiseCount(['5']);
            $data['cancel_appointment'] = $this->appointment_repo->getAppointmentStatusWiseCount(['6']);
            $data['today_appointments'] = $this->appointment_repo->getTodayAppointmentStatusWiseCount();
            $data['pending_appointments'] = $this->appointment_repo->getAppointmentStatusWiseCount(['0','1']);

            return view('admin.healthcare.dashboard', compact('data'));
        }else if($provider == 'pharmacy'){

            $data['approved_count'] = $this->user_repo->getUserParentCategoryWiseCount('2','0');
            $data['pending_count'] = $this->user_repo->getUserParentCategoryWiseCount('2', '1');
            $data['pharmacist'] = $this->user_repo->getUserCategoryWiseCount('7', '0');
            $data['completed_order'] = $this->order_repo->getOrderStatusWiseCount('1');
            $data['cancel_order'] = $this->order_repo->getOrderStatusWiseCount('2');
            $data['today_orders'] = $this->order_repo->getTodayOrderStatusWiseCount();
            $data['pending_orders'] = $this->order_repo->getOrderStatusWiseCount('0');
            return view('admin.pharmacy.dashboard', compact('data'));
        }else if($provider == 'laboratories'){

            $data['approved_count'] = $this->user_repo->getUserParentCategoryWiseCount('3', '0');
            $data['pending_count'] = $this->user_repo->getUserParentCategoryWiseCount('3', '1');

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

            return view('admin.dashboard.dashboard', compact('data'));
        }
    }
   

}
