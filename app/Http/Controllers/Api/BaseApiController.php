<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\CustomEncrypt;
use Carbon\Carbon;
use App\Repositories\CategoryRepository;
use App\Repositories\AppointmentRepository;
use App\Repositories\SupportRequestRepository;
use App\Repositories\UserRepository;
use App\Repositories\UserDetailsRepository;
use App\Repositories\UserReviewRepository;
use App\Repositories\UserAvailableTimeRepository;
use App\Repositories\UserBankAccountRepository;
use App\Repositories\UserTransactionRepository;
use App\Repositories\UserEductaionRepository;
use App\Repositories\UserExperianceRepository;
use App\Repositories\MedicineCategoryRepository;
use App\Repositories\MedicineSubcategoryRepository;
use App\Repositories\MedicineDetailsRepository;
use App\Repositories\ShopMedicineDetailsRepository;
use App\Repositories\ShoppingCartRepository;
use App\Repositories\OrderRepository;
use App\Repositories\OrderProductRepository;
use App\Repositories\OrderTrackingRepository;
use App\Repositories\FavoriteMedicineRepository;
use App\Repositories\ServicesRepository;
use App\Repositories\UserServiceRepository;
use App\Repositories\AppointmentServiceRepository;

class BaseApiController extends Controller
{
    private $ecnrypter;
    public $user_repo, $category_repo, $appointment_repo, $support_request_repo,
            $user_available_time_repo, $user_bank_account_repo, $user_education_repo, $user_service_repo,
            $user_trans_repo, $user_experiance_repo, $user_details_repo, $user_review_repo, $service_repo,
            $medicine_details_repo, $medicine_subcategory_repo, $medicine_category_repo, $shop_medicine_repo,
            $shop_cart_repo, $order_repo, $order_product_repo, $order_tracking_repo, $favorite_medicine_repo,
            $appointment_service_repo;

    public function __construct(
        CategoryRepository $category_repo,
        AppointmentRepository $appointment_repo,
        SupportRequestRepository $support_request_repo,
        UserRepository $user_repo,
        UserDetailsRepository $user_details_repo,
        UserReviewRepository $user_review_repo,
        UserAvailableTimeRepository $user_available_time_repo,
        UserBankAccountRepository $user_bank_account_repo,
        UserTransactionRepository $user_trans_repo,
        UserEductaionRepository $user_education_repo,
        UserExperianceRepository $user_experiance_repo,
        MedicineSubcategoryRepository $medicine_subcategory_repo, 
        MedicineCategoryRepository $medicine_category_repo,
        MedicineDetailsRepository $medicine_details_repo,
        ShopMedicineDetailsRepository $shop_medicine_repo,        
        ShoppingCartRepository $shop_cart_repo,
        OrderRepository $order_repo,
        OrderProductRepository $order_product_repo,
        OrderTrackingRepository $order_tracking_repo,
        FavoriteMedicineRepository $favorite_medicine_repo,
        ServicesRepository $service_repo,
        UserServiceRepository $user_service_repo,
        AppointmentServiceRepository $appointment_service_repo

    ){
        
       $this->ecnrypter = new CustomEncrypt();   
       $this->category_repo = $category_repo;
       $this->appointment_repo = $appointment_repo;
       $this->support_request_repo = $support_request_repo;
       $this->user_repo = $user_repo;
       $this->user_details_repo = $user_details_repo;
       $this->user_review_repo = $user_review_repo;
       $this->user_available_time_repo = $user_available_time_repo;
       $this->user_bank_account_repo = $user_bank_account_repo;
       $this->user_trans_repo = $user_trans_repo;
       $this->user_education_repo = $user_education_repo;
       $this->user_experiance_repo = $user_experiance_repo;
       $this->medicine_details_repo = $medicine_details_repo;
       $this->medicine_subcategory_repo = $medicine_subcategory_repo;
       $this->medicine_category_repo = $medicine_category_repo;
       $this->shop_medicine_repo = $shop_medicine_repo;
       $this->shop_cart_repo = $shop_cart_repo;
       $this->order_repo = $order_repo;
       $this->order_product_repo = $order_product_repo;
       $this->order_tracking_repo = $order_tracking_repo;
       $this->favorite_medicine_repo = $favorite_medicine_repo;
       $this->service_repo = $service_repo;
       $this->user_service_repo = $user_service_repo;
       $this->appointment_service_repo = $appointment_service_repo;
    }

    public function sendSuccess($result, $message = '') {
       return response()->json($this->ecnrypter->encrypt($result), 200);
    }

    public function sendError($errors, $errorMessage='', $code = 500) {
        return response()->json([
            'success' => false,
            'utc_time'=> Carbon::now()->format('Y-m-d H:i:s'),
            'errors' => $errors,
            'message' => $errorMessage,
        ], $code);
    }
    
    public function sendException($ex) {
        return response()->json([
            'success' => false,
            'message' => config('app.debug') ? $ex->getMessage().' at '.$ex->getLine() : 'Some Internal Server Error'
        ], 500);
    }
    
   
}
