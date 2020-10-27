<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use App\Http\Requests\Api\UserBankAccountRequest;
use App\Http\Requests\Api\UserAvailableTimesRequest;
use App\Http\Requests\Api\UserEducationDetailsRequest;
use App\Http\Requests\Api\UserExperianceDetailsRequest;
use App\Http\Requests\Api\UploadFileRequest;
use App\Http\Requests\Api\UserRequest;

class UserController extends BaseApiController
{

    public function getUserDetails(Request $request){
        $data = array();
        $data = $this->user_repo->getbyIdUserDetails($request->user()->id);
        return self::sendSuccess($data, 'User Details');
    }
    
    public function changeUserStatus(Request $request, $status){
        $data = array();
        $user = $this->user_details_repo->getbyUserId($request->user()->id);
        if(!empty($user)){
            $update = ['urgent'=> $status];
            $this->user_details_repo->update($update, $user->id);
            $data = $this->user_repo->getbyIdUserDetails($request->user()->id);
            return self::sendSuccess($data, 'User Status change Successfully');            
        }
        return self::sendError($data, 'User Status not change');
    }
   
    
     // bank details
    public function getUserBankDetails(Request $request){
        $data = array();
        $data = $this->user_bank_account_repo->getbyUserId($request->user()->id);
        return self::sendSuccess($data, 'Bank account details');
    }

    public function addUserBankDetails(UserBankAccountRequest $request){
        $data = array();
        $add_data = [
                    'user_id' => $request->user()->id,
                    'name' => $request->name,
                    'bank_name' => $request->bank_name,
                    'bank_branch_name'=> $request->bank_branch_name,
                    'account_number' => $request->account_number,
                    'ifsc_code' => $request->ifsc_code,
                    ];
        try{
            $data = $this->user_bank_account_repo->dataCrud($add_data);
            return self::sendSuccess($data, 'Bank account details Add Successfully');
        }catch(\Exception $e){
            return self::sendError($e->getMessage());
        }
    }
    
    public function updateUserBankDetails(UserBankAccountRequest $request){
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
            return self::sendError($e->getMessage());
        }
    }

    public function getByIdUserBankDetails($id){
        $data = array();
        $data = $this->user_bank_account_repo->getbyId($id);
        return self::sendSuccess($data, 'Bank account details');
    }
   
    public function deleteUserBankDetails($id){
        $data = $this->user_bank_account_repo->getById($id);
        if(!empty($data)){
            $this->user_bank_account_repo->destroy($id); 
             return self::sendSuccess([], 'Bank account details Deleted Successfully');
        }
        return self::sendError($data, 'Bank account not Deleted', 500);
    }


     // available times
    public function getUserAvailableTimes(Request $request){
        $data = array();
        $data = $this->user_available_time_repo->getbyUserId($request->user()->id);
        return self::sendSuccess($data, 'User Available times');
    }

    public function addUserAvailableTimes(UserAvailableTimesRequest $request){
        $data = array();
        $add_data = [
                    'user_id' => $request->user()->id,
                    'day' => $request->day,
                    'appointment_type' => $request->appointment_type,
                    'start_time'=> $request->start_time,
                    'end_time' =>  $request->end_time,
                    ];
        try{
            $data = $this->user_available_time_repo->dataCrud($add_data);
            return self::sendSuccess($data, 'Available times details Add Successfully');
        }catch(\Exception $e){
            return self::sendError($e->getMessage());
        }
    }
    
    public function updateUserAvailableTimes(UserAvailableTimesRequest $request){
        $data = array();
        $update_data = [
                    'day' => $request->day,
                    'appointment_type' => $request->appointment_type,
                    'start_time'=> $request->start_time,
                    'end_time' =>  $request->end_time,
                    ];
        try{
            $data = $this->user_available_time_repo->dataCrud($update_data, $request->id);
            return self::sendSuccess($data, 'Available times details Update Successfully');
        }catch(\Exception $e){
            return self::sendError($e->getMessage());
        }
    }

    public function getByIdUserAvailableTimes($id){
        $data = array();
        $data = $this->user_available_time_repo->getbyId($id);
        return self::sendSuccess($data, 'Available times details');
    }
   
    public function deleteUserAvailableTimes($id){
        $data = $this->user_available_time_repo->getById($id);
        if(!empty($data)){
            $this->user_available_time_repo->destroy($id); 
             return self::sendSuccess([], 'Available times details Deleted Successfully');
        }
        return self::sendError($data, 'Available times not Deleted', 500);
    }


     // education details
    public function getUserEducationDetails(Request $request){
        $data = array();
        $data = $this->user_education_repo->getbyUserId($request->user()->id);
        return self::sendSuccess($data, 'User Education details');
    }

    public function addUserEducationDetails(UserEducationDetailsRequest $request){
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
            return self::sendError($e->getMessage());
        }
    }
    
    public function updateUserEducationDetails(UserEducationDetailsRequest $request){
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
            return self::sendError($e->getMessage());
        }
    }

    public function getByIdUserEducationDetails($id){
        $data = array();
        $data = $this->user_education_repo->getbyId($id);
        return self::sendSuccess($data, 'Education details info');
    }
   
    public function deleteUserEducationDetails($id){
        $data = $this->user_education_repo->getById($id);
        if(!empty($data)){
            $this->user_education_repo->destroy($id); 
             return self::sendSuccess([], 'Education details Deleted Successfully');
        }
        return self::sendError($data, 'Education details not Deleted', 500);
    }


     // experiance details
    public function getUserExperianceDetails(Request $request){
        $data = array();
        $data = $this->user_experiance_repo->getbyUserId($request->user()->id);
        return self::sendSuccess($data, 'User Experiance details');
    }

    public function addUserExperianceDetails(UserExperianceDetailsRequest $request){
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
            return self::sendError($e->getMessage());
        }
    }
    
    public function updateUserExperianceDetails(UserExperianceDetailsRequest $request){
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
            return self::sendError($e->getMessage());
        }
    }

    public function getByIdUserExperianceDetails($id){
        $data = array();
        $data = $this->user_experiance_repo->getbyId($id);
        return self::sendSuccess($data, 'Experiance details info');
    }
   
    public function deleteUserExperianceDetails($id){
        $data = $this->user_education_repo->getById($id);
        if(!empty($data)){
            $this->user_experiance_repo->destroy($id); 
             return self::sendSuccess([], 'Experiance details Deleted Successfully');
        }
        return self::sendError($data, 'Experiance details not Deleted', 500);
    }

    public function addUserDetails(Request $request){
        try{
            $user = $this->user_repo->dataCrud($request, $request->user()->id);
            $this->user_details_repo->dataCrud($request);
            return self::sendSuccess($user, 'User Profile Add Successfully');
        }catch(\Exception $e){
            return self::sendError($e->getMessage());
        }
    }
 
    public function getHealthcareProviders(Request $request){
        $user_list = $this->user_repo->getHealthcareProviders($request);
        return self::sendSuccess($user_list, 'User Profile Add Successfully');
    }
    
    public function uploadDocumentFile(UploadFileRequest $request){
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
}
