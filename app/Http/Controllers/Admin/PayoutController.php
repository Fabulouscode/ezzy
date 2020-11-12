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
        if($request->all()){
            return $this->user_transaction_repo->getDatatable($request);
        }
        return view('admin.payout.index');
    }

   
}
