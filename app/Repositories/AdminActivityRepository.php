<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\AdminNotification;
use Illuminate\Support\Str;

class AdminActivityRepository extends Repository
{
    protected $model_name = 'App\Models\AdminActivity';
    protected $model;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $data
     * @return \Illuminate\Http\Response
     */
    public function dataCrud($data, $id = '')
    {
        if (!empty($data)) {
            if (!empty($id)) {
                return $this->update($data, $id);
            } else {
                return $this->store($data);
            }
        }
    }

    public function getbyIdedit($id)
    {   
        return $this->model->find($id);
    }

    public function getWithRelationship($request)
    {
        $query = $this->model;    

        if(!empty($request->start_date) && !empty($request->end_date)){
            $query = $query->whereDate('created_at', '>=',$request->start_date)->whereDate('created_at' , '<=',$request->end_date);
        }
        return $query;
    }

    /**
     * Display a listing of the Datatable.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDatatable($request)
    {
        $data = $this->getWithRelationship($request);
        return Datatables::of($data)
            ->addColumn('action', function ($selected) {
                $data = '';
                if (Auth::user()->hasPermissionTo('notification-edit')) {
                    $data .= '<a href="'.url('donotezzycaretouch/admin_activity/'.$selected->id).'" class="btn btn-sm btn-primary" title="View"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;';
                }
                return $data;
            })
            ->editColumn('created_at',function($selected)
            {                   
                $date_time_formate = new Carbon($selected->created_at);
                (!empty(Auth::user()) && !empty(Auth::user()->timezone)) ? $date_time_formate->setTimezone(Auth::user()->timezone) : '' ;
                return $date_time_formate->format('d M, Y h:i a');
            })
            ->rawColumns(['action','created_at'])
            ->make(true);
    }
}
