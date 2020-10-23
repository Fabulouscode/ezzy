<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Exceptions\HttpResponseException;
use Twilio\Rest\Client;
use Storage;

class Repository
{
    /**
     * The Model name.
     *
     * @var \Illuminate\Database\Eloquent\Model;
     */
    protected $model;
    
    public $api_data_limit = 10;
    
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
     * Delete the model from the database.
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
    public function getbyColumnWithFirstValue($column_name, $value, $condition = '=')
    {
        return $this->model->where($column_name, $condition, $value)->first();
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
     * generate OTP code
     */  
    public function generateOTPCode()
    {
        return rand(10000, 99999);
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

}