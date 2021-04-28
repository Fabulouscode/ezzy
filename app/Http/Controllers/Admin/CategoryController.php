<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\CategoryRequest;
use App\Repositories\CategoryRepository;

class CategoryController extends Controller
{
    private $category_repo;

    public function __construct(CategoryRepository $category_repo)
    {
        $this->category_repo = $category_repo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->all()){
            return $this->category_repo->getDatatable($request);
        }
        $categories = $this->category_repo->getByMultipleParentIds(['1','3']);
        return view('admin.category.index',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = $this->category_repo->getByMultipleParentIds(['1','2','3']);
        return view('admin.category.add',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $data = [
                'name' => $request->name,
                'parent_id' => $request->parent_id,
            ];
            
        if(!empty($request->id)){
            $category = $this->category_repo->getById($request->id);
            if(!empty($category)){
                $this->category_repo->dataCrud($data, $request->id);
            } 
        } else{
            $this->category_repo->dataCrud($data);
        }

        return redirect('/category');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = $this->category_repo->getByMultipleParentIds(['1','2','3']);
        $data = $this->category_repo->getById($id);
        return view('admin.category.add',compact('data','categories'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->category_repo->getById($id);
        try{
            if(!empty($data)){
                $this->category_repo->forceDelete($id); 
                return response()->json(['msg'=>'Deleted success'], 200);
            }
        }catch(\Exception $e){
            return response()->json(['msg'=>'Can not delete this hcp type'], 500);
        }  
        
        return response()->json(['msg'=>'Data Not success'], 500);
    }
}
