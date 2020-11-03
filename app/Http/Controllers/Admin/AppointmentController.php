<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Repositories\AppointmentRepository;
use App\Repositories\CategoryRepository;
use Auth;

class AppointmentController extends Controller
{
    private $appointment_repo, $category_repo;

    public function __construct(AppointmentRepository $appointment_repo, CategoryRepository $category_repo)
    {
        $this->appointment_repo = $appointment_repo;
        $this->category_repo = $category_repo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->all()) {
            return $this->appointment_repo->getDatatable($request);
        }
        return view('admin.appointment.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAppointments($status)
    {
        if ($status == 'completed') {
            return view('admin.appointment.complete');
        } else if ($status == 'cancel') {
            return view('admin.appointment.cancel');
        } else {
            return view('admin.appointment.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.appointment.add');
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
        $appointment_types = $this->appointment_repo->appointment_types;
        $status = $this->appointment_repo->status;
        $service_charge_type = $this->appointment_repo->service_charge_type;
        $categories = $this->category_repo->get();
        $data = $this->appointment_repo->getbyIdedit($id);
        // dd($data->toArray());
        return view('admin.appointment.view', compact('data', 'categories', 'appointment_types', 'status','service_charge_type'));
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
