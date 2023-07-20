<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Country;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $country = Country::pluck('country_name','id');
        return view('home',compact('country'));
    }
    public function doctors(Request $request)
    {
        // 4 No is for doctor
        $doctors = User::with(['userDetails','userEduction'])->whereNotIn('id',[20,240])->where(['category_id'=>4,'status'=>0])->whereNull('deleted_at');
        
        if (!empty($request->search)) {
            $doctors = $doctors->where('subcategory_id',$request->search);
        }
        $doctors = $doctors->paginate(10);
        $category = Category::where('parent_id', 4)->whereNull('deleted_at')->pluck('name','id');
        if($request->ajax()){
            return view('doctor_pagination',compact('doctors'))->render();
        }
        return view('doctors',compact('doctors','category'));
    }
    public function happyClients(Request $request)
    {
        $happyClients = Appointment::where('is_happy_clients',1)->whereNull('deleted_at');
        $happyClients = $happyClients->paginate(10);
        if($request->ajax()){
            return view('happy_clients_pagination',compact('doctors'))->render();
        }
        return view('happy_clients',compact('happyClients'));
    }

    public function googleRecaptcha(Request $request)
    {
        return view('googleRecaptcha');
    }
}
