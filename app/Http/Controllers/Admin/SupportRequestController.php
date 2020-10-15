<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Support_request;
use Yajra\DataTables\DataTables;

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
    public function store(CategoryRequest $request)
    {
         if(!empty($request->id)){
            $category = $this->support_request_repo->getById($request->id);
            if(!empty($category)){
                $this->support_request_repo->dataCrud($request, $request->id);
            } 
        } else{
            $this->support_request_repo->dataCrud($request);
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
        $data = $this->support_request_repo->getById($id);
        return view('admin.category.add',compact('data'));
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
        if(!empty($data)){
            $this->support_request_repo->destroy($id); 
            return response()->json(['msg'=>'Deleted success'], 200);
        }
        
        return response()->json(['msg'=>'Data Not success'], 500);
    }
}
