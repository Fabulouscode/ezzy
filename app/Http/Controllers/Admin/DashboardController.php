<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;


class DashboardController extends Controller
{
    private $user_repo;

    public function __construct(UserRepository $user_repo)
    {
        $this->user_repo = $user_repo;
    }
     
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($provider = '')
    {
        if($provider == 'healthcare'){
             $approved_count = $this->user_repo->getUserCategoryWiseApprovedCount('1');
             $pending_count = $this->user_repo->getUserCategoryWisePendingCount('1');
             return view('admin.healthcare.dashboard', compact('approved_count','pending_count'));
        }else if($provider == 'pharmacy'){
             $approved_count = $this->user_repo->getUserCategoryWiseApprovedCount('2');
             $pending_count = $this->user_repo->getUserCategoryWisePendingCount('2');
             return view('admin.pharmacy.dashboard', compact('approved_count','pending_count'));
        }else if($provider == 'laboratories'){
             $approved_count = $this->user_repo->getUserCategoryWiseApprovedCount('3');
             $pending_count = $this->user_repo->getUserCategoryWisePendingCount('3');
             return view('admin.laboratories.dashboard', compact('approved_count','pending_count'));
        }else{            
            return view('admin.dashboard.dashboard');
        }
    }
   

}
