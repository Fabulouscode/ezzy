<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\MedicineDetailsRepository;
use App\Repositories\MedicineCategoryRepository;
use App\Repositories\MedicineSubcategoryRepository;

class MedicineDetailsController extends Controller
{
    private $medicine_details_repo, $medicine_subcategory_repo, $medicine_category_repo;

    public function __construct(
        MedicineDetailsRepository $medicine_details_repo,
        MedicineSubcategoryRepository $medicine_subcategory_repo, 
        MedicineCategoryRepository $medicine_category_repo
        )
    {
        $this->medicine_details_repo = $medicine_details_repo;
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
            return $this->medicine_details_repo->getDatatable($request);
        }
        return view('admin.medicine.details.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = $this->medicine_category_repo->get();
        $subcategories = $this->medicine_subcategory_repo->get();
        $status = $this->medicine_details_repo->status;
        $medicine_types = $this->medicine_details_repo->medicine_types;
        return view('admin.medicine.details.add',compact('categories','subcategories','status','medicine_types'));
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
            $category = $this->medicine_details_repo->getById($request->id);
            if(!empty($category)){
                $this->medicine_details_repo->dataCrud($request, $request->id);
            } 
        } else{
            $this->medicine_details_repo->dataCrud($request);
        }

        return redirect('/medicine_details');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = $this->medicine_category_repo->get();
        $subcategories = $this->medicine_subcategory_repo->get();
        $status = $this->medicine_details_repo->status;
        $medicine_types = $this->medicine_details_repo->medicine_types;
        $data = $this->medicine_details_repo->getById($id);
        return view('admin.medicine.details.add',compact('data','categories','subcategories','status','medicine_types'));
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
        if(!empty($data)){
            $this->medicine_category_repo->destroy($id); 
            return response()->json(['msg'=>'Deleted success'], 200);
        }
        
        return response()->json(['msg'=>'Data Not success'], 500);
    }
}
