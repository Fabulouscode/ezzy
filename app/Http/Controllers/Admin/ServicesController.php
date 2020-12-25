<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\ServicesRequest;
use App\Repositories\ServicesRepository;
use App\Repositories\ServicesUsageRepository;

class ServicesController extends Controller
{
     private $services_repo, $service_usage_repo;

    public function __construct(ServicesRepository $services_repo, ServicesUsageRepository $service_usage_repo)
    {
        $this->services_repo = $services_repo;
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
            return $this->services_repo->getDatatable($request);
        }
        return view('admin.services.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $service_usage = $this->service_usage_repo->getAll();
        $status = $this->services_repo->getStatusValue();
        $service_type = $this->services_repo->getServiceTypeValue();
        return view('admin.services.add',compact('status','service_type','service_usage'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ServicesRequest $request)
    {
        $data = [
                    'service_name' => $request->service_name,
                    'service_type' => $request->service_type,
                    'sevice_usages' => json_encode($request->sevice_usages),
                    'status' => $request->status,
                ];
         if(!empty($request->id)){
            $category = $this->services_repo->getById($request->id);
            if(!empty($category)){
                $this->services_repo->dataCrud($data, $request->id);
            } 
        } else{
            $this->services_repo->dataCrud($data);
        }

        return redirect('/services');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $service_usage = $this->service_usage_repo->getAll();
        $status = $this->services_repo->getStatusValue();
        $service_type = $this->services_repo->getServiceTypeValue();
        $data = $this->services_repo->getById($id);
        return view('admin.services.add',compact('data','service_type','status','service_usage'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->services_repo->getById($id);
        if(!empty($data)){
            $this->services_repo->destroy($id); 
            return response()->json(['msg'=>'Deleted success'], 200);
        }
        
        return response()->json(['msg'=>'Data Not success'], 500);
    }
}
