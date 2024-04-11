<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\UserTransactionRepository;
use App\Repositories\PayoutAmountRepository;
use App\Repositories\UserRepository;
use App\Repositories\CategoryRepository;
use App\Http\Requests\Admin\PayoutAmountRequest;
use App\Exports\UserPayoutExport;
use Excel;

class PayoutAmountController extends Controller
{
    private $user_transaction_repo, $payout_amount_repo, $user_repo, $category_repo;

    public function __construct(CategoryRepository $category_repo, UserRepository $user_repo, PayoutAmountRepository $payout_amount_repo,UserTransactionRepository $user_transaction_repo)
    {
        $this->user_transaction_repo = $user_transaction_repo;
        $this->payout_amount_repo = $payout_amount_repo;
        $this->user_repo = $user_repo;
        $this->category_repo = $category_repo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->all()){
            return $this->payout_amount_repo->getDatatable($request);
        }
        $categories = $this->category_repo->getByMultipleParentIds(['1','2','3']);
        return view('admin.payout.index', compact('categories'));
    }
 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPayoutHistory(Request $request, $id = '')
    {
        if($request->all()){
            return $this->payout_amount_repo->getHistoryDatatable($request);
        }
        return view('admin.payout.history', compact('id'));
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPendingPayout(Request $request)
    {
        $categories = $this->category_repo->getByMultipleParentIds(['1','2','3']);
        return view('admin.payout.pending', compact('categories'));
    }
  
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPayouts(Request $request)
    {
        if($request->all()){
            return $this->user_transaction_repo->getDatatable($request);
        }
    }

    /**
     * paid payout.
     *
     * @return \Illuminate\Http\Response
     */
    public function savePayoutsInprocess(Request $request)
    {
        if(!empty($request->transaction_ids)){
            $some_user_no_bank = 0;
            $data = ['payout_status' => '3','payout_date' => $this->user_transaction_repo->getCurrentDateTime()];
            $user_data = $this->user_transaction_repo->userPayoutData($request->transaction_ids, '1');
            if(!empty($user_data) && count($user_data) > 0){
                foreach ($user_data as $key => $value) {
                    if(!empty($value->client->userBankAccount) && count($value->client->userBankAccount) > 0){
                        $user_transaction = $this->user_transaction_repo->getById($value->id);
                        if(!empty($user_transaction)){
                            $this->user_transaction_repo->dataCrud($data, $value->id);
                        } 
                    }else{
                         $some_user_no_bank = 1;
                    }
                }
            }

            if($some_user_no_bank == '1'){
                $notification_msg = 'Some User not Add Bank Account Please Add Bank then to Payout proceed';
            }else{
                $notification_msg = 'Payout success';
            }
        
            return response()->json(['msg'=>$notification_msg], 200);
        }

          return response()->json(['msg'=>'Data Not success'], 500);
    }

    /**
     * paid payout.
     *
     * @return \Illuminate\Http\Response
     */
    public function savePayoutsInprocessByUser($user_id)
    {
        if(!empty($user_id)){
            $some_user_no_bank = 0;
            $data = ['payout_status' => '3','payout_date' => $this->user_transaction_repo->getCurrentDateTime()];
            $user_data = $this->user_transaction_repo->userPayoutData([$user_id], '1');
            if(!empty($user_data) && count($user_data) > 0){
                foreach ($user_data as $key => $value) {
                    if(!empty($value->client->userBankAccount) && count($value->client->userBankAccount) > 0){
                        $user_transaction = $this->user_transaction_repo->getById($value->id);
                        if(!empty($user_transaction)){
                            $this->user_transaction_repo->dataCrud($data, $value->id);
                        } 
                    }else{
                         $some_user_no_bank = 1;
                    }
                }
            }

            if($some_user_no_bank == '1'){
                $notification_msg = 'Please Add Bank Account then to Payout proceed';
            }else{
                $notification_msg = 'Payout success';
            }
        
            if($some_user_no_bank == '1'){
                return response()->json(['msg'=>$notification_msg], 500);
            }else{
                return response()->json(['msg'=>$notification_msg], 200);
            }
        }

          return response()->json(['msg'=>'Data Not success'], 500);
    }
 
    /**
     * paid payout.
     *
     * @return \Illuminate\Http\Response
     */
    public function savePayoutTransaction(PayoutAmountRequest $request)
    {
        $data = $request->all();
        $user_data = $this->user_transaction_repo->userPayoutData([$request->user_id], '3');
        if(!empty($data) && count($user_data) > 0){
            $user = $this->user_repo->getById($request->user_id);
            
            $data = [
                        'user_id' => $request->user_id,
                        'user_bank_account_id'=> !empty($user->userPrimaryBankAccount) ? $user->userPrimaryBankAccount->id : NULL,
                        'amount'=> $request->amount,
                        'deduction_amount'=> $request->deduction,
                        'payable_amount'=> $request->payout_amount,
                        'notes'=> $request->notes,
                        'bank_transaction_id'=> $request->bank_transaction_id,
                        'approved_by'=> !empty($user->userPrimaryBankAccount) ? $request->approved_by : NULL,
                        'admin_id'=> $request->user()->id,
                        'approved_date' => $this->payout_amount_repo->getCurrentDateTime()
                    ];
            $this->payout_amount_repo->dataCrud($data);
  
            
            $user_data = $this->user_transaction_repo->userPayoutData([$request->user_id], '3');
            $payout_data = ['payout_status' => '0','payout_date' => $this->payout_amount_repo->getCurrentDateTime()];
            if(!empty($user_data) && count($user_data) > 0){
                foreach ($user_data as $key => $value) {
                    $user_transaction = $this->user_transaction_repo->getById($value->id);
                    if(!empty($user_transaction)){
                        $this->user_transaction_repo->dataCrud($payout_data, $value->id);
                    } 
                }
            }
            return response()->json(['msg'=>'Payout success'], 200);
        }

          return response()->json(['msg'=>'No any payment In-progress Please check'], 500);
    }

    /**
     * paid payout.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPayoutExport(Request $request)
    {
        if(!empty($request->transaction_ids ) || !empty($request->category_id)){
            $payout_file = Excel::raw(new UserPayoutExport($request->transaction_ids, $request->category_id), \Maatwebsite\Excel\Excel::XLSX);
        }else{
            $payout_file = Excel::raw(new UserPayoutExport(), \Maatwebsite\Excel\Excel::XLSX);
        }

        $response =  array(
            'name' => "payout_users", //no extention needed
            'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($payout_file) //mime type of used format
        );
            
        $notification_msg = 'Payout success';
    
        return response()->json(['data'=>$response, 'msg'=>$notification_msg], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTransactionList(Request $request)
    {
        // dd($request->category_id);
        $categories = $this->category_repo->getByMultipleParentIds(['1','2','3']);  
        return view('admin.payout.transaction_list', compact('categories'));
    }

    public function getUserWalletDepositTransactionList(Request $request)
    {
        return view('admin.payout.user_depoist_list');
    }

    public function getHealthcareProvidersCalculate(Request $request)
    {
        $transaction_calc = $this->user_transaction_repo->getHCPTransactionCalculate($request);
        return response()->json($transaction_calc, 200);
    }

    public function getTransactionData(Request $request)
    {
        // dd($request->start_date);
        if($request->all()){
            return $this->user_transaction_repo->getTransactionDatatable($request);
        }
    }
 
    public function getUserWalletDepositTransactionData(Request $request)
    {
        if($request->all()){
            return $this->user_transaction_repo->getUserWalletDepositTransactionData($request);
        }
    }

    
    public function getUserWalletDepositTransactionCalculate(Request $request)
    {
        \Log::info('getUserWalletDepositTransactionCalculate');
        \Log::info($request->all());
        $transaction_calc = $this->user_transaction_repo->getUserWalletDepositTransactionCalculate($request);
        return response()->json($transaction_calc, 200);
    }

}
