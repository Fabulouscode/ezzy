<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ContactDetailsRepository;

class ContactDetailsController extends Controller
{
    private $contact_repo;

    public function __construct(ContactDetailsRepository $contact_repo)
    {
        $this->contact_repo = $contact_repo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->all()){
            return $this->contact_repo->getDatatable($request);
        }
        return view('admin.contact.index');
    }
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

  /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AdminActivity  $adminActivity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $this->contact_repo->getById($id);
        if(!empty($data)){
            $updateData = ['read' => 0];
            $this->contact_repo->dataCrud($updateData, $id); 
            return response()->json(['msg'=>''], 200);
        }
        
        return response()->json(['msg'=>''], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = $this->contact_repo->getById($id);
        return view('admin.contact.view',compact('data'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = $this->contact_repo->getById($id);
        try{
            if(!empty($data)){
                $this->contact_repo->forceDelete($id); 
                return response()->json(['msg'=>'Deleted success'], 200);
            }
        }catch(\Exception $e){
            return response()->json(['msg'=>'Can not delete this contact form details'], 500);
        }  
        
        return response()->json(['msg'=>'Data Not success'], 500);
    }
}
