<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;

class TransactionController extends BaseApiController
{
    public function getTransactionHistory(Request $request){
        $data = $this->debit_trans_repo->getTransactionHistory($request); 
        return self::sendSuccess($data, 'Transaction History get');
    }
}
