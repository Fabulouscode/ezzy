<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\AdminRequest;
use App\Repositories\AdminRepository;
use App\Repositories\RoleRepository;
use App\DataTables\AdminDataTable;
use Illuminate\Support\Facades\Hash;
use Auth;

class AdminController extends Controller
{
     private $admin_repo, $role_repo;

    public function __construct(AdminRepository $admin_repo,RoleRepository $role_repo)
    {
        $this->admin_repo = $admin_repo;
        $this->role_repo = $role_repo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index(AdminDataTable $adminDataTable)
    public function index(Request $request)
    {
        if($request->all()){
            return $this->admin_repo->getDatatable($request);
        }
        return view('admin.admin.index');

        // return $adminDataTable->render('admin.admin.datatable');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = $this->role_repo->getAll();
        return view('admin.admin.add',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminRequest $request)
    {
        $filter = $request->all();
        if(!empty($request->id)){
            $admin_user = $this->admin_repo->getById($request->id);
            if(!empty($admin_user)){
                $data = array();
                if(!empty($filter)) {
                    foreach ($filter as $key => $value) {
                        if($key == 'password' && $value != '**********'){
                            $data[$key] =  Hash::make($value);
                        }else{                    
                            $data[$key] = $value;
                        }
                    }
                }
                $this->admin_repo->dataCrud($data, $request->id);
                if($request->password != '**********' && Auth::guard('admin')->user()->id == $request->id){
                    Auth::guard('admin')->logout();
                    return redirect('/');
                }
            } 
        } else{
            $data = array();
            if(!empty($filter)) {
                foreach ($filter as $key => $value) {
                    if($key == 'password' && $value != '**********'){
                        $data[$key] =  Hash::make($value);
                    }else{                    
                        $data[$key] = $value;
                    }
                }
            }
            $this->admin_repo->dataCrud($data);
        }

        return redirect('/admin/users');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $roles = $this->role_repo->getAll();
        $data = $this->admin_repo->getById($id);
        return view('admin.admin.add',compact('data','roles'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = $this->admin_repo->getById($id);
        return view('admin.admin.view',compact('data'));
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->admin_repo->getById($id);
        if(!empty($data)){
            $this->admin_repo->forceDelete($id); 
            return response()->json(['msg'=>'Deleted success'], 200);
        }
        
        return response()->json(['msg'=>'Data Not success'], 500);
    }
}
