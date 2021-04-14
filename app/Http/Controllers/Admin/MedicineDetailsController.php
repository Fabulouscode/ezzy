<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\MedicineDetailsRepository;
use App\Repositories\MedicineCategoryRepository;
use App\Repositories\MedicineSubcategoryRepository;
use App\Repositories\MedicineImagesRepository;
use App\Http\Requests\Admin\MedicineDetailsRequest;
use App\Http\Requests\Admin\MedicineDetailsImportRequest;
use App\Imports\MedicineDetaisImport;
use Excel;

class MedicineDetailsController extends Controller
{
    private $medicine_details_repo, $medicine_subcategory_repo, $medicine_category_repo, $medicine_images_repo;

    public function __construct(
        MedicineDetailsRepository $medicine_details_repo,
        MedicineSubcategoryRepository $medicine_subcategory_repo, 
        MedicineCategoryRepository $medicine_category_repo,
        MedicineImagesRepository $medicine_images_repo
        )
    {
        $this->medicine_details_repo = $medicine_details_repo;
        $this->medicine_subcategory_repo = $medicine_subcategory_repo;
        $this->medicine_category_repo = $medicine_category_repo;
        $this->medicine_images_repo = $medicine_images_repo;
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
        $status = $this->medicine_details_repo->getStatusValue();
        $medicine_types = $this->medicine_details_repo->getMedicineTypeValue();
        return view('admin.medicine.details.add',compact('categories','subcategories','status','medicine_types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MedicineDetailsRequest $request)
    {
        $filter = $request->all();
        if(!empty($request->id)){
            $medicine_details = $this->medicine_details_repo->getById($request->id);
            if(!empty($medicine_details)){
                $data = array();
                if(!empty($request)){
                    foreach ($filter as $key => $value) {
                        $data[$key] = $value;
                    }
                }
                $this->medicine_details_repo->dataCrud($data, $request->id);
                if(!empty($request->medicine_images) && !empty($request->id)){
                $this->medicine_images_repo->deleteImages($request->id);
                foreach ($request->medicine_images as $key => $value) {
                        $temp_data=[    
                                        'medicine_detail_id'=>$request->id,
                                        'product_image'=>$value,
                                        'sequence_no'=>$key+1
                                    ];
                        $this->medicine_images_repo->dataCrud($temp_data);
                    }
                }
            } 
        } else{
            $data = array();
            if(!empty($request)){
                foreach ($filter as $key => $value) {
                    $data[$key] = $value;
                }
            }
            $medicine =$this->medicine_details_repo->dataCrud($data);
            if(!empty($request->medicine_images) && !empty($medicine)){
                foreach ($request->medicine_images as $key => $value) {
                    $temp_data=[    
                                    'medicine_detail_id'=>$medicine->id,
                                    'product_image'=>$value,
                                    'sequence_no'=>$key+1
                                ];
                    $this->medicine_images_repo->dataCrud($temp_data);
                }
            }
        }

        return redirect('/medicine/details');
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
        $status = $this->medicine_details_repo->getStatusValue();
        $medicine_types = $this->medicine_details_repo->getMedicineTypeValue();
        $data = $this->medicine_details_repo->getbyIdedit($id);
        // dd($data);
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
        $data = $this->medicine_details_repo->getById($id);
        try{
            if(!empty($data)){
                $this->medicine_details_repo->forceDelete($id); 
                return response()->json(['msg'=>'Deleted success'], 200);
            }
        }catch(\Exception $e){
            return response()->json(['msg'=>'Can not delete this Medicine'], 500);
        }  
        return response()->json(['msg'=>'Data Not success'], 500);
    }
   
   
    public function importMedicine(MedicineDetailsImportRequest $request)
    {
        if(!empty($request->file('medicine_file'))) {          
            $file = $request->file('medicine_file');
            Excel::queueImport(new MedicineDetaisImport, $file);
            return response()->json(['msg'=>'Medicine Insert success'], 200);
        }
        return response()->json(['msg'=>'Medicine Not Insert'], 500);
    }
}
