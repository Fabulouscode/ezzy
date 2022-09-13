<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\Request;

class AppSettingController extends BaseApiController
{
    public function getAppSetting()
    {
        $setting = AppSetting::pluck('value_txt','key_name');

        return self::sendSuccess($setting,'App setting data');
    }
}
