<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AppVersion;
use App\Http\Requests\Admin\AppVersionRequest;


class AppVersionController extends Controller
{
    public function index(Request $request)
    {
        $data = AppVersion::where('id','1')->first();
        return view('admin.app_version.index',compact('data'));
 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AppVersionRequest $request)
    {
        $data = [
                'android_version' => $request->android_version,
                'ios_version' => $request->ios_version,
            ];
        if(!empty($request->id)){
            $app_version =  AppVersion::find($request->id);
            if(!empty($app_version)){
                $app_version->update($data);
            } 
        }
        return redirect('/donotezzycaretouch/app_version');
    }

}
