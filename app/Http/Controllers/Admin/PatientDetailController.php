<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class PatientDetailController extends Controller
{
    private $user_repo;

    public function __construct( UserRepository $user_repo )
    {
        $this->user_repo = $user_repo;
    }

    public function getPatientDetails(Request $request){
        if($request->all()){
            return $this->user_repo->getPatientDatatable($request);
         }
        return view('admin.appointment.patient_detail');
    }
}
