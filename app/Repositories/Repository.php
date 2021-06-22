<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Exceptions\HttpResponseException;
use Twilio\Rest\Client;
use App\Http\Helpers\Helper;
use Carbon\Carbon;
use Storage;
use Log;
use Auth;

class Repository
{
    /**
     * The Model name.
     *
     * @var \Illuminate\Database\Eloquent\Model;
     */
    protected $model;
    
    public $api_data_limit = 10;

    public $currency_symbol = '₦ ';
   
    public $timing_no_charges = '30';
    
    public $gender = array(
        '0' => 'Male',
        '1' => 'Female',
    );

    protected $model_name = '';

    public function __construct()
    {
        $this->model = new $this->model_name;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll()
    {
        return $this->model->all();
    }
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCount()
    {
        return $this->model->count();
    }

    /**
     * FindOrFail Model and return the instance.
     *
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getById($id)
    {
        return $this->model->findOrFail($id);
    }
   
    /**
     * get Model and return the instance.
     *
     * @param int $ids
     */
    public function getByMultipleIds($ids)
    {
        return $this->model->whereIn('id', $ids)->get();
    }

    /**
     * Softdelete the model from the deleted_at date.
     *
     * @param int $id
     *
     * @throws \Exception
     */
    public function destroyMultiple($ids)
    {
        return $this->model->whereIn('id',$ids)->update(['status'=>'1']);    
    }
    /**
     * Softdelete the model from the deleted_at date.
     *
     * @param int $id
     *
     * @throws \Exception
     */
    public function destroy($id)
    {
        return $this->getById($id)->delete();    
    }
    /**
     * Delete the model from the database.
     *
     * @param int $id
     *
     * @throws \Exception
     */
    public function forceDelete($id)
    {
        $this->getById($id)->forceDelete();
        
    }
    
    /**
     * get the model from the database.
     *
     * @param int $id
     *
     * @throws \Exception
     */
    public function get()
    {
        return $this->model->get();
    }
   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $data
     * @return \Illuminate\Http\Response
     */
    public function store($data)
    {
        return $this->model->create($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $data
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($data, $id)
    {
        $update = $this->getById($id);
        if(!empty($update)){
          return $update->update($data);    
        }else{
            return false;
        }
    }

    /**
     * get the model from the database.
     *
     * @param int $id
     *
     * @throws \Exception
     */
    public function getbyColumnWithValue($column_name, $value, $condition = '=')
    {
        return $this->model->where($column_name, $condition, $value)->get();
    }

    /**
     * get the model from the database.
     *
     * @param int $id
     *
     * @throws \Exception
     */
    public function getbyMultipleColumnWithValue($condition_column_array)
    {
        return $this->model->where($condition_column_array)->get();
    }
    
    /**
     * get the model from the database.
     *
     * @param int $id
     *
     * @throws \Exception
     */
    public function getbyColumnWithFirstValue($column_name, $value, $condition = '=')
    {
        return $this->model->where($column_name, $condition, $value)->first();
    }

    /**
     * get the model from the database.
     *
     * @param int $id
     *
     * @throws \Exception
     */
    public function getbyMultipleColumnWithFirstValue($condition_column_array)
    {
        return $this->model->where($condition_column_array)->first();
    }
   
     /**
     * Sends sms to user using Twilio's programmable sms client
     * @param String $message Body of sms
     * @param Number $recipients string or array of phone number of recepient
     */  
    public function sendMessage($message, $recipients)
    {   
         //twilio
        // try{
        //     $account_sid = config("app.TWILIO_SID");
        //     $auth_token = config("app.TWILIO_AUTH_TOKEN");
        //     $twilio_number = config("app.TWILIO_NUMBER");
        //     $client = new Client($account_sid, $auth_token);
        //     $client->messages->create($recipients,  ['from' => $twilio_number, 'body' => $message] );
        //     return '';
        //  }catch(\Exception $e){
        //       throw new HttpResponseException(response()->json([
        //         'success' => false,
        //         'errors' => $e->getMessage(),
        //         'message' => 'The given data was invalid.',
        //     ], 422));
        // }

        // sms provider
        try{
            $sms_provider_url = config("app.SMS_PROVIDER_URL");
            $sms_provider_username = config("app.SMS_PROVIDER_USERNAME");
            $sms_provider_password = config("app.SMS_PROVIDER_PASSWORD");
            $sms_provider_Sender = config("app.SMS_PROVIDER_SENDER");
            $data = [];
           if(!empty($sms_provider_url) && !empty($sms_provider_username) && !empty($sms_provider_password) && !empty($sms_provider_Sender) && !empty($recipients)){
                $smsprovider_url = $sms_provider_url;
                $smsprovider_url .= '?username='.$sms_provider_username;
                $smsprovider_url .= '&password='.$sms_provider_password;
                $smsprovider_url .= '&message='.$message;
                $smsprovider_url .= '&sender='.$sms_provider_Sender;
                $smsprovider_url .= '&mobiles='.$recipients;
                $msg_sent = Helper::sendBULKSMSRequest($smsprovider_url);
                if(!empty($msg_sent) && $msg_sent != 'true'){
                    return $msg_sent;
                }
            }else{
                $msg_sent = 'SMS Sending Failed';
                return $msg_sent;
            }
         }catch(\Exception $e){
              throw new HttpResponseException(response()->json([
                'success' => false,
                'errors' => $e->getMessage(),
                'message' => 'The given data was invalid.',
            ], 422));
        }
    
            // OCTOPUSH sms
        // try{
        //     $octopush_api_url = config("app.OCTOPUSH_API_URL");
        //     $octopush_login_name = config("app.OCTOPUSH_LOGIN_NAME");
        //     $octopush_api_key = config("app.OCTOPUSH_API_KEY");
        //     if(!empty($octopush_api_url) && !empty($octopush_login_name) && !empty($octopush_api_key) && !empty($recipients)){
        //         $url = $octopush_api_url.'/sms-campaign/send';
        //         $headers = array( 'Content-Type:application/json', 'api-key:'.$octopush_api_key, 'api-login:'.$octopush_login_name,'cache-control: no-cache');
        //         $recipients_obj = (object)["phone_number"=> $recipients];
        //         $data = [];
        //         $data["recipients"]= [$recipients_obj];                
        //         $data["text"]=$message;
        //         $data["type"]='sms_premium';
        //         $data["purpose"]='alert';
        //         $data["sender"]= config("app.name");
        //         $msg_sent = Helper::sendBULKSMSRequest($url, $headers, $data);
        //         if(!empty($msg_sent) && $msg_sent != 'true'){
        //             return $msg_sent;
        //         }
        //     }else{
        //         $msg_sent = 'SMS Sending Failed';
        //         return $msg_sent;
        //     }
        // }catch(\Exception $e){
        //         throw new HttpResponseException(response()->json([
        //         'success' => false,
        //         'errors' => $e->getMessage(),
        //         'message' => 'The given data was invalid.',
        //     ], 422));
        // }
        
    }

    /**
     * get timestamp formate date
     */  
    public function getRemainingDays($date)
    {
        $date_formate = new Carbon($date);
        (!empty(Auth::user()) && !empty(Auth::user()->timezone)) ? $date_formate->setTimezone(Auth::user()->timezone) : '' ;        
        $days = Carbon::now()->diffInDays($date_formate, false);
        return $days;
    }

    /**
     * get current date and time
     */  
    public function getCurrentDateTime()
    {
        return Carbon::now()->format('Y-m-d H:i:s');
    }

    /**
     * get timestamp formate date and time
     */  
    public function getDateTimeFormate($date_time)
    {
        $date_time_formate = new Carbon($date_time);
        (!empty(Auth::user()) && !empty(Auth::user()->timezone)) ? $date_time_formate->setTimezone(Auth::user()->timezone) : '' ;
        return $date_time_formate->format('d M, Y h:i a');
    }

    /**
     * get timestamp formate date
     */  
    public function getDateFormate($date)
    {
        $date_formate = new Carbon($date);
        (!empty(Auth::user()) && !empty(Auth::user()->timezone)) ? $date_formate->setTimezone(Auth::user()->timezone) : '' ;
        return $date_formate->format('d M, Y');
    }
   
    /**
     * get timestamp formate time
     */  
    public function getTimeFormate($time)
    {
        $time_formate = new Carbon($time);
        (!empty(Auth::user()) && !empty(Auth::user()->timezone)) ? $time_formate->setTimezone(Auth::user()->timezone) : '' ;
        return $time_formate->format('H:i:s');
    }
   
    /**
     * generate OTP code
     */  
    public function generateOTPCode()
    {
        // return '111111';
        return rand(100000, 999999);
    }
   
    /**
     * convert date utc timezone
     */  
    public function getConvertTimezoneDate($timestamp, $timezone = 'UTC')
    {
        $date = Carbon::createFromFormat('Y-m-d', $timestamp, $timezone);
        return $date->setTimezone('UTC');
    }

    /**
     * convert time utc timezone
     */  
    public function getConvertTimezoneTime($timestamp, $timezone = 'UTC')
    {
        $date = Carbon::createFromFormat('H:i:s', $timestamp, $timezone);
        return $date->setTimezone('UTC');
    }

    /**
     * convert date time utc timezone
     */  
    public function getConvertTimezoneDateTime($timestamp, $timezone = 'UTC')
    {
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, $timezone);
        return $date->setTimezone('UTC');
    }
  
    /**
     * convert date local timezone
     */  
    public function getConvertLocalTimezoneDate($timestamp, $timezone = '')
    {
        $date = Carbon::createFromFormat('Y-m-d', $timestamp, 'UTC');
        return !empty($timezone) ? $date->setTimezone($timezone) : $date;     
    }

    /**
     * convert time local timezone
     */  
    public function getConvertLocalTimezoneTime($timestamp, $timezone = '')
    {
        $date = Carbon::createFromFormat('H:i:s', $timestamp, 'UTC');
        return !empty($timezone) ? $date->setTimezone($timezone) : $date;
    }

    /**
     * convert date time local timezone
     */  
    public function getConvertLocalTimezoneDateTime($timestamp, $timezone = '')
    {
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $timestamp, 'UTC');
        return !empty($timezone) ? $date->setTimezone($timezone) : $date;
    }
   
    /**
     * File Upload
     */  
    public function uploadFolderWiseFile($file, $folderPath){
         if(!empty($file)) {          
            $orignalfileName = str_replace(' ', '', $file->getClientOriginalName());
            $storagePath = $folderPath.'/'. time() .'_'.$orignalfileName;
            Storage::disk('local')->put('/public/'.$storagePath, file_get_contents($file));
            return $storagePath;
        }
    }

    /**
     * File Upload
     */  
    public function uploadPDFFile($file, $folderPath, $file_name = 'order_invoice'){
         if(!empty($file)) {       
            $orignalfileName = $file_name.'.pdf';
            $storagePath = $folderPath.'/'. time() .'_'.$orignalfileName;
            Storage::disk('local')->put('/public/'.$storagePath, $file);
            return $storagePath;
        }
    }

    /**
     * File Remove from storage
     */  
    public function removeFolderWiseFile($file_path){
        if(Storage::disk('public')->exists($file_path)) {          
             Storage::disk('public')->delete($file_path);
             return true;
        }
        return false;
    }

    public function subscribeNotificationTopic($tokens, $topic){
        return Helper::subscribeNotificationTopic($tokens, $topic);
    }
    
    public function unsubscribeNotificationTopic($tokens, $topic){
        return Helper::unsubscribeNotificationTopic($tokens, $topic);
    }
   
    public function sendNotificationTopicWise($notification, $topic){
        return Helper::sendNotificationTopicWise($notification, $topic);
    }

    public function checkNotification($notification){
        return Helper::checkNotification($notification);
    }
}