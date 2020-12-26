<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use App\Repositories\ChatHistoryRepository;
use App\Repositories\ChateServicesRepository;
use App\Repositories\UserRepository;
use App\Http\Requests\Api\EPrescibeRequest;
use App\Http\Requests\Api\EDignosticsRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChatController extends BaseApiController
{
    private $chat_history_repo, $chat_service_repo, $user_repo;

    public function __construct(
        ChatHistoryRepository $chat_history_repo, 
        ChateServicesRepository $chat_service_repo,
        UserRepository $user_repo
        )
    {
        parent::__construct();
        $this->chat_history_repo = $chat_history_repo;
        $this->chat_service_repo = $chat_service_repo;
        $this->user_repo = $user_repo;
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
            return self::sendSuccess([], 'save ePrescibe Successfully');
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
            return self::sendSuccess([], 'save eDignostics Successfully');
        }catch(\Exception $e){
            DB::rollBack();
            return self::sendException($e);
        }
    }
  
    public function saveTreatmentPlan(Request $request)
    {
        $data = $request->all();
        dd($data);
        $this->chat_history_repo->dataCrud($data);
        return self::sendSuccess($user_list, 'save Treatment Plan Successfully');
    }

}
