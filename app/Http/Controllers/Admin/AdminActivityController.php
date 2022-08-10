<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminActivity;
use Illuminate\Http\Request;
use App\Repositories\AdminActivityRepository;

class AdminActivityController extends Controller
{
    private $admin_activity_repo;

    public function __construct(AdminActivityRepository $admin_activity_repo)
    {
        $this->admin_activity_repo = $admin_activity_repo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->all()){
            return $this->admin_activity_repo->getDatatable($request);
        }
        return view('admin.admin_activity.index');
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
     * @param  \App\Models\AdminActivity  $adminActivity
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = $this->admin_activity_repo->getbyIdedit($id);
        // dd($data);
        return view('admin.admin_activity.view', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AdminActivity  $adminActivity
     * @return \Illuminate\Http\Response
     */
    public function edit(AdminActivity $adminActivity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AdminActivity  $adminActivity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AdminActivity $adminActivity)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AdminActivity  $adminActivity
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdminActivity $adminActivity)
    {
        //
    }
}
