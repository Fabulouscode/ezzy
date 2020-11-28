<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\MedicineCategoryRepository;
use App\Http\Requests\Admin\MedicineCategoryRequest;

class MedicineCategoryController extends Controller
{
    private $medicine_category_repo;

    public function __construct(MedicineCategoryRepository $medicine_category_repo)
    {
        $this->medicine_category_repo = $medicine_category_repo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->all()){
            return $this->medicine_category_repo->getDatatable($request);
        }
        return view('admin.medicine.category.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $status = $this->medicine_category_repo->getStatusValue();
        return view('admin.medicine.category.add',compact('status'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MedicineCategoryRequest $request)
    {
        $data = [
                'name' => $request->name,
                'status' => $request->status,
            ];
        if(!empty($request->id)){
            $category = $this->medicine_category_repo->getById($request->id);
            if(!empty($category)){
                $this->medicine_category_repo->dataCrud($data, $request->id);
            } 
        } else{
            $this->medicine_category_repo->dataCrud($data);
        }

        return redirect('/medicine/categories');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = $this->medicine_category_repo->getById($id);
        $status = $this->medicine_category_repo->getStatusValue();
        return view('admin.medicine.category.add',compact('data','status'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->medicine_category_repo->getById($id);
       
        try{
            if(!empty($data)){
                $this->medicine_category_repo->forceDelete($id); 
                return response()->json(['msg'=>'Deleted success'], 200);
            }
        }catch(\Exception $e){
            return response()->json(['msg'=>'Can not delete this category'], 500);
        }     
        
        return response()->json(['msg'=>'Data Not success'], 500);
    }
}
