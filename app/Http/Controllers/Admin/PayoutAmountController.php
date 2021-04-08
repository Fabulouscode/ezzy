<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\UserTransactionRepository;
use App\Repositories\PayoutAmountRepository;
use App\Repositories\UserRepository;
use App\Http\Requests\Admin\PayoutAmountRequest;
use App\Exports\UserPayoutExport;
use Excel;

class PayoutAmountController extends Controller
{
    private $user_transaction_repo, $payout_amount_repo, $user_repo;

    public function __construct(UserRepository $user_repo, PayoutAmountRepository $payout_amount_repo,UserTransactionRepository $user_transaction_repo)
    {
        $this->user_transaction_repo = $user_transaction_repo;
        $this->payout_amount_repo = $payout_amount_repo;
        $this->user_repo = $user_repo;
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
        return view('admin.payout.index');
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
        return view('admin.payout.pending');
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
            $data = ['payout_status' => '3','payout_date' => $this->user_transaction_repo->getCurrentDateTime()];
            $user_data = $this->user_transaction_repo->userPayoutData($request->transaction_ids, '1');
            if(!empty($user_data) && count($user_data) > 0){
                foreach ($user_data as $key => $value) {
                    $user_transaction = $this->user_transaction_repo->getById($value->id);
                    if(!empty($user_transaction)){
                        $this->user_transaction_repo->dataCrud($data, $value->id);
                    } 
                }
            }

            $payout_file = Excel::raw(new UserPayoutExport('3'), \Maatwebsite\Excel\Excel::XLSX);
            
            $response =  array(
                'name' => "payout_users", //no extention needed
                'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($payout_file) //mime type of used format
            );
        
            return response()->json(['data'=>$response, 'msg'=>'Payout success'], 200);
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
       return Excel::download(new UserPayoutExport('1'), 'payout_users.xlsx');
    }
}
