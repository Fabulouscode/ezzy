<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\ServicesRequest;
use App\Repositories\ServicesRepository;

class ServicesController extends Controller
{
     private $services_repo;

    public function __construct(ServicesRepository $services_repo)
    {
        $this->services_repo = $services_repo;
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
        $status = $this->services_repo->status;
        $service_type = $this->services_repo->service_type;
        return view('admin.services.add',compact('status','service_type'));
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
        $status = $this->services_repo->status;
        $service_type = $this->services_repo->service_type;
        $data = $this->services_repo->getById($id);
        return view('admin.services.add',compact('data','service_type','status'));
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
