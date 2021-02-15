<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\ManageFeesRequest;
use App\Repositories\ManageFeesRepository;
use App\Repositories\CategoryRepository;

class ManageFeesController extends Controller
{
    private $manage_fees_repo, $category_repo;

    public function __construct(ManageFeesRepository $manage_fees_repo, CategoryRepository $category_repo)
    {
        $this->manage_fees_repo = $manage_fees_repo;
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
            return $this->manage_fees_repo->getDatatable($request);
        }
        return view('admin.manage_fees.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ids=['1','2','3'];
        $hcp_types = $this->category_repo->getByMultipleParentIds($ids);
        return response()->json(['status'=>true,'hcp_types' => $hcp_types], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ManageFeesRequest $request)
    {

        $data = [
                    'fees_percentage' => $request->fees_percentage,
                ];
                
        if(!empty($request->id)){
            $category = $this->manage_fees_repo->getById($request->id);
            if(!empty($category)){
                $this->manage_fees_repo->dataCrud($data, $request->id);
            } 
            return response()->json(['msg'=>'Update success'], 200);
        } else{
            $this->manage_fees_repo->dataCrud($data);
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
        $ids=['1','2','3'];
        $hcp_types = $this->category_repo->getByMultipleParentIds($ids);
        $data = $this->manage_fees_repo->getById($id);
        return response()->json(['status'=>true,'data'=>$data,'hcp_types' => $hcp_types], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->manage_fees_repo->getById($id);
        if(!empty($data)){
            $this->manage_fees_repo->destroy($id); 
            return response()->json(['msg'=>'Deleted success'], 200);
        }
        
        return response()->json(['msg'=>'Data Not success'], 500);
    }
}
