<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\UserDetailsRepository;
use App\Http\Requests\Api\UserBankAccountRequest;
use App\Http\Requests\Api\UserAvailableTimesRequest;
use App\Http\Requests\Api\UserEducationDetailsRequest;
use App\Http\Requests\Api\UserExperianceDetailsRequest;
use App\Http\Requests\Api\UploadFileRequest;
use App\Http\Requests\Api\UserRequest;

class UserController extends BaseApiController
{
    private $user_repo, $user_details_repo;

    public function __construct(UserRepository $user_repo, UserDetailsRepository $user_details_repo)
    {
        parent::__construct();
        $this->user_repo = $user_repo;
        $this->user_details_repo = $user_details_repo;
    }


    public function getUserDetails(Request $request)
    {
        $data = array();
        $data = $this->user_repo->getbyIdUserDetails($request->user()->id);
        return self::sendSuccess($data, 'User Details');
    }
   
    public function getUserbyIdDetails($id)
    {
        $data = array();
        $data = $this->user_repo->getbyIdedit($id);
        if(!empty($data)){            
            return self::sendSuccess($data, 'User Details');
        }
        return self::sendError($data, 'User Details not found');
    }

    public function getUserbyCardNumberDetails($card_num)
    {
        $data = array();
        $data = $this->user_repo->getUserbyCardNumber($card_num)->format();
        if(!empty($data)){            
            return self::sendSuccess($data, 'User Details');
        }
        return self::sendError($data, 'User Details not found');
    }
    
    public function changeUserStatus(Request $request, $status)
    {
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

 
    public function getHealthcareProviders(Request $request)
    {
        $user_list = $this->user_repo->getHealthcareProviders($request)->map(function ($response){
                                    return [
                                        'id'=>$response->id,
                                        'user_name'=>$response->user_name,
                                        'profile_image'=>$response->profile_image,
                                        'user_appointment_review'=>$response->user_appointment_review,
                                        'user_appointment_rating'=>$response->user_appointment_rating,
                                        'user_order_review'=>$response->user_order_review,
                                        'user_order_rating'=>$response->user_order_rating,
                                        'user_eduction_details'=>$response->user_eduction_details,
                                        'status'=>$response->status,
                                        'status_name'=>$response->status_name,
                                    ];
                                });
        return self::sendSuccess($user_list, 'User Profile list Successfully');
    }

    public function getHealthcareProvidersUrgent(Request $request)
    {
        $user_list = $this->user_repo->getHealthcareProvidersUrgent($request)->map(function ($response){
                                    return [
                                        'id'=>$response->id,
                                        'user_name'=>$response->user_name,
                                        'profile_image'=>$response->profile_image,
                                        'user_appointment_review'=>$response->user_appointment_review,
                                        'user_appointment_rating'=>$response->user_appointment_rating,
                                        'user_order_review'=>$response->user_order_review,
                                        'user_order_rating'=>$response->user_order_rating,
                                        'user_eduction_details'=>$response->user_eduction_details,
                                        'latitude'=>$response->latitude,
                                        'longitude'=>$response->longitude,
                                        'status'=>$response->status,
                                        'status_name'=>$response->status_name,
                                    ];
                                });
        return self::sendSuccess($user_list, 'User Profile list Successfully');
    }

}
