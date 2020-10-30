<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use App\Http\Requests\Api\UserServiceDetailsRequest;

class UserServiceDetailsController extends BaseApiController
{
    
    public function getServiceDetails($service_type){
        $data = array();
        $data = $this->service_repo->getbyServiceType($service_type);
        return self::sendSuccess($data, 'get Service details');
    }
  
    public function getUserServiceDetails(Request $request){
        $data = array();
        $data = $this->user_service_repo->getbyUserId($request->user()->id);
        return self::sendSuccess($data, 'User Experiance details');
    }

    public function addUserServiceDetails(UserServiceDetailsRequest $request){
        $data = array();
        $add_data = [
                    'user_id' => $request->user()->id,
                    'service_id' => $request->service_id,
                    'service_charge' => $request->service_charge,
                    'service_charge_type'=> $request->service_charge_type,
                    'status' => $request->status,
                    ];
        try{
            $data = $this->user_service_repo->dataCrud($add_data);
            return self::sendSuccess($data, 'Experiance details Add Successfully');
        }catch(\Exception $e){
            return self::sendException($e);
        }
    }
    
    public function updateUserServiceDetails(UserServiceDetailsRequest $request){
        $data = array();
        $update_data = [
                        'service_id' => $request->service_id,
                        'service_charge' => $request->service_charge,
                        'service_charge_type'=> $request->service_charge_type,
                        'status' => $request->status,
                        ];
        try{
            $data = $this->user_service_repo->dataCrud($update_data, $request->id);
            return self::sendSuccess($data, 'Service details Update Successfully');
        }catch(\Exception $e){
            return self::sendException($e);
        }
    }

    public function getByIdUserServiceDetails($id){
        $data = array();
        $data = $this->user_service_repo->getbyId($id);
        return self::sendSuccess($data, 'Experiance details info');
    }
   
    public function deleteUserServiceDetails($id){
        $data = $this->user_service_repo->getById($id);
        if(!empty($data)){
            $this->user_service_repo->destroy($id); 
             return self::sendSuccess([], 'Service details Deleted Successfully');
        }
        return self::sendError($data, 'Service details not Deleted', 500);
    }


    
}
