<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;

class PatientDetailController extends Controller
{
    private $user_repo, $category_repo;

    public function __construct(UserRepository $user_repo, CategoryRepository  $category_repo)
    {
        $this->user_repo = $user_repo;
        $this->category_repo = $category_repo;
    }

    public function getPatientDetails(Request $request)
    {
        $categories = $this->category_repo->getByParentId('1');
        if ($request->all()) {
            return $this->user_repo->getPatientDatatable($request);
        }
        return view('admin.appointment.patient_detail', compact('categories'));
    }
}
