<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Repositories\UserRepository;
use App\Repositories\AppointmentRepository;
use Illuminate\Http\Request;
use App\Http\Helpers\Helper;
use Log;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Http\Requests\Api\Video\VideoJoinRequest;

class WebRtcController extends BaseApiController
{
    private $user_repo, $appointment_repo;

    public function __construct(UserRepository $user_repo, AppointmentRepository $appointment_repo)
    {
        parent::__construct();
        $this->user_repo = $user_repo;
        $this->appointment_repo = $appointment_repo;

    }

    public function createToken($userId, $clientId, $roomName){

        $key = config('app.VIDEOSDK_SECRET_KEY');
        $tokenExpiration = time() + 7200;
        $payload = [
            'iss' => 'EzzyCare',
            'userId' => $userId,
            'clientId' => $clientId,
            'roomName' => $roomName,
            'apikey' => config('app.VIDEOSDK_API_KEY'),
            'permissions' => array(
                "allow_join", "allow_mod"
            ),
            'iat' => time(),
            'exp' => $tokenExpiration
        ];
        
        $token = JWT::encode($payload, $key, 'HS256');
        // $decoded = JWT::decode($jwt, new Key($key, 'HS256'));

        return $token;
    }

    public function createRoom(Request $request, $user_id)
    {
        $appointment_detail = $this->appointment_repo->getbyIdVideoCallCheck($request, $user_id);
        if(!empty($appointment_detail)){
            $room_name = self::genrateRoomName();
            $generateToken = self::createToken($appointment_detail->user_id, $appointment_detail->user_id, $room_name);

            try{
                if (empty($exists) && !empty($generateToken)) {
                    $url=config('app.VIDEOSDK_API_ENDPOINT').'/api/meetings';
                    $token = $generateToken;
                    $data=[];
                    $curlResponse = self::sendCurlRequest($url, $token, $data);
                    if(!empty($curlResponse) && !empty($curlResponse['id'])){
                        if($request->user()->id != $appointment_detail->user_id){
                            $receiver_user =  $this->user_repo->getById($appointment_detail->user_id);
                            $sender_user =  $this->user_repo->getById($appointment_detail->client_id);
                        }else{
                            $receiver_user =  $this->user_repo->getById($appointment_detail->client_id);
                            $sender_user =  $this->user_repo->getById($appointment_detail->user_id);
                        }
                        
                        $notification_user = [
                            'sender_id' => $request->user()->id,
                            'receiver_id' => ($request->user()->id != $appointment_detail->user_id) ? $appointment_detail->user_id : $appointment_detail->client_id,
                            'title' => 'Video Call',
                            'message' => (!empty($sender_user))? 'Incoming call from '.$sender_user->user_name:'Incoming call',         
                            'parameter' => json_encode(['appointment_id'=> $appointment_detail->id,'room_name'=>$room_name, 'meeting_id'=> $curlResponse['meetingId'], 'room_token'=> $generateToken,'notification_time'=>$this->user_repo->getCurrentDateTime()]),   
                            'msg_type' => '95',
                        ]; 
                        Helper::sendOfflineChatNotification($notification_user, $receiver_user, $sender_user);
                        $responseData = [];
                        $responseData['access_token'] = $generateToken;
                        $responseData['meeting_data'] = $curlResponse;
                        return self::sendSuccess($responseData, 'Room Name Send');
                    }    
                    return self::sendError([], 'Room not create');                
                }       
            }catch(\Exception $e){
                return self::sendException($e);
            }
        }
        return self::sendError([], 'Appointment not found');
        
    }

    public function joinRoom(VideoJoinRequest $request)
    {
        // A unique identifier for this user
        $identity = $request->user()->id;
        $url=config('app.VIDEOSDK_API_ENDPOINT').'/api/meetings/'.$request->meeting_id;
        $token = $request->video_token;
        $data = [];
        $curlResponse = self::sendCurlRequest($url, $token, $data);
        if(!empty($curlResponse) && !empty($curlResponse['id'])){
            $responseData = [];
            $responseData['access_token'] = $request->video_token;
            $responseData['meeting_data'] = $curlResponse;
            return self::sendSuccess($responseData, 'Room join');
        }
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
