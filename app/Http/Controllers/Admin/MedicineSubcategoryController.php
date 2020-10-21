<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\MedicineSubcategoryRepository;
use App\Repositories\MedicineCategoryRepository;
use App\Http\Requests\Admin\MedicineSubcategoryRequest;

class MedicineSubcategoryController extends Controller
{
    private $medicine_subcategory_repo, $medicine_category_repo;

    public function __construct(MedicineSubcategoryRepository $medicine_subcategory_repo, MedicineCategoryRepository $medicine_category_repo)
    {
        $this->medicine_subcategory_repo = $medicine_subcategory_repo;
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
            return $this->medicine_subcategory_repo->getDatatable($request);
        }
        return view('admin.medicine.subcategory.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $status = $this->medicine_category_repo->status;
        $categories = $this->medicine_category_repo->get();
        return view('admin.medicine.subcategory.add',compact('categories','status'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MedicineSubcategoryRequest $request)
    {
         if(!empty($request->id)){
            $category = $this->medicine_subcategory_repo->getById($request->id);
            if(!empty($category)){
                $this->medicine_subcategory_repo->dataCrud($request, $request->id);
            } 
        } else{
            $this->medicine_subcategory_repo->dataCrud($request);
        }

        return redirect('/medicine/subcategories');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $status = $this->medicine_category_repo->status;
        $categories = $this->medicine_category_repo->get();
        $data = $this->medicine_subcategory_repo->getById($id);
        return view('admin.medicine.subcategory.add',compact('data','categories','status'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->medicine_subcategory_repo->getById($id);
        if(!empty($data)){
            $this->medicine_subcategory_repo->destroy($id); 
            return response()->json(['msg'=>'Deleted success'], 200);
        }
        
        return response()->json(['msg'=>'Data Not success'], 500);
    }
}
