<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\SupportRequestRepository;
use App\Http\Requests\Admin\SupportRequest;

class SupportRequestController extends Controller
{
    private $support_request_repo;

    public function __construct(SupportRequestRepository $support_request_repo)
    {
        $this->support_request_repo = $support_request_repo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->all()){
            return $this->support_request_repo->getDatatable($request);
        }
        return view('admin.support_request.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.support_request.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SupportRequest $request)
    {
         if(!empty($request->id)){
            $category = $this->support_request_repo->getById($request->id);
            if(!empty($category)){
                $data = [
                        'user_id' => $request->user_id,
                        'title' => $request->title,
                        'description' => $request->description,
                        'status' => $request->status,
                        ];
                $this->support_request_repo->dataCrud($data, $request->id);
            } 
        } else{
            $data = [
                    'user_id' => $request->user_id,
                    'title' => $request->title,
                    'description' => $request->description,
                    'status' => $request->status,
                    ];
            $this->support_request_repo->dataCrud($data);
        }

        return redirect('/support_request');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $status = $this->support_request_repo->status;
        $data = $this->support_request_repo->getById($id);
        return view('admin.support_request.add',compact('data','status'));
    }
    
     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $status = $this->support_request_repo->status;
        $data = $this->support_request_repo->getbyIdedit($id);
        return view('admin.support_request.view',compact('data','status'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->support_request_repo->getById($id);
        try{
            if(!empty($data)){
                $this->support_request_repo->forceDelete($id); 
                return response()->json(['msg'=>'Deleted success'], 200);
            }
        }catch(\Exception $e){
            return response()->json(['msg'=>'Can not delete this support request'], 500);
        }  

        return response()->json(['msg'=>'Data Not success'], 500);
    }
}
