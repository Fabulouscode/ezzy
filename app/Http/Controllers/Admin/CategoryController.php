<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\Admin\CategoryRequest;
use App\Repositories\CategoryRepository;
use Auth;

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
        return view('admin.category.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = $this->category_repo->get();
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
         if(!empty($request->id)){
            $category = $this->category_repo->getById($request->id);
            if(!empty($category)){
                $this->category_repo->dataCrud($request, $request->id);
            } 
        } else{
            $this->category_repo->dataCrud($request);
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
        $categories = $this->category_repo->get();
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
        if(!empty($data)){
            $this->category_repo->destroy($id); 
            return response()->json(['msg'=>'Deleted success'], 200);
        }
        
        return response()->json(['msg'=>'Data Not success'], 500);
    }
}
