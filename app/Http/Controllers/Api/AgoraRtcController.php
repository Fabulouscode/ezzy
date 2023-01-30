<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Repositories\UserRepository;
use App\Repositories\AppointmentRepository;
use Illuminate\Http\Request;
use App\Http\Helpers\Helper;
use Log;
use App\Lib\AgoraDynamic\RtcTokenBuilder2;
use App\Http\Requests\Api\Video\VideoJoinRequest;

class AgoraRtcController extends BaseApiController
{
    private $user_repo, $appointment_repo;

    public function __construct(UserRepository $user_repo, AppointmentRepository $appointment_repo)
    {
        parent::__construct();
        $this->user_repo = $user_repo;
        $this->appointment_repo = $appointment_repo;

    }

    public function createToken($userId, $clientId, $roomName){

        $appId = config('app.AGORA_APP_ID');
        $appCertificate = config('app.AGORA_APP_CERTIFICATE');
        $channelName = $roomName;
        $uid = $userId;
        $uidStr = $userId;
        $tokenExpirationInSeconds = time() + 7200;
        $privilegeExpirationInSeconds = time() + 7200;

        $token = RtcTokenBuilder2::buildTokenWithUid($appId, $appCertificate, $channelName, $uid, RtcTokenBuilder2::ROLE_PUBLISHER, $tokenExpirationInSeconds, $privilegeExpirationInSeconds);

        return $token;
    }

    public function createRoom(Request $request, $user_id)
    {
        $appointment_detail = $this->appointment_repo->getbyIdVideoCallCheck($request, $user_id);
        if(!empty($appointment_detail)){
            $room_name = self::genrateRoomName();
            $callerGenerateToken = self::createToken($appointment_detail->client_id, $appointment_detail->client_id, $room_name);
            $receiverGenerateToken = self::createToken($appointment_detail->user_id, $appointment_detail->user_id, $room_name);
            Log::info($callerGenerateToken);
            Log::info($receiverGenerateToken);
            try{
                if (!empty($room_name) && !empty($callerGenerateToken) && !empty($receiverGenerateToken)) {
 
                    $receiver_user =  $this->user_repo->getById($appointment_detail->user_id);
                    $sender_user =  $this->user_repo->getById($appointment_detail->client_id);

                    if($request->user()->id == $appointment_detail->client_id){
                        $notification_user = [
                            'sender_id' => $request->user()->id,
                            'receiver_id' => $appointment_detail->user_id,
                            'title' => 'Video Call',
                            'message' => (!empty($sender_user))? 'Incoming call from '.$sender_user->user_name:'Incoming call',         
                            'parameter' => json_encode(['appointment_id'=> $appointment_detail->id,'room_name'=>$room_name, 'meeting_id'=> '', 'room_token'=> $receiverGenerateToken,'notification_time'=>$this->user_repo->getCurrentDateTime()]),   
                            'msg_type' => '94',
                        ]; 
                        Helper::sendOfflineChatNotification($notification_user, $receiver_user, $sender_user);
                        $responseData = [];
                        $responseData['access_token'] = $callerGenerateToken;
                        $responseData['meeting_data'] = $room_name;
                        return self::sendSuccess($responseData, 'Room Name Send');
                    }else{
                        $notification_user = [
                            'sender_id' => $request->user()->id,
                            'receiver_id' => $appointment_detail->client_id,
                            'title' => 'Video Call',
                            'message' => (!empty($receiver_user))? 'Incoming call from '.$receiver_user->user_name:'Incoming call',         
                            'parameter' => json_encode(['appointment_id'=> $appointment_detail->id,'room_name'=>$room_name, 'meeting_id'=> '', 'room_token'=> $callerGenerateToken,'notification_time'=>$this->user_repo->getCurrentDateTime()]),   
                            'msg_type' => '94',
                        ]; 
                        Helper::sendOfflineChatNotification($notification_user, $sender_user, $receiver_user);
                        $responseData = [];
                        $responseData['access_token'] = $receiverGenerateToken;
                        $responseData['meeting_data'] = $room_name;
                        return self::sendSuccess($responseData, 'Room Name Send');
                    }
                        
                }       
            }catch(\Exception $e){
                return self::sendException($e);
            }
        }
        return self::sendError([], 'Appointment not found');
        
    }

    public function joinRoom(VideoJoinRequest $request)
    {
        // // A unique identifier for this user
        // $identity = $request->user()->id;
        // $url=config('app.VIDEOSDK_API_ENDPOINT').'/api/meetings/'.$request->meeting_id;
        // $token = $request->video_token;
        // $data = [];
        // $curlResponse = self::sendCurlRequest($url, $token, $data);
        // if(!empty($curlResponse) && !empty($curlResponse['id'])){
        //     $responseData = [];
        //     $responseData['access_token'] = $request->video_token;
        //     $responseData['meeting_data'] = $curlResponse;
        //     return self::sendSuccess($responseData, 'Room join');
        // }
        return self::sendError([], 'Room not found');
    }

    public function genrateRoomName($length = 12) {     
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&"; 
        $room_name = substr( str_shuffle(sha1(rand() . time()) . $chars ), 0, $length );
        return $room_name;
    } 
      
    /**
     * sending curl request
     */ 
    public static function sendCurlRequest($url, $token, $data) 
    {
        if(!empty($url) && !empty($token)){
            $headers = array( 'Content-Type:application/json', 'Authorization:' . $token);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            if ($headers)
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            $response = curl_exec($ch);
            $response_arr =  json_decode($response, true);
            $response_error =  curl_error($ch);

            if(!empty($response_arr['id'])) {
                // Log::info($response);
                // Log::info('Push Notification Send Failed');
                return $response_arr;
            }
            if(!empty($response_error)){
                Log::info('web rtc video error');
                Log::info($response_error);
            }
        }
        return false;
    }
   
}
