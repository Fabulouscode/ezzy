<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\Admin\CategoryRequest;
use Yajra\DataTables\DataTables;
use Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->all()){
            $data = Category::with(['categoryParent'])->get();
            return Datatables::of($data)
                    ->addColumn('action',function($selected)
                    {
                        $data = '';
                        $data .= '<a href="'.url('category/'.$selected->id.'/edit').'" class="btn btn-sm btn-outline-info" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;';
                        $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-outline-danger" title="Delete" id="delete-rows" onclick="deleteRow('.$selected->id.')"><i class="fa fa-trash"></i></a>';
                        return $data;
                    })
                    ->addColumn('categoryParent',function($selected){
                        if(!empty($selected->categoryParent)){
                            return $selected->categoryParent->name;
                        }                            
                    })
                    ->rawColumns(['action','categoryParent'])
                    ->make(true);
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
        $categories = Category::get();
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
            $category = Category::find($request->id);
            $data = [
                        'name' => $request->name,
                        'parent_id' => $request->parent_id,
                    ];
            $category->update($data);       
        } else{
        
            Category::Create([
                                'name' => $request->name,
                                'parent_id' => $request->parent_id,
                            ]);
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
        $categories = Category::get();
        $data = Category::find($id);
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
        $data = Category::find($id);
        if($data){
            $data->delete(); 
            return response()->json(['msg'=>'Deleted success'], 200);
        }
        
        return response()->json(['msg'=>'Data Not success'], 500);
    }
}
