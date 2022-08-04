<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\UserTracking;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use Carbon\Carbon as Carbon;
use Storage;
use Log;
use Auth;

class UserTrackingController extends Controller
{
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->all()){
            $query = UserTracking::select('user_trackings.*');            
            $data = $query->leftJoin('users as user', 'user_trackings.user_id', '=', 'user.id')
                          ->leftJoin('admins as admin', 'user_trackings.user_id', '=', 'admin.id');
            
            return Datatables::of($data)
                ->editColumn('user_type',function($selected)
                {
                    //	0-Active, 1-Inactive	
                    $data = '';
                    if($selected->user_type == '0'){
                        $data .= '<div class="badge badge-success">User</div>';
                    }else if($selected->user_type == '1'){
                        $data .= '<div class="badge badge-info" >Admin</div>';                    
                    }
                    return $data;
                })
                
                ->editColumn('user_name',function($selected)
                {           
                    if(!empty($selected->user)){
                        return $selected->user->first_name.' '.$selected->user->last_name;
                    }  
                    return '-';  
                })
                ->filterColumn('user_name', function ($query, $keyword) {
                    $query->whereRaw("concat(user.first_name, ' ', user.last_name) like ?", ["%$keyword%"]);       })
                ->orderColumn('user_name', function ($query, $order) {
                    $query->orderBy('user.first_name', $order);
                })
               
                ->editColumn('admin_name',function($selected)
                {           
                    if(!empty($selected->admin) && $selected->user_type == '1'){
                        return $selected->admin->name;
                    } 
                    return '-';     
                })
                ->filterColumn('admin_name', function ($query, $keyword) {
                    $query->whereRaw("admin.name like ?", ["%$keyword%"]);       
                })
                ->orderColumn('admin_name', function ($query, $order) {
                    $query->orderBy('admin.name', $order);
                })

                ->editColumn('created_at',function($selected)
                {                   
                    $date_time_formate = new Carbon($selected->created_at);
                    (!empty(Auth::user()) && !empty(Auth::user()->timezone)) ? $date_time_formate->setTimezone(Auth::user()->timezone) : '' ;
                    return $date_time_formate->format('d M, Y h:i a');
                })
                ->orderColumn('created_at', function ($query, $order) {
                    $query->orderBy('user_trackings.created_at', $order);
                })
                ->rawColumns(['user_type','user_name','created_at'])
                ->make(true);
        }
        return view('admin.user_tracking.index');
    }
}
