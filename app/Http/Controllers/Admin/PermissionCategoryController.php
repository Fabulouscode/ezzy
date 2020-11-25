<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\PermissionCategoryRequest;
use App\Repositories\PermissionCategoryRepository;

class PermissionCategoryController extends Controller
{
   
    private $permission_cat_repo;

    public function __construct(PermissionCategoryRepository $permission_cat_repo)
    {
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
            return $this->permission_cat_repo->getDatatable($request);
        }
        return view('admin.setting.permission_category.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->json(['status'=>true], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PermissionCategoryRequest $request)
    {
        
        $data = [
                    'name' => $request->name,
                ];

        if(!empty($request->id)){
            $permission_cat = $this->permission_cat_repo->getById($request->id);
            if(!empty($permission_cat)){
                $this->permission_cat_repo->dataCrud($data, $request->id);
            } 
            return response()->json(['msg'=>'Update success'], 200);
        } else{
            $this->permission_cat_repo->dataCrud($data);
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
        $data = $this->permission_cat_repo->getById($id);
        return response()->json(['status'=>true,'data'=>$data], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $data = $this->permission_cat_repo->getById($id);
        try{
            if(!empty($data)){
                $this->permission_cat_repo->forceDelete($id); 
                return response()->json(['msg'=>'Deleted success'], 200);
            }
        }catch(\Exception $e){
            return response()->json(['msg'=>'Can not delete this permission category'], 500);
        }  
        
        return response()->json(['msg'=>'Data Not success'], 500);
    
    }
}
