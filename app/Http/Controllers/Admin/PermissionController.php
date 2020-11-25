<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\PermissionRequest;
use App\Repositories\PermissionRepository;
use App\Repositories\PermissionCategoryRepository;

class PermissionController extends Controller
{
    
    private $permission_repo, $permission_cat_repo;

    public function __construct(PermissionRepository $permission_repo, PermissionCategoryRepository $permission_cat_repo)
    {
        $this->permission_repo = $permission_repo;
        $this->permission_cat_repo = $permission_cat_repo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->all()){
            return $this->permission_repo->getDatatable($request);
        }
        return view('admin.setting.permission.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission_cats = $this->permission_cat_repo->getAll();
        return response()->json(['status'=>true,'permission_cats' => $permission_cats], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PermissionRequest $request)
    {
        $data = [
                'permission_category_id' => $request->permission_category_id,
                'permission_title' => $request->permission_title,
                'permission_name' => $request->permission_name,
            ];
            
        if(!empty($request->id)){
            $permission = $this->permission_repo->getById($request->id);
            if(!empty($permission)){
                $this->permission_repo->dataCrud($data, $request->id);
            } 
            return response()->json(['msg'=>'Update success'], 200);
        } else{
            $this->permission_repo->dataCrud($data);
            return response()->json(['msg'=>'Add success'], 200);
        }
        
        return response()->json(['msg'=>'Data Not success'], 500);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permission_cats = $this->permission_cat_repo->getAll();
        $data = $this->permission_repo->getById($id);
        return response()->json(['status'=>true,'data'=>$data,'permission_cats' => $permission_cats], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $data = $this->permission_repo->getById($id);
        try{
            if(!empty($data)){
                $this->permission_repo->forceDelete($id); 
                return response()->json(['msg'=>'Deleted success'], 200);
            }
        }catch(\Exception $e){
            return response()->json(['msg'=>'Can not delete this permission'], 500);
        }  
        
        return response()->json(['msg'=>'Data Not success'], 500);
    }
}
