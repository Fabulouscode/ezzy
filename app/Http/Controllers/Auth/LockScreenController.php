<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use Session;

class LockScreenController extends Controller
{
    public function showAdminLockScreenForm()
    {
        if(Auth::guard('admin')->check()){
            Session::put('locked', true);
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
            return redirect('/donotezzycaretouch');
        }
        return back()->withInput()->withError('Password does not match. Please try again.');

    }
}
