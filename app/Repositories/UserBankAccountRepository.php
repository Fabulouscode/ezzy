<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\User_bank_account;
use Illuminate\Support\Str;

class UserBankAccountRepository extends Repository
{
    protected $model_name = 'App\Models\User_bank_account';
    protected $model;

    public function __construct()
    {
        parent::__construct();
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $data
     * @return \Illuminate\Http\Response
     */
    public function dataCrud($request, $id = '')
    {   $data = [
                    'user_id' => $request->user()->id,
                    'name' => $request->name,
                    'bank_name' => $request->bank_name,
                    'bank_branch_name'=> $request->bank_branch_name,
                    'account_number' => $request->account_number,
                    'ifsc_code' => $request->ifsc_code,
                ];
        if(!empty($id)){
            return $this->update($data, $id);
        } else {
            return $this->store($data);
        }
    }
    
    /**
     * get Model and return the instance.
     *
     * @param int $user_id
     */
    public function getbyUserId($user_id)
    {
        return $this->model->where('user_id', $user_id)->get();
    }
    
}