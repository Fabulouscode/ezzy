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
        if(!Auth::check()){
            Session::put('locked', true);
            return view('auth.lock_screen');
        }
         return redirect('/');
    }

    public function adminLockscreen(Request $request)
    {
        
        if(!Auth::check()){            
            return redirect('/');
        }

        if(Hash::check($request->password,Auth::user()->password)){
            Session::forget('locked');
            return redirect('/');
        }
        
        return redirect('/lockscreen');
        // $this->validate($request, [
        //     'password' => 'required|min:6'
        // ]);

        // if (Auth::guard('admin')->attempt(['email' => $request->user()->email, 'password' => $request->password])) {

        //     return redirect()->intended('/');
        // }
        // return back()->withInput($request->only('email', 'remember'));
    }
}
