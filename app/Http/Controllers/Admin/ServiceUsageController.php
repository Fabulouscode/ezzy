<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\ManageFeesRequest;
use App\Repositories\ServicesUsageRepository;

class ServiceUsageController extends Controller
{
    private $service_usage_repo;

    public function __construct(ServicesUsageRepository $service_usage_repo)
    {
        $this->service_usage_repo = $service_usage_repo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->all()){
            return $this->service_usage_repo->getDatatable($request);
        }
        return view('admin.service_usage.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = [
                    'name' => $request->name,
                ];
                
        if(!empty($request->id)){
            $category = $this->service_usage_repo->getById($request->id);
            if(!empty($category)){
                $this->service_usage_repo->dataCrud($data, $request->id);
            } 
            return response()->json(['msg'=>'Update success'], 200);
        } else{
            $this->service_usage_repo->dataCrud($data);
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
        $data = $this->service_usage_repo->getById($id);
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
        $data = $this->service_usage_repo->getById($id);
        if(!empty($data)){
            $this->service_usage_repo->forceDelete($id); 
            return response()->json(['msg'=>'Deleted success'], 200);
        }
        
        return response()->json(['msg'=>'Data Not success'], 500);
    }
}
