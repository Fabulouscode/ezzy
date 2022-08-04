<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Permission;

class UserRolePermissionResourceMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$permissions)
    {        
        $path_arr = explode("/", $request->getPathInfo());
        if(count($permissions) > 0){ 
            foreach ($permissions as $key => $value) {
                $permission_val = substr($value, strpos($value, "-") + 1);
                if(in_array('create',$path_arr)){   
                        if($permission_val == 'add'){          
                            if($request->user()->hasPermissionTo($value)){ 
                                return $next($request);
                            }
                        }
                }else if(in_array('edit',$path_arr)){   
                        if($permission_val == 'edit'){      
                            if($request->user()->hasPermissionTo($value)){                
                                return $next($request);
                            }
                        }
                }else if(in_array('delete',$path_arr)){   
                        if($permission_val == 'delete'){     
                            if($request->user()->hasPermissionTo($value)){                
                                return $next($request);
                            }
                        }
                }else if($permission_val == 'list'){ 
                        if($request->user()->hasPermissionTo($value)){  
                                return $next($request);
                        }
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
