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
use Helper;

class UserDetailsRepository extends Repository
{
    protected $model_name = 'App\Models\User_details';
    
    protected $model;

    protected $json_enode_key = ['allergies','current_medications','past_medications'.'chronic_disease','injuries','surgeries'];
    protected $file_upload_key = ['profile_picture','qualification_certificate','practicing_licence','health_facility_certificate','regstration_certificate','pharmacist_certificate','support_request','client_lab_report','chat_attachment'];

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
     * get Model and return the instance.
     *
     * @param int $user_id
     */
    public function getbyDelete($user_id)
    {
        return $this->model->where('user_id', $user_id)->delete();
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $data
     * @return \Illuminate\Http\Response
     */
    public function dataCrudUsingData($request, $id = '')
    {  
        $data = array();
        if(!empty($request)){
            foreach ($request as $key => $value) {
                if(in_array($key, $this->json_enode_key)){
                    $data[$key] = json_encode($value);
                }else if($key == 'urgent_criteria'){
                    if(is_array($value)){
                        $data[$key] = implode(',', $value);
                    }else{
                        $data[$key] = '';
                    }
                }else{
                    $data[$key] = $value;
                }
            }
        }
        if(!empty($data['user_id'])){
            $user_details = $this->getbyColumnWithFirstValue('user_id', $data['user_id']);
        }
        if(!empty($user_details) && !empty($user_details->id)){
            return $this->update($data, $user_details->id);
        } else {
            return $this->store($data);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $data
     * @return \Illuminate\Http\Response
     */
    public function dataCrudUsingDataFileUpload($request, $id = '')
    {  
        $data = array();
        if(!empty($request)){
            foreach ($request as $key => $value) {
                if(in_array($key, $this->json_enode_key)){
                    $data[$key] = json_encode($value);
                }else if($key == 'urgent_criteria'){
                    if(is_array($value)){
                        $data[$key] = implode(',', $value);
                    }else{
                        $data[$key] = '';
                    }
                }else{
                    $data[$key] = $value;
                }
                // user tracking files
                
              
                if(in_array($key, $this->file_upload_key) && !empty($request['user_id'])){    
                    if(!empty(request()->user()) && request()->user()->getTable() == 'admins'){
                        Helper::addUserTracking('1',request()->user()->id, $request['user_id'], $key, $value);
                    }else if(!empty(request()->user())){
                        Helper::addUserTracking('0', '',request()->user()->id, $key, $value);
                    }
                }
            }
        }
        if(!empty($data['user_id'])){
            $user_details = $this->getbyColumnWithFirstValue('user_id', $data['user_id']);
        }
        if(!empty($user_details) && !empty($user_details->id)){
            return $this->update($data, $user_details->id);
        } else {
            return $this->store($data);
        }
    }
     

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $data
     * @return \Illuminate\Http\Response
     */
    public function dataCrud($request, $id = '')
    {  
        $data = array();
        if(!empty($request)){
            $filter = $request->all();
            foreach ($filter as $key => $value) {
                if(in_array($key, $this->json_enode_key)){
                    $data[$key] = json_encode($value);
                }else if($key == 'urgent_criteria'){
                    if(is_array($value)){
                        $data[$key] = implode(',', $value);
                    }else{
                        $data[$key] = '';
                    }
                }else{
                    $data[$key] = $value;
                }

                // user tracking files
                if(in_array($key, $this->file_upload_key) && !empty($request->user()->id)){    
                    if(!empty(request()->user()) && request()->user()->getTable() == 'admins'){
                        Helper::addUserTracking('1',request()->user()->id, $request->user()->id, $key, $value);
                    }else if(!empty(request()->user())){
                        Helper::addUserTracking('0', '',request()->user()->id, $key, $value);
                    }
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
     
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $data
     * @return \Illuminate\Http\Response
     */
    public function dataCrudByArray($filter, $id = '')
    {  
        $data = array();
        if(!empty($filter)){
            foreach ($filter as $key => $value) {
                if(in_array($key, $this->json_enode_key)){
                    $data[$key] = json_encode($value);
                }else{
                    $data[$key] = $value;
                }

                // user tracking files
                if(in_array($key, $this->file_upload_key) && !empty($id)){    
                    if(!empty(request()->user()) && request()->user()->getTable() == 'admins'){
                        Helper::addUserTracking('1',request()->user()->id, $id, $key, $value);
                    }else if(!empty(request()->user())){
                        Helper::addUserTracking('0', '',request()->user()->id, $key, $value);
                    }
                }
            }
        }
        $user_details = $this->getbyColumnWithFirstValue('user_id', $id);
        if(!empty($user_details) && !empty($user_details->id)){
            return $this->update($data, $user_details->id);
        } else {
            $data['user_id'] =  $id;
            return $this->store($data);
        }
    }

}