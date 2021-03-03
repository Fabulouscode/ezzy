<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\User_available_time;
use Illuminate\Support\Str;
use App\Repositories\UserRepository;

class UserAvailableTimeRepository extends Repository
{
    protected $model_name = 'App\Models\User_available_time';
    protected $model;
    private $user_repo;

    public function __construct(UserRepository $user_repo)
    {
        parent::__construct();
        $this->user_repo = $user_repo;
    }

    public function getDayValue()
    {
        return $this->model->day_value;
    }

    public function getAppointmentTypeValue()
    {
        return $this->model->appointment_type_value;
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
     * Display a edit of the record.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkUserAvailableTime($request, $user_id)
    {   
        $day_arr = ['1','2','3','4','5'];
        $same_timing = $this->user_repo->getById($user_id);
        \Log::info("request send ".json_encode($request));           
        if($request['same_timing'] == '1' && !empty($same_timing->userDetails->same_timing) && $same_timing->userDetails->same_timing != '0'){
        \Log::info("same timing ".json_encode($same_timing->userDetails->same_timing));   
            $query = $this->model->where('appointment_type', $request['appointment_type'])
                                ->where(function($query) use ($request){
                                    $query->whereBetween('start_time', [$request['start_time'], $request['end_time']])
                                    ->orWhereBetween('end_time', [$request['start_time'], $request['end_time']]);
                                })
                                ->where('day', '7')
                                ->where('same_timing', '1')
                                ->where('user_id', $user_id);
        }else{  
            $query = $this->model->where('appointment_type', $request['appointment_type'])
                                ->where(function($query) use ($request){
                                    $query->whereBetween('start_time', [$request['start_time'], $request['end_time']])
                                    ->orWhereBetween('end_time', [$request['start_time'], $request['end_time']]);
                                })
                                ->where('day', $request['day'])
                                ->where('same_timing', '0')
                                ->where('user_id', $user_id);
        }

        $query = $query->first();
        \Log::info("result ".json_encode($query));     
        return $query;
    }

    /**
     * get Model and return the instance.
     *
     * @param int $user_id
     */
    public function getbyUserId($user_id)
    {
        return $this->model->where('user_id', $user_id)->orderby('day','asc')->orderby('start_time','asc')->get();
    }
   
    /**
     * get Model and return the instance.
     *
     * @param int $user_id
     */
    public function getbyUserIdWithAppointmentType($user_id, $appointment_type = '0')
    {
        return $this->model->where('user_id', $user_id)->where('appointment_type',$appointment_type)->orderby('day','asc')->orderby('start_time','asc')->get();
    }
    
}