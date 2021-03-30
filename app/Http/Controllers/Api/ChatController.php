<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use App\Repositories\ChatHistoryRepository;
use App\Repositories\ChateServicesRepository;
use App\Repositories\UserRepository;
use App\Repositories\UserServiceRepository;
use App\Http\Requests\Api\EPrescibeRequest;
use App\Http\Requests\Api\EDignosticsRequest;
use App\Http\Requests\Api\TreatmentPlanRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChatController extends BaseApiController
{
    private $chat_history_repo, $chat_service_repo, $user_repo, $user_service_repo;

    public function __construct(
        ChatHistoryRepository $chat_history_repo, 
        ChateServicesRepository $chat_service_repo,
        UserRepository $user_repo,
        UserServiceRepository $user_service_repo
        )
    {
        parent::__construct();
        $this->chat_history_repo = $chat_history_repo;
        $this->chat_service_repo = $chat_service_repo;
        $this->user_repo = $user_repo;
        $this->user_service_repo = $user_service_repo;
    }

        
    public function getServices($id)
    {
        $user_services = $this->user_service_repo->getbyUserId($id)->map(function ($response){
                                    return [
                                        'id'=>$response->id,
                                        'service_charge'=>$response->service_charge,
                                        'service_name'=>(isset($response->service))? $response->service->service_name :'',
                                        'status'=>$response->status,
                                        'status_name'=>$response->status_name
                                    ];
                                });
        return self::sendSuccess($user_services, 'Services get');
    }
    
    public function getEDignosticsChat($id)
    {
        $chat_history = $this->chat_history_repo->getbyIdedit($id)->EDignosticsFormat();
        return self::sendSuccess($chat_history, 'EDignostics Chat');
    }
    
    public function getEPrescibeChat($id)
    {
        $chat_history = $this->chat_history_repo->getbyIdedit($id)->EPrescibeFormat();
        return self::sendSuccess($chat_history, 'EPrescibe Chat');
    }

    public function getTreatmentPlanChat($id)
    {
        $chat_history = $this->chat_history_repo->getbyIdedit($id)->TreatmentPlanFormat();
        return self::sendSuccess($chat_history, 'Treatment Plan Chat');
    }


    public function getERecommendationProviders(Request $request)
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
        return self::sendSuccess($user_list, 'User get list Successfully');
    }

    public function getEDignosticsProviders(Request $request)
    {
        $user_list = $this->user_repo->getLaboratoryProvider($request)->map(function ($response){
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
        return self::sendSuccess($user_list, 'User get list Successfully');
    }

    public function saveEPrescibe(EPrescibeRequest $request)
    {
        
            $add_data = [
                    'user_id' => $request->user()->id,
                    'client_id' => $request->client_id,
                    'recommended_id' => $request->recommended_id,
                    'chat_type' => $request->chat_type,
                ];
        try{
            DB::beginTransaction();
            $chat = $this->chat_history_repo->dataCrud($add_data);
            if(!empty($chat) && !empty($chat->id)){
                if(!empty($request->medicines) && count($request->medicines)){
                    foreach ($request->medicines as $key => $value) {
                        $add_chat = [
                            'chat_history_id' => $chat->id,
                            'shop_medicine_detail_id'=> (!empty($value['shop_medicine_detail_id'])) ? $value['shop_medicine_detail_id'] : '',
                            'effective_date'=> (!empty($value['effective_date'])) ?$value['effective_date'] : '',
                            'patient_direction'=> (isset($value['patient_direction'])) ?$value['patient_direction'] : '',
                            'dispense'=> (isset($value['dispense'])) ?$value['dispense'] : '',
                            'dispense_unit'=> (isset($value['dispense_unit'])) ?$value['dispense_unit'] : '',
                            'refills'=> (isset($value['refills'])) ?$value['refills'] : '',
                            'days_supply'=> (isset($value['days_supply'])) ?$value['days_supply'] : '',
                        ];
                      
                        $this->chat_service_repo->dataCrud($add_chat);
                    }
                }
            }      
            DB::commit(); 
            $data = $this->chat_history_repo->getbyId($chat->id);      
            return self::sendSuccess($data, 'save ePrescibe Successfully');
        }catch(\Exception $e){
            DB::rollBack();
            return self::sendException($e);
        }
    }

    public function saveEDignostics(EDignosticsRequest $request)
    {
        $add_data = [
                        'user_id' => $request->user()->id,
                        'client_id' => $request->client_id,
                        'recommended_id' => $request->recommended_id,
                        'chat_type' => $request->chat_type,
                    ];
        try{
            DB::beginTransaction();
            $chat = $this->chat_history_repo->dataCrud($add_data);
            if(!empty($chat) && !empty($chat->id)){
                if(!empty($request->user_service_id) && count($request->user_service_id)){
                    foreach ($request->user_service_id as $key => $value) {
                        $add_chat = [
                            'chat_history_id' => $chat->id,
                            'user_service_id' => $value,
                        ];
                      
                        $this->chat_service_repo->dataCrud($add_chat);
                    }
                }
            }      
            DB::commit();       
            $data = $this->chat_history_repo->getbyId($chat->id);   
            return self::sendSuccess($data, 'save eDignostics Successfully');
        }catch(\Exception $e){
            DB::rollBack();
            return self::sendException($e);
        }
    }
  
    public function saveTreatmentPlan(TreatmentPlanRequest $request)
    {
          $add_data = [
                    'user_id' => $request->user()->id,
                    'client_id' => $request->client_id,
                    'recommended_id' => !empty($request->recommended_id) ? $request->recommended_id : '',
                    'chat_type' => '3',
                    'plan_name' => (!empty($request->plan_name)) ? $request->plan_name : '',
                    'treatment_name' => (!empty($request->treatment_name)) ? $request->treatment_name : '',
                ];
        try{
            DB::beginTransaction();
            $chat = $this->chat_history_repo->dataCrud($add_data);
            if(!empty($chat) && !empty($chat->id)){
                if(!empty($request->medicines) && count($request->medicines)){
                    foreach ($request->medicines as $key => $value) {
                        $add_chat = [
                            'chat_history_id' => $chat->id,
                            'medicine_name'=> (isset($value['item_name'])) ?$value['item_name'] : '',
                            'patient_direction'=> (isset($value['description'])) ?$value['description'] : '',
                            'quanity'=> (isset($value['quantity'])) ?$value['quantity'] : '',
                            'price'=> (isset($value['price'])) ?$value['price'] : ''
                        ];
                      
                        $this->chat_service_repo->dataCrud($add_chat);
                    }
                }
            }      
            DB::commit();       
            $data = $this->chat_history_repo->getbyId($chat->id);   
            return self::sendSuccess($data, 'save ePrescibe Successfully');
        }catch(\Exception $e){
            DB::rollBack();
            return self::sendException($e);
        }
    }

}
