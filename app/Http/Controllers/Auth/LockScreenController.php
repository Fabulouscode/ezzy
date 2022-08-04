<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use Auth;
use Session;
use App\Jobs\AdminActivityJob;

class LockScreenController extends Controller
{
    public function showAdminLockScreenForm(Request $request)
    {
        if(Auth::guard('admin')->check()){
            Session::put('locked', true);
            try{
                $admin =  Admin::find(Auth::guard('admin')->user()->id);
                dispatch(new AdminActivityJob($admin , 'AdminLock', $request->ip(), $request->server('HTTP_USER_AGENT'), $admin->id));
            }
            catch (\Throwable $th)
            {
                
            }
            return view('auth.lock_screen');
        }
         return redirect('/donotezzycaretouch');
    }

    public function adminLockscreen(Request $request)
    {

        if(!Auth::guard('admin')->check()){            
            return redirect('/donotezzycaretouch/login');
        }
        $this->validate($request, [
            'password' => 'required|string',
        ]);

        if(Hash::check($request->password, Auth::guard('admin')->user()->password)){
            $request->session()->forget('locked');
            try{
                $admin =  Admin::find(Auth::guard('admin')->user()->id);
                dispatch(new AdminActivityJob($admin , 'AdminUnlock', $request->ip(), $request->server('HTTP_USER_AGENT'), $admin->id));
            }
            catch (\Throwable $th)
            {
                
            }
            return redirect('/donotezzycaretouch');
        }
        return back()->withInput()->withError('Password does not match. Please try again.');

    }
}
