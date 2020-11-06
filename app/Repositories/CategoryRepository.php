<?php

namespace App\Repositories;

use App\Events\ForgotPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryRepository extends Repository
{
    protected $model_name = 'App\Models\Category';
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
        if(!empty($data)){
            if(!empty($id)){
                return $this->update($data, $id);
            } else {
                return $this->store($data);
            }
        }
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getByParentId($parent_id)
    {
        $query = $this->model->where('parent_id',$parent_id)->get();
        return $query;
    }
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getByMultipleParentIds($parent_ids)
    {
        $query = $this->model->whereIn('parent_id',$parent_ids)->get();
        return $query;
    }
    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getWithRelationship()
    {
        $query = $this->model->with(['categoryParent']);
        $query = $query->orderBy('id','desc')->get();
        return $query;
    }
    
    /**
     * Display a listing of the Datatable.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDatatable($request)
    {
        $data = $this->getAll();
        return Datatables::of($data)
                ->addColumn('action',function($selected)
                {
                    $data = '';
                    $data .= '<a href="'.url('category/'.$selected->id.'/edit').'" class="btn btn-sm btn-info" title="Edit"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;';
<<<<<<< Updated upstream
                    // $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-danger" title="Delete" id="delete-rows" onclick="deleteRow('.$selected->id.')"><i class="fa fa-trash"></i></a>';
=======
                    // $data .= '<a href="javascript:void(0)" class="btn btn-sm btn-outline-danger" title="Delete" id="delete-rows" onclick="deleteRow('.$selected->id.')"><i class="fa fa-trash"></i></a>';
>>>>>>> Stashed changes
                    return $data;
                })
                ->editColumn('categoryParent',function($selected){
                    if(!empty($selected->categoryParent)){
                        return $selected->categoryParent->name;
                    }                            
                })
                ->rawColumns(['action','categoryParent'])
                ->make(true);
    }
}