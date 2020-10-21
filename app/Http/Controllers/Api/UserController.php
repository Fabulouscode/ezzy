<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use App\Http\Requests\Api\UserBankAccountRequest;
use App\Http\Requests\Api\UserAvailableTimesRequest;
use App\Http\Requests\Api\UserEducationDetailsRequest;
use App\Http\Requests\Api\UserExperianceDetailsRequest;
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
        $data = $this->user_bank_account_repo->dataCrud($request);
        return self::sendSuccess($data, 'Bank account details Add Successfully');
    }
    
    public function updateUserBankDetails(UserBankAccountRequest $request){
        $data = array();
        $data = $this->user_bank_account_repo->dataCrud($request, $request->id);
        return self::sendSuccess($data, 'Bank account details Update Successfully');
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
        $data = $this->user_available_time_repo->dataCrud($request);
        return self::sendSuccess($data, 'Available times details Add Successfully');
    }
    
    public function updateUserAvailableTimes(UserAvailableTimesRequest $request){
        $data = array();
        $data = $this->user_available_time_repo->dataCrud($request, $request->id);
        return self::sendSuccess($data, 'Available times details Update Successfully');
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
        $data = $this->user_education_repo->dataCrud($request);
        return self::sendSuccess($data, 'Education details Add Successfully');
    }
    
    public function updateUserEducationDetails(UserEducationDetailsRequest $request){
        $data = array();
        $data = $this->user_education_repo->dataCrud($request, $request->id);
        return self::sendSuccess($data, 'Education details Update Successfully');
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
        $data = $this->user_experiance_repo->dataCrud($request);
        return self::sendSuccess($data, 'Experiance details Add Successfully');
    }
    
    public function updateUserExperianceDetails(UserExperianceDetailsRequest $request){
        $data = array();
        $data = $this->user_experiance_repo->dataCrud($request, $request->id);
        return self::sendSuccess($data, 'Experiance details Update Successfully');
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
        $user = $this->user_repo->dataCrud($request, $request->user()->id);
        $this->user_details_repo->dataCrud($request);
        return self::sendSuccess($user, 'User Profile Add Successfully');
    }
}
