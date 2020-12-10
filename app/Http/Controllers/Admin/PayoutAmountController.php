<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\UserTransactionRepository;
use App\Repositories\PayoutAmountRepository;
use App\Http\Requests\Admin\PayoutAmountRequest;
use App\Exports\UserPayoutExport;
use Excel;

class PayoutAmountController extends Controller
{
    private $user_transaction_repo, $payout_amount_repo;

    public function __construct(PayoutAmountRepository $payout_amount_repo,UserTransactionRepository $user_transaction_repo)
    {
        $this->user_transaction_repo = $user_transaction_repo;
        $this->payout_amount_repo = $payout_amount_repo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.payout.index');
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
    public function paidPayouts(Request $request)
    {
        if(!empty($request->transaction_ids)){
            foreach ($request->transaction_ids as $key => $value) {
                $data = ['payout_status' => '2','payout_date' => $this->user_transaction_repo->getCurrentDateTime()];
                $category = $this->user_transaction_repo->getById($value);
                if(!empty($category)){
                    $this->user_transaction_repo->dataCrud($data, $value);
                } 
            }
            return response()->json(['msg'=>'Payout success'], 200);
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
        if(!empty($data)){
            $data = [
                        'user_id' => $request->user_id,
                        'user_bank_account_id'=> $request->user_bank_account_id,
                        'amount'=> $request->amount,
                        'deduction_amount'=> $request->deduction_amount,
                        'payable_amount'=> $request->payable_amount,
                        'notes'=> $request->notes,
                        'bank_transaction_id'=> $request->bank_transaction_id,
                        'approved_by'=> $request->approved_by,
                        'admin_id'=> $request->user()->id,
                        'approved_date' => $this->payout_amount_repo->getCurrentDateTime()
                    ];
            $this->payout_amount_repo->dataCrud($data);
            return response()->json(['msg'=>'Payout success'], 200);
        }

          return response()->json(['msg'=>'Data Not success'], 500);
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
