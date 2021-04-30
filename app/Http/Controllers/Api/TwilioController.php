<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Repositories\UserRepository;
use App\Repositories\AppointmentRepository;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VideoGrant;
use App\Http\Helpers\Helper;
use Log;

class TwilioController extends BaseApiController
{
    private $user_repo, $appointment_repo;
    protected $sid, $token, $key, $secret;

    public function __construct(UserRepository $user_repo, AppointmentRepository $appointment_repo)
    {
        parent::__construct();
        $this->user_repo = $user_repo;
        $this->appointment_repo = $appointment_repo;

        $this->sid = config('services.twilio.sid');
        $this->token = config('services.twilio.token');
        $this->key = config('services.twilio.key');
        $this->secret = config('services.twilio.secret');

    }

    public function createRoom(Request $request, $appointment_id)
    {
        $appointment_detail = $this->appointment_repo->getbyIdVideoCallCheck($appointment_id);
        if(!empty($appointment_detail)){
            $client = new Client($this->sid, $this->token);
            $room_name = self::genrateRoomName();
            $exists = $client->video->rooms->read([ 'uniqueName' => $room_name]);
            try{
                if (empty($exists)) {
                    $client->video->rooms->create([
                        'uniqueName' => $room_name,
                        'type' => 'group',
                        'recordParticipantsOnConnect' => false
                    ]);
                   
                    if($request->user()->id != $appointment_detail->user_id){
                        $receiver_user =  $this->user_repo->getById($appointment_detail->user_id);
                    }else{
                        $receiver_user =  $this->user_repo->getById($appointment_detail->client_id);
                    }
                    $sender_user =  '';
                    $notification_user = [
                        'sender_id' => NULL,
                        'receiver_id' => ($request->user()->id != $appointment_detail->user_id) ? $appointment_detail->user_id : $appointment_detail->client_id,
                        'title' => 'Video Call',
                        'message' => 'Appointment video call',               
                        'parameter' => json_encode(['appointment_id'=> $appointment_detail->id,'room_name'=>$room_name,'notification_time'=>$this->user_repo->getCurrentDateTime()]),   
                        'msg_type' => '96',
                    ]; 
                    Helper::sendOfflineChatNotification($notification_user, $receiver_user, $sender_user);
                    
                }
                return self::sendSuccess($room_name, 'Room Name Send');
            }catch(\Exception $e){
                return self::sendException($e);
            }
        }
        return self::sendError('Appointment not found');
        
    }

    public function joinRoom(Request $request, $roomName)
    {
        // A unique identifier for this user
        $identity = $request->user()->id;

        $token = new AccessToken($this->sid, $this->key, $this->secret, 3600, $identity);

        $videoGrant = new VideoGrant();
        $videoGrant->setRoom($roomName);

        $token->addGrant($videoGrant);
        
        $data = [];
        $data['accessToken'] = $token->toJWT();
        $data['room_name'] = $roomName;
        return self::sendSuccess($data, 'Room join');
    }

    public function genrateRoomName($length = 8) {     
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&"; 
        $room_name = substr( str_shuffle(sha1(rand() . time()) . $chars ), 0, $length );
        return $room_name;
    } 
}
