<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\StaticPageRequest;
use App\Repositories\StaticPageRepository;

class StaticPagesController extends Controller
{
    private $static_page_repo;

    public function __construct(StaticPageRepository $static_page_repo)
    {
        $this->static_page_repo = $static_page_repo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->all()){
            return $this->static_page_repo->getDatatable($request);
        }
        return view('admin.static_page.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $status = $this->static_page_repo->getStatusValue();
        return view('admin.static_page.add',compact('status'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StaticPageRequest $request)
    {
        
        $data = [
                    'page_name' => $request->page_name,
                    'page_description' => $request->page_description,
                    'status' => $request->status,
                ];

        if(!empty($request->id)){
            $category = $this->static_page_repo->getById($request->id);
            if(!empty($category)){
                $this->static_page_repo->dataCrud($data, $request->id);
            } 
        } else{
            $this->static_page_repo->dataCrud($data);
        }

        return redirect('/static_pages');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $status = $this->static_page_repo->getStatusValue();
        $data = $this->static_page_repo->getById($id);
        return view('admin.static_page.add',compact('data','status'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->static_page_repo->getById($id);
        if(!empty($data)){
            $this->static_page_repo->destroy($id); 
            return response()->json(['msg'=>'Deleted success'], 200);
        }
        
        return response()->json(['msg'=>'Data Not success'], 500);
    }
}
