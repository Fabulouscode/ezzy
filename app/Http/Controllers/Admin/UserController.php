<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Yajra\DataTables\DataTables;
use Auth;


class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->all()){
            $data = User::with(['categoryParent','categoryChild'])->get();
            return Datatables::of($data)
                    ->addColumn('action',function($selected)
                    {
                        $data = '';
                        $data .= '<a href="'.url('user/'.$selected->id.'/edit').'" class="btn btn-sm btn-outline-info" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;';
                        $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-outline-danger" title="Delete" id="delete-rows" onclick="deleteRow('.$selected->id.')"><i class="fa fa-trash"></i></a>';
                        return $data;
                    })
                    ->addColumn('categoryParent',function($selected){
                        if(!empty($selected->categoryParent)){
                            return $selected->categoryParent->name;
                        }                            
                    })
                    ->addColumn('categoryChild',function($selected){
                        if(!empty($selected->categoryChild)){
                            return $selected->categoryChild->name;
                        }                            
                    })
                    ->rawColumns(['action','categoryParent','categoryChild'])
                    ->make(true);
        }
        return view('admin.user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
