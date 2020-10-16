<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class DashboardController extends Controller
{
    public function __construct()
    {
    }
     
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($provider = '')
    {
        if($provider == 'healthcare'){
             return view('admin.healthcare.dashboard');
        }else if($provider == 'pharmacy'){
             return view('admin.pharmacy.dashboard');
        }else if($provider == 'laboratories'){
             return view('admin.laboratories.dashboard');
        }else{            
            return view('admin.dashboard.dashboard');
        }
    }
   

}
