<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\RoleRequest;
use App\Repositories\RoleRepository;
use App\Repositories\PermissionCategoryRepository;
use App\Repositories\PermissionRepository;
use App\Repositories\RolePermissionRepository;
use Auth;

class RoleController extends Controller
{
     private $role_repo, $permission_cat_repo, $role_permission_repo;

    public function __construct(
        RoleRepository $role_repo, 
        PermissionCategoryRepository $permission_cat_repo,
        RolePermissionRepository $role_permission_repo
        )
    {
        $this->role_repo = $role_repo;
        $this->permission_cat_repo = $permission_cat_repo;
        $this->role_permission_repo = $role_permission_repo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->all()){
            return $this->role_repo->getDatatable($request);
        }
        return view('admin.setting.role.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission_cat = $this->permission_cat_repo->getWithRelationship();
        return view('admin.setting.role.add',compact('permission_cat'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        if(!empty($request->id)){
            $role = $this->role_repo->getById($request->id);
            if(!empty($role)){
                $this->role_permission_repo->deletePermission($request->id);
                $data = [
                            'role_name' => $request->role_name,
                        ];
                $this->role_repo->dataCrud($data, $request->id);
                if(!empty($request->id) && !empty($request->permissions) && count($request->permissions) > 0){
                    foreach ($request->permissions as $key => $value) {
                        $data = [
                            'role_id' => $request->id,
                            'permission_id' => $value,
                        ];
                        $this->role_permission_repo->dataCrud($data);
                    }
                }
            } 
        } else{
            $data = [
                        'role_name' => $request->role_name,
                    ];
            $role = $this->role_repo->dataCrud($data);

            if(!empty($role) && !empty($request->permissions) && count($request->permissions) > 0){
                foreach ($request->permissions as $key => $value) {
                    $data = [
                        'role_id' => $role->id,
                        'permission_id' => $value,
                    ];
                    $this->role_permission_repo->dataCrud($data);
                }
            }
           

        }

        return redirect('/role');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permission_cat = $this->permission_cat_repo->getWithRelationship();
        $data = $this->role_repo->getbyIdedit($id);
        $assign_permission = array();
        if(!empty($data) && $data->getPermissions){
            foreach ($data->getPermissions as $key => $value) {
               $assign_permission[] = $value->permission_id; 
            }
        }
        return view('admin.setting.role.add',compact('data','permission_cat','assign_permission'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->role_repo->getById($id);
        try{
            if(!empty($data)){
                $this->role_repo->forceDelete($id); 
                return response()->json(['msg'=>'Deleted success'], 200);
            }
        }catch(\Exception $e){
            return response()->json(['msg'=>'Can not delete this role'], 500);
        }  
        
        return response()->json(['msg'=>'Data Not success'], 500);
    }
}
