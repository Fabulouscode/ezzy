<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\AppVersion;
use App\Http\Controllers\Api\BaseApiController;


class AppVersionController extends BaseApiController
{
     
    public function checkAndroidVersion($version)
    {
        $data = AppVersion::where('id','1')->first();   
        if(!empty($data) && !empty($data->android_version) && !empty($version)){
            if(version_compare($data->android_version, $version, '>')){
                return self::sendSuccess(false,'App Version outdated');
            }else{
                return self::sendSuccess(true,'App Version Latest');
            }
        }
        return self::sendSuccess(false,'App Version outdated');
    }
   
    public function checkIOSVersion($version)
    {
        $data = AppVersion::where('id','1')->first();
        if(!empty($data) && !empty($data->ios_version) && !empty($version)){
            if(version_compare($data->ios_version, $version, '>')){
                return self::sendSuccess(false,'App Version outdated');
            }else{
                return self::sendSuccess(true,'App Version Latest');
            }
        }
        return self::sendSuccess(false,'App Version outdated');
    }

    public function getAppVersion(Request $request)
    {
        $data = AppVersion::where('id','1')->first();
        if(!empty($data)){
            $tempdata = [];
            $tempdata['ios_version'] = $data->ios_version;
            $tempdata['android_version'] =$data->android_version;
            return self::sendSuccess($tempdata,'App Version Latest');
        }
        return self::sendSuccess(false,'App Version outdated');
    }
   
    public function versionCompare($currentVersion, $userVersion)
    {
        if(!empty($currentVersion) && !empty($userVersion)){
            $currentData = [];
            $currentVersionArray = explode('.', $currentVersion);
            $currentData['major'] = (isset($currentVersionArray) && isset($currentVersionArray['0'])) ? $currentVersionArray['0'] : 0;
            $currentData['minor'] = (isset($currentVersionArray) && isset($currentVersionArray['1'])) ? $currentVersionArray['1'] : 0;
            $currentData['patch'] = (isset($currentVersionArray) && isset($currentVersionArray['2'])) ? $currentVersionArray['2'] : 0;
         
            $userData = [];
            $userVersionArray = explode('.', $userVersion);
            $userData['major'] = (isset($userVersionArray) && isset($userVersionArray['0'])) ? $userVersionArray['0'] : 0;
            $userData['minor'] = (isset($userVersionArray) && isset($userVersionArray['1'])) ? $userVersionArray['1'] : 0;
            $userData['patch'] = (isset($userVersionArray) && isset($userVersionArray['2'])) ? $userVersionArray['2'] : 0;

            if(($currentData['major'] <= $userData['major']) && ($currentData['minor'] <= $userData['minor']) && ($currentData['patch'] <= $userData['patch'])){
                dd($currentVersion, $userVersion);
                return true;
            }
        }
        return false;
    }

}
