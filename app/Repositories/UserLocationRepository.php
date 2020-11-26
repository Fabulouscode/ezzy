<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\User_location;
use Illuminate\Support\Str;

class UserLocationRepository extends Repository
{
    protected $model_name = 'App\Models\User_location';
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
    public function dataCrud($data, $id = '')
    {   
        if(!empty($data)){
            if(!empty($id)){
                return $this->update($data, $id);
            } else {
                return $this->store($data);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $data
     * @return \Illuminate\Http\Response
     */
    public function updatebyUserId($data, $user_id)
    {   
        return $this->model->where('user_id', $user_id)->update($data);
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