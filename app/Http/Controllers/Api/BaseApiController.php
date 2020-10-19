<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;


class BaseApiController extends Controller
{

    public function sendSuccess($result, $message) {
        return response()->json([
            'success' => true,
            'utc_time'=> Carbon::now()->format('Y-m-d H:i:s'),
            'data' => $result,
            'message' => $message,
        ], 200);
    }

    public function sendError($errors, $errorMessage, $code = 500) {
        return response()->json([
            'success' => false,
            'utc_time'=> Carbon::now()->format('Y-m-d H:i:s'),
            'errors' => $errors,
            'message' => $errorMessage,
        ], $code);
    }

    
   
}
