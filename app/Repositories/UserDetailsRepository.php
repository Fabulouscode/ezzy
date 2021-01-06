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
    
    public $user_documents = array(
        '0' => 'profile_picture',
        '1' => 'qualification_certificate',
        '2' => 'practicing_licence',
        '3' => 'health_facility_certificate',
        '4' => 'regstration_certificate',
        '5' => 'pharmacist_certificate',
        '6' => 'support_request',
        '7' => 'client_lab_report',
        '8' => 'chat_attachment',
    );

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
        $json_enode_key = ['allergies','current_medications','past_medications'.'chronic_disease','injuries','surgeries'];
        if(!empty($request)){
            $filter = $request->all();
            foreach ($filter as $key => $value) {
                if(in_array($key, $json_enode_key)){
                    $data[$key] = json_encode($value);
                }else{
                    $data[$key] = $value;
                }
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