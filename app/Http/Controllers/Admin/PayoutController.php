<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\UserTransactionRepository;

class PayoutController extends Controller
{
    private $user_transaction_repo;

    public function __construct(UserTransactionRepository $user_transaction_repo)
    {
        $this->user_transaction_repo = $user_transaction_repo;
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
                $data = ['payout_status' => '0','payout_date' => $this->user_transaction_repo->getCurrentDateTime()];
                $category = $this->user_transaction_repo->getById($value);
                if(!empty($category)){
                    $this->user_transaction_repo->dataCrud($data, $value);
                } 
            }
             return response()->json(['msg'=>'Payout success'], 200);
        }

          return response()->json(['msg'=>'Data Not success'], 500);
    }

   
}
