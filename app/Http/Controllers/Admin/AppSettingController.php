<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\Request;
use Validator;

class AppSettingController extends Controller
{
    public function index(Request $request)
    {
        $data = AppSetting::pluck('value_txt', 'key_name');
        return view('admin.app_setting.index', compact('data'));
    }

    public function store(Request $request)
    {
        $rules = [
            'paystack' => 'required',
            'interswitch' => 'required',
        ];

        $validator = Validator::make($request->setting, $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->WithInput();
        } else {
            if (!empty($request->setting)) {
                foreach ($request->setting as $key => $value) {
                    $data = AppSetting::where('key_name', $key)->first();
                    if (empty($data)) {
                        $data = new AppSetting();
                        $data->key_name = $key;
                    } else {
                        $data->key_name = $key;
                    }

                    $data->value_txt = $value;
                    $data->save();
                }
                return back()->with('success', 'Setting Updated Successfully');
            }
        }
    }
}
