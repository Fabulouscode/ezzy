<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\MedicalItemRequest;
use App\Repositories\MedicalItemRepository;
use App\Repositories\MedicalCategoryRepository;

class MedicalItemController extends Controller
{
      private $medical_cat_repo, $medical_item_repo;

    public function __construct(MedicalCategoryRepository $medical_cat_repo, MedicalItemRepository $medical_item_repo)
    {
        $this->medical_cat_repo = $medical_cat_repo;
        $this->medical_item_repo = $medical_item_repo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->all()){
            return $this->medical_item_repo->getDatatable($request);
        }
        return view('admin.medical.items.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $medical_status = $this->medical_cat_repo->getStatusValue();
        $medical_category = $this->medical_cat_repo->getAll();
        return response()->json(['status'=>true,'medical_category'=>$medical_category, 'medical_status' => $medical_status], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MedicalItemRequest $request)
    {
        $data = [
                    'medical_item_name' => $request->medical_item_name,
                    'medical_category_id' => $request->medical_category_id,
                    'status' => $request->status,
                ];
                
        if(!empty($request->id)){
            $medical_cat = $this->medical_item_repo->getById($request->id);
            if(!empty($medical_cat)){
                $this->medical_item_repo->dataCrud($data, $request->id);
            } 
            return response()->json(['msg'=>'Update success'], 200);
        } else{
            $this->medical_item_repo->dataCrud($data);
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
        $medical_status = $this->medical_cat_repo->getStatusValue();
        $medical_category = $this->medical_cat_repo->getAll();
        $data = $this->medical_item_repo->getById($id);
        return response()->json(['status'=>true,'data'=>$data, 'medical_category' => $medical_category, 'medical_status' => $medical_status], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->medical_item_repo->getById($id);
        if(!empty($data)){
            $this->medical_item_repo->forceDelete($id); 
            return response()->json(['msg'=>'Deleted success'], 200);
        }
        
        return response()->json(['msg'=>'Data Not success'], 500);
    }
}
