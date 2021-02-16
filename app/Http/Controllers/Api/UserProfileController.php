<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\UserDetailsRepository;
use App\Repositories\UserBankAccountRepository;
use App\Repositories\UserAvailableTimeRepository;
use App\Repositories\UserEductaionRepository;
use App\Repositories\UserExperianceRepository;
use App\Repositories\UserLocationRepository;
use App\Http\Requests\Api\UserBankAccountRequest;
use App\Http\Requests\Api\UserCardRequest;
use App\Http\Requests\Api\UserAvailableTimesRequest;
use App\Http\Requests\Api\UserEducationDetailsRequest;
use App\Http\Requests\Api\UserExperianceDetailsRequest;
use App\Http\Requests\Api\UserLocationRequest;
use App\Http\Requests\Api\UploadFileRequest;
use App\Http\Requests\Api\UploadDocFileRequest;
use App\Http\Requests\Api\UserRequest;

class UserProfileController extends BaseApiController
{
    private $user_repo, $user_details_repo, $user_bank_account_repo, $user_location_repo, $user_available_time_repo, $user_education_repo, $user_experiance_repo;

    public function __construct(
        UserRepository $user_repo,
        UserDetailsRepository $user_details_repo,
        UserBankAccountRepository $user_bank_account_repo,
        UserAvailableTimeRepository $user_available_time_repo,
        UserEductaionRepository $user_education_repo,
        UserExperianceRepository $user_experiance_repo,
        UserLocationRepository $user_location_repo
        )
    {
        parent::__construct();
        $this->user_repo = $user_repo;
        $this->user_details_repo = $user_details_repo;
        $this->user_bank_account_repo = $user_bank_account_repo;
        $this->user_available_time_repo = $user_available_time_repo;
        $this->user_education_repo = $user_education_repo;
        $this->user_experiance_repo = $user_experiance_repo;
        $this->user_location_repo = $user_location_repo;
    }


    // user add details
    public function addUserDetails(Request $request)
    {
        try{
            $user = $this->user_repo->dataCrud($request, $request->user()->id);
            $this->user_details_repo->dataCrud($request);
            return self::sendSuccess($user, 'User Profile Add Successfully');
        }catch(\Exception $e){
            return self::sendException($e);
        }
    }

    // upload Document
    public function uploadImageFile(UploadFileRequest $request)
    {
        $user_document = $this->user_details_repo->user_documents;
        if(!empty($request->file('document')) && !empty($user_document)) {          
            $file = $request->file('document');
            $storagePath = 'images/'.$user_document[$request->document_key];
            $data['file'] = $this->user_repo->uploadFolderWiseFile($file, $storagePath);
            $data['url'] = url('storage/'.$data['file']);
            return self::sendSuccess($data, 'file Upload Successfully');
        }
        return self::sendError('', 'File Not Uploaded', 500);
    }

    // upload Document
    public function uploadDocumentFile(UploadDocFileRequest $request)
    {
        $user_document = $this->user_details_repo->user_documents;
        if(!empty($request->file('document')) && !empty($user_document)) {          
            $file = $request->file('document');
            $storagePath = 'images/'.$user_document[$request->document_key];
            $data['file'] = $this->user_repo->uploadFolderWiseFile($file, $storagePath);
            $data['url'] = url('storage/'.$data['file']);
            return self::sendSuccess($data, 'file Upload Successfully');
        }
        return self::sendError('', 'File Not Uploaded', 500);
    }

    // bank details
    public function getUserBankDetails(Request $request)
    {
        $data = array();
        $data = $this->user_bank_account_repo->getbyUserId($request->user()->id);
        return self::sendSuccess($data, 'Bank account details');
    }

    public function addUserCardDetails(UserCardRequest $request)
    {
        $data = array();
        $primary_status = 0;
        $primary_account = $this->user_bank_account_repo->getbyUserId($request->user()->id);
        if(isset($primary_account) && count($primary_account) == '0'){
            $primary_status = 1;
        }
        $add_data = [
                        'user_id' => $request->user()->id,
                        'name' => $request->name,
                        'bank_name' => $request->bank_name,
                        'card_number'=> $request->card_number,
                        'card_expiry' => $request->card_expiry,
                        'primary_account' => $primary_status,
                    ];
        try{
            $data = $this->user_bank_account_repo->dataCrud($add_data);
            return self::sendSuccess($data, 'Bank account details Add Successfully');
        }catch(\Exception $e){
            return self::sendException($e);
        }
    }
    
    public function updateUserCardDetails(UserCardRequest $request)
    {
        $data = array();
        $update_data = [
                        'name' => $request->name,
                        'bank_name' => $request->bank_name,
                        'card_number'=> $request->card_number,
                        'card_expiry' => $request->card_expiry,
                        ];
        try{
            $data = $this->user_bank_account_repo->dataCrud($update_data, $request->id);
            return self::sendSuccess($data, 'Card details Update Successfully');
        }catch(\Exception $e){
            return self::sendException($e);
        }
    }
    public function addUserBankDetails(UserBankAccountRequest $request)
    {
        $data = array();
        $primary_status = 0;
        $primary_account = $this->user_bank_account_repo->getbyUserId($request->user()->id);
        if(isset($primary_account) && count($primary_account) == '0'){
            $primary_status = 1;
        }
        $add_data = [
                    'user_id' => $request->user()->id,
                    'name' => $request->name,
                    'bank_name' => $request->bank_name,
                    'bank_branch_name'=> $request->bank_branch_name,
                    'account_number' => $request->account_number,
                    'ifsc_code' => $request->ifsc_code,
                    'primary_account' => $primary_status,
                    ];
        try{
            $data = $this->user_bank_account_repo->dataCrud($add_data);
            return self::sendSuccess($data, 'Bank account details Add Successfully');
        }catch(\Exception $e){
            return self::sendException($e);
        }
    }
    
    public function updateUserBankDetails(UserBankAccountRequest $request)
    {
        $data = array();
        $update_data = [
                        'name' => $request->name,
                        'bank_name' => $request->bank_name,
                        'bank_branch_name'=> $request->bank_branch_name,
                        'account_number' => $request->account_number,
                        'ifsc_code' => $request->ifsc_code,
                        ];
        try{
            $data = $this->user_bank_account_repo->dataCrud($update_data, $request->id);
            return self::sendSuccess($data, 'Bank account details Update Successfully');
        }catch(\Exception $e){
            return self::sendException($e);
        }
    }

    public function getByIdUserBankDetails($id)
    {
        $data = array();
        $data = $this->user_bank_account_repo->getbyId($id);
        return self::sendSuccess($data, 'Bank account details');
    }

    public function updatePrimaryUserBankDetails(Request $request, $id)
    {   
        $data = ['primary_account' => '0'];
        $update_data = ['primary_account' => '1'];
        try{
            $this->user_bank_account_repo->updatebyUserId($data, $request->user()->id);
            $this->user_bank_account_repo->dataCrud($update_data, $id);
            return self::sendSuccess([], 'Primary Bank account Update Successfully');
        }catch(\Exception $e){
            return self::sendException($e);
        }

        return self::sendSuccess($data, 'Bank account info');
    }

    public function deleteUserBankDetails($id){
        $data = $this->user_bank_account_repo->getById($id);
        if(!empty($data)){
            if($data->primary_account == '1'){
                return self::sendError('', 'Primary Bank account Not Deleted', 500);
            }
            
            try{
                $this->user_bank_account_repo->destroy($id); 
                return self::sendSuccess([], 'Bank account details Deleted Successfully');
            }catch(\Exception $e){
               return self::sendError('', 'You can not delete this Bank account details', 500);
            }
            
        }
        return self::sendError('', 'Bank account not Deleted', 500);
    }


    // available times
    public function getUserAvailableTimes(Request $request)
    {
        $data = array();
        $data = $this->user_available_time_repo->getbyUserId($request->user()->id);
        return self::sendSuccess($data, 'User Available times');
    }

    public function addUserAvailableTimes(UserAvailableTimesRequest $request)
    {
        $data = array();
        $add_data = [
                    'user_id' => $request->user()->id,
                    'day' => (isset($request->day)) ? $request->day : '',
                    'appointment_type' => $request->appointment_type,
                    'start_time'=> $request->start_time,
                    'end_time' =>  $request->end_time,
                    'same_timing' => (!empty($request->same_timing)) ?  $request->same_timing : 0,
                    ];
        try{
            $data = $this->user_available_time_repo->dataCrud($add_data);
            return self::sendSuccess($data, 'Available times details Add Successfully');
        }catch(\Exception $e){
            return self::sendException($e);
        }
    }
    
    public function updateUserAvailableTimes(UserAvailableTimesRequest $request)
    {
        $data = array();
        $update_data = [
                    'day' => (isset($request->day)) ? $request->day : '',
                    'appointment_type' => $request->appointment_type,
                    'start_time'=> $request->start_time,
                    'end_time' =>  $request->end_time,
                    'same_timing' => (isset($request->same_timing)) ?  $request->same_timing : 0,
                    ];
        try{
            $data = $this->user_available_time_repo->dataCrud($update_data, $request->id);
            return self::sendSuccess($data, 'Available times details Update Successfully');
        }catch(\Exception $e){
            return self::sendException($e);
        }
    }

    public function getByIdUserAvailableTimes($id)
    {
        $data = array();
        $data = $this->user_available_time_repo->getbyId($id);
        return self::sendSuccess($data, 'Available times details');
    }
   
    public function getByUserWithAppointmentTypeAvailableTimes(Request $request, $type)
    {
        $data = array();
        $user_details = $this->user_details_repo->getbyUserId($request->user()->id);
        if(!empty($user_details) && $user_details->availability == '1'){
            $data = $this->user_available_time_repo
                                    ->getbyUserIdWithAppointmentType($request->user()->id, $type)
                                    ->map(function ($response){
                                    return [
                                        "appointment_type"=> $response->appointment_type,
                                        "start_time"=> $response->start_time,
                                        "end_time"=> $response->end_time,
                                        "same_timing"=> $response->same_timing,
                                        "day_name"=> $response->day_name,
                                        "appointment_type_name"=> $response->appointment_type_name,
                                    ];
                                });;
            return self::sendSuccess($data, 'Available times details');   
        }else{
            return self::sendSuccess($data, 'User not available');
        }
    }
   
    public function deleteUserAvailableTimes($id)
    {
        $data = $this->user_available_time_repo->getById($id);
        if(!empty($data)){
            try{
                $this->user_available_time_repo->destroy($id); 
                return self::sendSuccess([], 'Available times details Deleted Successfully');
            }catch(\Exception $e){
                return self::sendError('', 'You can not delete this Available times details', 500);
            }
          
        }
        return self::sendError('', 'Available times not Deleted', 500);
    }


    // education details
    public function getUserEducationDetails(Request $request)
    {
        $data = array();
        $data = $this->user_education_repo->getbyUserId($request->user()->id);
        return self::sendSuccess($data, 'User Education details');
    }

    public function addUserEducationDetails(UserEducationDetailsRequest $request)
    {
        $data = array();
        $add_data = [
                    'user_id' => $request->user()->id,
                    'college_name' => $request->college_name,
                    'degree_name' => $request->degree_name,
                    'start_year'=> $request->start_year,
                    'end_year' => $request->end_year,
                    'currently_work' => $request->currently_work,
                    ];
        try{
            $data = $this->user_education_repo->dataCrud($add_data);
            return self::sendSuccess($data, 'Education details Add Successfully');
        }catch(\Exception $e){
            return self::sendException($e);
        }
    }
    
    public function updateUserEducationDetails(UserEducationDetailsRequest $request)
    {
        $data = array();
        $update_data = [
                        'college_name' => $request->college_name,
                        'degree_name' => $request->degree_name,
                        'start_year'=> $request->start_year,
                        'end_year' => $request->end_year,
                        'currently_work' => $request->currently_work,
                        ];
        try{
            $data = $this->user_education_repo->dataCrud($update_data, $request->id);
            return self::sendSuccess($data, 'Education details Update Successfully');
        }catch(\Exception $e){
            return self::sendException($e);
        }
    }

    public function getByIdUserEducationDetails($id)
    {
        $data = array();
        $data = $this->user_education_repo->getbyId($id);
        return self::sendSuccess($data, 'Education details info');
    }
   
    public function deleteUserEducationDetails($id)
    {
        $data = $this->user_education_repo->getById($id);
        if(!empty($data)){
            try{
                $this->user_education_repo->destroy($id); 
                return self::sendSuccess([], 'Education details Deleted Successfully');
            }catch(\Exception $e){
                return self::sendError('', 'You can not delete this Education details', 500);
            }
        }
        return self::sendError('', 'Education details not Deleted', 500);
    }


    // experiance details
    public function getUserExperianceDetails(Request $request)
    {
        $data = array();
        $data = $this->user_experiance_repo->getbyUserId($request->user()->id);
        return self::sendSuccess($data, 'User Experiance details');
    }

    public function addUserExperianceDetails(UserExperianceDetailsRequest $request)
    {
        $data = array();
        $add_data = [
                    'user_id' => $request->user()->id,
                    'name' => $request->name,
                    'descritption' => $request->descritption,
                    'start_year'=> $request->start_year,
                    'end_year' => $request->end_year,
                    'currently_work' => $request->currently_work,
                    ];
        try{
            $data = $this->user_experiance_repo->dataCrud($add_data);
            return self::sendSuccess($data, 'Experiance details Add Successfully');
        }catch(\Exception $e){
            return self::sendException($e);
        }
    }
    
    public function updateUserExperianceDetails(UserExperianceDetailsRequest $request)
    {
        $data = array();
        $update_data = [
                        'name' => $request->name,
                        'descritption' => $request->descritption,
                        'start_year'=> $request->start_year,
                        'end_year' => $request->end_year,
                        'currently_work' => $request->currently_work,
                        ];
        try{
            $data = $this->user_experiance_repo->dataCrud($update_data, $request->id);
            return self::sendSuccess($data, 'Experiance details Update Successfully');
        }catch(\Exception $e){
            return self::sendException($e);
        }
    }

    public function getByIdUserExperianceDetails($id)
    {
        $data = array();
        $data = $this->user_experiance_repo->getbyId($id);
        return self::sendSuccess($data, 'Experiance details info');
    }
   
    public function deleteUserExperianceDetails($id)
    {
        $data = $this->user_experiance_repo->getById($id);
        if(!empty($data)){
            try{
                $this->user_experiance_repo->destroy($id); 
                return self::sendSuccess([], 'Experiance details Deleted Successfully');
            }catch(\Exception $e){
                return self::sendError('', 'You can not delete this Experiance details', 500);
            }
        }
        return self::sendError('', 'Experiance details not Deleted', 500);
    }


    // location details
    public function getUserLocationDetails(Request $request)
    {
        $data = array();
        $data = $this->user_location_repo->getbyUserId($request->user()->id);
        return self::sendSuccess($data, 'User Location details');
    }

    public function addUserLocationDetails(UserLocationRequest $request)
    {
        $data = array();
        $primary_status = 0;
        $primary_address = $this->user_location_repo->getbyUserId($request->user()->id);
        if(isset($primary_address) && count($primary_address) == '0'){
            $primary_status = 1;
        }
        $add_data = [
                    'user_id' => $request->user()->id,
                    'name' => $request->name,
                    'email' => $request->email,
                    'mobile_no'=> $request->mobile_no,
                    'address' => $request->address,
                    'primary_address' => $primary_status,
                    ];
        try{
            $data = $this->user_location_repo->dataCrud($add_data);
            return self::sendSuccess($data, 'Location details Add Successfully');
        }catch(\Exception $e){
            return self::sendException($e);
        }
    }
    
    public function updateUserLocationDetails(UserLocationRequest $request)
    {
        $data = array();
        $update_data = [
                        'name' => $request->name,
                        'email' => $request->email,
                        'mobile_no'=> $request->mobile_no,
                        'address' => $request->address,
                        ];
        try{
            $data = $this->user_location_repo->dataCrud($update_data, $request->id);
            return self::sendSuccess($data, 'Location details Update Successfully');
        }catch(\Exception $e){
            return self::sendException($e);
        }
    }

    public function getByIdUserLocationDetails($id)
    {
        $data = array();
        $data = $this->user_location_repo->getbyId($id);
        return self::sendSuccess($data, 'Location details info');
    }
   
    public function updatePrimaryUserLocationDetails(Request $request, $id)
    {   
        $data = ['primary_address' => '0'];
        $update_data = ['primary_address' => '1'];
        try{
            $this->user_location_repo->updatebyUserId($data, $request->user()->id);
            $this->user_location_repo->dataCrud($update_data, $id);
            return self::sendSuccess([], 'Primary Location Update Successfully');
        }catch(\Exception $e){
            return self::sendException($e);
        }

        return self::sendSuccess($data, 'Location details info');
    }
   
    public function deleteUserLocationDetails($id)
    {
        $data = $this->user_location_repo->getById($id);
        if(!empty($data)){
            if($data->primary_address == '1'){
                return self::sendError('', 'Primary Location details Not Deleted', 500);
            }

            try{
                $this->user_location_repo->destroy($id); 
                return self::sendSuccess([], 'Location details Deleted Successfully');
            }catch(\Exception $e){
                return self::sendError('', 'You can not delete this Location details', 500);
            }

        }
        return self::sendError('', 'Location details not Deleted', 500);
    }

   




}
