<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VideoGrant;

class TwilioController extends BaseApiController
{
    private $user_repo;
    protected $sid, $token, $key, $secret;

    public function __construct(UserRepository $user_repo)
    {
        parent::__construct();
        $this->user_repo = $user_repo;

        $this->sid = config('services.twilio.sid');
        $this->token = config('services.twilio.token');
        $this->key = config('services.twilio.key');
        $this->secret = config('services.twilio.secret');

    }

    public function createRoom(Request $request)
    {
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
            }
            return self::sendSuccess($room_name, 'Room Name Send');
        }catch(\Exception $e){
            return self::sendException($e);
        }
        
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
