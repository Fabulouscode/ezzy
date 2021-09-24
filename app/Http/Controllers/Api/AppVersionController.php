<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\AppVersion;
use App\Http\Controllers\Api\BaseApiController;


class AppVersionController extends BaseApiController
{

    public function checkAndroidVersion($version)
    {
        $data = AppVersion::where('android_version',$version)->where('id','1')->first();
        if(!empty($data)){
            return self::sendSuccess(true,'App Version Latest');
        }
        return self::sendError(false,'App Version outdated');
    }
   
    public function checkIOSVersion($version)
    {
        $data = AppVersion::where('ios_version',$version)->where('id','1')->first();
        if(!empty($data)){
            return self::sendSuccess(true,'App Version Latest');
        }
        return self::sendError(false,'App Version outdated');
    }

}
