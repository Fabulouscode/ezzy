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
use App\Repositories\UserAvailableTimeRepository;
use App\Repositories\UserBankAccountRepository;
use App\Repositories\CreditTransactionRepository;
use App\Repositories\DebitTransactionRepository;
use App\Repositories\UserEductaionRepository;
use App\Repositories\UserExperianceRepository;

class BaseApiController extends Controller
{
    private $ecnrypter;
    public $user_repo, $category_repo, $appointment_repo, $support_request_repo,
            $user_available_time_repo, $user_bank_account_repo, $user_education_repo, 
            $credit_trans_repo, $debit_trans_repo, $user_experiance_repo, $user_details_repo;

    public function __construct(
        CategoryRepository $category_repo,
        AppointmentRepository $appointment_repo,
        SupportRequestRepository $support_request_repo,
        UserRepository $user_repo,
        UserDetailsRepository $user_details_repo,
        UserAvailableTimeRepository $user_available_time_repo,
        UserBankAccountRepository $user_bank_account_repo,
        CreditTransactionRepository $credit_trans_repo,
        DebitTransactionRepository $debit_trans_repo,
        UserEductaionRepository $user_education_repo,
        UserExperianceRepository $user_experiance_repo
    ){
        
       $this->ecnrypter = new CustomEncrypt();   
       $this->category_repo = $category_repo;
       $this->appointment_repo = $appointment_repo;
       $this->support_request_repo = $support_request_repo;
       $this->user_repo = $user_repo;
       $this->user_details_repo = $user_details_repo;
       $this->user_available_time_repo = $user_available_time_repo;
       $this->user_bank_account_repo = $user_bank_account_repo;
       $this->credit_trans_repo = $credit_trans_repo;
       $this->debit_trans_repo = $debit_trans_repo;
       $this->user_education_repo = $user_education_repo;
       $this->user_experiance_repo = $user_experiance_repo;
    }

    public function sendSuccess($result, $message = '') {
       return response()->json($this->ecnrypter->encrypt($result), 200);
    }

    public function sendError($errors, $errorMessage, $code = 500) {
        return response()->json([
            'success' => false,
            'utc_time'=> Carbon::now()->format('Y-m-d H:i:s'),
            'errors' => $errors,
            'message' => $errorMessage,
        ], $code);
    }

    
   
}
