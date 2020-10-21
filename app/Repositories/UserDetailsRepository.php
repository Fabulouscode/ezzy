<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\User_details;
use Illuminate\Support\Str;
use Validator;

class UserDetailsRepository extends Repository
{
    protected $model_name = 'App\Models\User_details';
    protected $model;

    public function __construct()
    {
        parent::__construct();
    }

     /**
     * get Model and return the instance.
     *
     * @param int $user_id
     */
    public function getbyUserId($user_id)
    {
        return $this->model->where('user_id', $user_id)->first();
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $data
     * @return \Illuminate\Http\Response
     */
    public function dataCrud($request, $id = '')
    {   $data = array();
        if(!empty($request)){
            $filter = $request->all();
            foreach ($filter as $key => $value) {
                $data[$key] = $value;
            }
        }
        $user_details = $this->getbyColumnWithFirstValue('user_id', $request->user()->id);
        if(!empty($user_details) && !empty($user_details->id)){
            return $this->update($data, $user_details->id);
        } else {
            $data['user_id'] =  $request->user()->id;
            return $this->store($data);
        }
    }

}