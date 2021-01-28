<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
use App\Repositories\SupportRequestRepository;
use App\Repositories\SupportChatRepository;
use App\Http\Requests\Api\SupportRequestRequest;
use App\Http\Requests\Api\SupportChatRequest;

class SupportRequestController extends BaseApiController
{
    private $support_request_repo, $support_chat_repo;

    public function __construct(
        SupportRequestRepository $support_request_repo,
        SupportChatRepository $support_chat_repo
        )
    {
        parent::__construct();
        $this->support_request_repo = $support_request_repo;
        $this->support_chat_repo = $support_chat_repo;
    }


    public function getSupportRequest(Request $request)
    {
        $extra = array();
        $data = $this->support_request_repo->getSupportRequest($request);
        return self::sendSuccess($data, 'Support request list', $extra);
    }
   
    public function getSupportRequestInfo($id)
    {
        $extra = array();
        $data = $this->support_request_repo->getbyIdedit($id);
        return self::sendSuccess($data, 'Support request list', $extra);
    } 
  
    public function getSupportRequestMessages(Request $request)
    {
        $extra = array();
        $data = $this->support_chat_repo->getSupportMessages($request)->map(function ($response){
                                    return [
                                        'id'=>$response->id,
                                        'message'=>$response->message,
                                        'created_at'=>$response->created_at,
                                        'admin'=>(isset($response->admin))?
                                                        [
                                                            'user_name'=>$response->admin->name,
                                                            'profile_image'=>$response->admin->avatar
                                                        ]:'',
                                        'user'=>(isset($response->user))?
                                                    [
                                                        'user_name'=>$response->user->user_name,
                                                        'profile_image'=>$response->user->profile_image
                                                    ]:'',
                                    ];
                                });
        return self::sendSuccess($data, 'Support request list', $extra);
    } 
 
    public function addSupportRequest(SupportRequestRequest $request)
    {

        $add_data = [
                    'user_id' => $request->user()->id,
                    'title' => $request->title,
                    'description' => $request->description,
                    'attachment' => $request->attachment,
                    'status' => '0',
                ];

        try{
            $data = $this->support_request_repo->dataCrud($add_data);
            if(!empty($data->id)){
                $chat_data = [
                        'support_request_id' => $data->id,
                        'user_id' => $request->user()->id,
                        'message' => json_encode($request->description),
                    ];
                $this->support_chat_repo->dataCrud($chat_data);
            }
            return self::sendSuccess($data, 'Support request add');
        }catch(\Exception $e){
            return self::sendException($e);
        }
    }
    
    public function addSupportMessage(SupportChatRequest $request)
    {
        $chat_data = [
                'support_request_id' => $request->support_id,
                'user_id' => $request->user()->id,
                'message' => json_encode($request->message),
            ];

        try{
            $this->support_chat_repo->dataCrud($chat_data);
            $data = $this->support_request_repo->getbyIdedit($request->support_id);
            return self::sendSuccess($data, 'Support request add chat msg');
        }catch(\Exception $e){
            return self::sendException($e);
        }
    }

}
