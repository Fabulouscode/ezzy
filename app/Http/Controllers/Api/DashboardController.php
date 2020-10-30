<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use App\Http\Resources\Api\HeathCareProviderResource;
use App\Http\Resources\Api\PharmacyResource;
use App\Http\Resources\Api\LaboratoriesResource;
use App\Http\Resources\Api\PatientResource;

class DashboardController extends BaseApiController
{
    
    public function getDashboardDetails(Request $request){
        $data = array();        
        $data['user'] = $this->user_repo->getbyId($request->user()->id);
        $data['appointment'] = $this->appointment_repo->getUpcomingAppointment($request);
        if(!empty($request->user()->category_id)){
            if($request->user()->categoryParent->parent_id == '2'){
                $data['user'] = new PharmacyResource($data['user']);
            }else if($request->user()->categoryParent->parent_id == '3'){
                $data['user'] = new LaboratoriesResource($data['user']);
            }else{
                $data['user'] = new HeathCareProviderResource($data['user']);
            }
        }else{
            $data['user'] = new PatientResource($data['user']);
        }
        return self::sendSuccess($data, 'User Dashboard');
    }
 
    public function getHealthCareTypes($id){
        $data = array();        
        $data = $this->category_repo->getByParentId($id);
        return self::sendSuccess($data, 'HCP Types');
    }

    public function getPaymentHistory(Request $request){
        $data = array();        
        $data = $this->user_trans_repo->getTransactionHistory($request);
        return self::sendSuccess($data, 'User Transaction History');
    }
 
}
