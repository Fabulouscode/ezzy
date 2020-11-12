<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Permission;

class UserRolePermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permission)
    {   
        $current_args = $request->route()->parameters();
        $current_parameter = array_shift($current_args);
        if(!empty($current_parameter) && (strpos($permission, '{provider}') !== false)){
            if (strpos($permission, '{provider}') !== false) {
                 $permission = str_replace( '{provider}', $current_parameter, $permission );
            }
        }
        $permission_arr = Permission::get();
        foreach($permission_arr as $value){
            if($value->permission_name == $permission){                
                if($request->user()->hasPermissionTo($value->permission_name)){                
                    return $next($request);
                }
            }
        }
        
        if($request->ajax()){
            return response()->json(['msg'=>'Sorry, Permission Not Access'], 401);
        }else{            
            return redirect('permission_not_access');
        }
    }
}
