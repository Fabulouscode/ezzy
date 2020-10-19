<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\User_available_time;
use Illuminate\Support\Str;

class UserAvailableTimeRepository extends Repository
{
    protected $model_name = 'App\Models\User_available_time';
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
                    'day' => $request->day,
                    'appointment_type' => $request->appointment_type,
                    'start_time'=> $request->start_time,
                    'end_time' =>  $request->end_time,
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