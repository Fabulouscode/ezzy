<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Exceptions\HttpResponseException;
use Twilio\Rest\Client;
use App\Http\Helpers\Helper;
use Carbon\Carbon;
use Storage;
use Log;

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
        return $this->model->all()->sortByDesc('id');
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
        try{
            $account_sid = config("app.TWILIO_SID");
            $auth_token = config("app.TWILIO_AUTH_TOKEN");
            $twilio_number = config("app.TWILIO_NUMBER");
            $client = new Client($account_sid, $auth_token);
            $client->messages->create($recipients,  ['from' => $twilio_number, 'body' => $message] );
            return '';
         }catch(\Exception $e){
              throw new HttpResponseException(response()->json([
                'success' => false,
                'errors' => $e->getMessage(),
                'message' => 'The given data was invalid.',
            ], 422));
        }
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
        return $date_time_formate->format('d M, Y H:i:s');
    }

    /**
     * get timestamp formate date
     */  
    public function getDateFormate($date)
    {
        $date_formate = new Carbon($date);
        return $date_formate->format('d M, Y');
    }
   
    /**
     * get timestamp formate time
     */  
    public function getTimeFormate($time)
    {
        $time_formate = new Carbon($time);
        return $time_formate->format('H:i:s');
    }
   
    /**
     * generate OTP code
     */  
    public function generateOTPCode()
    {
        return rand(100000, 999999);
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
    public function uploadPDFFile($file, $folderPath){
         if(!empty($file)) {       
            $orignalfileName = 'order_invoice.pdf';
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