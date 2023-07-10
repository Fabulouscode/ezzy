<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\CustomEncrypt;
use Carbon\Carbon;

class BaseApiController extends Controller
{
    private $ecnrypter;
    
    public function __construct()
    {
       $this->ecnrypter = new CustomEncrypt();   
    }   
    

    public function sendSuccess($result, $message = '') 
    {
       return response()->json($this->ecnrypter->encrypt($result), 200);
    }

    public function sendError($errors, $errorMessage='', $code = 500) 
    {
        return response()->json([
            'success' => false,
            'utc_time'=> Carbon::now()->format('Y-m-d H:i:s'),
            'errors' => $errors,
            'message' => $errorMessage,
        ], $code);
    }
    
    public function sendException($ex) 
    {
        return response()->json([
            'success' => false,
            'error' => config('app.debug') ? $ex->getMessage().' at '.$ex->getLine() : 'Oops ! Something went wrong, Try Again after sometime',
            'message' => 'Oops ! Something went wrong, Try Again after sometime'
        ], 500);
    }
    
   
}
