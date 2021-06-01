<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\UserDetailsRepository;
use App\Repositories\AppointmentRepository;
use App\Repositories\ContactDetailsRepository;
use App\Http\Requests\Api\UserBankAccountRequest;
use App\Http\Requests\Api\UserAvailableTimesRequest;
use App\Http\Requests\Api\UserEducationDetailsRequest;
use App\Http\Requests\Api\UserExperianceDetailsRequest;
use App\Http\Requests\Api\UploadFileRequest;
use App\Http\Requests\Api\UserRequest;
use App\Http\Requests\Api\CallNotificationSend;
use App\Http\Requests\Api\ContactDetailsRequest;
use App\Http\Helpers\Helper;

class UserController extends BaseApiController
{
    private $user_repo, $user_details_repo, $appointment_repo, $contact_repo;

    public function __construct(
        UserRepository $user_repo, 
        UserDetailsRepository $user_details_repo,
        AppointmentRepository $appointment_repo,
        ContactDetailsRepository $contact_repo
    )
    {
        parent::__construct();
        $this->user_repo = $user_repo;
        $this->user_details_repo = $user_details_repo;
        $this->appointment_repo = $appointment_repo;
        $this->contact_repo = $contact_repo;
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

    public function getUserbyIdLocationDetails($id)
    {
        $data = array();
        $data = $this->user_repo->getbyIdedit($id);
        if(!empty($data)){            
            return self::sendSuccess($data->userLocationFormat(), 'User Details');
        }
        return self::sendError($data, 'User Details not found');
    }

    public function getUserbyCardNumberDetails($card_num)
    {
        $data = array();
        $data = $this->user_repo->getUserbyCardNumber($card_num);
        if(!empty($data)){          
            $data = $this->user_repo->getUserbyCardNumber($card_num)->format();  
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

 
    public function getHealthcareProvidersTop(Request $request)
    {   
    
        $user_list = $this->user_repo->getHealthcareProvidersTop($request)->map(function ($response){
                                    return [
                                        'id'=>$response->id,
                                        'user_name'=>$response->user_name,
                                        'profile_image'=>$response->profile_image,
                                        'user_appointment_review'=>$response->user_appointment_review,
                                        'user_appointment_rating'=>$response->user_appointment_rating,
                                        'user_order_review'=>$response->user_order_review,
                                        'user_order_rating'=>$response->user_order_rating,
                                        'user_eduction_details'=>$response->user_eduction_details,
                                        'category_name'=> (!empty($response->categoryParent)) ? $response->categoryParent->name : '',
                                        'subcategory_name'=> (!empty($response->categoryChild)) ? $response->categoryChild->name : '',
                                        'status'=>$response->status,
                                        'status_name'=>$response->status_name,
                                    ];
                                });
        return self::sendSuccess($user_list, 'User Profile list Successfully');
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
                                        'category_name'=>(!empty($response->categoryParent)) ? $response->categoryParent->name : '',
                                        'subcategory_name'=>(!empty($response->categoryChild)) ? $response->categoryChild->name : '',
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
                                        'latitude'=>$response->current_latitude,
                                        'longitude'=>$response->current_longitude,
                                        'category_name'=>(!empty($response->categoryParent)) ? $response->categoryParent->name : '',
                                        'subcategory_name'=>(!empty($response->categoryChild)) ? $response->categoryChild->name : '',
                                        'status'=>$response->status,
                                        'status_name'=>$response->status_name,
                                    ];
                                });
        return self::sendSuccess($user_list, 'User Profile list Successfully');
    }

    public function callNotificationSend(CallNotificationSend $request)
    {
         $sender = $this->user_repo->getbyId($request->user()->id);
         $receiver = $this->user_repo->getbyId($request->user_id);
         $notification_data = [
                            'sender_id' => $request->user()->id,
                            'receiver_id' => $request->user_id,
                            'title' => 'Call',
                            'message' => (!empty($sender))? 'Incoming call from '.$sender->user_name:'-','Incoming call',
                            'parameter' => json_encode(['notification_time'=> $this->user_repo->getCurrentDateTime()]),
                            'msg_type' => '98',
                        ];         
        try{
            Helper::sendOfflineChatNotification($notification_data, $receiver, $sender);
            return self::sendSuccess('','Notification Send Success');
        }catch(\Exception $e){
            return self::sendException($e);
        }
    }

    public function getUserAppointmentHistory($card_num)
    {
        $user_list = $this->appointment_repo->getCompletedAppointmentHistory($card_num)->map(function ($response){
                                    return [
                                        'id'=>$response->id,
                                        'appointment_date'=>$response->appointment_date,                                
                                        'user_name'=>(!empty($response->user)) ? $response->user->user_name : '',
                                        'profile_image'=>(!empty($response->user)) ? $response->user->profile_image : '',
                                        'category_name'=>(!empty($response->user) && !empty($response->user->categoryParent)) ? $response->user->categoryParent->name : '',
                                        'subcategory_name'=>(!empty($response->user) && !empty($response->user->categoryChild)) ? $response->user->categoryChild->name : '',
                                        'consult_notes' => $response->consult_notes,
                                        'appointment_type_name'=>$response->appointment_type_name,
                                    ];
                                });
        return self::sendSuccess($user_list, 'User Profile list Successfully');
    }

    // add contact details
    public function addContactDetails(ContactDetailsRequest $request)
    {
        $data = $request->all();
        try{
            $this->contact_repo->dataCrud($data);
            return self::sendSuccess('', 'Contact Form Add Successfully');
        }catch(\Exception $e){
            return self::sendException($e);
        }
    }
}
